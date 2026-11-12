# Readerly for Ghost

Readerly is a free Ghost theme for people who love books. It is built for a book blog first, and it also gives you a reading log and an author website. It comes in two styles, Paper (light) and Lamplight (dark), and bundles its fonts, Fraunces and Literata, so it loads no fonts from other servers.

- Demo: Marginalia, a book blog by the fictional reviewer Nora Vale.
- Requires Ghost 6.0 or later.
- License: MIT. The fonts are under the SIL Open Font License (`assets/fonts/*/OFL.txt`).

## Install

1. Download `readerly-ghost-<version>.zip`.
2. In Ghost Admin, go to **Settings > Design & branding > Change theme > Upload theme** and select the zip file.
3. Click **Activate**.

The theme works with any existing Ghost content. Every section that needs book data shows nothing until you add that data.

## Theme settings

Go to **Settings > Design & branding > Customize**.

| Setting | Group | What it does |
| --- | --- | --- |
| Color scheme | Site-wide | Paper, Lamplight, or Auto (follows the reader's system setting). |
| Style toggle | Site-wide | Shows a button in the header that switches between Paper and Lamplight. |
| Footer about | Site-wide | One or two sentences under the site title in the footer. |
| Secondary navigation title | Site-wide | The heading of the secondary navigation in the footer. The default is "Genres". |
| Copyright note | Site-wide | Text after the copyright line. |
| Intro eyebrow, heading, lead | Homepage | The introduction at the top of the home page. Put words in `*asterisks*` to set them in italics. An empty heading hides the introduction. |
| Intro button text | Homepage | A button that links to your newest post. |
| Secondary button text and URL | Homepage | A second button, for example to your reading log. |
| Show currently reading | Homepage | Shows books tagged `#reading`. |
| Show genres | Homepage | Shows your tags with the number of posts in each. |
| Newsletter eyebrow, heading, note | Homepage | The signup section. It shows only when your site lets visitors sign up. |
| Show related reviews | Post | Shows up to three posts with the same primary tag. |
| Show reviewer bio | Post | Shows the author's bio card when the author has a bio. |

Ghost's own settings also apply: the site title, description (shown as the tagline), logo, icon, accent color, navigation, secondary navigation, and the heading and body fonts under **Typography**.

## Write a book review

A review is an ordinary Ghost post.

- **Cover**: the post's feature image. Add alt text, for example "Cover of The Salt Orchard by Mira Hollis".
- **Summary**: the post's excerpt. It shows under the title and on cards.
- **Genre**: the first public tag. It is the post's primary tag. Add more public tags for series, themes and so on.
- **Book facts**: internal tags, which start with `#`. Readers never see the tags themselves; the theme reads them.

| Internal tag | Example | Shows |
| --- | --- | --- |
| `#by:<name>` | `#by:Mira Hollis` | "by Mira Hollis" on cards and in the book panel. A post with this tag is a book review. |
| `#rating:<0.5 to 5>` | `#rating:4.5` | Stars on cards, in the book panel, and in the Review structured data. Half stars are allowed. |
| `#series:<text>` | `#series:The Tidewater Books · Book 2 of 3` | A Series row in the book panel. |
| `#pages:<number>` | `#pages:384` | A Pages row, the reading progress bar, and the pages count on the reading log. |
| `#published:<text>` | `#published:2026 · Harrow & Lane` | A Published row in the book panel. |
| `#on-page:<number>` | `#on-page:142` | With `#reading` and `#pages`, the reading progress bar. |
| `#reading`, `#read`, `#want-to-read`, `#did-not-finish` | `#read` | A status badge, and the shelf on your reading log. |

Ghost's tag field treats a comma as the end of a tag, so do not use commas inside a tag. Use ` · ` instead.

A post with `#by:` shows a book panel with the cover and the facts. A post without it shows its feature image as a normal header image. A book without a cover image gets a typeset placeholder cover.

Mark a post as featured to label it "Featured" on cards and on the home page.

### Verdict, pull quote and other review blocks

Readerly styles Ghost's cards: images (normal, wide and full width), galleries, bookmarks, embeds, callouts, toggles, buttons, products, headers, files, audio, video and the alternative blockquote style. For the design's verdict box and pull quote, add an **HTML** card with this markup. Save it as a snippet to reuse it.

```html
<section class="verdict" aria-labelledby="verdict-title">
  <div class="verdict__head">
    <h2 id="verdict-title">My verdict</h2>
    <div class="rating-line"><span class="rating rating--large" style="--rating: 4.5" role="img" aria-label="Rated 4.5 out of 5"></span><span aria-hidden="true">4.5 / 5</span></div>
  </div>
  <p class="verdict__summary">Patient, precise and quietly devastating.</p>
  <div class="verdict__lists">
    <div><h3>What worked</h3><ul><li>The orchard as a living clock</li></ul></div>
    <div><h3>Less so</h3><ul><li>A too-tidy ending</li></ul></div>
  </div>
</section>
```

```html
<figure class="pullquote">
  <blockquote><p>“Grief is a ledger you keep in pencil.”</p></blockquote>
  <figcaption><cite>The Salt Orchard</cite>, chapter nine</figcaption>
</figure>
```

## Add a reading log

1. Create a page with the slug `reading-log`.
2. In the page settings, set **Template** to **Reading log**.
3. Optional: add the internal tag `#eyebrow:Reading journal` for the small label above the title, and an excerpt for the text under it.

The page lists every post and page tagged `#reading`, `#read`, `#want-to-read` and `#did-not-finish`, and shows your number of books read, pages read, average rating and books not finished. Each shelf shows up to 100 posts and 100 pages, newest first, and the numbers count the books on the shelves. The home page links to this page from its "Currently reading" section, and the 404 page links to it too.

For a book you have not reviewed, create a **page** instead of a post: title, feature image (the cover), and the same internal tags. Pages stay out of your home page, your RSS feed and your email newsletter. Write something in the page body to make the book's card a link to the page.

## Add an author website

For authors who want to present their own books:

1. Create a page, for example with your name as the title. Set **Template** to **Author site**. Its feature image is your portrait, its excerpt is the text under your name, and `#eyebrow:Novelist` sets the label above it.
2. Create a page for each of your books. Tag it `#book`, give it a public tag for the kind of book (for example "Novel"), set its publish date to the book's publication date, and add `#pages:` and `#buy:<link to a bookshop>` if you like. Mark your newest book as featured to show it as the new release.
3. Anything you write in the author page's body shows below your books. For quotes from reviews and for events, add HTML cards with this markup:

```html
<section class="section" aria-labelledby="praise-title">
  <div class="wrap">
    <div class="section-head"><h2 id="praise-title">Kind words</h2></div>
    <ul class="praise">
      <li><figure>
        <blockquote><p>“A novel that rings long after you close it.”</p></blockquote>
        <figcaption><strong>The Northern Review</strong> on The Bell Foundry</figcaption>
      </figure></li>
    </ul>
  </div>
</section>
```

```html
<section class="section" aria-labelledby="events-title">
  <div class="wrap">
    <div class="section-head"><h2 id="events-title">Events</h2></div>
    <ul class="events">
      <li><article class="event">
        <time class="event__date" datetime="2026-11-19"><span class="event__day">19</span> <span class="event__month">Nov</span></time>
        <div>
          <h3 class="event__title">In conversation: The Bell Foundry</h3>
          <p class="event__where">Wren &amp; Ash Books, York · 7 pm</p>
        </div>
        <a class="button button--outline" href="https://example.com/">Book a place</a>
      </article></li>
    </ul>
  </div>
</section>
```

The newsletter signup section follows when your site lets visitors sign up.

## Search, members, newsletters and comments

- **Search**: the search box in the header and on the 404 page opens Ghost's built-in search.
- **Signups**: the newsletter section is a Ghost signup form. It shows when **Settings > Membership** lets visitors sign up and the reader is not signed in.
- **Comments**: when you turn on comments in **Settings > Membership**, they show under each post.
- **Paid content**: Ghost shows its own call to action in place of content a reader cannot access.

## Templates

| Template | Used for |
| --- | --- |
| `index.hbs` | The home page and its later pages |
| `post.hbs` | Posts |
| `page.hbs` | Pages, including book pages; it respects "Show title and feature image" |
| `custom-reading-log.hbs` | Template "Reading log" |
| `custom-author-site.hbs` | Template "Author site" |
| `tag.hbs`, `author.hbs` | Tag and author archives |
| `error-404.hbs`, `error.hbs` | Not found and other errors |

## Translate the theme

Every interface string is in `locales/en.json`. Copy it to `locales/<language>.json`, translate the values, and set the publication language under **Settings > General**.

## The footer credit

The footer says "Published with Ghost" and "Theme by List of Books", with a link to https://www.listofbooks.net/. Both are in `partials/site/credit.hbs`. We ask that you keep the credit; it is how a free theme pays for itself.

## Develop

The theme's source lives in the repository https://github.com/listofbooks-net/readerly-theme. `assets/css/readerly.css`, `assets/js/theme.js`, the fonts and the grain images are generated from its `design/` folder, so change them there. `assets/css/ghost.css`, `assets/js/books.js` and the templates are edited here.
