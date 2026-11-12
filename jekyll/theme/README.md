# Readerly

Readerly is a free Jekyll theme for book blogs, author sites and reading logs. It runs on GitHub Pages with no plugins beyond the ones Pages already allows.

- Review posts with a cover, star rating, book details and a verdict box.
- A reading log with yearly statistics and shelves for Reading, Read, Want to read and Did not finish.
- An author page for your own books, praise and upcoming events.
- Two styles, Paper (light) and Lamplight (dark), that follow the visitor's system setting.
- Bundled fonts and no third-party requests.

Every part is optional. The theme creates no posts or pages, and a section with no data prints nothing, heading included.

## Install

### Start a new site on GitHub Pages

1. Open [the Readerly starter repository](https://github.com/listofbooks-net/jekyll-theme-readerly-starter) and click **Use this template**.
2. In your new repository, open **Settings > Pages**, set **Source** to **Deploy from a branch**, and pick `main`.
3. Edit `_config.yml`: set `title`, `tagline` and `description`.
4. Write your first review in `_posts/`. The starter's `_drafts/example-review.md` shows every field.

GitHub Pages rebuilds the site each time you push.

### Add the theme to an existing site

Add two lines to `_config.yml`:

```yaml
remote_theme: listofbooks-net/jekyll-theme-readerly@v1
plugins:
  - jekyll-remote-theme
```

Remove any `theme:` line. Readerly uses the layout names `default`, `home`, `page` and `post`, the same names Minima uses, so existing posts and pages render without changes.

`@v1` follows every backward-compatible release. To stay on one exact release, use a tag such as `@v1.0.0` instead.

To show nine reviews per page on the home page, also add `jekyll-paginate` to `plugins`, set `paginate: 9`, and name the home page `index.html` (jekyll-paginate does not paginate `index.md`).

### Preview on your computer

Run `bundle exec jekyll serve` in your site. If your Gemfile uses the `github-pages` gem and `_config.yml` has no `description:`, the local build asks the GitHub API for your repository's description, and it can stop with an error when it cannot reach it. Add a `description:` line to `_config.yml`, which also sets your pages' meta description.

## Write a review

A review is an ordinary post with extra front matter. Every key except `title` is optional. Delete a key and the part it fills disappears. A post with none of the book keys renders as a plain article.

```yaml
---
layout: post
title: The Salt Orchard
lead: A novel about grief that keeps its accounts in apples.
book_author: Mira Hollis
cover: /assets/covers/salt-orchard.jpg
genre: Literary fiction
tags: [Historical, Tidewater series]
rating: 4.5
series: The Tidewater Books, Book 2 of 3
pages: 384
publication: 2026, Harrow & Lane
status: read
finished: 2026-10-30
excerpt: A grieving fruit scientist inherits a failing orchard on a salt-burned coast.
verdict:
  summary: Patient, precise and quietly devastating.
  liked: [The orchard as a living clock, Danny Aske]
  disliked: [A too-tidy answer to the ledger mystery]
---
The review, in Markdown.
```

### Book entry keys

Review posts and the entries in `_data/reading_log.yml` share these keys.

| Key | Type | What it does | When it is missing |
| --- | --- | --- | --- |
| `title` | text | The post headline, and the book title unless `book_title` is set. | Required. |
| `book_title` | text | The book's title, when the headline differs from it. | The title is `title`. |
| `book_author` | text | The book's author. `author` stays free for the person who wrote the post. | No "by" line. |
| `cover` | path or URL | A cover image, ideally 2:3. | A typeset cover with the title and author. |
| `genre` | text | One genre. Fills the label above the title, the genre list and "You might also like". | No label, and the post is not in the genre list. |
| `tags` | list | More labels, shown as chips next to the genre. | Only the genre chip. |
| `rating` | number, 0 to 5 | Stars. Halves are allowed. | No stars anywhere, and no average rating stat. |
| `series` | text | Shown in the book panel and the featured card. | That row is left out. |
| `pages` | number | Page count. Counts toward "Pages read" and the progress bar. | That row is left out. |
| `publication` | text | Publication details, such as `2026, Harrow & Lane`. | That row is left out. |
| `status` | `reading`, `read`, `want` or `dnf` | Puts the book on a reading-log shelf and shows a badge. | On no shelf, and no badge. |
| `finished` | date | Orders the Read shelf and counts the book in that year's statistics. | Last on the Read shelf, and not in a year-scoped log. |
| `current_page` | number | With `pages`, a progress bar for `reading` and "Stopped at page N" for `dnf`. | No progress bar. |
| `url` | path or URL | A link for a reading-log entry. Posts have their own URL. | The card has no link. |

These keys apply to posts only:

| Key | What it does |
| --- | --- |
| `lead` | A subtitle under the headline. |
| `excerpt` | The text on review cards. Without it, Jekyll uses the post's first paragraph. |
| `featured` | `true` puts the post in the large card on the home page. Without any featured post, the newest post fills it. |
| `verdict` | `summary`, `liked` and `disliked`, shown in a box after the review. |

If a post has `rating` and `book_author`, its page carries schema.org `Review` data for search engines.

## Keep a reading log

Create `reading-log.md`:

```yaml
---
layout: reading-log
title: Reading log
year: 2026
intro:
  eyebrow: Reading journal
  heading: What I'm reading in 2026
---
```

The log shows every review with a `status`, plus the books in `_data/reading_log.yml`. A book you reviewed needs no entry there. List the books you have not reviewed, with the same keys as a review:

```yaml
- title: Moth Season
  book_author: Pell Ordway
  status: reading
  pages: 368
  current_page: 142
- title: The Ninth Wave Hotel
  book_author: Bea Lindqvist
  status: want
```

`year` limits the statistics and the Read shelf to the books finished that year. Without `year`, they cover every book.

## Build an author page

Create a page with `layout: author` and fill three data files: `_data/books.yml` for your own books, `_data/praise.yml` for quotes, and `_data/events.yml` for readings and signings.

```yaml
---
layout: author
title: Isobel Thorne
hero:
  eyebrow: Novelist
  portrait: /assets/img/isobel.jpg
  lead: I write novels about small towns and old trades.
  buttons:
    - { title: Discover The Bell Foundry, url: "#new-release" }
---
```

| File | Keys |
| --- | --- |
| `_data/books.yml` | `title`, `cover`, `year`, `kind`, `blurb`, `buy_url`, `url`. The first book with `featured: true` also fills the new-release band, which reads `label`, `lead`, `facts`, `url_label` and `where_to_buy` (a list). |
| `_data/praise.yml` | `quote`, `source`, `context`. The theme adds the quotation marks. |
| `_data/events.yml` | `title`, `date`, `where`, `url`, `label`. Events dated before the build are left out. |

## Choose and order sections

The `home`, `author` and `reading-log` layouts are lists of sections. To remove a section, reorder sections, or use one on another page, set `sections` in the page's front matter:

```yaml
---
layout: home
sections: [intro, recent-reviews, genres]
---
```

A misspelt name stops the build with an error that names the missing file.

| Layout | Default sections |
| --- | --- |
| `home` | `intro`, `content`, `featured-review`, `recent-reviews`, `currently-reading`, `genres`, `newsletter` |
| `author` | `author-hero`, `new-release`, `books`, `praise`, `events`, `newsletter` |
| `reading-log` | `intro-stats`, `currently-reading`, `shelves` |

Any page can use `layout: sections` with its own list. These are all the sections:

| Section | Shows | Page keys it reads |
| --- | --- | --- |
| `intro` | A heading with an eyebrow, lead and buttons. | `intro: {eyebrow, heading, lead, buttons}`. The heading accepts Markdown, so `*past midnight*` is set in italics. |
| `intro-stats` | `intro` with the reading statistics under it. | `intro`, `year` |
| `featured-review` | The newest featured post, else the newest post. | None |
| `recent-reviews` | A grid of reviews, with pagination when jekyll-paginate runs. | None |
| `currently-reading` | Books with `status: reading`. | `currently-reading: {heading, note}` |
| `genres` | Each genre with its number of reviews. | `genres: {heading, note}` |
| `newsletter` | A sign-up form. | `newsletter`, which overrides any `readerly.newsletter` key |
| `author-hero` | The author's portrait and introduction. | `hero: {eyebrow, portrait, lead, text, buttons}` |
| `new-release` | The featured book from `_data/books.yml`. | None |
| `books` | Every book in `_data/books.yml`. | `books: {heading, note}` |
| `praise` | Quotes from `_data/praise.yml`. | `praise: {heading, note}` |
| `events` | Upcoming events from `_data/events.yml`. | `events: {heading, note}` |
| `shelves` | The Read, Want to read and Did not finish shelves. | `year`, and `shelves: {read, want, dnf}`, where each takes `{note}` or `false` to hide that shelf |
| `content` | The page's own Markdown. | None |

On page 2 and later of the home page, only `recent-reviews` shows.

## Add an archive and search

Create a page with `layout: archive`. It lists every review, newest first, and turns on the search box in the header and on the 404 page. Without such a page, the site has no search box and genre names are plain text.

```yaml
---
layout: archive
title: All reviews
permalink: /reviews/
---
```

For a page per genre, create one more archive page with a `genre` key. It lists the reviews whose `genre` or `tags` contain that name, and every link to that genre goes to it. Genres without their own page link to the main archive, filtered in the browser.

```yaml
---
layout: archive
title: Literary fiction
genre: Literary fiction
lead: Novels where not much happens and everything changes.
---
```

Search runs in the visitor's browser over the archive page, with no index file and no external service.

## Configure the site

Readerly reads the standard Jekyll keys `title`, `description`, `lang`, `author`, `paginate` and `permalink`, plus `tagline`. Theme settings go under `readerly`. Every key is optional.

```yaml
title: Marginalia
tagline: Book notes & reviews by Nora Vale
author:
  name: Nora Vale
  avatar: /assets/img/nora.jpg
  bio: Former school librarian who reads about sixty books a year.
  url: /about/

readerly:
  style: auto
  style_toggle: true
  nav:
    - { title: Reviews, url: / }
    - { title: Reading log, url: /reading-log/ }
  footer:
    about: Slow, honest reviews of novels, essays and poetry.
    columns:
      - title: Browse
        links: [{ title: Reviews, url: / }, { title: About, url: /about/ }]
    copyright: All reviews are my own.
  newsletter:
    action: https://buttondown.com/api/emails/embed-subscribe/you
    heading: One email a month.
```

| Key | Default | What it does |
| --- | --- | --- |
| `tagline` | none | The line under the site title. |
| `author` | none | `name`, `avatar`, `bio`, `url` and `links` (a list of `{title, url}`). With `name` and `bio`, each post ends with a bio card. |
| `readerly.style` | `auto` | `auto` follows the visitor's system setting. `paper` and `lamplight` fix one style. |
| `readerly.style_toggle` | `true` | Shows the light and dark button in the header. `false` also removes the theme's script from every page. |
| `readerly.nav` | none | Header links, a list of `{title, url}`. |
| `readerly.footer` | none | `about`, `columns` (each `{title, links}`) and `copyright`. |
| `readerly.newsletter` | none | `action` (the form's URL) turns the newsletter band on. Also `eyebrow`, `heading`, `note`, `button`, and `field` (the email field's name, default `email`). |
| `readerly.related_posts` | `3` | How many posts "You might also like" shows. `0` turns it off. |
| `readerly.date_format` | `%-d %B %Y` | A Liquid date format. |
| `readerly.favicon` | none | The site icon. |
| `readerly.custom_css` | none | A stylesheet loaded after the theme's. |
| `readerly.thousands` | `,` | The thousands separator in statistics. |

If `plugins` lists `jekyll-feed`, every page links the feed.

## Change colours and fonts

The theme's colours, fonts and spacing are CSS custom properties. To change them, add a stylesheet and name it in `readerly.custom_css`:

```css
:root { --color-accent: #1d4ed8; }
```

The full list is at the top of `assets/css/readerly.css`.

## Translate the interface

Every interface string has an English default. To replace one, add `_data/readerly_strings.yml` with the keys you want to change. `%n` stands for a number or a name.

```yaml
recent_reviews: Neueste Rezensionen
by: von %n
```

The keys and their defaults are in `_includes/data/t.html`.

## Add comments or analytics

Readerly makes no third-party requests. To add a comment system or analytics, create one of these files in your site:

- `_includes/comments.html` is printed at the end of each post.
- `_includes/head-custom.html` is printed at the end of `<head>`.

## The footer credit

Each page ends with one link, "Theme by List of Books", to https://www.listofbooks.net/. Keeping it is how you thank us for the theme. To remove it, create an empty file at `_includes/shell/credit.html` in your site.

## License

The theme is released under the MIT License. The bundled fonts, Fraunces and Literata, are under the SIL Open Font License 1.1, and each font folder holds its license text.

This repository is a read-only copy of the `jekyll/theme` folder of [listofbooks-net/readerly-theme](https://github.com/listofbooks-net/readerly-theme), where the design and the WordPress version also live. Open issues here or there. Send changes to the monorepo.
