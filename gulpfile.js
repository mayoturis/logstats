var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
	mix.less('installation.less');

	mix.less(['main.less', 'login.less'], 'public/css/main.css');

	mix.scripts(['installation.js'], 'public/js/installation.js');
	mix.scripts(['main.js'], 'public/js/main.js');
});

