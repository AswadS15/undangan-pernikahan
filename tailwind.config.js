/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./*.{html,js,php}"],
  theme: {
    extend: {
      fontFamily: {
      'poppins': ['Poppins', 'sans-serif'],
      'play': ['Playwrite HR', 'cursive'],
      'vibes': ['Great Vibes', 'cursive'],
      'work': ['Work Sans', 'sans-serif'],
      'cinzel': ['Cinzel', 'serif'],
    },
    },
  },
  plugins: [],
}
