/** @type {import('tailwindcss').Config} */
const plugin = require('tailwindcss/plugin')
module.exports = {
  content: [
    "./assets/**/*.scss",
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
      "./node_modules/tw-elements/dist/js/**/*.js"
  ],
  theme: {
    extend: {
      colors: {
        'primary-color': '#001F3FFF',
      },
    },
  },
  plugins: [
    plugin(function({ addVariant, addBase, theme  }) {
      addVariant('optional', '&:optional')
      addVariant('hocus', ['&:hover', '&:focus'])
      addVariant('inverted-colors', '@media (inverted-colors: inverted)')
      addBase({
        'h1': { fontSize: theme('fontSize.2xl') },
        'h2': { fontSize: theme('fontSize.xl') },
        'h3': { fontSize: theme('fontSize.lg') },
      })
    })
  ],
}