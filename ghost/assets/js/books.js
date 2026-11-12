/*
 * Readerly for Ghost: numbers that Handlebars cannot compute.
 *  - Reading progress percentages on "currently reading" cards
 *  - Shelf counts and the reading statistics on the reading log page
 * Without this file the progress bars and shelves still show; the counts and statistics stay hidden.
 */
(function () {
  function percent(progress) {
    var max = Number(progress.getAttribute("max"));
    var value = Number(progress.getAttribute("value"));
    if (!(max > 0) || !isFinite(value)) return 0;
    return Math.min(100, Math.max(0, Math.round((value / max) * 100)));
  }

  function fillProgress() {
    Array.prototype.forEach.call(document.querySelectorAll(".progress"), function (box) {
      var bar = box.querySelector("progress");
      var slot = box.querySelector("[data-percent]");
      if (!bar || !slot) return;
      var text = percent(bar) + "%";
      bar.textContent = text;
      slot.textContent = text;
    });
  }

  function cards(shelf) {
    var list = document.querySelector('[data-shelf="' + shelf + '"]');
    return list ? Array.prototype.slice.call(list.querySelectorAll(".book-card")) : [];
  }

  var locale = document.documentElement.lang || undefined;
  function format(number, digits) {
    try {
      return number.toLocaleString(locale, { maximumFractionDigits: digits || 0 });
    } catch (error) {
      return String(digits ? Math.round(number * 10) / 10 : number);
    }
  }

  function fillCounts() {
    Array.prototype.forEach.call(document.querySelectorAll("[data-count]"), function (slot) {
      var count = cards(slot.getAttribute("data-count")).length;
      var label = slot.getAttribute(count === 1 ? "data-one" : "data-many") || "%";
      slot.textContent = label.replace("%", format(count));
    });
  }

  function fillStats() {
    var stats = document.querySelector("[data-stats]");
    if (!stats) return;
    var read = cards("read");
    if (!read.length) return;
    var pages = 0;
    var ratings = [];
    read.forEach(function (card) {
      pages += Number(card.getAttribute("data-pages")) || 0;
      var rating = parseFloat(card.getAttribute("data-rating"));
      if (isFinite(rating)) ratings.push(rating);
    });
    var values = {
      read: format(read.length),
      pages: pages ? format(pages) : "",
      rating: ratings.length ? format(ratings.reduce(function (a, b) { return a + b; }, 0) / ratings.length, 1) : "",
      dnf: format(cards("dnf").length),
    };
    Array.prototype.forEach.call(stats.querySelectorAll("[data-stat]"), function (slot) {
      var value = values[slot.getAttribute("data-stat")];
      if (value) slot.textContent = value;
      else if (slot.closest("li")) slot.closest("li").hidden = true;
    });
    stats.hidden = false;
  }

  document.addEventListener("DOMContentLoaded", function () {
    fillProgress();
    fillCounts();
    fillStats();
  });
})();
