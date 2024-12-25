const plugin = require('tailwindcss/plugin');

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
    extend: {
      animation: {
        'fade-in': 'fadeIn .5s ease-out;',
      },
      keyframes: {
        fadeIn: {
          '0%': {opacity: 0},
          '100%': {opacity: 1},
        },
      },
    },
  },
  // ...
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
    plugin(function({ addVariant }) {
      addVariant('turbo-frame', 'turbo-frame[src] &')
      addVariant('modal', 'dialog &')
    }),
  ],
  safelist: [
    'text-red-700',
  ],
}
