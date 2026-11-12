// Crawls a running Ghost into a static copy for Cloudflare Pages, then loads the copy in a
// browser to prove it stands alone.
//
//   node export-static.mjs <ghost url> <out dir> <public url>
//
// What the copy changes from what Ghost serves:
//   - the Ghost origin becomes the public URL everywhere (links, canonical, JSON-LD, feeds, sitemaps)
//   - Portal, comments and member-attribution scripts and the comments section are removed; they need Ghost's members API
//   - every form[data-members-form] becomes data-demo-form, which the theme script confirms in place
//   - the three search-index responses sodo-search reads are saved as files, so search works
//   - _headers allows framing by ghost.org (the Marketplace preview) and types the feed and the search files
//   - _redirects lists the redirects Ghost answered while crawling
import { mkdirSync, readFileSync, writeFileSync, existsSync, statSync, rmSync, readdirSync } from 'node:fs';
import { dirname, extname, join, normalize } from 'node:path';

const [source, out, publicUrl] = process.argv.slice(2);
if (!source || !out || !publicUrl) {
	console.error('usage: node export-static.mjs <ghost url> <out dir> <public url>');
	process.exit(2);
}
const origin = new URL(source).origin;
const publicOrigin = new URL(publicUrl).origin;
const publicHost = new URL(publicUrl).host;
const sourceHost = new URL(source).host;
const SKIPPED = /^\/(ghost|members|r|webmentions)(\/|$)/;
const TEXT = /^(text\/|application\/(json|xml|rss\+xml|atom\+xml|javascript|xslt\+xml)|image\/svg)/;
const PROBE_404 = '/readerly-404-probe/';

const enc = encodeURIComponent;
function rewrite(text) {
	return text
		.replaceAll(origin, publicOrigin)
		.replaceAll(`//${sourceHost}`, `//${publicHost}`)
		.replaceAll(enc(origin), enc(publicOrigin))
		.replaceAll(origin.replaceAll('/', '\\/'), publicOrigin.replaceAll('/', '\\/'));
}

