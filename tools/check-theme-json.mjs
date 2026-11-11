// Checks that wordpress/theme.json and every file in wordpress/styles/ is valid
// JSON and matches the WordPress theme.json schema named by the $schema of
// wordpress/theme.json. Every styles file must name the same schema.
//
// Usage: npm ci --prefix tools && node tools/check-theme-json.mjs
//
// The schema is fetched from schemas.wp.org, so this needs network access.

import { readFileSync, readdirSync, statSync } from 'node:fs';
import { dirname, join, relative } from 'node:path';
import { fileURLToPath } from 'node:url';
import Ajv from 'ajv';

const root = join(dirname(fileURLToPath(import.meta.url)), '..');
const themeDir = join(root, 'wordpress');
const FETCH_TIMEOUT_MS = 30_000;

function jsonFiles(dir) {
	return readdirSync(dir).flatMap((name) => {
		const path = join(dir, name);
		if (statSync(path).isDirectory()) return jsonFiles(path);
		return name.endsWith('.json') ? [path] : [];
	});
}

const files = [join(themeDir, 'theme.json'), ...jsonFiles(join(themeDir, 'styles'))];
const schemaUrl = JSON.parse(readFileSync(files[0], 'utf8')).$schema;
if (!schemaUrl) {
	console.error('FAIL wordpress/theme.json has no $schema');
	process.exit(1);
}

let response;
try {
	response = await fetch(schemaUrl, { signal: AbortSignal.timeout(FETCH_TIMEOUT_MS) });
} catch (error) {
	console.error(`FAIL could not fetch ${schemaUrl} within ${FETCH_TIMEOUT_MS / 1000}s: ${error.message}`);
	process.exit(1);
}
if (!response.ok) {
	console.error(`FAIL could not fetch ${schemaUrl}: HTTP ${response.status}`);
	process.exit(1);
}
const validate = new Ajv({ strict: false, allErrors: true }).compile(await response.json());

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
	if (data.$schema !== schemaUrl) {
		console.error(`FAIL ${name}: $schema is ${data.$schema}, expected ${schemaUrl}`);
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
