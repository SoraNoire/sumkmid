/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */
  const{ mix} = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

 mix.sass('resources/assets/sass/app.scss', 'public/css')
 .combine([
  'Modules/Blog/Assets/js/jquery-3.2.1.js',
  'Modules/Blog/Assets/js/select2.min.js',
  'Modules/Blog/Assets/js/jquery.dataTables.min.js',
  'Modules/Blog/Assets/js/bootstrap.min.js',
  'Modules/Blog/Assets/js/ie10-viewport-bug-workaround.js',
  'Modules/Blog/Assets/js/bootstrap-datetimepicker.min.js',
  'Modules/Blog/Assets/js/blogPlugin.js',
  'Modules/Menu/Assets/js/jquery.nestable.js',
  'Modules/Menu/Assets/js/menu.js',
  'Modules/Blog/Assets/js/component.js',
  'Modules/Blog/Assets/js/mydatatables.js',
  'Modules/Blog/Assets/js/jquery.nestable.js',
  'Modules/Blog/Assets/js/custom.js',
  'Modules/Video/Assets/js/video.js',
  'Modules/Event/Assets/js/event.js',
  'Modules/Gallery/Assets/js/gallery.js',
  'Modules/Blog/Assets/js/bootstrap-notify.js'
  ],'public/js/index.js')
  .combine([
    'resources/assets/js/jscroll.js',
    'resources/assets/js/home.js'
  ], 'public/js/home.js')
 .sass('Modules/Blog/Assets/scss/style.scss', 'public/css')
 .sass('Modules/Blog/Assets/font-awesome/scss/font-awesome.scss', 'public/css')
 .sass('resources/assets/sass/index.scss', 'public/css')
 .browserSync({'proxy' : 'www.sahabat.test',files: ['Modules/Blog/Resources/views/admin/*.php','Modules/Blog/Resources/views/layouts/*.php', 'Modules/Blog/Assets/js/*.js', 'resources/assets/js/*.js', 'resources/views/layouts/*.php', 'resources/views/page/*.php']});
