// Every check over the output of jekyll-dev.sh build. Exits 1 when any fails.
//   node check-builds.mjs <out dir>
// 1. Each site's HTML is the same on both engines, whitespace aside.
// 2. Every internal link and asset in every page resolves to a built file.
// 3. Attributes parse, no heading is empty, and each fixture's own cases hold (check-markup.mjs).
// 4. The demo matches the design pages' markup (check-parity.mjs).
// 5. Every page of every site passes check-pages.cjs at 1280 and 390 px, in both styles.
import { readdirSync, readFileSync, existsSync, statSync, writeFileSync, mkdirSync } from 'node:fs';
import { dirname, join, relative, posix } from 'node:path';
import { fileURLToPath } from 'node:url';
import { spawn } from 'node:child_process';
import { parse } from 'node-html-parser';
import { checkParity } from './check-parity.mjs';
import { checkMarkup } from './check-markup.mjs';
import { startServer } from './serve.mjs';

const here = dirname(fileURLToPath(import.meta.url));
const repo = join(here, '../..');
const out = process.argv[2] ?? join(here, 'out');
const BASE = '/sub';
const sites = readdirSync(join(out, 'pages'), { withFileTypes: true }).filter((d) => d.isDirectory()).map((d) => d.name);
const results = [];

function htmlFiles(dir) {
	return readdirSync(dir, { recursive: true }).filter((f) => f.endsWith('.html')).sort();
}

function report(name, failures) {
	results.push({ name, failures });
	console.log(`${failures.length ? 'FAIL' : 'ok  '} ${name}${failures.length ? `\n  ${failures.slice(0, 30).join('\n  ')}` : ''}`);
}

// Asynchronous, because the site server runs in this process and must keep answering.
function runNode(args) {
	return new Promise((done) => {
		const child = spawn(process.execPath, args);
		let output = '';
		child.stdout.on('data', (d) => (output += d));
		child.stderr.on('data', (d) => (output += d));
		child.on('close', (status) => done({ status, output }));
	});
}

const normalise =(html) => html.replace(/>\s+</g, '><').replace(/\s+/g, ' ').trim();

for (const site of sites) {
	const a = join(out, 'pages', site);
	const b = join(out, 'jekyll4', site);
	const failures = [];
	const filesA = htmlFiles(a);
	const filesB = existsSync(b) ? htmlFiles(b) : [];
	if (filesA.join() !== filesB.join()) failures.push(`different pages: pages has ${filesA.length}, jekyll4 has ${filesB.length}`);
	for (const file of filesA.filter((f) => filesB.includes(f))) {
		const x = normalise(readFileSync(join(a, file), 'utf8'));
		const y = normalise(readFileSync(join(b, file), 'utf8'));
		if (x !== y) {
			let i = 0;
			while (x[i] === y[i]) i += 1;
			failures.push(`${file} differs at: pages "${x.slice(i - 40, i + 60)}" / jekyll4 "${y.slice(i - 40, i + 60)}"`);
		}
	}
	report(`${site}: same HTML on Jekyll 3.10 (GitHub Pages) and Jekyll 4.4`, failures);
}

for (const site of sites) {
	const root = join(out, 'pages', site);
	const failures = [];
	for (const file of htmlFiles(root)) {
		const page = `/${file.replace(/index\.html$/, '')}`;
		const document = parse(readFileSync(join(root, file), 'utf8'));
		for (const element of document.querySelectorAll('[href], [src], form[action]')) {
			const raw = element.getAttribute('href') ?? element.getAttribute('src') ?? element.getAttribute('action');
			if (/^([a-z]+:|#|$)/i.test(raw) || raw.startsWith('//')) continue;
			const url = new URL(raw, `http://site${BASE}${page}`);
			if (!url.pathname.startsWith(`${BASE}/`)) {
				failures.push(`${file}: ${raw} is outside the baseurl`);
				continue;
			}
			let target = join(root, decodeURIComponent(url.pathname.slice(BASE.length)));
			if (existsSync(target) && statSync(target).isDirectory()) target = join(target, 'index.html');
			if (!existsSync(target)) failures.push(`${file}: ${raw} does not resolve`);
		}
	}
	report(`${site}: every internal link and asset resolves under baseurl ${BASE}`, failures);
}

for (const site of sites) report(`${site}: attributes parse, no empty headings, and the fixture's own cases`, checkMarkup(join(out, 'pages'), site));

if (sites.includes('demo')) report('demo: markup matches the seven design pages', checkParity(join(repo, 'design'), join(out, 'pages', 'demo')));

const browserSites = sites.filter((s) => !s.endsWith('-drafts'));
let port = 4100;
for (const site of browserSites) {
	const root = join(out, 'pages', site);
	const pages = {};
	for (const file of htmlFiles(root).filter((f) => f !== '404.html')) {
		const path = `/${file.replace(/index\.html$/, '')}`;
		pages[path === '/' ? 'home' : path.replace(/^\/|\/$/g, '').replace(/[^a-z0-9]+/gi, '-')] = path;
	}
	if (existsSync(join(root, '404.html'))) pages['404'] = '/this-page-does-not-exist/';
	if (site === 'demo') Object.assign(pages, { search: '/reviews/?s=ledger', 'search-empty': '/reviews/?s=zzzzqqq', 'genre-filter': '/reviews/?genre=Fantasy' });
	const mapFile = join(out, `${site}.pages.json`);
	writeFileSync(mapFile, JSON.stringify({ pages, notFound: ['404'], colorScheme: { paper: 'light', lamplight: 'dark' } }, null, '\t'));
	const shots = join(out, 'shots', site);
	mkdirSync(shots, { recursive: true });
	const server = await startServer(root, port);
	const failures = [];
	for (const label of ['paper', 'lamplight']) {
		const run = await runNode([join(repo, 'tools/wp-dev/check-pages.cjs'), `http://127.0.0.1:${port}${BASE}`, shots, label, mapFile]);
		if (run.status !== 0) failures.push(...run.output.trim().split('\n'));
	}
	server.close();
	port += 1;
	report(`${site}: browser checks on ${Object.keys(pages).length} pages, 1280 and 390 px, Paper and Lamplight`, failures);
}

const failed = results.filter((r) => r.failures.length);
console.log(failed.length ? `\n${failed.length} of ${results.length} checks failed.` : `\nAll ${results.length} checks passed.`);
process.exit(failed.length ? 1 : 0);
