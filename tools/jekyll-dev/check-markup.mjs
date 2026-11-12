// Markup checks over the pages build of every site. Each returns failure strings.
//   attributes parse: no attribute name is the debris of a broken quoted value
//   headings: no empty h1 to h6
//   fixtures: the cases the fixtures exist to prove
import { readFileSync, readdirSync, existsSync } from 'node:fs';
import { join } from 'node:path';
import { parse } from 'node-html-parser';

const read = (root, file) => parse(readFileSync(join(root, file), 'utf8'));
const htmlFiles = (root) => readdirSync(root, { recursive: true }).filter((f) => f.endsWith('.html')).sort();

export function checkMarkup(pagesDir, site) {
	const root = join(pagesDir, site);
	const failures = [];
	for (const file of htmlFiles(root)) {
		const document = read(root, file);
		for (const element of document.querySelectorAll('*')) {
			for (const name of Object.keys(element.attributes)) {
				if (!/^[a-z][a-z0-9:_-]*$/i.test(name)) failures.push(`${file}: <${element.tagName.toLowerCase()}> has the attribute "${name}", a quoted value ended early`);
			}
		}
		for (const heading of document.querySelectorAll('h1, h2, h3, h4, h5, h6')) {
			if (heading.textContent.trim() === '') failures.push(`${file}: an empty <${heading.tagName.toLowerCase()}>`);
		}
	}
	return [...failures, ...fixtureCases[site]?.(root) ?? []];
}

const expect = (failures, ok, message) => ok || failures.push(message);

const fixtureCases = {
	sparse(root) {
		const failures = [];
		const title = 'The "Quiet" Arithmetic';
		const author = `Ann "Ace" O'Neil </script>`;
		const post = read(root, 'quoted-title/index.html');
		const og = post.querySelector('meta[property="og:title"]')?.getAttribute('content') ?? '';
		expect(failures, og.includes(title) && og.includes(author), `og:title lost its quotes: ${og}`);
		expect(failures, post.querySelector('link[rel="icon"]')?.getAttribute('href') === '//example.com/favicon.svg', 'a protocol-relative favicon URL was changed');
		const scripts = post.querySelectorAll('script[type="application/ld+json"]');
		expect(failures, scripts.length === 1, `the review has ${scripts.length} JSON-LD blocks, not 1`);
		try {
			const data = JSON.parse(scripts[0].text);
			expect(failures, data.name === title && data.itemReviewed.author.name === author, 'the JSON-LD names changed');
		} catch (error) {
			failures.push(`the JSON-LD does not parse: ${error.message}`);
		}
		const labelled = (file, selector) => read(root, file).querySelectorAll(selector).map((e) => e.getAttribute('aria-label'));
		expect(failures, labelled('index.html', '.cover--placeholder').some((l) => l.includes(title) && l.includes(author)), 'no placeholder cover label keeps the title and author');
		expect(failures, labelled('reading-log/index.html', 'progress').some((l) => l.includes(title)), 'no progress label keeps the title');
		const author_page = read(root, 'author/index.html');
		expect(failures, author_page.querySelector('section#about')?.getAttribute('aria-label')?.includes('An Author With "No" Books'), 'the author hero label lost its quotes');
		expect(failures, !author_page.querySelector('#new-release'), 'a book with no title made a new-release section');
		expect(failures, !author_page.querySelector('#events'), 'an event with no title made an events section');
		return failures;
	},
	minima(root) {
		const failures = [];
		expect(failures, read(root, 'index.html').text.includes('This body text belongs to the page'), 'the home layout dropped the page body');
		return failures;
	},
	paginated(root) {
		const failures = [];
		const page = read(root, 'blog/page2/index.html');
		const hrefs = page.querySelectorAll('nav.pagination a').map((a) => a.getAttribute('href'));
		expect(failures, hrefs.includes('/sub/blog/'), `the pager does not link to /sub/blog/: ${hrefs.join(' ')}`);
		expect(failures, !hrefs.includes('/sub/'), 'the pager links to the site root');
		expect(failures, read(root, 'reviews/index.html').querySelector('h1')?.text.trim() === 'Archive', 'an untitled archive page has no "Archive" heading');
		return failures;
	},
};
