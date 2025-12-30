document.addEventListener("DOMContentLoaded", function () {
  const fileInput = document.getElementById("file-input");
  const dropZone = document.getElementById("drop-zone");
  const fileNameDisplay = document.getElementById("file-name");

  dropZone.addEventListener("click", () => fileInput.click());

  fileInput.addEventListener("change", function () {
    if (this.files && this.files.length > 0) {
      fileNameDisplay.innerHTML = `Wybrany plik: <strong>${this.files[0].name}</strong>`;
    }
  });

  dropZone.addEventListener("dragover", (e) => {
    e.preventDefault();
    dropZone.style.borderColor = "#2ecc71";
  });

  dropZone.addEventListener("dragleave", () => {
    dropZone.style.borderColor = "";
  });

  dropZone.addEventListener("drop", (e) => {
    e.preventDefault();
    dropZone.style.borderColor = "";

    if (e.dataTransfer.files.length > 0) {
      fileInput.files = e.dataTransfer.files;
      fileNameDisplay.innerHTML = `Wybrany plik: <strong>${e.dataTransfer.files[0].name}</strong>`;
    }
  });
});
