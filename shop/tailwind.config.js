/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.{html,js,vue,php}",
    "./resources/views/**/*.blade.php", // برای Laravel
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
