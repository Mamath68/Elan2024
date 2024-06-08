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
