#!/usr/bin/env bash
# Local WordPress for developing and checking the Bookshelf theme.
#
#   tools/wp-dev/wp-dev.sh up          start WordPress, install it, activate bookshelf, load demo content
#   tools/wp-dev/wp-dev.sh check       run the Theme Check plugin against bookshelf
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
export COMPOSE_PROJECT_NAME="${COMPOSE_PROJECT_NAME:-bookshelf-wp}"
port_file="$here/.port${COMPOSE_PROJECT_NAME#bookshelf-wp}"

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

export WP_PORT="$(pick_port)"
url="http://localhost:$WP_PORT"
wp() { compose --profile cli run --rm -T cli wp "$@"; }

start() {
	compose up -d --wait db wordpress >/dev/null
	until wp core version >/dev/null 2>&1; do sleep 1; done
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
		wp theme activate bookshelf
		wp eval-file /opt/wp-dev/seed.php
		echo "WordPress is up at $url (admin / admin)"
		;;
	check)
		start
		wp plugin is-installed theme-check || wp plugin install theme-check
		wp plugin activate theme-check >/dev/null
		wp eval-file /opt/wp-dev/theme-check.php bookshelf
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
		export COMPOSE_PROJECT_NAME=bookshelf-wp-zip DIST_DIR="$(dirname "$zip")" THEME_TARGET=/opt/theme-source-unused
		exec "$0" zip-test-run "$(basename "$zip")"
		;;
	zip-test-run)
		compose down -v >/dev/null 2>&1 || true
		start
		install
		wp theme install "/opt/dist/$2" --activate
		wp theme status bookshelf
		code="$(curl -s -o /tmp/bookshelf-zip-home.html -w '%{http_code}' "$url/")"
		echo "front page HTTP $code"
		grep -c 'wp-content/themes/bookshelf/' /tmp/bookshelf-zip-home.html | sed 's/^/theme asset references on front page: /'
		compose down -v >/dev/null
		;;
	*)
		sed -n '2,15p' "$0"
		exit 1
		;;
esac
