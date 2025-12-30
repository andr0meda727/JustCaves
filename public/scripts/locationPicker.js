document.addEventListener("DOMContentLoaded", function () {
  const initialLat = 50.0614;
  const initialLng = 19.9366;

  const map = L.map("map").setView([initialLat, initialLng], 13);

  L.tileLayer(
    "https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}",
    {
      attribution:
        "Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community",
    }
  ).addTo(map);

  let marker = L.marker([initialLat, initialLng], {
    draggable: true,
  }).addTo(map);

  const latInput = document.getElementById("lat");
  const lngInput = document.getElementById("lng");

  function updateInputs(lat, lng) {
    latInput.value = lat.toFixed(6);
    lngInput.value = lng.toFixed(6);
  }

  marker.on("dragend", function (e) {
    const position = marker.getLatLng();
    updateInputs(position.lat, position.lng);
  });

  map.on("click", function (e) {
    marker.setLatLng(e.latlng);
    updateInputs(e.latlng.lat, e.latlng.lng);
  });

  setTimeout(() => {
    map.invalidateSize();
  }, 100);
});
