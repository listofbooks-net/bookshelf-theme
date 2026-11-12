#!/usr/bin/env bash
# Builds the distributable Ghost theme zip: the contents of ghost/ inside a
# top-level readerly/ folder, ready for Ghost Admin > Settings > Design > Change theme > Upload theme.
#
#   tools/build-ghost-zip.sh      writes dist/readerly-ghost-<version>.zip and prints its path
#
# Only files below ghost/ go in; the design reference, tools and harness never
# ship. Dotfiles, node_modules and editor and OS leftovers are excluded.
set -euo pipefail

repo="$(cd "$(dirname "$0")/.." && pwd)"
theme="$repo/ghost"
version="$(sed -n 's/^[[:space:]]*"version":[[:space:]]*"\([^"]*\)".*/\1/p' "$theme/package.json" | head -n1)"
[[ -n "$version" ]] || { echo "no version in ghost/package.json" >&2; exit 1; }

for required in package.json default.hbs index.hbs post.hbs; do
	[[ -f "$theme/$required" ]] || { echo "missing ghost/$required" >&2; exit 1; }
done

out="$repo/dist/readerly-ghost-$version.zip"
stage="$(mktemp -d)"
trap 'rm -rf "$stage"' EXIT

mkdir -p "$repo/dist" "$stage/readerly"
cp -R "$theme/." "$stage/readerly/"
find "$stage/readerly" -depth \( -name '.*' -o -name node_modules -o -name 'Thumbs.db' -o -name '*.orig' -o -name '*.rej' -o -name '*~' -o -name '*.log' -o -name '*.map' \) -exec rm -rf {} +

rm -f "$out"
# -X drops extra file attributes and -D skips directory entries, so the zip holds only files.
(cd "$stage" && find readerly -type f | LC_ALL=C sort | zip -qXD "$out" -@)
echo "$out"
