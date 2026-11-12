# Readerly

Readerly is a free theme for people who love books, for WordPress, Jekyll, Hugo and Ghost. It is built for a book blog first, and it also gives you an author website and a reading log. Each use is optional: the theme creates no pages, posts or categories, and every section can be removed. It comes in two styles, Paper (light) and Lamplight (dark), and bundles its fonts, so it makes no third-party requests.

The WordPress theme requires WordPress 6.9 and PHP 7.4, under GPLv2 or later. The Jekyll theme runs on GitHub Pages and Jekyll 4, the Hugo theme on Hugo 0.146.0 or later, and the Ghost theme on Ghost 6, all three under the MIT License.

## Layout

| Folder | What it holds |
| --- | --- |
| `design/` | Seven static HTML pages (blog home, review, author, reading log, archive, page, 404), their CSS, script and fonts, and `tokens.json`. This is the approved visual reference and the source of every generated file. |
| `wordpress/` | The WordPress theme. Its contents are what ships; `wordpress/readme.txt` is the WordPress.org readme. |
| `jekyll/theme/` | The Jekyll theme, published as `listofbooks-net/jekyll-theme-readerly`. |
| `jekyll/starter/` | A ready-to-publish site that uses the theme, published as the template repository `listofbooks-net/jekyll-theme-readerly-starter`. |
| `jekyll/demo/` | The Jekyll demo site, Marginalia, with the design pages' content. |
| `jekyll/fixtures/` | Sites that only the checks build: `bare` (a title and nothing else), `minima` (the output of `jekyll new`) and `sparse` (one post per missing field). |
| `hugo/theme/` | The Hugo theme, published as `listofbooks-net/hugo-theme-readerly`, with its demo site, Marginalia, in `hugo/theme/exampleSite/`. |
| `hugo/fixtures/` | Sites that only the checks build: `bare` (a title and nothing else), `quickstart` (what `hugo new site` and `hugo new content` make), `sparse` (one review per missing field), `paginated` (a custom pager path) and `no-credit` (the credit partial emptied). |
| `ghost/` | The Ghost theme. Its contents are what ships; `ghost/README.md` is the theme's documentation. |
| `tools/` | The generator, the checks that CI runs, the zip builder, a local WordPress and the Jekyll and Hugo build images and a local Ghost. None of it ships. |

## Generated files

`design/` is the single source for colors, type, spacing, radii, fonts, CSS and the theme script. One command writes every file derived from it:

