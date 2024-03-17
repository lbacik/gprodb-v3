/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: 'selector',
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
    // "./src/Form/**/*.php",
    "./vendor/pagerfanta/twig/templates/tailwind.html.twig",
  ],
  theme: {
    extend: {},
  },
  plugins: [
      require('@tailwindcss/forms'),
  ],
}
