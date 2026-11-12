/*
 * Readerly — optional enhancements. Every page works without this file.
 *  - Light/dark toggle (remembers the choice; otherwise follows the system)
 *  - Friendly confirmation for demo forms (newsletter, comments)
 *  - Search term echo on the archive page
 * Loaded in <head> so a saved theme applies before first paint.
 */
(function () {
  var root = document.documentElement;
  var STORAGE_KEY = "readerly-theme";
  var darkQuery = window.matchMedia ? window.matchMedia("(prefers-color-scheme: dark)") : null;

  function readStoredTheme() {
    try {
      return localStorage.getItem(STORAGE_KEY);
    } catch (error) {
      return null;
    }
  }

  function storeTheme(value) {
    try {
      localStorage.setItem(STORAGE_KEY, value);
    } catch (error) {
      /* Storage blocked: the choice lasts for this page view only. */
    }
  }

  function effectiveTheme() {
    var explicit = root.getAttribute("data-theme");
    if (explicit === "light" || explicit === "dark") return explicit;
    return darkQuery && darkQuery.matches ? "dark" : "light";
  }

  var stored = readStoredTheme();
  if (stored === "light" || stored === "dark") root.setAttribute("data-theme", stored);

  function initThemeToggle() {
    var toggle = document.querySelector("[data-theme-toggle]");
    if (!toggle) return;

    function sync() {
      toggle.setAttribute("aria-pressed", effectiveTheme() === "dark" ? "true" : "false");
    }

    toggle.hidden = false;
    sync();
    toggle.addEventListener("click", function () {
      var next = effectiveTheme() === "dark" ? "light" : "dark";
      root.setAttribute("data-theme", next);
      storeTheme(next);
      sync();
    });
    if (darkQuery && darkQuery.addEventListener) darkQuery.addEventListener("change", sync);
  }

  function initDemoForms() {
    var forms = document.querySelectorAll("form[data-demo-form]");
    Array.prototype.forEach.call(forms, function (form) {
      var status = form.querySelector("[data-form-status]");
      form.addEventListener("submit", function (event) {
        event.preventDefault();
        if (!form.checkValidity()) {
          form.reportValidity();
          return;
        }
        form.reset();
        if (status) status.textContent = status.getAttribute("data-message") || "";
      });
    });
  }

  function initSearchNote() {
    var note = document.querySelector("[data-search-note]");
    if (!note || !window.URLSearchParams) return;
    var term = new URLSearchParams(window.location.search).get("s");
    if (!term) return;
    var slot = note.querySelector("[data-search-term]");
    if (slot) slot.textContent = term;
    note.hidden = false;
  }

  document.addEventListener("DOMContentLoaded", function () {
    initThemeToggle();
    initDemoForms();
    initSearchNote();
  });
})();
