#!/usr/bin/env bash
# Publishes a Hugo theme release to the public mirror repository.
#
#   tools/publish-hugo.sh <version>      for example: tools/publish-hugo.sh 1.0.0
#
# 1. Checks that the tree is clean, on main and equal to origin/main after a fetch.
# 2. Runs every check (node tools/build-tokens.mjs --check and tools/hugo-dev/hugo-dev.sh check).
# 3. Splits hugo/theme out of this repository's history and pushes its tag
#    v<version>, then its main.
#
# Hugo Modules resolve semantic version tags, so the tag is what users get; there
# is no v<major> branch to move. The tag goes first: if it is rejected, nothing
# else has moved.
#
# Pushing is public and cannot be taken back, so only an operator runs this, by hand.
# Set THEME_REMOTE to push somewhere else, for example a fork.
set -euo pipefail

version="${1:?usage: tools/publish-hugo.sh <version, for example 1.0.0>}"
[[ "$version" =~ ^[0-9]+\.[0-9]+\.[0-9]+$ ]] || { echo "version must look like 1.0.0" >&2; exit 1; }
major="${version%%.*}"
repo="$(cd "$(dirname "$0")/.." && pwd)"
theme_remote="${THEME_REMOTE:-git@github.com:listofbooks-net/hugo-theme-readerly.git}"
cd "$repo"

# Go modules resolve a v2+ tag only when the module path ends in /v<major>.
if (( major >= 2 )); then
	grep -q "^module github.com/listofbooks-net/hugo-theme-readerly/v$major\$" hugo/theme/go.mod ||
		{ echo "hugo/theme/go.mod must declare the /v$major module path for v$version" >&2; exit 1; }
fi

[[ -z "$(git status --porcelain)" ]] || { echo "the working tree is not clean" >&2; exit 1; }
[[ "$(git branch --show-current)" == main ]] || { echo "publish from main" >&2; exit 1; }
git fetch --quiet origin main
[[ "$(git rev-parse HEAD)" == "$(git rev-parse origin/main)" ]] || { echo "HEAD is not origin/main; pull or push first" >&2; exit 1; }

node tools/build-tokens.mjs --check
tools/hugo-dev/hugo-dev.sh check

theme_sha="$(git subtree split --prefix=hugo/theme)"
git push "$theme_remote" "$theme_sha:refs/tags/v$version"
git push "$theme_remote" "$theme_sha:refs/heads/main"
echo "Published v$version. Submit the repository to themes.gohugo.io if it is new."
