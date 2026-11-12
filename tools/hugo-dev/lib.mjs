// Helpers the checks share.
import { readdirSync, readFileSync } from 'node:fs';
import { join } from 'node:path';

// The baseURL path every check build uses (build-sites.sh).
export const BASE = '/sub';

export const htmlFiles = (root) => readdirSync(root, { recursive: true }).filter((f) => f.endsWith('.html')).sort();

// Hugo writes a redirect page for each alias, such as /page/1/ for a list's first
// page. It has a meta refresh and no content, so content checks skip it.
export const isAlias = (html) => /http-equiv=["']?refresh/i.test(html);

// The pages a content check looks at: every built HTML file that is not an alias.
export function contentPages(root) {
	return htmlFiles(root).filter((file) => !isAlias(readFileSync(join(root, file), 'utf8')));
}

// The site-relative URL a built file answers at.
export const urlOf = (file) => `/${file.replace(/index\.html$/, '')}`;
