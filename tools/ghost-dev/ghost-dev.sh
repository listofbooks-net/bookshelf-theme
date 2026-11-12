#!/usr/bin/env bash
# Local Ghost for developing and checking the Readerly Ghost theme. Needs Docker and Node 20+.
#
#   tools/ghost-dev/ghost-dev.sh up           start Ghost 6 with ghost/ mounted as theme readerly, create the admin, activate the theme, load the demo
#   tools/ghost-dev/ghost-dev.sh seed         reload the Marginalia demo content (deletes every post, page and tag first)
#   tools/ghost-dev/ghost-dev.sh gscan [path] [-z]   run GScan for Ghost 6 on a theme folder (default ghost/) or, with -z, a zip; exit 1 on any error or warning
#   tools/ghost-dev/ghost-dev.sh check        markup parity with design/, browser checks and side-by-side screenshots, against the running site
#   tools/ghost-dev/ghost-dev.sh export <out dir> [public url]   crawl the running site into a static copy for Cloudflare Pages, then verify it
#   tools/ghost-dev/ghost-dev.sh url          print the site URL
#   tools/ghost-dev/ghost-dev.sh down         stop Ghost (keeps the database and uploaded images)
#   tools/ghost-dev/ghost-dev.sh clean        remove the container, its volume and the remembered port
#
# Every command is safe to rerun. Ghost listens on 127.0.0.1 at PORT, or at the first
# free port from 2380, remembered in tools/ghost-dev/.state. The admin is
# admin@readerly.test with the password in seed.mjs (printed by up).
#
# Environment:
#   PORT            fixed port
#   THEME_DIR       theme folder to mount (default ghost/); without package.json the default theme stays active
#   GHOST_NETWORK   a Docker network. Ghost joins it as host "ghost" and publishes no port; seed,
#                   check and export then run in a Playwright container on that network. CI uses
#                   this, because a job container cannot reach the host's published ports.
set -euo pipefail

here="$(cd "$(dirname "$0")" && pwd)"
repo="$(cd "$here/../.." && pwd)"
state="$here/.state"
name=readerly-ghost-dev
volume="$name-content"
ghost_image=ghost:6
runner_image=mcr.microsoft.com/playwright:v1.61.0-noble
theme_dir="$(realpath -m "${THEME_DIR:-$repo/ghost}")"
network="${GHOST_NETWORK:-}"

port_in_use() { ss -Hltn "sport = :$1" | grep -q .; }
running() { [[ "$(docker inspect -f '{{.State.Running}}' "$name" 2>/dev/null || true)" == true ]]; }

pick_port() {
	if [[ -n "${PORT:-}" ]]; then echo "$PORT"; return; fi
	if running; then docker port "$name" 2368/tcp | head -n1 | sed 's/.*://'; return; fi
	if [[ -f "$state" ]]; then
		local saved
		saved="$(sed -n 's/^PORT=//p' "$state")"
		if [[ -n "$saved" ]] && ! port_in_use "$saved"; then echo "$saved"; return; fi
	fi
	for p in $(seq 2380 2499); do
		if ! port_in_use "$p"; then echo "$p"; return; fi
	done
	echo "no free port in 2380-2499" >&2
	exit 1
}

if [[ -n "$network" ]]; then
	url="http://ghost:2368"
else
	port="$(pick_port)"
	url="http://localhost:$port"
fi

ghost_ready() {
	if [[ -n "$network" ]]; then
		docker exec "$name" node -e 'fetch("http://localhost:2368/ghost/api/admin/site/").then((r) => process.exit(r.ok ? 0 : 1), () => process.exit(1))'
	else
		curl -fsS -o /dev/null "$url/ghost/api/admin/site/"
	fi
}

start() {
	if running; then
		ghost_ready 2>/dev/null && return
	fi
	docker rm -f "$name" >/dev/null 2>&1 || true
	local args=(
		-e "url=$url" -e NODE_ENV=development -e security__staffDeviceVerification=false
		-e privacy__useUpdateCheck=false -e privacy__useGravatar=false -e privacy__useRpcPing=false
		-v "$volume:/var/lib/ghost/content"
	)
	if [[ -n "$network" ]]; then
		args+=(--network "$network" --network-alias ghost)
	else
		args+=(-p "127.0.0.1:$port:2368")
		[[ -f "$theme_dir/package.json" ]] && args+=(-v "$theme_dir:/var/lib/ghost/content/themes/readerly:ro")
		printf 'PORT=%s\n' "$port" >"$state"
	fi
	local cid
	cid="$(docker create --name "$name" "${args[@]}" "$ghost_image")"
	# A job container cannot bind-mount its own files, so the theme is copied in instead.
	if [[ -n "$network" && -f "$theme_dir/package.json" ]]; then
		# The image has no content folder until Ghost first starts, so make the theme folder in the volume.
		docker run --rm -v "$volume:/var/lib/ghost/content" --entrypoint mkdir "$ghost_image" -p /var/lib/ghost/content/themes/readerly
		docker cp "$theme_dir/." "$cid:/var/lib/ghost/content/themes/readerly"
	fi
	docker start "$cid" >/dev/null
	local tries=0
	until ghost_ready 2>/dev/null; do
		if (( ++tries > 180 )); then
			echo "Ghost did not answer within 180 seconds. Check: docker logs $name" >&2
			exit 1
		fi
		sleep 1
	done
}

