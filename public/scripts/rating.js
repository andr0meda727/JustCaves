document.addEventListener("DOMContentLoaded", () => {
  const container = document.getElementById("difficulty-rating");
  if (!container) return;

  const stars = Array.from(container.querySelectorAll(".star"));

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
  });
});
