/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: 'selector',
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
    "./vendor/pagerfanta/twig/templates/tailwind.html.twig",
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