# node_tool <script> <needs dependencies: 0|1> <args...>: run a tools/ghost-dev script on the host,
# or, in network mode, inside a Playwright container on the same network. {out} in an
# argument becomes the output folder; in network mode its contents are copied back to
# tools/ghost-dev/out afterwards.
node_tool() {
	local script="$1" deps="$2"
	shift 2
	if [[ -z "$network" ]]; then
		mkdir -p "$here/out"
		local args=("${@//\{out\}/$here/out}")
		CHROMIUM_PATH="${CHROMIUM_PATH:-$(command -v chromium || true)}" node "$here/$script" "${args[@]}"
		return
	fi
	local args=("${@//\{out\}//out}") cid status=0 install=''
	[[ "$deps" == 1 ]] && install='npm ci --prefix tools --no-audit --no-fund >/dev/null && '
	cid="$(docker create --network "$network" -w /repo "$runner_image" bash -c "mkdir -p /out && ${install}node tools/ghost-dev/$script $(printf '%q ' "${args[@]}")")"
	tar -C "$repo" --exclude=.git --exclude=dist --exclude=node_modules --exclude=tools/ghost-dev/out -cf - . | docker cp - "$cid:/repo"
	docker start -a "$cid" || status=$?
	mkdir -p "$here/out"
	docker cp "$cid:/out/." "$here/out" >/dev/null 2>&1 || true
	docker rm -f "$cid" >/dev/null
	return "$status"
}

gscan() {
	local target="$theme_dir" mode=dir
	for arg in "$@"; do
		if [[ "$arg" == -z ]]; then mode=zip; else target="$(realpath "$arg")"; fi
	done
	[[ "$mode" == dir || "$target" == *.zip ]] || { echo "gscan -z needs the path of a .zip" >&2; return 2; }
	local cli=(node /var/lib/ghost/current/node_modules/gscan/bin/cli.js)
	local cid log status=0
	log="$(mktemp)"
	if [[ "$mode" == zip ]]; then
		cid="$(docker create "$ghost_image" "${cli[@]}" /theme.zip -z --v6 --verbose)"
		docker cp "$target" "$cid:/theme.zip"
	else
		cid="$(docker create "$ghost_image" "${cli[@]}" /theme --v6 --verbose)"
		docker cp "$target/." "$cid:/theme"
	fi
	docker start -a "$cid" >"$log" 2>&1 || status=$?
	docker rm -f "$cid" >/dev/null
	cat "$log"
	# Errors and warnings fail; recommendations do not.
	if grep -qE '^(Errors|Warnings)$' "$log"; then status=1; fi
	if (( status != 0 )); then echo "gscan failed: the theme must have zero errors and zero warnings" >&2; else echo "gscan passed: zero errors, zero warnings"; fi
	rm -f "$log"
	return "$status"
}

case "${1:-}" in
	up)
		start
		node_tool seed.mjs 0 "$url"
		echo "Ghost is up at $url (admin $url/ghost/, admin@readerly.test)"
		;;
	seed)
		start
		node_tool seed.mjs 0 "$url"
		;;
	gscan) shift; gscan "$@" ;;
	check)
		start
		node_tool check.mjs 1 "$url" "{out}"
		;;
	export)
		out="${2:?usage: export <out dir> [public url]}"
		start
		if [[ -n "$network" ]]; then
			node_tool export-static.mjs 1 "$url" "{out}/static" "${3:-https://readerly-ghost-demo.pages.dev}"
			rm -rf "$out" && mkdir -p "$out" && cp -r "$here/out/static/." "$out"
		else
			node_tool export-static.mjs 1 "$url" "$(realpath -m "$out")" "${3:-https://readerly-ghost-demo.pages.dev}"
		fi
		;;
	url) echo "$url" ;;
	down) docker rm -f "$name" >/dev/null 2>&1 || true ;;
	clean)
		docker rm -f "$name" >/dev/null 2>&1 || true
		docker volume rm "$volume" >/dev/null 2>&1 || true
		rm -f "$state"
		;;
	*)
		sed -n '2,23p' "$0"
		exit 1
		;;
esac
