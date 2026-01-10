document.addEventListener("DOMContentLoaded", () => {
  const container = document.getElementById("difficulty-rating");
  if (!container) return;

  const stars = Array.from(container.querySelectorAll(".star"));
  const statusText = document.getElementById("rating-status");
  const caveId = container.dataset.caveId;

  const renderStars = (rating, className = "active") => {
    stars.forEach((star) => {
      const val = parseInt(star.dataset.value);
      if (val <= rating) {
        star.classList.add(className);
      } else {
        star.classList.remove(className);
      }
    });
  };

  stars.forEach((star) => {
    star.addEventListener("mouseenter", () => {
      const hoverVal = parseInt(star.dataset.value);
      stars.forEach((s) => s.classList.remove("active"));
      renderStars(hoverVal, "hovered");
    });

    star.addEventListener("mouseleave", () => {
      stars.forEach((s) => s.classList.remove("hovered"));
      const savedRating = parseInt(container.dataset.currentRating) || 0;
      renderStars(savedRating, "active");
    });

    star.addEventListener("click", () => {
      const score = parseInt(star.dataset.value);
      submitRating(caveId, score);
    });
  });

  async function submitRating(caveId, score) {
    statusText.innerText = "Zapisywanie...";
    try {
      const response = await fetch("/rateCave", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ caveId, score }),
      });

      const result = await response.json();

      if (response.ok && result.success) {
        container.dataset.currentRating = score;
        statusText.innerText = `Twoja ocena: ${score}/10 (Zapisano!)`;
        renderStars(score, "active");
      } else {
        alert(result.message || "Błąd podczas oceniania.");
        statusText.innerText = "Wybierz poziom trudności (1-10)";
      }
    } catch (error) {
      console.error("Error:", error);
      statusText.innerText = "Wybierz poziom trudności (1-10)";
    }
  }
});
