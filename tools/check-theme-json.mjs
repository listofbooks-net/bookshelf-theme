// Checks that wordpress/theme.json and every file in wordpress/styles/ is valid
// JSON and matches the WordPress theme.json schema for the minimum WordPress
// version the theme supports.
//
// Usage: npm ci --prefix tools && node tools/check-theme-json.mjs
//
// The schema is fetched from schemas.wp.org, so this needs network access.

import { readFileSync, readdirSync, statSync } from 'node:fs';
import { dirname, join, relative } from 'node:path';
import { fileURLToPath } from 'node:url';
import Ajv from 'ajv-draft-04';

const root = join(dirname(fileURLToPath(import.meta.url)), '..');
const themeDir = join(root, 'wordpress');
const schemaUrl = 'https://schemas.wp.org/wp/6.6/theme.json';

function jsonFiles(dir) {
	return readdirSync(dir).flatMap((name) => {
		const path = join(dir, name);
		if (statSync(path).isDirectory()) return jsonFiles(path);
		return name.endsWith('.json') ? [path] : [];
	});
}

const response = await fetch(schemaUrl);
if (!response.ok) throw new Error(`could not fetch ${schemaUrl}: HTTP ${response.status}`);
const validate = new Ajv({ strict: false, allErrors: true }).compile(await response.json());

const files = [join(themeDir, 'theme.json'), ...jsonFiles(join(themeDir, 'styles'))];
let failed = false;
for (const file of files) {
	const name = relative(root, file);
	let data;
	try {
		data = JSON.parse(readFileSync(file, 'utf8'));
	} catch (error) {
		console.error(`FAIL ${name}: ${error.message}`);
		failed = true;
		continue;
	}
	if (validate(data)) {
		console.log(`ok   ${name}`);
		continue;
	}
	failed = true;
	console.error(`FAIL ${name}`);
	for (const error of validate.errors) console.error(`       ${error.instancePath || '/'} ${error.message} ${JSON.stringify(error.params)}`);
}
process.exit(failed ? 1 : 0);
