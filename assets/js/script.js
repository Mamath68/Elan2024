document.addEventListener("DOMContentLoaded", function () {
  const menuButton = document.getElementById("menu-button");
  const mobileMenu = document.getElementById("mobile-menu");
  const openIcon = menuButton.querySelector("svg:nth-of-type(1)");
  const closeIcon = menuButton.querySelector("svg:nth-of-type(2)");

  menuButton.addEventListener("click", () => {
    mobileMenu.classList.toggle("hidden");
    openIcon.classList.toggle("hidden");
    closeIcon.classList.toggle("hidden");
  });
});

document.addEventListener("DOMContentLoaded", function () {
  // Sélectionnez le bouton du dropdown
  const dropdownButton = document.getElementById("cards-dropdown");

  // Sélectionnez le menu du dropdown
  const dropdownMenu = document.getElementById("cards-dropdown-menu");

  // Ajoutez un écouteur d'événements au bouton pour le click
  dropdownButton.addEventListener("click", function () {
    // Si le menu est caché, affichez-le
    if (dropdownMenu.classList.contains("hidden")) {
      dropdownMenu.classList.remove("hidden");
    } else {
      // Sinon, cachez-le
      dropdownMenu.classList.add("hidden");
    }
  });

  // Ajoutez un écouteur d'événements pour fermer le menu si l'utilisateur clique en dehors
  document.addEventListener("click", function (event) {
    if (
      !dropdownButton.contains(event.target) &&
      !dropdownMenu.contains(event.target)
    ) {
      dropdownMenu.classList.add("hidden");
    }
  });
});
