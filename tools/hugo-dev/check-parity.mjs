// Markup parity between the design pages and the demo built from the theme.
// Both pages reduce to a tree of tag names, classes, roles and ARIA attribute
// names; runs of siblings with the same tag and classes collapse to their first.
// The trees must be equal once parity-exceptions.json has removed its elements.
//   node check-parity.mjs <design dir> <built demo dir>
import { readFileSync } from 'node:fs';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';
import { parse } from 'node-html-parser';

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

function signature(element) {
	const classes = (element.getAttribute('class') ?? '').split(/\s+/).filter(Boolean).sort().join('.');
	const role = element.getAttribute('role');
	const aria = Object.keys(element.attributes).filter((a) => a.startsWith('aria-')).sort();
	return `${element.rawTagName.toLowerCase()}${classes ? `.${classes}` : ''}${role ? `[role=${role}]` : ''}${aria.length ? `[${aria.join(',')}]` : ''}`;
}

function tree(element) {
	const children = [];
	let previousKey = null;
	for (const child of element.childNodes) {
		if (child.nodeType !== 1) continue;
		const key = `${child.rawTagName}|${child.getAttribute('class') ?? ''}`;
		if (key === previousKey) continue;
		previousKey = key;
		children.push(tree(child));
	}
	return { sig: signature(element), children };
}

function body(html, page, side) {
	const root = parse(html, { comment: false });
	const bodyElement = root.querySelector('body');
	const drop = ['script', '.demo-bar', ...exceptions.filter((e) => e.page === page && (e.side === side || e.side === 'both')).map((e) => e.selector)];
	for (const selector of drop) for (const element of bodyElement.querySelectorAll(selector)) element.remove();
	return tree(bodyElement);
}

function compare(a, b, path, problems) {
	const here = `${path} > ${a.sig}`;
	if (a.sig !== b.sig) {
		problems.push(`${path}: design ${a.sig}, demo ${b.sig}`);
		return;
	}
	const length = Math.max(a.children.length, b.children.length);
	for (let i = 0; i < length; i += 1) {
		if (!a.children[i]) problems.push(`${here}: demo has extra ${b.children[i].sig}`);
		else if (!b.children[i]) problems.push(`${here}: design has ${a.children[i].sig}, demo has nothing`);
		else compare(a.children[i], b.children[i], here, problems);
	}
}

export function checkParity(designDir, demoDir) {
	const failures = [];
	for (const [page, url] of Object.entries(PAGES)) {
		const file = url.endsWith('/') ? join(demoDir, url, 'index.html') : join(demoDir, url);
		const problems = [];
		compare(body(readFileSync(join(designDir, page), 'utf8'), page, 'design'), body(readFileSync(file, 'utf8'), page, 'demo'), '', problems);
		failures.push(...problems.slice(0, 8).map((p) => `${page}${p}`));
	}
	return failures;
}

if (import.meta.url === `file://${process.argv[1]}`) {
	const failures = checkParity(process.argv[2], process.argv[3]);
	console.log(failures.length ? failures.join('\n') : `Markup parity holds for all ${Object.keys(PAGES).length} design pages.`);
	process.exit(failures.length ? 1 : 0);
}
