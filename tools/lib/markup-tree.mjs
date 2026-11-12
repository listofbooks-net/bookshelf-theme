// Reduces a page body to a tree of tag names, classes, roles and ARIA attribute
// names; runs of siblings with the same tag and classes collapse to their first.
// Shared by the Ghost markup-parity check.
import { parse } from 'node-html-parser';

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

export function body(html, page, side, list) {
	const root = parse(html, { comment: false });
	const bodyElement = root.querySelector('body');
	const drop = ['script', '.demo-bar', ...list.filter((e) => e.page === page && (e.side === side || e.side === 'both')).map((e) => e.selector)];
	for (const selector of drop) for (const element of bodyElement.querySelectorAll(selector)) element.remove();
	return tree(bodyElement);
}

export function compare(a, b, path, problems) {
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
