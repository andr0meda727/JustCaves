document.addEventListener("DOMContentLoaded", function () {
  const visitBtn = document.getElementById("visit-btn");
  const visitText = document.getElementById("visit-text");

  if (!visitBtn) return;

  visitBtn.addEventListener("click", function () {
    const caveId = this.dataset.id;

    fetch(`/visit/${caveId}`, {
      method: "POST",
    })
      .then((response) => {
        if (response.status === 401) {
          alert("Musisz być zalogowany!");
          return;
        }
        return response.json();
      })
      .then((data) => {
        if (data && data.success) {
          if (data.status === "marked") {
            visitBtn.classList.add("visited-active");
            visitText.innerText = "Odwiedzona";
          } else {
            visitBtn.classList.remove("visited-active");
            visitText.innerText = "Oznacz jako odwiedzoną";
          }
        }
      })
      .catch((err) => console.error("Error:", err));
  });
});
