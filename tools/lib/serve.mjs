// Serves a built static site under /sub/, the baseurl the check builds use, the
// way a static host does: directory URLs get index.html and misses get 404.html.
//   node serve.mjs <site dir> [port]
import { createServer } from 'node:http';
import { readFile, stat } from 'node:fs/promises';
import { extname, join, normalize } from 'node:path';

const BASE = '/sub';
const TYPES = { '.html': 'text/html; charset=utf-8', '.css': 'text/css', '.js': 'text/javascript', '.svg': 'image/svg+xml', '.woff2': 'font/woff2', '.xml': 'application/xml', '.json': 'application/json', '.txt': 'text/plain', '.png': 'image/png', '.jpg': 'image/jpeg' };

async function resolve(root, path) {
	const file = join(root, normalize(path));
	try {
		const info = await stat(file);
		if (info.isDirectory()) return path.endsWith('/') ? resolve(root, `${path}index.html`) : { redirect: `${BASE}${path}/` };
		return { file };
	} catch {
		return null;
	}
}

export function startServer(root, listenPort) {
	return new Promise((done) => {
		const server = createServer(async (request, response) => {
			const url = new URL(request.url, 'http://localhost');
			const path = decodeURIComponent(url.pathname);
			let found = path === BASE || path.startsWith(`${BASE}/`) ? await resolve(root, path.slice(BASE.length) || '/') : null;
			if (found?.redirect) {
				response.writeHead(301, { location: found.redirect + url.search });
				return response.end();
			}
			let status = 200;
			if (!found) {
				status = 404;
				found = { file: join(root, '404.html') };
			}
			try {
				const body = await readFile(found.file);
				response.writeHead(status, { 'content-type': TYPES[extname(found.file)] ?? 'application/octet-stream' });
				response.end(body);
			} catch {
				response.writeHead(404).end();
			}
		});
		server.listen(Number(listenPort), '127.0.0.1', () => done(server));
	});
}

if (import.meta.url === `file://${process.argv[1]}`) {
	const [root, port = '4000'] = process.argv.slice(2);
	await startServer(root, port);
	console.log(`Serving ${root} at http://localhost:${port}${BASE}/`);
}
