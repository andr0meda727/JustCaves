document.addEventListener("DOMContentLoaded", () => {
  const searchInput = document.getElementById("user-search");

  if (searchInput) {
    searchInput.addEventListener("keypress", function (e) {
      if (e.key === "Enter") {
        const searchTerm = this.value.trim();
        applyUserSearch(searchTerm);
      }
    });

    if (searchInput.value.length > 0) {
      searchInput.focus();
      searchInput.setSelectionRange(
        searchInput.value.length,
        searchInput.value.length,
      );
    }
  }
});

function applyUserSearch(term) {
  const url = new URL(window.location.href);

  if (term) {
    url.searchParams.set("search", term);
  } else {
    url.searchParams.delete("search");
  }

  url.searchParams.set("page", "1");

  window.location.href = url.toString();
}
