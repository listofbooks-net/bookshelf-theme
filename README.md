# Bookshelf

Bookshelf is a free WordPress block theme for people who love books. It is built for a book blog first, and it also gives you patterns for an author website and a reading log. Each use is optional: the theme creates no pages, posts or categories, and every section can be removed in one click. It comes in two styles, Paper (light) and Lamplight (dark), and bundles its fonts, so it makes no third-party requests.

Requires WordPress 6.6 and PHP 7.4. License: GPLv2 or later.

## Layout

| Folder | What it holds |
| --- | --- |
| `design/` | Seven static HTML pages (blog home, review, author, reading log, archive, page, 404) and `tokens.json`. This is the approved visual reference. |
| `wordpress/` | The theme itself. Its contents are what ships; `wordpress/readme.txt` is the WordPress.org readme. |
| `tools/` | The token generator, the checks that CI runs, the zip builder and a local WordPress. None of it ships. |

## Design tokens

`design/tokens.json` is the single source for colors, type, spacing and radii. One command writes `design/assets/css/tokens.css`, the palette and presets in `wordpress/theme.json`, and the Lamplight style `wordpress/styles/lamplight.json`:

```sh
node tools/build-tokens.mjs           # regenerate
node tools/build-tokens.mjs --check   # exit 1 if a generated file is out of date
```

Edit the tokens, rerun the command and commit the generated files. Do not edit those files by hand.

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

It checks that the tokens are up to date, that `theme.json` and the style files match the WordPress schema (`npm ci --prefix tools && node tools/check-theme-json.mjs`), and that every PHP file passes `php -l` on PHP 7.4 and 8.4.

## Build the zip

```sh
tools/build-zip.sh
```

This writes `dist/bookshelf-<version>.zip`, holding `wordpress/` as `bookshelf/`. Upload it under Appearance > Themes > Add New. To prove it installs on a fresh site:

```sh
tools/wp-dev/wp-dev.sh zip-test dist/bookshelf-1.0.0.zip
```

## The footer credit

The footer keeps one link, "Theme by List of Books", to https://www.listofbooks.net/. A site owner can edit or delete it in the Site Editor.
