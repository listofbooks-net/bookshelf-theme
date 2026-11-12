// Markup checks over the output of every site. Each returns failure strings.
//   attributes parse: no attribute name is the debris of a broken quoted value
//   headings: no empty h1 to h6
//   fixtures: the cases the fixtures exist to prove
import { readFileSync, existsSync } from 'node:fs';
import { join } from 'node:path';
import { parse } from 'node-html-parser';
import { BASE, contentPages } from './lib.mjs';

const read = (root, file) => {
	if (!existsSync(join(root, file))) throw new Error(`${file} was not built`);
	return parse(readFileSync(join(root, file), 'utf8'));
};

export function checkMarkup(builtDir, site) {
	const root = join(builtDir, site);
	const failures = [];
	for (const file of contentPages(root)) {
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
	let cases = [];
	try {
		cases = fixtureCases[site]?.(root) ?? [];
	} catch (error) {
		cases = [`${site}: ${error.message}`];
	}
	return [...failures, ...cases];
}

const expect = (failures, ok, message) => ok || failures.push(message);

const fixtureCases = {
	sparse(root) {
		const failures = [];
		const title = 'The "Quiet" Arithmetic';
		const author = `Ann "Ace" O'Neil </script>`;
		const post = read(root, 'reviews/quoted-title/index.html');
		const og = post.querySelector('meta[property="og:title"]')?.getAttribute('content') ?? '';
		expect(failures, og.includes(title) && og.includes(author), `og:title lost its quotes: ${og}`);
		expect(failures, post.querySelector('link[rel="icon"]')?.getAttribute('href') === '//example.com/favicon.svg', 'a protocol-relative favicon URL was changed');
		const scripts = post.querySelectorAll('script[type="application/ld+json"]');
		expect(failures, scripts.length === 1, `the review has ${scripts.length} JSON-LD blocks, not 1`);
		try {
			const data = JSON.parse(scripts[0].text);
			expect(failures, data.name === title && data.itemReviewed?.author?.name === author, `the JSON-LD names changed: ${scripts[0].text.slice(0, 300)}`);
		} catch (error) {
			failures.push(`the JSON-LD does not parse: ${error.message}`);
		}
		const labelled = (file, selector) => read(root, file).querySelectorAll(selector).map((e) => e.getAttribute('aria-label') ?? '');
		expect(failures, labelled('index.html', '.cover--placeholder').some((l) => l.includes(title) && l.includes(author)), 'no placeholder cover label keeps the title and author');
		expect(failures, labelled('reading-log/index.html', 'progress').some((l) => l.includes(title)), 'no progress label keeps the title');
		const authorPage = read(root, 'author/index.html');
		expect(failures, authorPage.querySelector('section#about')?.getAttribute('aria-label')?.includes('An Author With "No" Books'), 'the author hero label lost its quotes');
		expect(failures, !authorPage.querySelector('#new-release'), 'a book with no title made a new-release section');
		expect(failures, !authorPage.querySelector('#events'), 'an event with no title made an events section');
		return failures;
	},
	quickstart(root) {
		const failures = [];
		const post = read(root, 'posts/my-first-post/index.html');
		expect(failures, post.text.includes('A third paragraph proves that the whole body is printed'), 'the post did not print its body');
		expect(failures, post.text.includes('Jane Doe'), 'the byline does not show the plain-string author Jane Doe');
		const nav = read(root, 'index.html').querySelectorAll('nav a').map((a) => a.getAttribute('href'));
		for (const href of [`${BASE}/`, `${BASE}/posts/`, `${BASE}/about/`]) expect(failures, nav.includes(href), `no nav link to ${href}: ${nav.join(' ')}`);
		return failures;
	},
	paginated(root) {
		const failures = [];
		const page = read(root, 'blog/seite/2/index.html');
		const hrefs = page.querySelectorAll('.pagination a').map((a) => a.getAttribute('href'));
		expect(failures, hrefs.includes(`${BASE}/blog/`), `the pager does not link to ${BASE}/blog/: ${hrefs.join(' ')}`);
		expect(failures, !hrefs.includes(`${BASE}/`), 'the pager links to the site root');
		expect(failures, page.querySelectorAll('h1').length === 1, `page 2 has ${page.querySelectorAll('h1').length} h1 elements, not 1`);
		return failures;
	},
	demo(root) {
		// Science fiction has no content/categories/science-fiction/_index.md, so Hugo's own title would be "Science Fiction".
		const failures = [];
		const typed = 'Science fiction';
		const home = read(root, 'index.html');
		expect(failures, home.querySelectorAll('.toc__name').some((e) => e.text.trim() === typed), `the genre list does not say "${typed}" as the posts write it`);
		const term = read(root, 'categories/science-fiction/index.html');
		expect(failures, term.querySelector('h1')?.text.trim() === typed, `the genre page heading is "${term.querySelector('h1')?.text.trim()}", not "${typed}"`);
		expect(failures, term.querySelector('title')?.text.startsWith(`${typed} ·`), `the genre page title is "${term.querySelector('title')?.text}"`);
		const review = read(root, 'reviews/the-glass-cartographer/index.html');
		expect(failures, review.querySelectorAll('a.tag').some((e) => e.text.trim() === typed), `the review's genre chip is not "${typed}"`);
		expect(failures, review.querySelectorAll('.book-card .eyebrow').every((e) => e.text.trim() !== 'Science Fiction'), 'a card eyebrow title-cases the genre');
		return failures;
	},
	'no-credit'(root) {
		const failures = [];
		for (const file of contentPages(root)) {
			const links = read(root, file).querySelectorAll('a[href*="listofbooks.net"]').length;
			expect(failures, links === 0, `${file}: ${links} links to listofbooks.net after the credit partial was emptied`);
		}
		return failures;
	},
};
