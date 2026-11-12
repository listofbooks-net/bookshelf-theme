/*
 * Readerly archive search. Narrows the archive list to ?s= (words in the title,
 * author, series, genres or tags). Without JavaScript the page lists every review.
 */
(function () {
  if (!window.URLSearchParams) return;
  var search = (new URLSearchParams(window.location.search).get("s") || "").trim();
  if (!search) return;

  document.addEventListener("DOMContentLoaded", function () {
    var list = document.querySelector("[data-filter-list]");
    var note = document.querySelector("[data-search-note]");
    if (!list) return;

    var words = search.toLowerCase().split(/\s+/).filter(Boolean);
    var shown = 0;
    Array.prototype.forEach.call(list.children, function (item) {
      var text = item.getAttribute("data-search") || "";
      var match = words.every(function (word) { return text.indexOf(word) !== -1; });
      item.hidden = !match;
      if (match) shown += 1;
    });

    if (!note) return;
    var term = note.querySelector("[data-search-term]");
    if (term) term.textContent = search;
    var count = note.getAttribute(shown === 0 ? "data-none" : shown === 1 ? "data-one" : "data-many") || "";
    note.appendChild(document.createTextNode(" " + count.replace("%n", shown)));
    note.hidden = false;
  });
})();
