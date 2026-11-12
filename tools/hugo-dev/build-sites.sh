#!/usr/bin/env bash
# Runs inside a readerly-hugo image. Builds every site in /src/sites with the
# theme in /themes/readerly and writes /out/<site>/, /out/<site>.log (stdout)
# and /out/<site>.err (stderr). Hugo writes every WARN and ERROR line to stderr,
# and --quiet would hide them, so a build fails on a non-zero exit or on any
# stderr output.
set -euo pipefail
mkdir -p /out
status=0
# The demo names its theme by the path it has in a checkout (theme and themesDir).
# A config file wins over --themesDir, so the copy built here drops both lines and
# the flags below load the same theme from /themes.
sed -i '/^theme = /d;/^themesDir = /d' /src/sites/demo/hugo.toml
for dir in /src/sites/*/; do
	name="$(basename "$dir")"
	code=0
	hugo --source "$dir" --themesDir /themes --theme readerly \
		--baseURL http://localhost:4000/sub/ --clock 2026-11-12T12:00:00Z \
		--destination "/out/$name" --printPathWarnings --printI18nWarnings \
		>"/out/$name.log" 2>"/out/$name.err" || code=$?
	if [[ "$code" != 0 ]]; then
		echo "$name: hugo exited with $code" >&2; status=1
	fi
	if [[ -s "/out/$name.err" ]]; then
		echo "$name: hugo wrote to stderr:" >&2; sed 's/^/  /' "/out/$name.err" >&2; status=1
	fi
done
exit "$status"
