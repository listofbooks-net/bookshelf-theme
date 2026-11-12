#!/usr/bin/env bash
# Publishes a Jekyll theme release to the two public mirror repositories.
#
#   tools/publish-jekyll.sh <version>      for example: tools/publish-jekyll.sh 1.0.0
#
# 1. Checks that the tree is clean, on main, equal to origin/main after a fetch, and
#    that the gemspec has <version>.
# 2. Runs every Jekyll check (tools/jekyll-dev/jekyll-dev.sh check).
# 3. Splits jekyll/theme and jekyll/starter out of this repository's history and
#    pushes only the theme's tag v<version>.
# 4. Builds the starter on the GitHub Pages image with its remote_theme line pinned
#    to @v<version>, which downloads that tag from GitHub.
# 5. Only when that build passes, moves the branch v<major> that remote_theme users
#    follow and the theme's main, and pushes the starter's main.
#
# Pushing is public and cannot be taken back, so only an operator runs this, by hand.
# Set THEME_REMOTE and STARTER_REMOTE to push somewhere else, for example a fork.
set -euo pipefail

version="${1:?usage: tools/publish-jekyll.sh <version, for example 1.0.0>}"
[[ "$version" =~ ^[0-9]+\.[0-9]+\.[0-9]+$ ]] || { echo "version must look like 1.0.0" >&2; exit 1; }
major="${version%%.*}"
repo="$(cd "$(dirname "$0")/.." && pwd)"
theme_remote="${THEME_REMOTE:-git@github.com:listofbooks-net/jekyll-theme-readerly.git}"
starter_remote="${STARTER_REMOTE:-git@github.com:listofbooks-net/jekyll-theme-readerly-starter.git}"
cd "$repo"

[[ -z "$(git status --porcelain)" ]] || { echo "the working tree is not clean" >&2; exit 1; }
[[ "$(git branch --show-current)" == main ]] || { echo "publish from main" >&2; exit 1; }
git fetch --quiet origin main
[[ "$(git rev-parse HEAD)" == "$(git rev-parse origin/main)" ]] || { echo "HEAD is not origin/main; pull or push first" >&2; exit 1; }
grep -q "spec.version  = \"$version\"" jekyll/theme/jekyll-theme-readerly.gemspec ||
	{ echo "jekyll/theme/jekyll-theme-readerly.gemspec does not have version $version" >&2; exit 1; }
grep -q "remote_theme: listofbooks-net/jekyll-theme-readerly@v$major\$" jekyll/starter/_config.yml ||
	{ echo "jekyll/starter/_config.yml does not use @v$major" >&2; exit 1; }

node tools/build-tokens.mjs --check
tools/jekyll-dev/jekyll-dev.sh check

theme_sha="$(git subtree split --prefix=jekyll/theme)"
starter_sha="$(git subtree split --prefix=jekyll/starter)"
git push "$theme_remote" "$theme_sha:refs/tags/v$version"

stage="$(mktemp -d)"
trap 'rm -rf "$stage"' EXIT
cp -r jekyll/starter "$stage/starter"
sed -i "s|^remote_theme: .*|remote_theme: listofbooks-net/jekyll-theme-readerly@v$version|" "$stage/starter/_config.yml"
grep -q "^remote_theme: listofbooks-net/jekyll-theme-readerly@v$version\$" "$stage/starter/_config.yml" ||
	{ echo "could not pin the starter to @v$version" >&2; exit 1; }
cid="$(docker create -w /src/starter readerly-jekyll:pages bash -c 'BUNDLE_GEMFILE=/work/Gemfile bundle exec jekyll build --safe --quiet && test -f _site/assets/css/readerly.css && grep -q "Theme by List of Books" _site/index.html')"
docker cp "$stage/." "$cid:/src"
if docker start -a "$cid"; then
	echo "The starter builds on GitHub Pages against @v$version."
else
	echo "The starter did not build against @v$version. The tag v$version is pushed; delete it (git push $theme_remote :refs/tags/v$version) before you retry." >&2
	docker rm -f "$cid" >/dev/null
	exit 1
fi
docker rm -f "$cid" >/dev/null

git push "$theme_remote" "$theme_sha:refs/heads/main" "$theme_sha:refs/heads/v$major"
git push "$starter_remote" "$starter_sha:refs/heads/main"
echo "Published v$version. Mark the starter repository as a template in its GitHub settings if it is new."
