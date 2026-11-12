// Every check over the output of hugo-dev.sh build. Exits 1 when any fails.
//   node check-builds.mjs <out dir>
// 1. Each site's HTML is the same on the minimum Hugo version and the latest.
// 2. Every internal link and asset in every page resolves to a built file.
// 3. Attributes parse, no heading is empty, and each fixture's own cases hold (check-markup.mjs).
// 4. The demo matches the design pages' markup (check-parity.mjs).
// 5. Every page of every site but no-credit passes check-pages.cjs at 1280 and 390 px, in both styles.
// Checks 2 to 5 read the latest version's output; check 1 proves the other is the same.
import { readdirSync, readFileSync, existsSync, statSync, writeFileSync, mkdirSync } from 'node:fs';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';
import { spawn } from 'node:child_process';
import { parse } from 'node-html-parser';
import { checkParity } from './check-parity.mjs';
import { checkMarkup } from './check-markup.mjs';
import { startServer } from '../jekyll-dev/serve.mjs';
import { BASE, htmlFiles, contentPages, urlOf, isAlias } from './lib.mjs';

const here = dirname(fileURLToPath(import.meta.url));
const repo = join(here, '../..');
const out = process.argv[2] ?? join(here, 'out');
const sites = readdirSync(join(out, 'latest'), { withFileTypes: true }).filter((d) => d.isDirectory()).map((d) => d.name);
const results = [];

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

// Hugo's own differences are not the theme's: it puts <meta name="generator"> at a
// different spot in the head on each version, and writes alias redirect pages (such
// as /page/1/) with different whitespace and a robots meta. Alias pages are compared
// by their file names only.
const normalise = (html) => html.replace(/<meta name="generator"[^>]*>/, '').replace(/>\s+</g, '><').replace(/\s+/g, ' ').trim();

for (const site of sites) {
	const a = join(out, 'min', site);
	const b = join(out, 'latest', site);
	const failures = [];
	const filesA = existsSync(a) ? htmlFiles(a) : [];
	const filesB = htmlFiles(b);
	if (filesA.join() !== filesB.join()) failures.push(`different pages: min has ${filesA.length}, latest has ${filesB.length}`);
	for (const file of filesA.filter((f) => filesB.includes(f))) {
		if (isAlias(readFileSync(join(b, file), 'utf8'))) continue;
		const x = normalise(readFileSync(join(a, file), 'utf8'));
		const y = normalise(readFileSync(join(b, file), 'utf8'));
		if (x !== y) {
			let i = 0;
			while (x[i] === y[i]) i += 1;
			failures.push(`${file} differs at: min "${x.slice(Math.max(0, i - 40), i + 60)}" / latest "${y.slice(Math.max(0, i - 40), i + 60)}"`);
		}
	}
	report(`${site}: same HTML on the minimum Hugo version and the latest`, failures);
}

for (const site of sites) {
	const root = join(out, 'latest', site);
	const failures = [];
	for (const file of htmlFiles(root)) {
		const page = urlOf(file);
		const document = parse(readFileSync(join(root, file), 'utf8'));
		for (const element of document.querySelectorAll('[href], [src], form[action]')) {
			const raw = element.getAttribute('href') ?? element.getAttribute('src') ?? element.getAttribute('action');
			if (/^([a-z]+:|#|$)/i.test(raw) || raw.startsWith('//')) continue;
			const url = new URL(raw, `http://site${BASE}${page}`);
			if (!url.pathname.startsWith(`${BASE}/`)) {
				failures.push(`${file}: ${raw} is outside the baseURL`);
				continue;
			}
			let target = join(root, decodeURIComponent(url.pathname.slice(BASE.length)));
			if (existsSync(target) && statSync(target).isDirectory()) target = join(target, 'index.html');
			if (!existsSync(target)) failures.push(`${file}: ${raw} does not resolve`);
		}
	}
	report(`${site}: every internal link and asset resolves under baseURL ${BASE}`, failures);
}

for (const site of sites) report(`${site}: attributes parse, no empty headings, and the fixture's own cases`, checkMarkup(join(out, 'latest'), site));

if (sites.includes('demo')) report('demo: markup matches the seven design pages', checkParity(join(repo, 'design'), join(out, 'latest', 'demo')));

let port = 4100;
for (const site of sites.filter((s) => s !== 'no-credit')) {
	const root = join(out, 'latest', site);
	const pages = {};
	for (const file of contentPages(root).filter((f) => f !== '404.html')) {
		const path = urlOf(file);
		pages[path === '/' ? 'home' : path.replace(/^\/|\/$/g, '').replace(/[^a-z0-9]+/gi, '-')] = path;
	}
	if (existsSync(join(root, '404.html'))) pages['404'] = '/this-page-does-not-exist/';
	if (site === 'demo') Object.assign(pages, { search: '/reviews/?s=ledger', 'search-empty': '/reviews/?s=zzzzqqq' });
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
