let mix = require('laravel-mix');

mix.setPublicPath('./');
mix.webpackConfig({
  devtool: 'inline-source-map',
});
mix
  .sass('assets/scss/style.scss', '/assets/css/style.min.css')
  .options({
    processCssUrls: false, // Keep original URLs in CSS
  })
  .sourceMaps();