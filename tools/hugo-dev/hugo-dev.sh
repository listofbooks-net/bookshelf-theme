#!/usr/bin/env bash
# Local Hugo builds and checks for the Readerly Hugo theme. Needs Docker and Node.
#
#   tools/hugo-dev/hugo-dev.sh images        build one image per Hugo version: the theme's minimum and the latest tested
#   tools/hugo-dev/hugo-dev.sh build         build the demo and the fixtures on both versions into tools/hugo-dev/out/
#   tools/hugo-dev/hugo-dev.sh check         build, then run every check over the output (see README.md)
#   tools/hugo-dev/hugo-dev.sh serve [site] [port]   serve a built site (default demo) at http://localhost:<port>/sub/
#   tools/hugo-dev/hugo-dev.sh screenshots   build, then write the theme's screenshot.png and tn.png from the demo
#
# Files are copied into containers rather than bind-mounted, so the same commands
# work on a workstation and inside a CI job that talks to the host's Docker.
set -euo pipefail

here="$(cd "$(dirname "$0")" && pwd)"
repo="$(cd "$here/../.." && pwd)"
out="$here/out"

# The Hugo versions the theme is built and checked on, with the sha256 of each
# official extended tarball. The minimum must equal [module.hugoVersion] min in
# hugo/theme/hugo.toml, so the theme cannot promise a version the checks skip.
min_version=0.146.0
min_sha=ff7bbad837f5dfc403f5e2e1c656533bdf3ec102a303d13f77facd9192a4eb2a
latest_version=0.169.0
latest_sha=c41672a0725d307758a132bc94a962e59760eb33f9ba08c991b81f9881d6fa7d
labels=(min latest)

version_of() { if [[ "$1" == min ]]; then echo "$min_version"; else echo "$latest_version"; fi; }
sha_of() { if [[ "$1" == min ]]; then echo "$min_sha"; else echo "$latest_sha"; fi; }

check_min_version() {
	local theme_min
	theme_min="$(sed -n 's/^[[:space:]]*min[[:space:]]*=[[:space:]]*"\(.*\)"/\1/p' "$repo/hugo/theme/hugo.toml")"
	[[ "$theme_min" == "$min_version" ]] ||
		{ echo "hugo/theme/hugo.toml has min = \"$theme_min\", but $0 tests $min_version" >&2; exit 1; }
}

images() {
	check_min_version
	for label in "${labels[@]}"; do
		docker build -q --build-arg HUGO_VERSION="$(version_of "$label")" --build-arg HUGO_SHA256="$(sha_of "$label")" \
			-t "readerly-hugo:$(version_of "$label")" "$here" >/dev/null
	done
}

# /stage/src holds the sites and the build script, /stage/themes/readerly the theme.
stage_sites() {
	local stage="$1"
	mkdir -p "$stage/src/sites" "$stage/themes"
	cp -r "$repo/hugo/theme" "$stage/themes/readerly"
	cp -r "$repo/hugo/theme/exampleSite" "$stage/src/sites/demo"
	for fixture in "$repo"/hugo/fixtures/*/; do
		cp -r "$fixture" "$stage/src/sites/$(basename "$fixture")"
	done
	cp "$here/build-sites.sh" "$stage/src/"
}

build_version() {
	local label="$1" stage="$2" cid status=0
	cid="$(docker create "readerly-hugo:$(version_of "$label")" bash /src/build-sites.sh)"
	docker cp "$stage/src/." "$cid:/src"
	docker cp "$stage/themes/." "$cid:/themes"
	docker start -a "$cid" || status=$?
	rm -rf "${out:?}/$label" && mkdir -p "$out/$label"
	docker cp "$cid:/out/." "$out/$label" 2>/dev/null || true
	docker rm -f "$cid" >/dev/null
	return "$status"
}

build() {
	local stage status=0
	check_min_version
	stage="$(mktemp -d)"
	trap 'rm -rf "$stage"' RETURN
	stage_sites "$stage"
	for label in "${labels[@]}"; do
		build_version "$label" "$stage" || status=1
	done
	[[ "$status" == 0 ]] && echo "Built $(ls "$stage/src/sites" | tr '\n' ' ')on Hugo $min_version and $latest_version into $out"
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
		node "$repo/tools/jekyll-dev/serve.mjs" "$out/latest/${2:-demo}" "${3:-4000}"
		;;
	screenshots)
		build
		node "$here/screenshots.mjs" "$out"
		;;
	*)
		sed -n '2,9p' "$0"
		exit 1
		;;
esac
