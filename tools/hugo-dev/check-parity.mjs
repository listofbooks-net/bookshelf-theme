// Markup parity between the design pages and the demo built from the theme.
// Both pages reduce to a tree of tag names, classes, roles and ARIA attribute
// names; runs of siblings with the same tag and classes collapse to their first.
// The trees must be equal once parity-exceptions.json has removed its elements.
//   node check-parity.mjs <design dir> <built demo dir>
import { readFileSync } from 'node:fs';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';
import { body, compare } from '../lib/markup-tree.mjs';

export const PAGES = {
	'index.html': '/',
	'review.html': '/reviews/the-salt-orchard/',
	'author.html': '/author/',
	'reading-log.html': '/reading-log/',
	'archive.html': '/categories/literary-fiction/',
	'page.html': '/about/',
	'404.html': '/404.html',
};

const exceptions = JSON.parse(readFileSync(join(dirname(fileURLToPath(import.meta.url)), 'parity-exceptions.json'), 'utf8'));


export function checkParity(designDir, demoDir) {
	const failures = [];
	for (const [page, url] of Object.entries(PAGES)) {
		const file = url.endsWith('/') ? join(demoDir, url, 'index.html') : join(demoDir, url);
		const problems = [];
		compare(body(readFileSync(join(designDir, page), 'utf8'), page, 'design', exceptions), body(readFileSync(file, 'utf8'), page, 'demo', exceptions), '', problems);
		failures.push(...problems.slice(0, 8).map((p) => `${page}${p}`));
	}
	return failures;
}

if (import.meta.url === `file://${process.argv[1]}`) {
	const failures = checkParity(process.argv[2], process.argv[3]);
	console.log(failures.length ? failures.join('\n') : `Markup parity holds for all ${Object.keys(PAGES).length} design pages.`);
	process.exit(failures.length ? 1 : 0);
}
