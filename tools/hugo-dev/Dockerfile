# One image per Hugo version: docker build --build-arg HUGO_VERSION=<v> --build-arg HUGO_SHA256=<sum> tools/hugo-dev
# The official extended release, checked against the checksum the release publishes.
# The tarball is linux-amd64, so the base image is too, also on an arm64 host.
FROM --platform=linux/amd64 debian:bookworm-slim
ARG HUGO_VERSION
ARG HUGO_SHA256
RUN apt-get update -qq && apt-get install -y -qq --no-install-recommends ca-certificates curl >/dev/null \
 && curl -fsSL -o /tmp/hugo.tgz "https://github.com/gohugoio/hugo/releases/download/v${HUGO_VERSION}/hugo_extended_${HUGO_VERSION}_linux-amd64.tar.gz" \
 && echo "${HUGO_SHA256}  /tmp/hugo.tgz" | sha256sum -c - \
 && tar -xzf /tmp/hugo.tgz -C /usr/local/bin hugo && rm /tmp/hugo.tgz \
 && apt-get purge -y -qq curl >/dev/null && rm -rf /var/lib/apt/lists/* \
 && hugo version
