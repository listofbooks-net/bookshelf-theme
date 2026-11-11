#!/usr/bin/env bash
# Builds the distributable theme zip: the contents of wordpress/ inside a
# top-level bookshelf/ folder, ready for Appearance > Themes > Add New > Upload.
#
#   tools/build-zip.sh            writes dist/bookshelf-<version>.zip and prints its path
#
# Only files below wordpress/ go in; the design reference, tools and harness
# never ship. Editor and OS leftovers are excluded.
set -euo pipefail

repo="$(cd "$(dirname "$0")/.." && pwd)"
theme="$repo/wordpress"
version="$(sed -n 's/^Version:[[:space:]]*//p' "$theme/style.css" | head -n1 | tr -d '\r')"
[[ -n "$version" ]] || { echo "no Version header in wordpress/style.css" >&2; exit 1; }

for required in style.css theme.json readme.txt screenshot.png templates/index.html; do
	[[ -f "$theme/$required" ]] || { echo "missing wordpress/$required" >&2; exit 1; }
done

out="$repo/dist/bookshelf-$version.zip"
stage="$(mktemp -d)"
trap 'rm -rf "$stage"' EXIT

mkdir -p "$repo/dist" "$stage/bookshelf"
cp -R "$theme/." "$stage/bookshelf/"
find "$stage/bookshelf" \( -name '.DS_Store' -o -name 'Thumbs.db' -o -name '*.orig' -o -name '*.rej' -o -name '*.swp' -o -name '*~' -o -name '*.log' -o -name '*.map' \) -delete

rm -f "$out"
# -X drops extra file attributes and -D skips directory entries, so the zip holds only files.
(cd "$stage" && find bookshelf -type f | LC_ALL=C sort | zip -qXD "$out" -@)
echo "$out"
