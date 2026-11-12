# One image per engine: docker build --build-arg ENGINE=pages|jekyll4 tools/jekyll-dev
FROM ruby:3.3-bookworm
ARG ENGINE
WORKDIR /work
# A stand-in theme with the real gem's name, version and dependencies, so the
# bundle resolves here. jekyll-dev.sh copies the real theme over it at run time.
RUN mkdir theme && printf '%s\n' \
      'Gem::Specification.new do |s|' \
      '  s.name = "jekyll-theme-readerly"; s.version = "1.0.0"; s.summary = "stand-in"; s.authors = ["x"]' \
      '  s.add_runtime_dependency "jekyll", ">= 3.10", "< 5.0"' \
      'end' > theme/jekyll-theme-readerly.gemspec
COPY ${ENGINE}/Gemfile Gemfile
RUN bundle install --jobs 4 --quiet && bundle exec jekyll --version
