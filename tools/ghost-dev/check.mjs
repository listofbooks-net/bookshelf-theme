// Every check against a running, seeded Ghost with the Readerly theme. Exits 1 when any fails.
//   node check.mjs <site url> [out dir]
// 1. Markup parity with the seven design pages (same algorithm as tools/jekyll-dev/check-parity.mjs).
// 2. check-pages.cjs on pages.json at 1280 and 390 px, in Paper and Lamplight.
// 3. Side-by-side screenshots of the design and Ghost (side-by-side.mjs).
import { readFileSync, mkdirSync } from 'node:fs';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';
import { spawn } from 'node:child_process';
import { parse } from 'node-html-parser';
import { body, compare } from '../jekyll-dev/check-parity.mjs';

const here = dirname(fileURLToPath(import.meta.url));
const repo = join(here, '../..');
const site = (process.argv[2] ?? '').replace(/\/$/, '');
const out = process.argv[3] ?? join(here, 'out');
if (!site) {
	console.error('usage: node check.mjs <site url> [out dir]');
	process.exit(2);
}

export const GHOST_PAGES = {
	'index.html': '/',
	'review.html': '/the-salt-orchard/',
	'author.html': '/isobel-thorne/',
	'reading-log.html': '/reading-log/',
	'archive.html': '/tag/literary-fiction/',
	'page.html': '/about/',
	'404.html': '/readerly-missing-page/',
};

const exceptions = JSON.parse(readFileSync(join(here, 'parity-exceptions.json'), 'utf8'));
const results = [];

function report(name, failures) {
	results.push({ name, failures });
	console.log(`${failures.length ? 'FAIL' : 'ok  '} ${name}${failures.length ? `\n  ${failures.slice(0, 40).join('\n  ')}` : ''}`);
}

function run(args) {
	return new Promise((done) => {
		const child = spawn(process.execPath, args, { stdio: ['ignore', 'pipe', 'pipe'] });
		let output = '';
		child.stdout.on('data', (d) => (output += d));
		child.stderr.on('data', (d) => (output += d));
		child.on('close', (status) => done({ status, output }));
	});
}

async function parity() {
	const failures = [];
	for (const [file, path] of Object.entries(GHOST_PAGES)) {
		const response = await fetch(site + path);
		const expected = file === '404.html' ? 404 : 200;
		if (response.status !== expected) failures.push(`${file}: ${path} answered ${response.status}, expected ${expected}`);
		// Ghost adds a class per page, tag and author to body; the design's body has none.
		const siteHtml = (await response.text()).replace(/<body\b[^>]*>/, '<body>');
		const designHtml = readFileSync(join(repo, 'design', file), 'utf8');
		const problems = [];
		compare(body(designHtml, file, 'design', exceptions), body(siteHtml, file, 'demo', exceptions), '', problems);
		failures.push(...problems.slice(0, 8).map((p) => `${file}${p}`));
	}
	return failures;
}

mkdirSync(out, { recursive: true });
report('markup parity with the seven design pages', await parity());

const mapFile = join(here, 'pages.json');
const pageCount = Object.keys(JSON.parse(readFileSync(mapFile, 'utf8')).pages).length;
const browserFailures = [];
for (const label of ['paper', 'lamplight']) {
	const shots = join(out, 'shots');
	mkdirSync(shots, { recursive: true });
	const result = await run([join(repo, 'tools/wp-dev/check-pages.cjs'), site, shots, label, mapFile]);
	if (result.status !== 0) browserFailures.push(...result.output.trim().split('\n'));
}
report(`browser checks on ${pageCount} pages, 1280 and 390 px, Paper and Lamplight`, browserFailures);

const sideBySide = await run([join(here, 'side-by-side.mjs'), site, out]);
report('side-by-side screenshots written', sideBySide.status === 0 ? [] : sideBySide.output.trim().split('\n').slice(-5));
if (sideBySide.status === 0) console.log(`  ${sideBySide.output.trim().split('\n').pop()}`);

const failed = results.filter((r) => r.failures.length);
console.log(failed.length ? `\n${failed.length} of ${results.length} checks failed.` : `\nAll ${results.length} checks passed.`);
process.exit(failed.length ? 1 : 0);
