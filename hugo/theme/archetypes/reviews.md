---
# A review. Every key except title is optional: delete a line and that part of the page disappears.
title: "{{ replace .File.ContentBaseName "-" " " | title }}"
date: {{ .Date }}
draft: true
lead: One sentence that sums up the book, shown under the title.
book_author: A. N. Author
# cover: cover.jpg                  # an image in this page's folder, a path under static/, or a URL. Without one a typeset cover shows
categories: [Literary fiction]      # the first is the book's genre: the label above the title, the genre list, "You might also like"
tags: [Debut]                       # extra chips next to the genre
rating: 4.5                         # 0 to 5, halves allowed
series: The Example Trilogy, Book 1 of 3
pages: 320
publication: 2026, Example Press
status: read                        # reading, read, want or dnf: puts the book on the reading log
finished: {{ now.Format "2006-01-02" }}   # orders the Read shelf and counts the book in that year's statistics
# current_page: 120                 # with pages: a progress bar for reading, "Stopped at page N" for dnf
# featured: true                    # the newest featured review fills the big card on the home page
summary: Two or three sentences for the review cards on the home page and in the archive.
verdict:
  summary: A one-line verdict.
  liked: [Something that worked, Another thing]
  disliked: [Something that didn't]
---

Write the review here, in Markdown.
