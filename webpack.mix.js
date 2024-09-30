const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
   .vue() // Tambahkan ini untuk mendukung file Vue
   .sass('resources/sass/app.scss', 'public/css');
