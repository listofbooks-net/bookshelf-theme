// Writes the two images a theme listing needs, from the demo home page in Paper,
// top of the page, with no browser chrome. Needs a demo build (hugo-dev.sh build).
//   hugo/theme/images/screenshot.png  1500 x 1000
//   hugo/theme/images/tn.png           900 x 600, the same view at a device scale factor of 0.6
//   node screenshots.mjs [out dir]
import { mkdirSync } from 'node:fs';
import { createRequire } from 'node:module';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';
import { startServer } from '../jekyll-dev/serve.mjs';

const require = createRequire(import.meta.url);
const here = dirname(fileURLToPath(import.meta.url));
const out = process.argv[2] ?? join(here, 'out');
const images = join(here, '../../hugo/theme/images');
mkdirSync(images, { recursive: true });

let chromium;
for (const name of [process.env.PLAYWRIGHT_MODULE, 'playwright', 'playwright-core'].filter(Boolean)) {
	try {
		({ chromium } = require(name));
		break;
	} catch {}
}
const server = await startServer(join(out, 'latest', 'demo'), 4292);
const browser = await chromium.launch({ executablePath: process.env.CHROMIUM_PATH || undefined });
for (const [file, deviceScaleFactor] of [['screenshot.png', 1], ['tn.png', 0.6]]) {
	const context = await browser.newContext({ viewport: { width: 1500, height: 1000 }, deviceScaleFactor, colorScheme: 'light' });
	const page = await context.newPage();
	await page.goto('http://127.0.0.1:4292/sub/', { waitUntil: 'networkidle' });
	await page.evaluate(async () => {
		document.querySelectorAll('img').forEach((i) => (i.loading = 'eager'));
		await Promise.all([...document.images].map((i) => (i.complete ? 0 : new Promise((r) => (i.onload = i.onerror = r)))));
		await document.fonts.ready;
	});
	await page.screenshot({ path: join(images, file) });
	await context.close();
}
await browser.close();
server.close();
console.log(`Wrote screenshot.png and tn.png to ${images}`);
