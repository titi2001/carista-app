/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './resources/views/**/*.blade.php',     
    './resources/js/**/*.vue',              
    './resources/js/**/*.jsx',              
    './resources/js/**/*.js',               
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
  ],
  theme: {
    extend: {
      colors: {
      },
    },
  },
  plugins: [],
  safelist: [
    'text-red-500',
    'text-blue-500',
    'bg-green-200',
    'bg-yellow-300',
    'hover:bg-blue-500',
    'focus:ring-2',
    /^text-/,
    /^bg-/,
    /^hover:/,
    /^focus:/,
  ],
}
