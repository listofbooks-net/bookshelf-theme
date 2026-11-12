// A parser for the small YAML subset the Jekyll demo uses: block maps and lists,
// flow lists and maps, quoted and plain scalars. Values stay strings, except
// true and false. Enough for front matter and _data files; not general YAML.

function splitTop(text, separator) {
	const parts = [];
	let depth = 0;
	let quote = '';
	let current = '';
	for (const ch of text) {
		if (quote) {
			if (ch === quote) quote = '';
		} else if (ch === '"' || ch === "'") quote = ch;
		else if (ch === '[' || ch === '{') depth += 1;
		else if (ch === ']' || ch === '}') depth -= 1;
		if (ch === separator && depth === 0 && !quote) {
			parts.push(current);
			current = '';
		} else current += ch;
	}
	if (current.trim()) parts.push(current);
	return parts.map((p) => p.trim());
}

function scalar(text) {
	const t = text.trim();
	if (t.startsWith('"') && t.endsWith('"')) return t.slice(1, -1).replace(/\\(["\\])/g, '$1');
	if (t.startsWith("'") && t.endsWith("'")) return t.slice(1, -1).replace(/''/g, "'");
	if (t.startsWith('[') && t.endsWith(']')) return splitTop(t.slice(1, -1), ',').map(scalar);
	if (t.startsWith('{') && t.endsWith('}')) {
		return Object.fromEntries(splitTop(t.slice(1, -1), ',').map((pair) => {
			const [key, ...rest] = splitTop(pair, ':');
			return [key, scalar(rest.join(':'))];
		}));
	}
	if (t === 'true') return true;
	if (t === 'false') return false;
	return t;
}

const KEY = /^([A-Za-z_][\w-]*):(?:\s+(.*))?$/;

export function parseYaml(source) {
	const lines = source
		.split('\n')
		.filter((l) => l.trim() && !l.trim().startsWith('#'))
		.map((l) => ({ indent: l.match(/^ */)[0].length, text: l.trim() }));
	let at = 0;

	function block(indent) {
		return lines[at].text.startsWith('- ') ? list(indent) : map(indent);
	}

	function map(indent) {
		const result = {};
		while (at < lines.length && lines[at].indent === indent && !lines[at].text.startsWith('- ')) {
			const match = lines[at].text.match(KEY);
			if (!match) throw new Error(`cannot parse YAML line: ${lines[at].text}`);
			at += 1;
			if (match[2] !== undefined) result[match[1]] = scalar(match[2]);
			else if (at < lines.length && lines[at].indent > indent) result[match[1]] = block(lines[at].indent);
			else if (at < lines.length && lines[at].indent === indent && lines[at].text.startsWith('- ')) result[match[1]] = list(indent);
			else result[match[1]] = null;
		}
		return result;
	}

	function list(indent) {
		const result = [];
		while (at < lines.length && lines[at].indent === indent && lines[at].text.startsWith('- ')) {
			const rest = lines[at].text.slice(2);
			if (KEY.test(rest) && !/^[{["']/.test(rest)) {
				lines[at] = { indent: indent + 2, text: rest };
				result.push(map(indent + 2));
			} else {
				at += 1;
				result.push(scalar(rest));
			}
		}
		return result;
	}

	return at < lines.length ? block(lines[0].indent) : {};
}

export function parseFrontMatter(source) {
	const match = source.match(/^---\n([\s\S]*?)\n---\n?([\s\S]*)$/);
	return match ? { data: parseYaml(match[1]), body: match[2] } : { data: {}, body: source };
}
