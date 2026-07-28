/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
   theme: {
    extend: {
      colors: {
        washoku: {
          bg: '#0B0908',
          input: '#1F1A18',
          inputtext: '#8D847E',
          red: '#8C2121',
          redhover: '#701A1A',
          cream: '#FBF8F1',
          beige: '#E3DBCD',
          yellow: '#F68F1A',
        },
      },
      fontFamily: {
        display: ['"Shippori Mincho"', 'serif'],
        body: ['"Inter"', 'sans-serif'],
      },
    },
  },
  plugins: [],
}