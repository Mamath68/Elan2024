/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./assets/**/*.js", "./templates/**/*.html.twig"],
  theme: {
    extend: {
      width: {
        "3/10": "30%",
        "7/10": "70%",
      },
      colors: {
        aqua: "aqua", // Ajoute la couleur aqua
      },
    },
  },
  plugins: [require("flowbite/plugin")],
};
