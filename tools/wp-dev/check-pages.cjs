// Screenshots every demo page at desktop and mobile width and checks what a
// reviewer would otherwise check by eye. Exits 1 when any check fails.
//
//   node tools/wp-dev/check-pages.cjs <site url> <output dir> [label]
//
// Set PLAYWRIGHT_MODULE to a Playwright install and CHROMIUM_PATH to a browser
// when they are not resolvable from this directory.

const { chromium } = require(process.env.PLAYWRIGHT_MODULE || 'playwright');
const fs = require('fs');

const USAGE = 'usage: node check-pages.cjs <site url> <output dir> [label]';
const [base, out, label = 'paper'] = process.argv.slice(2);
if (!base || !out) {
	console.error(USAGE);
	process.exit(2);
}
let origin;
try {
	origin = new URL(base).origin;
} catch {
	console.error(`not a URL: ${base}\n${USAGE}`);
	process.exit(2);
}

const pages = {
	home: '/',
	'home-page-2': '/page/2/',
	review: '/the-salt-orchard/',
	author: '/author-home/',
	'reading-log': '/reading-log/',
	about: '/about/',
	contact: '/contact/',
	archive: '/category/literary-fiction/',
	search: '/?s=ledger',
	'search-empty': '/?s=zzzzqqq',
	'404': '/this-page-does-not-exist/',
};
const viewports = { desktop: { width: 1280, height: 900 }, mobile: { width: 390, height: 844 } };
const CREDIT = 'https://www.listofbooks.net';

// PHP prints "<b>Warning</b>:  message in <b>/path/file.php</b> on line <b>12</b>" when
// html_errors is on, and "Warning: message in /path/file.php on line 12" when it is off.
const PHP_MESSAGE = /(?:<b>|\b(?:PHP )?)(Warning|Notice|Deprecated|Fatal error|Parse error)(?:<\/b>)?:\s[\s\S]{0,600}?\bon line (?:<b>)?\d+/g;

async function checkPage(context, name, path, vpName) {
	const problems = [];
	const page = await context.newPage();
	try {
		page.on('console', (m) => {
			if (m.type() === 'error' && !(name === '404' && m.location().url === base + path)) problems.push(`console: ${m.text()}`);
		});
		page.on('pageerror', (e) => problems.push(`page error: ${e.message}`));
		page.on('request', (r) => {
			const url = r.url();
			if (!/^(data|blob):/.test(url) && new URL(url).origin !== origin) problems.push(`third-party request: ${url}`);
		});
		page.on('requestfailed', (r) => problems.push(`failed: ${r.url()}`));

		let response = null;
		try {
			response = await page.goto(base + path, { waitUntil: 'networkidle' });
		} catch (error) {
			problems.push(`navigation failed: ${error.message}`);
		}
		if (!response) return problems;

		const expected = name === '404' ? 404 : 200;
		if (response.status() !== expected) problems.push(`HTTP ${response.status()}, expected ${expected}`);
		const phpMessages = ((await response.text()).match(PHP_MESSAGE) || []).slice(0, 3);
		if (phpMessages.length) problems.push(`PHP: ${phpMessages.map((m) => m.replace(/<[^>]+>/g, '')).join(' | ')}`);

		await page.evaluate(async () => {
			document.querySelectorAll('img').forEach((i) => (i.loading = 'eager'));
			await Promise.all([...document.images].map((i) => (i.complete ? 0 : new Promise((r) => (i.onload = i.onerror = r)))));
			await document.fonts.ready;
		});
		const info = await page.evaluate((credit) => {
			const credits = [...document.querySelectorAll('a[href]')].filter((a) => a.href.replace(/\/$/, '') === credit);
			return {
				overflow: document.documentElement.scrollWidth - document.documentElement.clientWidth,
				broken: [...document.images].filter((i) => !i.complete || i.naturalWidth === 0).map((i) => i.src),
				credits: credits.map((a) => ({ text: a.textContent.trim(), rel: a.getAttribute('rel') })),
				creditish: document.querySelectorAll('a[href*="listofbooks.net"]').length,
				h1: document.querySelectorAll('h1').length,
				fonts: [...document.fonts].filter((f) => f.status === 'loaded').map((f) => `${f.family} ${f.style}`),
			};
		}, CREDIT);

		if (info.overflow > 0) problems.push(`horizontal overflow ${info.overflow}px`);
		if (info.broken.length) problems.push(`broken images: ${info.broken.join(', ')}`);
		if (info.credits.length !== 1 || info.creditish !== 1) problems.push(`credit links: ${JSON.stringify(info.credits)} (any listofbooks.net link: ${info.creditish})`);
		if (info.credits[0] && (info.credits[0].rel !== null || info.credits[0].text !== 'Theme by List of Books')) problems.push(`credit link wrong: ${JSON.stringify(info.credits[0])}`);
		if (!info.fonts.some((f) => f.startsWith('Fraunces')) || !info.fonts.some((f) => f.startsWith('Literata'))) problems.push(`fonts loaded: ${info.fonts.join(', ')}`);
		if (info.h1 !== 1) problems.push(`expected exactly one h1, found ${info.h1}`);

		if (vpName === 'mobile') {
			const toggle = page.locator('.readerly-header .wp-block-navigation__responsive-container-open').first();
			if (await toggle.isVisible()) {
				await toggle.click();
				await page.waitForTimeout(300);
				const links = await page.locator('.wp-block-navigation__responsive-container.is-menu-open a').count();
				if (!links) problems.push('mobile menu opened with no links');
				if (name === 'home') await page.screenshot({ path: `${out}/${name}-menu-open-${vpName}-${label}.png` });
				await page.locator('.wp-block-navigation__responsive-container-close').first().click();
				await page.waitForTimeout(200);
				if (await page.locator('.wp-block-navigation__responsive-container.is-menu-open').count()) problems.push('mobile menu did not close');
			} else {
				problems.push('mobile menu toggle not visible');
			}
		}

		await page.screenshot({ path: `${out}/${name}-${vpName}-${label}.png`, fullPage: true });
	} catch (error) {
		problems.push(`check crashed: ${error.message}`);
	} finally {
		await page.close();
	}
	return problems;
}

(async () => {
	fs.mkdirSync(out, { recursive: true });
	const browser = await chromium.launch({ executablePath: process.env.CHROMIUM_PATH || undefined });
	const failures = [];
	try {
		for (const [vpName, viewport] of Object.entries(viewports)) {
			const context = await browser.newContext({ viewport, deviceScaleFactor: 1 });
			try {
				for (const [name, path] of Object.entries(pages)) {
					const problems = await checkPage(context, name, path, vpName);
					if (problems.length) failures.push(`${name} ${vpName} ${label}: ${problems.join('; ')}`);
				}
			} finally {
				await context.close();
			}
		}
	} finally {
		await browser.close();
	}
	const total = Object.keys(pages).length * Object.keys(viewports).length;
	console.log(failures.length ? failures.join('\n') : `All ${total} page checks passed (${label}).`);
	process.exit(failures.length ? 1 : 0);
})().catch((error) => {
	console.error(error);
	process.exit(1);
});
