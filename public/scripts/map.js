let map;
let markers = [];

function initMap() {
  const mapContainer = document.getElementById("map");
  if (!mapContainer) return;

  map = L.map("map").setView([52.06, 19.48], 6);

  L.tileLayer(
    "https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}",
    {
      attribution: "Tiles &copy; Esri &mdash; Source: Esri",
    }
  ).addTo(map);

  const caveCards = document.querySelectorAll(".cave-card");

  caveCards.forEach((card) => {
    const lat = parseFloat(card.dataset.lat);
    const lng = parseFloat(card.dataset.lng);
    const rawName = card.querySelector("h3").innerText;

    if (!isNaN(lat) && !isNaN(lng)) {
      const marker = L.marker([lat, lng]).addTo(map);
      marker.bindPopup(`<b>${rawName}</b>`);

      markers.push({
        name: card.dataset.name,
        instance: marker,
        element: card,
      });
    }
  });

  const searchInput = document.getElementById("search-input");
  if (searchInput) {
    searchInput.addEventListener("input", function (e) {
      const val = e.target.value.toLowerCase();

      markers.forEach((item) => {
        const isVisible = item.name.includes(val);

        item.element.style.display = isVisible ? "flex" : "none";

        if (isVisible) {
          map.addLayer(item.instance);
        } else {
          map.removeLayer(item.instance);
        }
      });
    });
  }
}

function focusCave(lat, lng) {
  if (map && lat && lng) {
    map.flyTo([lat, lng], 14);
  }
}

window.onload = initMap;
