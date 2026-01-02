document.addEventListener("DOMContentLoaded", function () {
  const mapContainer = document.getElementById("map");

  if (!mapContainer) return;

  const lat = parseFloat(mapContainer.dataset.lat);
  const lng = parseFloat(mapContainer.dataset.lng);
  const caveName = mapContainer.dataset.name;

  if (isNaN(lat) || isNaN(lng)) return;

  const smallMap = L.map("map").setView([lat, lng], 13);

  L.tileLayer(
    "https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}",
    {
      attribution: "Tiles &copy; Esri",
    }
  ).addTo(smallMap);

  L.marker([lat, lng])
    .addTo(smallMap)
    .bindPopup(`<b>${caveName}</b>`)
    .openPopup();
});
