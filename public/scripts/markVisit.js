document.addEventListener("DOMContentLoaded", function () {
  const visitBtn = document.getElementById("visit-btn");

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
          visitBtn.classList.add("visited-active");
          visitBtn.disabled = true;
          document.getElementById("visit-text").innerText = "Odwiedzona";
        }
      })
      .catch((err) => console.error("Error:", err));
  });
});
