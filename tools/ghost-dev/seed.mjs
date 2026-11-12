// Loads the Marginalia demo (jekyll/demo and design/) into a running Ghost through the Admin API.
// Rerunnable: it deletes every post, page and tag first, and reuses images it uploaded before,
// so a second run leaves the same slugs and the same HTML.
//
//   node seed.mjs <ghost url>
//
// Environment: ADMIN_EMAIL, ADMIN_PASSWORD (defaults below), REPO (default: two folders up).
import { readFileSync, readdirSync } from 'node:fs';
import { basename, dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';
import { parseFrontMatter, parseYaml } from './yaml-lite.mjs';

const site = (process.argv[2] ?? '').replace(/\/$/, '');
if (!site) {
	console.error('usage: node seed.mjs <ghost url>');
	process.exit(2);
}
const here = dirname(fileURLToPath(import.meta.url));
const repo = process.env.REPO ?? join(here, '../..');
const demo = join(repo, 'jekyll/demo');
const design = join(repo, 'design');
const ADMIN_EMAIL = process.env.ADMIN_EMAIL ?? 'admin@readerly.test';
const ADMIN_PASSWORD = process.env.ADMIN_PASSWORD ?? 'Readerly-demo-2026!';

const read = (path) => readFileSync(path, 'utf8');
const yaml = (name) => parseYaml(read(join(demo, '_data', name)));
const page = (name) => parseFrontMatter(read(join(demo, name)));

let cookie = '';
async function api(method, path, body, { form } = {}) {
	const response = await fetch(`${site}/ghost/api/admin${path}`, {
		method,
		headers: {
			Origin: site,
			'Accept-Version': 'v6.0',
			...(cookie ? { Cookie: cookie } : {}),
			...(body && !form ? { 'Content-Type': 'application/json' } : {}),
		},
		body: form ?? (body ? JSON.stringify(body) : undefined),
	});
	const text = await response.text();
	if (!response.ok) throw new Error(`${method} ${path} -> ${response.status} ${text.slice(0, 400)}`);
	const set = response.headers.getSetCookie?.() ?? [];
	if (set.length) cookie = set.map((c) => c.split(';')[0]).join('; ');
	try {
		return JSON.parse(text);
	} catch {
		return {};
	}
}

async function waitForGhost() {
	for (let i = 0; i < 120; i += 1) {
		try {
			if ((await fetch(`${site}/ghost/api/admin/site/`)).ok) return;
		} catch {}
		await new Promise((r) => setTimeout(r, 1000));
	}
	throw new Error(`Ghost did not answer at ${site}`);
}

async function login() {
	const { setup } = await api('GET', '/authentication/setup/');
	if (!setup[0].status) {
		await api('POST', '/authentication/setup/', { setup: [{ name: 'Nora Vale', email: ADMIN_EMAIL, password: ADMIN_PASSWORD, blogTitle: 'Marginalia' }] });
	}
	await api('POST', '/session/', { username: ADMIN_EMAIL, password: ADMIN_PASSWORD });
}

// Image reuse. Ghost has no delete for uploads and renames a repeat upload to name-2.svg, so the
// URLs already in use are collected before the cleanup and uploaded again only when missing.
const imageUrls = new Map();
function rememberImages(json) {
	for (const url of JSON.stringify(json).match(/https?:[^"\\\s]*?\/content\/images\/[^"\\\s]+?\.svg/g) ?? []) {
		const key = basename(url).replace(/(-\d+)?\.svg$/, '.svg');
		if (!imageUrls.has(key) || url.length < imageUrls.get(key).length) imageUrls.set(key, url);
	}
}

async function upload(file) {
	const key = basename(file);
	if (imageUrls.has(key)) return imageUrls.get(key);
	const form = new FormData();
	form.set('file', new Blob([readFileSync(file)], { type: 'image/svg+xml' }), key);
	form.set('purpose', 'image');
	const { images } = await api('POST', '/images/upload/', null, { form });
	imageUrls.set(key, images[0].url);
	return images[0].url;
}
const cover = (path) => upload(join(design, 'assets/covers', basename(path)));
const asset = (path) => upload(join(design, 'assets/img', basename(path)));

async function clearContent() {
	for (const kind of ['posts', 'pages']) {
		const { [kind]: items } = await api('GET', `/${kind}/?limit=all&formats=html&fields=id,feature_image,html`);
		rememberImages(items);
		for (const item of items) await api('DELETE', `/${kind}/${item.id}/`);
	}
	const { tags } = await api('GET', '/tags/?limit=all&fields=id');
	for (const tag of tags) await api('DELETE', `/tags/${tag.id}/`);
}

const slugify = (title) => title.toLowerCase().replace(/[’']/g, '').replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
const escapeHtml = (text) => text.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
const dots = (text) => text.replace(/, /g, ' · ');
const card = (html) => `<!--kg-card-begin: html-->${html}<!--kg-card-end: html-->`;

// Top-level elements of an HTML fragment. An element with a class, or a <cite>, is design markup that
// Ghost's editor would flatten, so it becomes an HTML card; the rest stays native content.
function blocks(html) {
	const found = [];
	const tag = /<(\/?)([a-z0-9]+)\b[^>]*?(\/?)>/gi;
	const VOID = new Set(['img', 'hr', 'br', 'input']);
	let depth = 0;
	let start = -1;
	for (const m of html.matchAll(tag)) {
		const name = m[2].toLowerCase();
		const closing = m[1] === '/';
		const selfClosing = VOID.has(name) || m[3] === '/';
		if (!closing && depth === 0) start = m.index;
		if (!closing && !selfClosing) depth += 1;
		if (closing) depth -= 1;
		if (depth === 0) {
			const end = m.index + m[0].length;
			found.push(html.slice(start, end));
		}
	}
	return found;
}

function toCards(html) {
	return blocks(html).map((block) => (/^<[a-z0-9]+\s[^>]*\bclass=/i.test(block) || block.includes('<cite') ? card(block) : block)).join('\n');
}

function paragraphs(body) {
	return body.trim().startsWith('<') ? body.trim() : body.trim().split(/\n\s*\n/).map((p) => `<p>${escapeHtml(p.trim())}</p>`).join('\n');
}

function verdictHtml(v, rating) {
	const stars = `<span class="rating rating--large" style="--rating: ${rating}" role="img" aria-label="Rated ${rating} out of 5"></span><span aria-hidden="true">${rating} / 5</span>`;
	const items = (list) => list.map((i) => `<li>${escapeHtml(i)}</li>`).join('\n');
	return `<section class="verdict" aria-labelledby="verdict-title">
<div class="verdict__head">
<h2 id="verdict-title">My verdict</h2>
<div class="rating-line">${stars}</div>
</div>
<p class="verdict__summary">${escapeHtml(v.summary)}</p>
<div class="verdict__lists">
<div>
<h3>What worked</h3>
<ul>
${items(v.liked)}
</ul>
</div>
<div>
<h3>Less so</h3>
<ul>
${items(v.disliked)}
</ul>
</div>
</div>
</section>`;
}

function block(html, opening, name = 'div') {
	const start = html.indexOf(opening);
	if (start < 0) throw new Error(`design element not found: ${opening}`);
	let depth = 0;
	for (const m of html.slice(start).matchAll(new RegExp(`<(/?)${name}\\b[^>]*>`, 'g'))) {
		depth += m[1] ? -1 : 1;
		if (depth === 0) return html.slice(start, start + m.index + m[0].length);
	}
	throw new Error(`unclosed element: ${opening}`);
}

const STATUS = { read: '#read', reading: '#reading', want: '#want-to-read', dnf: '#did-not-finish' };
const at = (day, offsetMinutes = 0) => new Date(Date.parse(`${day}T09:00:00.000Z`) - offsetMinutes * 60000).toISOString();
const stripTags = (html) => html.replace(/<[^>]+>/g, '');

function bookTags({ by, series, rating, pages, published, status, onPage, buy, own }) {
	return [
		by && `#by:${by}`,
		series && `#series:${dots(series)}`,
		rating && `#rating:${rating}`,
		pages && `#pages:${pages}`,
		published && `#published:${dots(published)}`,
		buy && `#buy:${buy}`,
		own && '#book',
		status && STATUS[status],
		onPage && `#on-page:${onPage}`,
	].filter(Boolean);
}

const created = { posts: [], pages: [] };
async function create(kind, item) {
	const { [kind]: made } = await api('POST', `/${kind}/?source=html`, { [kind]: [{ status: 'published', ...item, tags: item.tags.map((name) => ({ name })) }] });
	created[kind].push(made[0].slug);
	return made[0];
}

async function loadReviews() {
	const dir = join(demo, '_posts');
	const files = readdirSync(dir).sort();
	const reviewTitles = new Set();
	// The theme treats every public tag as a genre, so a Jekyll tag becomes a public Ghost tag only when
	// it is some review's genre. Series and ratings already travel as internal #series: and #rating: tags.
	const genres = new Set(files.map((file) => parseFrontMatter(read(join(dir, file))).data.genre).filter(Boolean));
	for (const file of files) {
		const { data, body } = parseFrontMatter(read(join(dir, file)));
		const [, day, slug] = file.match(/^(\d{4}-\d{2}-\d{2})-(.+)\.md$/);
		reviewTitles.add(data.title);
		let html = paragraphs(body)
			.replace(/\{\{\s*"([^"]+)"\s*\|\s*relative_url\s*\}\}/g, (_, path) => path);
		const images = [...html.matchAll(/src="(\/assets\/img\/[^"]+)"/g)];
		for (const [, path] of images) html = html.replace(path, await asset(path));
		html = toCards(html);
		if (data.verdict) html += `\n${card(verdictHtml(data.verdict, data.rating))}`;
		const publicTags = [data.genre, ...(data.tags ?? []).filter((tag) => genres.has(tag))].filter(Boolean);
		await create('posts', {
			title: data.title,
			slug,
			html,
			published_at: at(day),
			featured: file === files.at(-1),
			feature_image: await cover(data.cover),
			feature_image_alt: `Cover of ${data.title} by ${data.book_author}`,
			custom_excerpt: data.excerpt,
			tags: [...publicTags, ...bookTags({ by: data.book_author, series: data.series, rating: data.rating, pages: data.pages, published: data.publication, status: data.status })],
		});
	}
	return reviewTitles;
}

async function loadBooks(shelf) {
	const books = yaml('books.yml');
	const own = new Map();
	for (const book of books) {
		const entry = shelf.find((s) => s.title === book.title);
		const pages = book.facts?.match(/(\d+) pages/)?.[1] ?? entry?.pages;
		const published = book.facts?.match(/Published (\d+ \w+ \d{4})/)?.[1];
		const day = published ? new Date(`${published} UTC`).toISOString().slice(0, 10) : `${book.year}-06-01`;
		const by = book.book_author ?? entry?.book_author;
		const where = book.featured ? card(block(read(join(design, 'author.html')), '<div class="where-to-buy"')) : '';
		await create('pages', {
			title: book.title,
			html: where,
			published_at: at(day),
			featured: book.featured === true,
			feature_image: await cover(book.cover),
			feature_image_alt: `Cover of ${book.title} by ${by ?? 'Isobel Thorne'}`,
			custom_excerpt: book.featured ? book.lead : book.blurb,
			tags: [book.kind, ...bookTags({ by, pages, buy: '/the-bell-foundry/#where-to-buy', own: true, status: entry?.status, onPage: entry?.current_page })],
		});
		own.set(book.title, true);
	}
	return own;
}

async function loadShelf(shelf, reviewTitles, ownBooks) {
	let index = 0;
	for (const entry of shelf) {
		index += 1;
		if (reviewTitles.has(entry.title) || ownBooks.has(entry.title)) continue;
		await create('pages', {
			title: entry.title,
			html: '',
			published_at: at('2026-11-09', index),
			feature_image: entry.cover ? await cover(entry.cover) : null,
			feature_image_alt: entry.cover ? `Cover of ${entry.title} by ${entry.book_author}` : null,
			tags: bookTags({ by: entry.book_author, pages: entry.pages, status: entry.status, onPage: entry.current_page }),
		});
	}
}

async function loadPages() {
	const about = page('about.md');
	const aboutHtml = about.body.replace(/\{\{\s*"([^"]+)"\s*\|\s*relative_url\s*\}\}/g, (_, path) => path);
	let html = aboutHtml;
	for (const [, path] of [...aboutHtml.matchAll(/src="(\/assets\/img\/[^"]+)"/g)]) html = html.replace(path, await asset(path));
	await create('pages', { title: about.data.title, slug: 'about', html: toCards(html), published_at: at('2026-01-05'), custom_excerpt: about.data.lead, tags: ['#eyebrow:About'] });

	const log = page('reading-log.md').data;
	await create('pages', {
		title: log.intro.heading,
		slug: 'reading-log',
		html: '',
		published_at: at('2026-01-06'),
		custom_excerpt: log.intro.lead,
		custom_template: 'custom-reading-log',
		tags: [`#eyebrow:${log.intro.eyebrow}`],
	});

	const author = page('author.md').data;
	const authorDesign = read(join(design, 'author.html'));
	const sections = ['<section class="section" aria-labelledby="praise-title">', '<section class="section" id="events"'].map((o) => block(authorDesign, o, 'section').replace(/href="page\.html"/g, 'href="/about/"'));
	await create('pages', {
		title: author.title,
		slug: 'isobel-thorne',
		html: sections.map(card).join('\n'),
		published_at: at('2026-01-07'),
		feature_image: await asset(author.hero.portrait),
		feature_image_alt: 'Portrait placeholder for Isobel Thorne',
		custom_excerpt: stripTags(author.hero.lead),
		custom_template: 'custom-author-site',
		tags: [`#eyebrow:${author.hero.eyebrow}`],
	});
}

async function configureSite() {
	const config = parseYaml(read(join(demo, '_config.yml')));
	const nav = config.readerly.nav;
	const literary = '/tag/literary-fiction/';
	const primary = [
		{ label: 'Reviews', url: '/' },
		{ label: 'Reading log', url: '/reading-log/' },
		{ label: 'Archive', url: literary },
		{ label: 'About', url: '/about/' },
	];
	if (nav.length !== primary.length) throw new Error('the demo navigation changed; update the Ghost navigation in seed.mjs');
	const secondary = ['Literary fiction', 'Fantasy', 'Historical', 'Poetry'].map((label) => ({ label, url: `/tag/${slugify(label)}/` }));
	await api('PUT', '/settings/', {
		settings: [
			{ key: 'title', value: config.title },
			{ key: 'description', value: config.tagline },
			{ key: 'timezone', value: 'Europe/London' },
			{ key: 'locale', value: 'en' },
			{ key: 'navigation', value: JSON.stringify(primary) },
			{ key: 'secondary_navigation', value: JSON.stringify(secondary) },
			{ key: 'members_signup_access', value: 'all' },
			{ key: 'comments_enabled', value: 'all' },
			{ key: 'accent_color', value: '#9e3d27' },
		],
	});
	const { users } = await api('GET', '/users/me/');
	await api('PUT', `/users/${users[0].id}/`, {
		users: [{ name: config.author.name, slug: 'nora-vale', bio: config.author.bio, website: null, profile_image: await asset(config.author.avatar) }],
	});
	return config;
}

async function describeTags() {
	const lead = read(join(design, 'archive.html')).match(/<p class="lead">([^<]+)<\/p>/)[1];
	const { tags } = await api('GET', '/tags/slug/literary-fiction/');
	await api('PUT', `/tags/${tags[0].id}/`, { tags: [{ description: lead.replace(/&rsquo;/g, '’') }] });
}

async function themeSettings(config) {
	const { themes } = await api('GET', '/themes/');
	if (!themes.some((t) => t.name === 'readerly')) return console.log('theme readerly is not installed: skipped theme settings');
	if (!themes.find((t) => t.name === 'readerly').active) await api('PUT', '/themes/readerly/activate/');
	const home = page('index.html').data.intro;
	const wanted = {
		intro_eyebrow: home.eyebrow,
		intro_heading: home.heading,
		intro_lead: home.lead,
		secondary_button_text: 'See my reading log',
		secondary_button_url: '/reading-log/',
		newsletter_eyebrow: config.readerly.newsletter.eyebrow,
		newsletter_heading: config.readerly.newsletter.heading,
		newsletter_note: config.readerly.newsletter.note,
		footer_about: config.readerly.footer.about,
		copyright_note: config.readerly.footer.copyright,
	};
	const { custom_theme_settings: known } = await api('GET', '/custom_theme_settings/');
	const keys = new Set(known.map((s) => s.key));
	const skipped = Object.keys(wanted).filter((k) => !keys.has(k));
	const apply = Object.entries(wanted).filter(([k]) => keys.has(k)).map(([key, value]) => ({ key, value }));
	if (apply.length) await api('PUT', '/custom_theme_settings/', { custom_theme_settings: apply });
	console.log(skipped.length ? `theme settings skipped, the theme has no such key: ${skipped.join(', ')}` : 'theme settings: all keys applied');
}

await waitForGhost();
await login();
await clearContent();
const config = await configureSite();
const shelf = yaml('reading_log.yml');
const reviewTitles = await loadReviews();
const ownBooks = await loadBooks(shelf);
await loadShelf(shelf, reviewTitles, ownBooks);
await loadPages();
await describeTags();
await themeSettings(config);
console.log(`Seeded ${site}: ${created.posts.length} posts, ${created.pages.length} pages`);
console.log(`posts: ${created.posts.join(', ')}`);
console.log(`pages: ${created.pages.join(', ')}`);
