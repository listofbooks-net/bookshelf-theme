// Screenshots each design page next to the Ghost page built from it, at 1280 and 390 px in
// Paper and Lamplight, and writes <out>/side-by-side/index.html to compare them by eye.
//   node side-by-side.mjs <site url> [out dir]
import { createServer } from 'node:http';
import { readFile } from 'node:fs/promises';
import { mkdirSync, writeFileSync } from 'node:fs';
import { dirname, extname, join } from 'node:path';
import { fileURLToPath } from 'node:url';
import { chromium } from 'playwright-core';

const here = dirname(fileURLToPath(import.meta.url));
const site = (process.argv[2] ?? '').replace(/\/$/, '');
const out = process.argv[3] ?? join(here, 'out');
if (!site) {
	console.error('usage: node side-by-side.mjs <site url> [out dir]');
	process.exit(2);
}
const shots = join(out, 'side-by-side');
mkdirSync(shots, { recursive: true });

const PAGES = {
	'index.html': '/',
	'review.html': '/the-salt-orchard/',
	'author.html': '/isobel-thorne/',
	'reading-log.html': '/reading-log/',
	'archive.html': '/tag/literary-fiction/',
	'page.html': '/about/',
	'404.html': '/readerly-missing-page/',
};

const TYPES = { '.html': 'text/html', '.css': 'text/css', '.js': 'text/javascript', '.svg': 'image/svg+xml', '.woff2': 'font/woff2' };
const design = createServer(async (request, response) => {
	const path = decodeURIComponent(new URL(request.url, 'http://x').pathname);
	try {
		const body = await readFile(join(here, '../../design', path));
		response.writeHead(200, { 'content-type': TYPES[extname(path)] ?? 'application/octet-stream' }).end(body);
	} catch {
		response.writeHead(404).end();
	}
}).listen(0, '127.0.0.1');
await new Promise((r) => design.once('listening', r));
const designOrigin = `http://127.0.0.1:${design.address().port}`;

const browser = await chromium.launch({ executablePath: process.env.CHROMIUM_PATH || undefined });
const rows = [];
for (const [scheme, label] of [['light', 'paper'], ['dark', 'lamplight']]) {
	for (const width of [1280, 390]) {
		const context = await browser.newContext({ viewport: { width, height: 900 }, deviceScaleFactor: 1, colorScheme: scheme });
		const page = await context.newPage();
		for (const [file, path] of Object.entries(PAGES)) {
			const name = `${file.replace('.html', '')}-${width}-${label}`;
			for (const [side, address] of [['design', `${designOrigin}/${file}`], ['ghost', site + path]]) {
				await page.goto(address, { waitUntil: 'networkidle' });
				await page.evaluate(async () => {
					document.querySelector('.demo-bar')?.remove();
					document.querySelectorAll('img').forEach((i) => (i.loading = 'eager'));
					await Promise.all([...document.images].map((i) => (i.complete ? 0 : new Promise((r) => (i.onload = i.onerror = r)))));
					await document.fonts.ready;
				});
				await page.screenshot({ path: join(shots, `${name}-${side}.png`), fullPage: true });
			}
			rows.push(name);
		}
		await context.close();
	}
}
await browser.close();
design.close();
writeFileSync(
	join(shots, 'index.html'),
	`<!doctype html><meta charset="utf-8"><title>Design vs Ghost</title><style>body{font:14px sans-serif;margin:16px}section{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:48px}img{width:100%;border:1px solid #ccc}</style>
${rows.map((n) => `<h2>${n}</h2><section><div>design<br><img src="${n}-design.png"></div><div>ghost<br><img src="${n}-ghost.png"></div></section>`).join('\n')}`,
);
console.log(`Wrote ${rows.length} pairs to ${shots}/index.html`);
