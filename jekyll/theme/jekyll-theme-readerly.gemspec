Gem::Specification.new do |spec|
  spec.name     = "jekyll-theme-readerly"
  spec.version  = "1.0.0"
  spec.authors  = ["List of Books"]
  spec.summary  = "A Jekyll theme for book blogs, author sites and reading logs."
  spec.homepage = "https://github.com/listofbooks-net/jekyll-theme-readerly"
  spec.license  = "MIT"
  spec.metadata = { "plugin_type" => "theme" }

  spec.files = Dir["_layouts/**/*", "_includes/**/*", "assets/**/*", "LICENSE", "README.md"]

  spec.add_runtime_dependency "jekyll", ">= 3.10", "< 5.0"
end
