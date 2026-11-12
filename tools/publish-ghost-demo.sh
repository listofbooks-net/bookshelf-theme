#!/usr/bin/env bash
# Publishes the static Ghost demo (the Marginalia site) that the Ghost Marketplace listing links to.
#
#   tools/publish-ghost-demo.sh [public url]     default: https://readerly-ghost.listofbooks.net
#
# 1. Checks that the tree is clean and on main, equal to origin/main after a fetch.
# 2. Starts Ghost with the theme and the demo content, runs every check
#    (tools/ghost-dev/ghost-dev.sh check), and exports the static copy for the public URL,
#    which export-static.mjs verifies in a browser.
# 3. Uploads the copy to the Cloudflare Pages project readerly-ghost-demo with wrangler.
#
# Uploading changes a public site and the Marketplace listing points at it, so only an operator
# runs this, by hand, and wrangler must already be logged in (wrangler login).
set -euo pipefail

public="${1:-https://readerly-ghost.listofbooks.net}"
repo="$(cd "$(dirname "$0")/.." && pwd)"
cd "$repo"

[[ -z "$(git status --porcelain)" ]] || { echo "the working tree is not clean" >&2; exit 1; }
[[ "$(git branch --show-current)" == main ]] || { echo "publish from main" >&2; exit 1; }
git fetch --quiet origin main
[[ "$(git rev-parse HEAD)" == "$(git rev-parse origin/main)" ]] || { echo "HEAD is not origin/main; pull or push first" >&2; exit 1; }

node tools/build-tokens.mjs --check
tools/ghost-dev/ghost-dev.sh gscan
tools/ghost-dev/ghost-dev.sh up
tools/ghost-dev/ghost-dev.sh check

stage="$(mktemp -d)"
trap 'rm -rf "$stage"; tools/ghost-dev/ghost-dev.sh down' EXIT
tools/ghost-dev/ghost-dev.sh export "$stage/site" "$public"

npx --yes wrangler pages deploy "$stage/site" --project-name readerly-ghost-demo
echo "Published the demo to $public."
