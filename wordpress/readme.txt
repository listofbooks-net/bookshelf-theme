=== Bookshelf ===
Contributors: listofbooks
Requires at least: 6.9
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A warm, readable block theme for book blogs, author websites and reading logs.

== Description ==

Bookshelf is a block theme for people who love books. It is built for book review blogs first, and it also gives you everything an author website or a reading log needs, as patterns you add only when you want them.

* A blog front page with a featured review (your newest sticky post), a four-column grid of your other posts that follows Settings > Reading and paginates, a "currently reading" strip, a contents list of your genres and a newsletter band. Each one is a separate block you can delete in one click.
* A single review layout with a book details panel, a pull quote, a verdict box, an author bio and threaded comments.
* Author site patterns: an introduction with your portrait, a new release band with where-to-buy notes, a books grid whose Buy and Learn more buttons line up across every card, praise quotes and an events list.
* Reading log patterns: yearly statistics, shelves of covers, and book cards with status badges (Reading, Read, Want to read, Did not finish) and ratings.
* Two styles: "Paper", warm and light, and "Lamplight", a dark style for night reading.
* Block styles for book covers, cards, eyebrow labels, ratings, tag chips and status badges.
* Fraunces and Literata fonts, bundled with the theme. The theme makes no requests to font services or other third-party hosts.

Bookshelf does not create pages, posts, menus or categories on your site. Nothing changes until you choose a pattern.

== Choosing what you use ==

Bookshelf serves three uses: a book blog, an author website and a reading log. Use any of them, all of them, or none.

= Turn a use on =

1. Go to Pages > Add New Page.
2. In the "Choose a pattern" window, pick one of the Bookshelf page patterns: Author home page, My books page, Events page, Reading log page, About page, or Contact and newsletter page.
3. For the Author home, My books, Events and Reading log pages, open the Template panel in the page settings and choose "Landing page (no title, full width)".
4. Publish the page, then add it to your menu in Appearance > Editor > Navigation.

You can also insert single sections anywhere. In the block inserter, open Patterns and look in the "Bookshelf: Book blog", "Bookshelf: Author site" and "Bookshelf: Reading log" categories.

= Turn a use off =

* Pages: do not create the page, or move it to the trash. Remove it from your menu in Appearance > Editor > Navigation.
* Sections on the blog front page: go to Appearance > Editor > Templates > Blog Home. Click the section you do not need (for example "Currently reading" or "Newsletter"), use the List View to select its outer group, and press Delete or choose "Delete" from the block toolbar's options menu. Save.
* Sections on a page: open the page, select the section's outer group and delete it.
* To bring back a template you changed, go to Appearance > Editor > Templates, open the template, and choose "Reset" from its options menu.

= Make it a book blog without a static front page =

By default WordPress shows your latest posts on the front page, and Bookshelf uses the Blog Home template for it. To use the Author home page as your front page instead, go to Settings > Reading, choose "A static page", and pick that page as the homepage and another page as the posts page.

= Choose the featured review =

The card under the front page introduction shows your newest sticky post. To feature a review, edit it and turn on "Stick to the top of the blog" in the post settings. The same post is left out of the grid below it. With no sticky post the card shows nothing and the grid lists every post. If you delete the card from the Blog Home template, the grid lists every post again.

= Reading log without plugins =

Shelves and book cards are ordinary blocks. To add a book, duplicate a card and change its cover image, title, author, rating and status. To change a status badge, select the paragraph and pick another style in the Styles panel: Status: Reading, Status: Read, Status: Want to read, or Status: Did not finish. The badge text names the status, so it never relies on colour alone.

= Newsletter and contact =

WordPress has no built-in form block, so the newsletter band has a Subscribe button. Link it to the signup page of your newsletter service. For a contact form, add a form plugin and insert its block into the Contact page.

= Footer credit =

The footer has one link, "Theme by List of Books". To remove or change it, go to Appearance > Editor > Patterns > Footer (or Template Parts > Footer) and edit or delete that paragraph.

== Frequently Asked Questions ==

= Where do the review covers on the front page come from? =

From each post's featured image. Set a featured image on every review, ideally a book cover with a 2:3 shape.

= How do I switch to the dark style? =

Go to Appearance > Editor > Styles and choose "Lamplight".

== Changelog ==

= 1.0.0 =
* First release.

== Copyright ==

Bookshelf WordPress Theme, (C) 2026 List of Books.
Bookshelf is distributed under the terms of the GNU GPL.

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

== Resources ==

Fraunces
Copyright 2020 The Fraunces Project Authors (https://github.com/undercasetype/Fraunces)
License: SIL Open Font License, Version 1.1, https://openfontlicense.org
Source: https://github.com/undercasetype/Fraunces, variable woff2 files from https://fontsource.org/fonts/fraunces
Files: assets/fonts/fraunces/*.woff2, license text in assets/fonts/fraunces/OFL.txt

Literata
Copyright 2017 The Literata Project Authors (https://github.com/googlefonts/literata)
License: SIL Open Font License, Version 1.1, https://openfontlicense.org
Source: https://github.com/googlefonts/literata, variable woff2 files from https://fontsource.org/fonts/literata
Files: assets/fonts/literata/*.woff2, license text in assets/fonts/literata/OFL.txt

Book covers, portraits and illustrations
Copyright 2026 List of Books. Original artwork made for this theme; every book, author and title shown is fictional.
License: GPLv2 or later, https://www.gnu.org/licenses/gpl-2.0.html
Files: assets/images/covers/*.svg, assets/images/portrait-isobel.svg, assets/images/avatar-nora.svg, assets/images/still-life.svg, assets/images/paper-grain.svg

Screenshot
Copyright 2026 List of Books. A capture of this theme with its own demo content.
License: GPLv2 or later, https://www.gnu.org/licenses/gpl-2.0.html
File: screenshot.png
