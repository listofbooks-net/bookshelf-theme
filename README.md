# Readerly

Readerly is a free theme for people who love books, for WordPress and for Jekyll. It is built for a book blog first, and it also gives you an author website and a reading log. Each use is optional: the theme creates no pages, posts or categories, and every section can be removed. It comes in two styles, Paper (light) and Lamplight (dark), and bundles its fonts, so it makes no third-party requests.

The WordPress theme requires WordPress 6.9 and PHP 7.4, under GPLv2 or later. The Jekyll theme runs on GitHub Pages and Jekyll 4, under the MIT License.

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
| `tools/` | The generator, the checks that CI runs, the zip builder, a local WordPress and the Jekyll and Hugo build images. None of it ships. |

## Generated files

`design/` is the single source for colors, type, spacing, radii, fonts, CSS and the theme script. One command writes every file derived from it:

- `design/assets/css/tokens.css` and `design/assets/css/fonts.css`
- the palette, presets and font faces in `wordpress/theme.json`, and the Lamplight style `wordpress/styles/lamplight.json`
- `wordpress/assets/fonts/`
- `jekyll/theme/assets/css/readerly.css` (fonts, tokens and `design/assets/css/style.css`), `jekyll/theme/assets/js/theme.js`, and the theme's fonts and images
- the demo's covers and images in `jekyll/demo/assets/`
- `hugo/theme/assets/readerly/css/readerly.css` (the same CSS as the Jekyll theme's) and `hugo/theme/assets/readerly/js/theme.js`, the theme's fonts and images in `hugo/theme/static/readerly/`, and the demo's covers and images in `hugo/theme/exampleSite/static/assets/`

```sh
node tools/build-tokens.mjs           # regenerate
node tools/build-tokens.mjs --check   # exit 1 if a generated file is out of date
```

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

It checks that the generated files are up to date, that `theme.json` and the style files match the WordPress schema (`npm ci --prefix tools && node tools/check-theme-json.mjs`), that every PHP file passes `php -l` on PHP 7.4 and 8.4, and every Jekyll check in the next section.

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

## Build the zip

```sh
tools/build-zip.sh
```

This writes `dist/readerly-<version>.zip`, holding `wordpress/` as `readerly/`. Upload it under Appearance > Themes > Add New. To prove it installs on a fresh site:

```sh
tools/wp-dev/wp-dev.sh zip-test dist/readerly-1.0.0.zip
```

## The footer credit

The footer keeps one link, "Theme by List of Books", to https://www.listofbooks.net/. In WordPress, a site owner can edit or delete it in the Site Editor. In Jekyll, a site owner removes it by adding an empty `_includes/shell/credit.html`.
