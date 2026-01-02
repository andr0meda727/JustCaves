document.addEventListener("DOMContentLoaded", function () {
  const map = L.map("map").setView([52.06, 19.48], 6);

  L.tileLayer(
    "https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}",
    {
      attribution: "Tiles &copy; Esri &mdash; Source: Esri",
    }
  ).addTo(map);

  const caveCards = document.querySelectorAll(".cave-card");
  const markers = [];

  caveCards.forEach((card) => {
    const lat = parseFloat(card.dataset.lat);
    const lng = parseFloat(card.dataset.lng);
    const name = card.querySelector("h3").innerText;

    if (lat && lng) {
      const marker = L.marker([lat, lng])
        .addTo(map)
        .bindPopup(`<b>${name}</b>`);
      markers.push(marker);
    }
  });

  window.focusCave = function (lat, lng) {
    if (lat && lng) {
      map.flyTo([lat, lng], 14, {
        duration: 1.5,
      });
    }
  };

  setTimeout(() => {
    map.invalidateSize();
  }, 200);
});
