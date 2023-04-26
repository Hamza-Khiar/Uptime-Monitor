/** @type {import('tailwindcss').Config} */
module.exports = {
  purge: ['./index.html', './src/**/*.{vue,js,ts,jsx,tsx}'],
  content:[
    './src/**/*.{js,tsx,vue,jsx}'
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}

