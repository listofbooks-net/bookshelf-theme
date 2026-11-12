# My book notes

This site uses [Readerly](https://github.com/listofbooks-net/jekyll-theme-readerly), a free Jekyll theme for book blogs, author sites and reading logs. It runs on GitHub Pages.

## Publish the site

1. Click **Use this template** to create your own copy of this repository.
2. In your copy, open **Settings > Pages**, set **Source** to **Deploy from a branch**, and pick `main`.
3. Edit `_config.yml`: set `title`, `tagline` and `description`, then commit.

After a minute or two, the site is live at the address that **Settings > Pages** shows.

## Write a review

1. Copy `_drafts/example-review.md` to `_posts/`, and name the copy with the date and title, for example `_posts/2026-11-04-the-salt-orchard.md`.
2. Fill in the front matter. Delete any line you do not need: that part of the page disappears.
3. Replace the example text with your review, then commit.

Jekyll does not publish files in `_drafts`, so the example never appears on your site.

## Add more pages

The theme creates no pages for you. Each page below is a file you add when you want it:

- A reading log: `reading-log.md` with `layout: reading-log`.
- An author page for your own books: a page with `layout: author`.
- An archive with search: `reviews.md` with `layout: archive`.
- An about page: `about.md` with `layout: page`.

The [theme README](https://github.com/listofbooks-net/jekyll-theme-readerly#readme) shows the front matter for each, and every setting `_config.yml` accepts.

## Preview on your computer

You need Ruby and Bundler. Run:

```sh
bundle install
bundle exec jekyll serve
```

The site opens at http://localhost:4000.

## The footer credit

Each page ends with one link, "Theme by List of Books". To remove it, create an empty file at `_includes/shell/credit.html`.
