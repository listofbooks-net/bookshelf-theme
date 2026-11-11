// Parses every Bookshelf pattern, template and template part in the block
// editor and reports any block the editor would flag as invalid. Exits 1 on failure.
//
//   node tools/wp-dev/check-patterns.cjs <site url>
//
// Set PLAYWRIGHT_MODULE and CHROMIUM_PATH as for check-pages.cjs.

const { chromium } = require(process.env.PLAYWRIGHT_MODULE || 'playwright');

const base = process.argv[2];

(async () => {
	const browser = await chromium.launch({ executablePath: process.env.CHROMIUM_PATH || undefined });
	const page = await browser.newPage();
	const errors = [];
	page.on('pageerror', (e) => errors.push(e.message));
	await page.goto(`${base}/wp-login.php`);
	await page.fill('#user_login', 'admin');
	await page.fill('#user_pass', 'admin');
	await page.click('#wp-submit');
	await page.waitForURL(/wp-admin/);
	await page.goto(`${base}/wp-admin/post-new.php`, { waitUntil: 'domcontentloaded' });
	await page.waitForFunction(() => window.wp?.blocks?.getBlockTypes?.().length > 50 && window.wp?.apiFetch, null, { timeout: 60000 });

	const report = await page.evaluate(async () => {
		const sources = [];
		const patterns = await wp.apiFetch({ path: '/wp/v2/block-patterns/patterns' });
		for (const p of patterns.filter((p) => p.name.startsWith('bookshelf/'))) sources.push([`pattern ${p.name}`, p.content]);
		for (const type of ['wp_template', 'wp_template_part']) {
			const items = await wp.apiFetch({ path: `/wp/v2/${type === 'wp_template' ? 'templates' : 'template-parts'}?context=edit&per_page=100` });
			for (const t of items.filter((t) => t.theme === 'bookshelf')) sources.push([`${type} ${t.slug}`, t.content.raw]);
		}
		const invalid = [];
		const walk = (blocks, label) => {
			for (const b of blocks) {
				if (!b.isValid) {
					const issue = (b.validationIssues || []).map((i) => i.args.slice(1).filter((a) => typeof a === 'string').join(' ')).join(' | ');
					invalid.push(`${label}: ${b.name} ${issue}`.slice(0, 900));
				}
				if (b.name === 'core/missing') invalid.push(`${label}: missing block ${b.attributes.originalName}`);
				walk(b.innerBlocks, label);
			}
		};
		for (const [label, content] of sources) walk(wp.blocks.parse(content), label);
		return { count: sources.length, invalid };
	});

	await browser.close();
	for (const line of report.invalid) console.log(line);
	if (errors.length) console.log('page errors:', errors.join('; '));
	console.log(`${report.count} sources parsed, ${report.invalid.length} invalid blocks.`);
	process.exit(report.invalid.length || report.count < 30 ? 1 : 0);
})();
