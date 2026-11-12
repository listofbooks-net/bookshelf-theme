#!/usr/bin/env bash
# Runs inside a readerly-jekyll image. Builds every site in /src/sites with the
# theme in /work/theme and writes /out/<site>/ plus /out/<site>.log.
# Any output from a quiet build is a warning, so the build fails on it.
set -euo pipefail
engine="$1"
mkdir -p /out
cd /work && bundle install --local --quiet >/dev/null
flags=(--quiet --config _config.yml,/src/check.yml)
[[ "$engine" == pages ]] && flags+=(--safe)
status=0
for dir in /src/sites/*/; do
	name="$(basename "$dir")"
	extra=()
	[[ "$name" == *-drafts ]] && extra+=(--drafts)
	rm -rf "/tmp/$name" && cp -r "$dir" "/tmp/$name"
	sed -i '/^remote_theme:/d' "/tmp/$name/_config.yml"
	# On GitHub Pages a site with no description gets its repository's description
	# from the GitHub API. The checks run offline, so they stand in an empty one.
	grep -q '^description:' "/tmp/$name/_config.yml" || echo 'description: ""' >>"/tmp/$name/_config.yml"
	# Jekyll loads the bundle's plugins (on Pages: its whole plugin set) only
	# when the site has a Gemfile. Bundler reads /work/Gemfile either way.
	touch "/tmp/$name/Gemfile"
	if ! (cd "/tmp/$name" && BUNDLE_GEMFILE=/work/Gemfile bundle exec jekyll build "${flags[@]}" "${extra[@]}" --destination "/out/$name") >"/out/$name.log" 2>&1; then
		echo "$engine/$name: build failed" >&2; status=1
	fi
	if [[ -s "/out/$name.log" ]]; then
		echo "$engine/$name: build printed output:" >&2; sed 's/^/  /' "/out/$name.log" >&2; status=1
	fi
done
exit "$status"
