#!/usr/bin/env node
// Writes ghost/locales/en.json from every {{t "..."}} string in the Ghost theme's templates,
// so the English file always holds exactly the strings the theme prints.
// Usage: node tools/ghost-locales.mjs [--check]   (--check writes nothing and exits 1 when stale)
import { readFileSync, readdirSync, writeFileSync } from 'node:fs';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';

const theme = join(dirname(fileURLToPath(import.meta.url)), '../ghost');
const templates = (dir) =>
	readdirSync(dir, { withFileTypes: true }).flatMap((entry) =>
		entry.isDirectory() ? (entry.name === 'assets' ? [] : templates(join(dir, entry.name))) : entry.name.endsWith('.hbs') ? [join(dir, entry.name)] : [],
	);
const strings = new Set(templates(theme).flatMap((file) => [...readFileSync(file, 'utf8').matchAll(/[({]t "([^"]+)"/g)].map((m) => m[1])));
const next = `${JSON.stringify(Object.fromEntries([...strings].sort((a, b) => a.localeCompare(b)).map((s) => [s, s])), null, '\t')}\n`;
const file = join(theme, 'locales/en.json');
if (readFileSync(file, 'utf8') === next) {
	console.log('ghost/locales/en.json is up to date.');
} else if (process.argv.includes('--check')) {
	console.error('ghost/locales/en.json is out of date (run node tools/ghost-locales.mjs)');
	process.exit(1);
} else {
	writeFileSync(file, next);
	console.log(`Wrote ghost/locales/en.json (${strings.size} strings)`);
}
