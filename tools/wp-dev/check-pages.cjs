// Screenshots every demo page at desktop and mobile width and checks what a
// reviewer would otherwise check by eye. Exits 1 when any check fails.
//
//   node tools/wp-dev/check-pages.cjs <site url> <output dir> <label>
//
// Set PLAYWRIGHT_MODULE to a Playwright install and CHROMIUM_PATH to a browser
// when they are not resolvable from this directory.

const { chromium } = require(process.env.PLAYWRIGHT_MODULE || 'playwright');
const fs = require('fs');

const [base, out, label = 'paper'] = process.argv.slice(2);
const pages = {
	home: '/',
	review: '/the-salt-orchard/',
	author: '/author-home/',
	'reading-log': '/reading-log/',
	about: '/about/',
	archive: '/category/literary-fiction/',
	search: '/?s=ledger',
	'search-empty': '/?s=zzzzqqq',
	'404': '/this-page-does-not-exist/',
	'home-page-2': '/?query-11-page=2',
};
const viewports = { desktop: { width: 1280, height: 900 }, mobile: { width: 390, height: 844 } };
const CREDIT = 'https://listofbooks.net';

(async () => {
	fs.mkdirSync(out, { recursive: true });
	const browser = await chromium.launch({ executablePath: process.env.CHROMIUM_PATH || undefined });
	const failures = [];
	const origin = new URL(base).origin;

	for (const [vpName, viewport] of Object.entries(viewports)) {
		const context = await browser.newContext({ viewport, deviceScaleFactor: 1 });
		for (const [name, path] of Object.entries(pages)) {
			const page = await context.newPage();
			const problems = [];
			page.on('console', (m) => {
				if (m.type() === 'error' && !(name === '404' && m.location().url === base + path)) problems.push(`console: ${m.text()}`);
			});
			page.on('pageerror', (e) => problems.push(`page error: ${e.message}`));
			page.on('request', (r) => {
				const url = r.url();
				if (!/^(data|blob):/.test(url) && new URL(url).origin !== origin) problems.push(`third-party request: ${url}`);
			});
			page.on('requestfailed', (r) => problems.push(`failed: ${r.url()}`));

			const response = await page.goto(base + path, { waitUntil: 'networkidle' });
			const expected = name === '404' ? 404 : 200;
			if (response.status() !== expected) problems.push(`HTTP ${response.status()}`);

			await page.evaluate(async () => {
				document.querySelectorAll('img').forEach((i) => (i.loading = 'eager'));
				await Promise.all([...document.images].map((i) => (i.complete ? 0 : new Promise((r) => (i.onload = i.onerror = r)))));
				await document.fonts.ready;
			});
			const info = await page.evaluate((credit) => {
				const credits = [...document.querySelectorAll('a[href]')].filter((a) => a.href.replace(/\/$/, '') === credit);
				const allCreditish = [...document.querySelectorAll('a[href*="listofbooks.net"]')];
				const html = document.documentElement.outerHTML;
				return {
					overflow: document.documentElement.scrollWidth - document.documentElement.clientWidth,
					broken: [...document.images].filter((i) => !i.complete || i.naturalWidth === 0).map((i) => i.src),
					credits: credits.map((a) => ({ text: a.textContent.trim(), rel: a.getAttribute('rel') })),
					creditish: allCreditish.length,
					php: (html.match(/<b>(Warning|Notice|Deprecated|Fatal error)<\/b>:[^<]*<\/b>[^<]*/g) || []).slice(0, 3),
					fonts: [...document.fonts].filter((f) => f.status === 'loaded').map((f) => `${f.family} ${f.style}`),
					navToggle: !!document.querySelector('.wp-block-navigation__responsive-container-open'),
				};
			}, CREDIT);

			if (info.overflow > 0) problems.push(`horizontal overflow ${info.overflow}px`);
			if (info.broken.length) problems.push(`broken images: ${info.broken.join(', ')}`);
			if (info.credits.length !== 1 || info.creditish !== 1) problems.push(`credit links: ${JSON.stringify(info.credits)} (any listofbooks.net link: ${info.creditish})`);
			if (info.credits[0] && (info.credits[0].rel !== null || info.credits[0].text !== 'Theme by List of Books')) problems.push(`credit link wrong: ${JSON.stringify(info.credits[0])}`);
			if (info.php.length) problems.push(`PHP: ${info.php.join(' | ')}`);
			if (!info.fonts.some((f) => f.startsWith('Fraunces')) || !info.fonts.some((f) => f.startsWith('Literata'))) problems.push(`fonts loaded: ${info.fonts.join(', ')}`);

			if (vpName === 'mobile') {
				const toggle = page.locator('.bookshelf-header .wp-block-navigation__responsive-container-open').first();
				if (await toggle.isVisible()) {
					await toggle.click();
					await page.waitForTimeout(300);
					const linksVisible = await page.locator('.wp-block-navigation__responsive-container.is-menu-open a').count();
					if (!linksVisible) problems.push('mobile menu opened with no links');
					if (name === 'home') await page.screenshot({ path: `${out}/${name}-menu-open-${vpName}-${label}.png` });
					await page.locator('.wp-block-navigation__responsive-container-close').first().click();
				} else {
					problems.push('mobile menu toggle not visible');
				}
			}

			await page.screenshot({ path: `${out}/${name}-${vpName}-${label}.png`, fullPage: true });
			if (problems.length) failures.push(`${name} ${vpName} ${label}: ${problems.join('; ')}`);
			await page.close();
		}
		await context.close();
	}
	await browser.close();
	console.log(failures.length ? failures.join('\n') : `All ${Object.keys(pages).length * 2} page checks passed (${label}).`);
	process.exit(failures.length ? 1 : 0);
})();