function clean(html) {
	return html
		.replace(/<script\b[^>]*\bsrc="[^"]*(?:portal|comments-ui|comment-counts|member-attribution|ghost-stats|tinybird)[^"]*"[^>]*><\/script>\s*/gi, '')
		.replace(/<section\b[^>]*\bclass="[^"]*\bcomments\b[^"]*"[^>]*>[\s\S]*?<\/section>\s*/gi, '')
		.replace(/<link\b[^>]*webmention[^>]*>\s*/gi, '')
		.replace(/(<form\b[^>]*?)\sdata-members-form(?:="[^"]*")?/gi, '$1 data-demo-form');
}

const pages = new Map();
const redirects = new Map();
const queue = [];
const seen = new Set();
const bodies = new Map();
const strict = new Set();
const aliases = new Map();
const skipped = [];

function enqueue(url, required = false) {
	const parsed = new URL(url, origin);
	if (parsed.origin !== origin || SKIPPED.test(parsed.pathname)) return;
	if (required) strict.add(parsed.pathname);
	if (!seen.has(parsed.pathname)) {
		seen.add(parsed.pathname);
		queue.push(parsed.pathname);
	}
}

function refs(text, base) {
	const found = new Set();
	for (const m of text.matchAll(/\b(?:href|src|poster|data-src|data-sodo-search|data-api)=["']([^"']+)["']/g)) found.add(m[1]);
	for (const m of text.matchAll(/\b(?:srcset|data-srcset)=["']([^"']+)["']/g)) for (const part of m[1].split(',')) found.add(part.trim().split(/\s+/)[0]);
	for (const m of text.matchAll(/url\(\s*["']?([^)"']+)/g)) found.add(m[1]);
	const locs = [...text.matchAll(/<loc>([^<]+)<\/loc>/g)].map((m) => m[1]);
	for (const loc of locs) enqueue(loc, true);
	for (const m of text.matchAll(/<link[^>]+rel="(?:next|prev)"[^>]*href="([^"]+)"/g)) found.add(m[1]);
	for (const m of text.matchAll(new RegExp(`${origin.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')}/[^"'\\s<>)\\\\]+`, 'g'))) found.add(m[0]);
	for (const ref of found) {
		if (/^(#|mailto:|tel:|data:|javascript:)/i.test(ref) || /\{|%7b|%url%/i.test(ref)) continue;
		try {
			enqueue(new URL(ref, base).href);
		} catch {}
	}
}

function fileFor(pathname) {
	if (pathname.endsWith('/')) return `${pathname}index.html`;
	return extname(pathname) ? pathname : `${pathname}/index.html`;
}

async function fetchOne(pathname) {
	const response = await fetch(origin + pathname, { redirect: 'manual' });
	if (response.status >= 300 && response.status < 400) {
		const target = new URL(response.headers.get('location'), origin);
		if (target.origin !== origin) return;
		// A file that Ghost redirects (a sized copy of an SVG) is stored under both paths, so no
		// host has to apply a redirect rule for an asset. A page redirect goes into _redirects.
		if (extname(pathname)) aliases.set(pathname, target.pathname);
		else redirects.set(pathname, target.pathname);
		enqueue(target.href);
		return;
	}
	if (pathname === PROBE_404) {
		if (response.status !== 404) throw new Error(`${PROBE_404} answered ${response.status}, expected 404`);
		store('/404.html', 'text/html', Buffer.from(await response.arrayBuffer()), pathname);
		return;
	}
	if (!response.ok) {
		if (strict.has(pathname)) throw new Error(`${pathname} answered ${response.status}`);
		skipped.push(`${pathname} (${response.status})`);
		return;
	}
	store(fileFor(pathname), response.headers.get('content-type') ?? '', Buffer.from(await response.arrayBuffer()), pathname);
}

function store(file, type, buffer, pathname) {
	if (TEXT.test(type)) {
		let text = buffer.toString('utf8');
		refs(text, origin + pathname);
		text = rewrite(text);
		if (/^text\/html/.test(type)) text = clean(text);
		bodies.set(file, Buffer.from(text));
	} else bodies.set(file, buffer);
}

async function crawl() {
	for (const path of ['/', '/robots.txt', '/sitemap.xml', '/rss/', PROBE_404]) enqueue(origin + path, true);
	let key = null;
	while (queue.length) {
		const batch = queue.splice(0, 8);
		await Promise.all(batch.map(fetchOne));
	}
	for (const [file, body] of bodies) {
		const m = body.toString('latin1').match(/data-key="([0-9a-f]{26})"/);
		if (m && file.endsWith('.html')) key = key ?? m[1];
	}
	return key;
}

async function searchIndex(key) {
	if (!key) return false;
	for (const name of ['posts', 'authors', 'tags']) {
		const response = await fetch(`${origin}/ghost/api/content/search-index/${name}/?key=${key}`);
		if (!response.ok) throw new Error(`search index ${name} answered ${response.status}`);
		bodies.set(`/ghost/api/content/search-index/${name}/index.html`, Buffer.from(rewrite(await response.text())));
	}
	return true;
}

const headers = `/*
  Content-Security-Policy: frame-ancestors 'self' https://*.ghost.org
  X-Content-Type-Options: nosniff
  Referrer-Policy: strict-origin-when-cross-origin

/rss/
  Content-Type: application/rss+xml; charset=utf-8

/ghost/api/content/search-index/*
  Content-Type: application/json; charset=utf-8
  Access-Control-Allow-Origin: *
`;

function write() {
	rmSync(out, { recursive: true, force: true });
	for (const [file, body] of bodies) {
		const target = join(out, file);
		mkdirSync(dirname(target), { recursive: true });
		writeFileSync(target, body);
	}
	writeFileSync(join(out, '_headers'), headers);
	if (redirects.size) writeFileSync(join(out, '_redirects'), [...redirects].map(([from, to]) => `${from} ${to} 301`).join('\n') + '\n');
}

// Serves the copy the way Cloudflare Pages does: directory URLs get index.html, a miss gets 404.html.
function lookup(pathname) {
	const root = normalize(out);
	const path = decodeURIComponent(pathname);
	let file = join(root, path);
	if (existsSync(file) && statSync(file).isDirectory()) file = join(file, 'index.html');
	if (existsSync(file) && statSync(file).isFile()) {
		const type = path === '/rss/' ? 'application/rss+xml' : path.startsWith('/ghost/api/') ? 'application/json' : { '.html': 'text/html', '.css': 'text/css', '.js': 'text/javascript', '.svg': 'image/svg+xml', '.xml': 'application/xml', '.txt': 'text/plain', '.woff2': 'font/woff2', '.json': 'application/json', '.xsl': 'text/xsl' }[extname(file)] ?? 'application/octet-stream';
		return { status: 200, type, body: readFileSync(file) };
	}
	const missing = join(root, '404.html');
	return { status: 404, type: 'text/html', body: existsSync(missing) ? readFileSync(missing) : Buffer.from('Not found') };
}

async function verify() {
	const { chromium } = await import('playwright-core');
	const files = readdirSync(out, { recursive: true }).filter((f) => f.endsWith('.html')).sort();
	const browser = await chromium.launch({ executablePath: process.env.CHROMIUM_PATH || undefined });
	const context = await browser.newContext({ viewport: { width: 1280, height: 900 } });
	const problems = [];
	let current = '';
	await context.route('**/*', async (route) => {
		const url = new URL(route.request().url());
		if (url.origin === publicOrigin) {
			const { status, type, body } = lookup(url.pathname);
			if (status === 404 && url.pathname !== PROBE_404 && !current.endsWith('404.html') && route.request().resourceType() !== 'document') problems.push(`${current}: ${url.pathname} is not in the copy`);
			return route.fulfill({ status, contentType: type, body });
		}
		if (url.origin === origin) problems.push(`${current}: request to the Ghost origin ${url.href}`);
		else if (!/^https:\/\/cdn\.jsdelivr\.net\/ghost\//.test(url.href) && url.protocol !== 'data:') problems.push(`${current}: third-party request ${url.href}`);
		return route.continue();
	});
	const page = await context.newPage();
	page.on('requestfailed', (r) => problems.push(`${current}: failed ${r.url()} (${r.failure()?.errorText})`));
	page.on('pageerror', (e) => problems.push(`${current}: page error ${e.message}`));
	page.on('console', (m) => {
		if (m.type() === 'error' && !/status of 404/.test(m.text())) problems.push(`${current}: console ${m.text()}`);
	});
	for (const file of files) {
		const path = `/${file}`.replace(/index\.html$/, '');
		current = file;
		const response = await page.goto(publicOrigin + path, { waitUntil: 'networkidle' });
		const expected = file === '404.html' ? 404 : 200;
		if (file !== '404.html' && response.status() !== expected) problems.push(`${file}: HTTP ${response.status()}`);
	}
	current = 'search';
	await page.goto(`${publicOrigin}/#/search`, { waitUntil: 'networkidle' });
	const frame = page.frameLocator('iframe[title="Search"], iframe[srcdoc], .gh-search iframe, iframe').first();
	const input = frame.locator('input').first();
	try {
		await input.waitFor({ timeout: 10000 });
		await input.fill('Salt');
		await frame.getByText(/Salt Orchard/).first().waitFor({ timeout: 10000 });
	} catch (error) {
		problems.push(`search did not open and find "Salt": ${error.message.split('\n')[0]}`);
	}
	await browser.close();
	return { files, problems };
}

const key = await crawl();
for (const [from, to] of aliases) if (bodies.has(to)) bodies.set(from, bodies.get(to));
const search = await searchIndex(key);
write();
console.log(`Exported ${[...bodies.keys()].filter((f) => f.endsWith('.html')).length} pages and ${bodies.size} files to ${out}${search ? ', with the sodo-search index' : ', without a search index (the site exposes no key)'}`);
if (skipped.length) console.log(`not saved, Ghost answered an error: ${skipped.join(', ')}`);
if (redirects.size) console.log(`redirects: ${[...redirects].map(([a, b]) => `${a} -> ${b}`).join(', ')}`);
const { files, problems } = await verify();
console.log(problems.length ? `FAIL\n${[...new Set(problems)].slice(0, 40).join('\n')}` : `Static copy verified: ${files.length} pages load with no Ghost-origin or failed requests, and search finds "Salt".`);
process.exit(problems.length ? 1 : 0);