- `design/assets/css/tokens.css` and `design/assets/css/fonts.css`
- the palette, presets and font faces in `wordpress/theme.json`, and the Lamplight style `wordpress/styles/lamplight.json`
- `wordpress/assets/fonts/`
- `jekyll/theme/assets/css/readerly.css` (fonts, tokens and `design/assets/css/style.css`), `jekyll/theme/assets/js/theme.js`, and the theme's fonts and images
- the demo's covers and images in `jekyll/demo/assets/`
- `hugo/theme/assets/readerly/css/readerly.css` (the same CSS as the Jekyll theme's) and `hugo/theme/assets/readerly/js/theme.js`, the theme's fonts and images in `hugo/theme/static/readerly/`, and the demo's covers and images in `hugo/theme/exampleSite/static/assets/`
- `ghost/assets/css/readerly.css` (fonts, tokens and `design/assets/css/style.css`), `ghost/assets/js/theme.js`, and the Ghost theme's fonts and images

```sh
node tools/build-tokens.mjs           # regenerate
node tools/build-tokens.mjs --check   # exit 1 if a generated file is out of date
```

`node tools/ghost-locales.mjs [--check]` does the same for `ghost/locales/en.json`, from the strings in the Ghost templates.

Edit the source in `design/`, rerun the command and commit the generated files. Do not edit those files by hand. The design pages link `design/assets/css/demo.css`, which holds the demo-bar rules that no theme ships.

## Run WordPress locally

You need Docker. The script starts WordPress with `WP_DEBUG` on, installs it, activates the theme and loads demo content (ten reviews, four pages and a menu). The site opens at the URL it prints, with the login `admin` / `admin`.

```sh
tools/wp-dev/wp-dev.sh up                      # start, install, seed
tools/wp-dev/wp-dev.sh style lamplight         # or: paper
tools/wp-dev/wp-dev.sh check                   # Theme Check against the theme
tools/wp-dev/wp-dev.sh wp <wp-cli arguments>   # any wp-cli command
tools/wp-dev/wp-dev.sh down                    # stop, keep the data
tools/wp-dev/wp-dev.sh clean                   # stop and delete the data
```

The theme folder is mounted read-only, so edits show up on reload. `tools/wp-dev/check-pages.cjs` screenshots every demo page at 1280 px and 390 px and checks for overflow, broken images, console errors, third-party requests and the footer credit; see the header of that file for usage.

## Checks

CI is `.github/workflows/ci.yml`. GitHub Actions is off for this repository, so run it locally with [act](https://github.com/nektos/act):

```sh
act pull_request -W .github/workflows/ci.yml
```

It checks that the generated files are up to date, that `theme.json` and the style files match the WordPress schema (`npm ci --prefix tools && node tools/check-theme-json.mjs`), that every PHP file passes `php -l` on PHP 7.4 and 8.4, and every Jekyll, Hugo and Ghost check in the sections below.

## Build and check the Jekyll theme

You need Docker and Node. The script builds two images: `pages`, with the `github-pages` gem that GitHub Pages builds with (Jekyll 3.10, run with `--safe`), and `jekyll4`, with Jekyll 4.4. Both load the theme from `jekyll/theme/` as a local gem, so nothing is downloaded from the published repository.

```sh
tools/jekyll-dev/jekyll-dev.sh images        # build the two images, once
tools/jekyll-dev/jekyll-dev.sh build         # build every site on both engines into tools/jekyll-dev/out/
tools/jekyll-dev/jekyll-dev.sh serve demo    # serve the demo at http://localhost:4000/sub/
```

`build` builds the demo, the starter (once as published, once with its example draft) and the four fixtures, under the baseurl `/sub`. A build that prints anything, warnings included, fails.

To run the checks, install the tools and point `CHROMIUM_PATH` at a Chromium:

```sh
npm ci --prefix tools
CHROMIUM_PATH=/usr/bin/chromium tools/jekyll-dev/jekyll-dev.sh check
```

`check` builds, then runs `tools/jekyll-dev/check-builds.mjs`:

1. Each site's HTML is the same on both engines, whitespace aside.
2. Every internal link and asset resolves to a built file under the baseurl.
3. Every attribute parses, no heading is empty, and each fixture's own cases hold: quotes in titles and authors, the Minima page body, a custom `paginate_path`, an untitled archive and missing titles (`check-markup.mjs`).
4. The demo has the same markup as the seven design pages (`check-parity.mjs`). Each allowed difference is in `parity-exceptions.json` with its reason.
5. Every page of every site passes `tools/wp-dev/check-pages.cjs` at 1280 px and 390 px, in Paper and Lamplight: no overflow, broken images, console errors or third-party requests, one h1, no empty headings or sections, and exactly one footer credit.

To compare the design pages with the demo by eye, run `node tools/jekyll-dev/side-by-side.mjs` after a build. It writes `tools/jekyll-dev/out/side-by-side/index.html`.

`tools/publish-jekyll.sh <version>` pushes a release to the two public repositories: the tag first, then, once the starter builds against that tag, the `v<major>` branch and `main`. Only an operator runs it; see the header of the script.

## Build and check the Hugo theme

You need Docker and Node. The script builds two images from the official extended Hugo release, each checked against its published checksum: the theme's minimum version (`0.146.0`, which must equal `[module.hugoVersion] min` in `hugo/theme/hugo.toml`) and the latest version the theme is tested on (`0.169.0`). Both load the theme from `hugo/theme/`, so nothing is downloaded from the published repository.

```sh
tools/hugo-dev/hugo-dev.sh images        # build the two images, once
tools/hugo-dev/hugo-dev.sh build         # build every site on both versions into tools/hugo-dev/out/
tools/hugo-dev/hugo-dev.sh serve demo    # serve the demo at http://localhost:4000/sub/
```

`build` builds the demo and the five fixtures under the baseURL `http://localhost:4000/sub/`. A build fails when Hugo exits with an error or writes anything to stderr, where it prints every warning.

To run the checks, install the tools and point `CHROMIUM_PATH` at a Chromium:

```sh
npm ci --prefix tools
CHROMIUM_PATH=/usr/bin/chromium tools/hugo-dev/hugo-dev.sh check
```

`check` builds, then runs `tools/hugo-dev/check-builds.mjs`:

1. Each site's HTML is the same on both Hugo versions, whitespace aside.
2. Every internal link and asset resolves to a built file under the baseURL.
3. Every attribute parses, no heading is empty, and each fixture's own cases hold: quotes and `</script>` in titles and authors, a plain-string author and a menu of plain URLs on a site made with `hugo new site`, a custom pager path, and no credit link once the partial is emptied (`check-markup.mjs`).
4. The demo has the same markup as the seven design pages (`check-parity.mjs`). Each allowed difference is in `parity-exceptions.json` with its reason.
5. Every page of every site but `no-credit` passes `tools/wp-dev/check-pages.cjs` at 1280 px and 390 px, in Paper and Lamplight: no overflow, broken images, console errors or third-party requests, one h1, no empty headings or sections, and exactly one footer credit.

To compare the design pages with the demo by eye, run `node tools/hugo-dev/side-by-side.mjs` after a build. It writes `tools/hugo-dev/out/side-by-side/index.html`. `tools/hugo-dev/hugo-dev.sh screenshots` rebuilds the two listing images, `hugo/theme/images/screenshot.png` (1500 by 1000) and `tn.png` (900 by 600), from the demo home page in Paper.

`tools/publish-hugo.sh <version>` pushes a release to the public repository: the tag first, then `main`. Hugo Modules resolve the semantic version tag, so there is no branch to move. Only an operator runs it; see the header of the script.

## Run Ghost locally

You need Docker and Node 20 or later. The script starts Ghost 6 (the `ghost:6` image, SQLite) with `ghost/` mounted read-only as the theme `readerly`, creates the admin, activates the theme and loads the Marginalia demo. Ghost listens on the first free port from 2380 (set `PORT` to fix it) and the script remembers the port in `tools/ghost-dev/.state`. The admin login is `admin@readerly.test` with the password in `tools/ghost-dev/seed.mjs`.

```sh
tools/ghost-dev/ghost-dev.sh up                     # start, set up, activate the theme, seed
tools/ghost-dev/ghost-dev.sh seed                   # reload the demo content
tools/ghost-dev/ghost-dev.sh gscan                  # GScan for Ghost 6 on ghost/; or: gscan dist/readerly-ghost-1.0.0.zip -z
tools/ghost-dev/ghost-dev.sh check                  # markup parity, browser checks, side-by-side screenshots
tools/ghost-dev/ghost-dev.sh export site/ https://readerly-ghost-demo.pages.dev   # static copy, verified in a browser
tools/ghost-dev/ghost-dev.sh url                    # print the site URL
tools/ghost-dev/ghost-dev.sh down                   # stop, keep the data
tools/ghost-dev/ghost-dev.sh clean                  # remove the container and its data
```

`gscan` runs the GScan that ships in the `ghost:6` image and exits with 1 on any error or warning. Recommendations do not fail it.

Editing a template needs `down` and `up` again, because Ghost reads templates at start. To mount another theme folder, set `THEME_DIR`.

### Demo content

`seed.mjs` loads the demo through the Admin API. It first deletes every post, page and tag, so a rerun gives the same slugs and the same HTML. It reuses the images it uploaded before, because Ghost cannot delete an upload and would rename a repeat to `name-2.svg`. The content comes from `jekyll/demo` and `design/`:

- Ten reviews are posts. The newest, The Salt Orchard, is featured. A review's `excerpt` is the post's `custom_excerpt`; the Jekyll `lead` is not loaded, because Ghost has one summary field and the theme prints it under the title and on the cards.
- Facts about a book are internal tags in the order genre, other public tags, `#by:`, `#series:`, `#rating:`, `#pages:`, `#published:`, then the status (`#read`, `#reading`, `#want-to-read`, `#did-not-finish`). The tag table is in the theme README.
- Books on the reading log with no review are pages with the same fact tags, plus `#on-page:` for a book in progress. Isobel Thorne's four novels are pages tagged `#book` and `#buy:`.
- Design markup that Ghost's editor would flatten (the verdict, the pull quote, the praise and events lists, the where-to-buy block) goes in as HTML cards.
- The site settings, navigation, accent colour, Nora Vale's profile and the theme's custom settings are set too. The seed prints any theme setting the theme does not define.

### Checks against the running site

`check` runs three things:

1. Markup parity. Each of the seven design pages and its Ghost page reduce to a tree of tag names, classes, roles and ARIA attributes, and the trees must match. `tools/ghost-dev/parity-exceptions.json` lists the elements removed from either side first, each with a reason. The algorithm is `tools/jekyll-dev/check-parity.mjs`.
2. `tools/wp-dev/check-pages.cjs` on the pages in `tools/ghost-dev/pages.json`, at 1280 px and 390 px, in Paper and Lamplight. Requests to `cdn.jsdelivr.net/ghost/`, where Ghost loads Portal and its search from, are allowed; any other third-party request fails.
3. `tools/ghost-dev/side-by-side.mjs` screenshots each design page next to the Ghost page. Open `tools/ghost-dev/out/side-by-side/index.html`.

### Static copy for the demo site

`export` crawls the sitemaps, the home page, the feed, the 404 page and everything they reference into a folder you can host on Cloudflare Pages. It rewrites the Ghost address to the public one. It removes Portal, the comments and the member-attribution scripts, and the comments section. It turns each `form[data-members-form]` into `data-demo-form`, which the theme script confirms in place. Search keeps working: sodo-search reads three files, `/ghost/api/content/search-index/posts/`, `.../authors/` and `.../tags/`, and the export saves them. It writes `_headers`, which allows framing by `ghost.org` for the Marketplace preview, and `_redirects` when Ghost redirected something while crawling. The export then serves the copy, loads every page in a browser and fails on a request to the Ghost address, a failed request, or a search for "Salt" that finds nothing.

`tools/publish-ghost-demo.sh` runs all of that and deploys the copy with `wrangler pages deploy`. It changes a public site, so only an operator runs it, by hand.

### In CI

The `ghost` job builds the zip (`tools/build-ghost-zip.sh`, which writes `dist/readerly-ghost-<version>.zip`), runs GScan on it, starts Ghost with the unzipped theme, seeds it and runs `check`. A CI job cannot reach ports published on the host, so it sets `GHOST_NETWORK`: Ghost joins that Docker network as `ghost`, and the seed and the checks run in a Playwright container on the same network.

## Build the zip

```sh
tools/build-zip.sh
```

This writes `dist/readerly-<version>.zip`, holding `wordpress/` as `readerly/`. Upload it under Appearance > Themes > Add New. To prove it installs on a fresh site:

```sh
tools/wp-dev/wp-dev.sh zip-test dist/readerly-1.0.0.zip
```

## The footer credit

The footer keeps one link, "Theme by List of Books", to https://www.listofbooks.net/. In WordPress, a site owner can edit or delete it in the Site Editor. In Jekyll, a site owner removes it by adding an empty `_includes/shell/credit.html`, and in Hugo an empty `layouts/_partials/credit.html`. The Ghost theme prints it next to "Published with Ghost" from `ghost/partials/site/credit.hbs`, which a site owner edits in the theme files.
