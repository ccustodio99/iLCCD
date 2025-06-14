/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './resources/views/**/*.blade.php',
    './resources/js/**/*.js',
    './resources/js/**/*.vue'
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Poppins', 'Roboto', 'Montserrat', 'sans-serif']
      },
      colors: {
        navy: '#1B2660',
        gold: '#FFCD38',
        ccyan: '#20BFEA',
        brandred: '#E5403A',
        brandwhite: '#FFFFFF',
        brandgray: '#808080'
      }
    }
  },
  plugins: []
};
