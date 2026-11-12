/*
 * Readerly archive filter. Narrows the archive list to ?s= (words in the title,
 * author, series, genre or tags) or ?genre= (one genre or tag). Without JavaScript
 * the page lists every review.
 */
(function () {
  if (!window.URLSearchParams) return;
  var params = new URLSearchParams(window.location.search);
  var search = (params.get("s") || "").trim();
  var genre = (params.get("genre") || "").trim();
  if (!search && !genre) return;

  document.addEventListener("DOMContentLoaded", function () {
    var list = document.querySelector("[data-filter-list]");
    var note = document.querySelector("[data-search-note]");
    if (!list) return;

    var words = search.toLowerCase().split(/\s+/).filter(Boolean);
    var genreKey = "|" + genre.toLowerCase() + "|";
    var shown = 0;
    Array.prototype.forEach.call(list.children, function (item) {
      var text = item.getAttribute("data-search") || "";
      var match = genre
        ? (item.getAttribute("data-genres") || "").indexOf(genreKey) !== -1
        : words.every(function (word) { return text.indexOf(word) !== -1; });
      item.hidden = !match;
      if (match) shown += 1;
    });

    if (!note) return;
    var term = note.querySelector("[data-search-term]") || document.createElement("strong");
    term.textContent = genre || search;
    var label = note.getAttribute(genre ? "data-genre-label" : "data-search-label") || "";
    var count = note.getAttribute(shown === 0 ? "data-none" : shown === 1 ? "data-one" : "data-many") || "";
    note.replaceChildren(
      document.createTextNode(label + " \u201c"),
      term,
      document.createTextNode("\u201d. " + count.replace("%n", shown))
    );
    note.hidden = false;
  });
})();
