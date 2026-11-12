#!/usr/bin/env bash
# Local Jekyll builds and checks for the Readerly Jekyll theme. Needs Docker and Node.
#
#   tools/jekyll-dev/jekyll-dev.sh images   build the two engine images: pages (GitHub Pages 232, Jekyll 3.10) and jekyll4 (Jekyll 4.4)
#   tools/jekyll-dev/jekyll-dev.sh build    build the demo, the starter and the fixtures on both engines into tools/jekyll-dev/out/
#   tools/jekyll-dev/jekyll-dev.sh check    build, then run every check over the output (see README.md)
#   tools/jekyll-dev/jekyll-dev.sh serve [site] [port]   serve a built site (default demo) at http://localhost:<port>/sub/
#
# Files are copied into containers rather than bind-mounted, so the same commands
# work on a workstation and inside a CI job that talks to the host's Docker.
set -euo pipefail

here="$(cd "$(dirname "$0")" && pwd)"
repo="$(cd "$here/../.." && pwd)"
out="$here/out"
engines=(pages jekyll4)

images() {
	for engine in "${engines[@]}"; do
		docker build -q --build-arg ENGINE="$engine" -t "readerly-jekyll:$engine" "$here" >/dev/null
	done
}

stage_sites() {
	local stage="$1"
	mkdir -p "$stage/sites"
	cp -r "$repo/jekyll/demo" "$stage/sites/demo"
	cp -r "$repo/jekyll/starter" "$stage/sites/starter"
	cp -r "$repo/jekyll/starter" "$stage/sites/starter-drafts"
	for fixture in "$repo"/jekyll/fixtures/*/; do
		cp -r "$fixture" "$stage/sites/$(basename "$fixture")"
	done
	cp "$here/check.yml" "$here/build-sites.sh" "$stage/"
}

build_engine() {
	local engine="$1" stage="$2" cid status=0
	cid="$(docker create "readerly-jekyll:$engine" bash /src/build-sites.sh "$engine")"
	docker cp "$stage/." "$cid:/src"
	docker cp "$repo/jekyll/theme/." "$cid:/work/theme"
	docker start -a "$cid" || status=$?
	rm -rf "${out:?}/$engine" && mkdir -p "$out/$engine"
	docker cp "$cid:/out/." "$out/$engine" 2>/dev/null || true
	docker rm -f "$cid" >/dev/null
	return "$status"
}

build() {
	local stage status=0
	stage="$(mktemp -d)"
	trap 'rm -rf "$stage"' RETURN
	stage_sites "$stage"
	for engine in "${engines[@]}"; do
		build_engine "$engine" "$stage" || status=1
	done
	[[ "$status" == 0 ]] && echo "Built $(ls "$stage/sites" | tr '\n' ' ')on ${engines[*]} into $out"
	return "$status"
}

case "${1:-}" in
	images) images ;;
	build) build ;;
	check)
		build
		node "$here/check-builds.mjs" "$out"
		;;
	serve)
		node "$here/serve.mjs" "$out/pages/${2:-demo}" "${3:-4000}"
		;;
	*)
		sed -n '2,10p' "$0"
		exit 1
		;;
esac
