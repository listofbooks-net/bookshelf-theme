#!/usr/bin/env bash
# Local WordPress for developing and checking the Readerly theme.
#
#   tools/wp-dev/wp-dev.sh up          start WordPress, install it, activate readerly, load demo content
#   tools/wp-dev/wp-dev.sh check       run Theme Check against readerly; exit 1 on any REQUIRED or WARNING
#   tools/wp-dev/wp-dev.sh style <paper|lamplight>   switch the site's style variation
#   tools/wp-dev/wp-dev.sh wp <args>   run any wp-cli command
#   tools/wp-dev/wp-dev.sh url         print the site URL
#   tools/wp-dev/wp-dev.sh down        stop the containers (keeps the database)
#   tools/wp-dev/wp-dev.sh clean       stop the containers and delete the database and WordPress volumes
#   tools/wp-dev/wp-dev.sh zip-test <zip>   install a theme zip on a fresh, separate WordPress and activate it
#
# Every command is safe to rerun. The site listens on 127.0.0.1 at a free port,
# remembered in tools/wp-dev/.port so the URL stays stable between runs.
set -euo pipefail

here="$(cd "$(dirname "$0")" && pwd)"
repo="$(cd "$here/../.." && pwd)"
export COMPOSE_PROJECT_NAME="${COMPOSE_PROJECT_NAME:-readerly-wp}"
port_file="$here/.port${COMPOSE_PROJECT_NAME#readerly-wp}"

mkdir -p "$repo/dist"
compose() { docker compose --progress quiet -f "$here/compose.yaml" "$@"; }
port_in_use() { ss -Hltn "sport = :$1" | grep -q .; }

pick_port() {
	local running
	running="$(WP_PORT=0 compose port wordpress 80 2>/dev/null | sed 's/.*://')" || true
	if [[ -n "$running" && "$running" != 0 ]]; then echo "$running"; return; fi
	if [[ -f "$port_file" ]] && ! port_in_use "$(cat "$port_file")"; then cat "$port_file"; return; fi
	for p in $(seq 8180 8299); do
		if ! port_in_use "$p"; then echo "$p" | tee "$port_file"; return; fi
	done
	echo "no free port in 8180-8299" >&2
	exit 1
}

WP_PORT="$(pick_port)"
export WP_PORT
url="http://localhost:$WP_PORT"
wp() { compose --profile cli run --rm -T cli wp "$@"; }

start() {
	compose up -d --wait db wordpress >/dev/null
	local tries=0
	until wp core version >/dev/null 2>&1; do
		if (( ++tries > 120 )); then
			echo "WordPress did not become ready within 120 attempts. Check: docker compose logs" >&2
			exit 1
		fi
		sleep 1
	done
}

install() {
	if ! wp core is-installed 2>/dev/null; then
		wp core install --url="$url" --title="Marginalia" --admin_user=admin --admin_password=admin \
			--admin_email=admin@example.com --skip-email
	fi
	wp option update home "$url" >/dev/null
	wp option update siteurl "$url" >/dev/null
	wp rewrite structure '/%postname%/' >/dev/null
}

case "${1:-}" in
	up)
		start
		install
		wp theme activate readerly
		wp eval-file /opt/wp-dev/seed.php
		echo "WordPress is up at $url (admin / admin)"
		;;
	check)
		start
		wp plugin is-installed theme-check || wp plugin install theme-check
		wp plugin activate theme-check >/dev/null
		wp theme-check run readerly --format=json | node "$here/summarize-theme-check.cjs"
		;;
	style)
		wp eval-file /opt/wp-dev/set-style.php "${2:?usage: style <paper|lamplight>}"
		;;
	wp) shift; wp "$@" ;;
	url) echo "$url" ;;
	down) compose down ;;
	clean) compose down -v; rm -f "$port_file" ;;
	zip-test)
		zip="$(realpath "${2:?usage: zip-test <path to zip>}")"
		export COMPOSE_PROJECT_NAME=readerly-wp-zip DIST_DIR="$(dirname "$zip")" THEME_TARGET=/opt/theme-source-unused
		exec "$0" zip-test-run "$(basename "$zip")"
		;;
	zip-test-run)
		home="$(mktemp)"
		trap 'rm -f "$home"; compose down -v >/dev/null 2>&1 || true' EXIT
		compose down -v >/dev/null 2>&1 || true
		start
		install
		wp theme install "/opt/dist/$2" --activate
		wp theme status readerly
		code="$(curl -s -o "$home" -w '%{http_code}' "$url/" || true)"
		echo "front page HTTP $code"
		refs="$(grep -c 'wp-content/themes/readerly/' "$home" || true)"
		echo "theme asset references on front page: $refs"
		[[ "$code" == 200 && "$refs" -gt 0 ]] || { echo "zip-test failed: front page must return 200 and load theme assets" >&2; exit 1; }
		echo "zip-test passed"
		;;
	*)
		sed -n '2,15p' "$0"
		exit 1
		;;
esac
