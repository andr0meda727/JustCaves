document.addEventListener("DOMContentLoaded", function () {
  const applyFilters = () => {
    const regionSelect = document.querySelector(".region-select");
    const statusSelect = document.querySelector(".status-select");
    const searchInput = document.querySelector(".search-box input");

    const regionId = regionSelect ? regionSelect.value : "";
    const status = statusSelect ? statusSelect.value : "";
    const search = searchInput ? searchInput.value : "";

    let url = new URL(window.location.href);

    if (regionId) url.searchParams.set("region_id", regionId);
    else url.searchParams.delete("region_id");

    url.searchParams.set("status", status);

    if (search) url.searchParams.set("search", search);
    else url.searchParams.delete("search");

    url.searchParams.set("page", 1);
    window.location.href = url.toString();
  };

  const regionSelect = document.querySelector(".region-select");
  const statusSelect = document.querySelector(".status-select");
  const searchInput = document.querySelector(".search-box input");

  if (regionSelect) regionSelect.addEventListener("change", applyFilters);
  if (statusSelect) statusSelect.addEventListener("change", applyFilters);

  if (searchInput) {
    searchInput.addEventListener("keypress", function (e) {
      if (e.key === "Enter") applyFilters();
    });
  }
});
