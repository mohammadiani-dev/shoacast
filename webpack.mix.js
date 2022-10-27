const { js } = require("laravel-mix");
const mix = require("laravel-mix");

mix.options({
  processCssUrls: false,
});

mix
  .sass("assets/css/style.scss", "assets/css/style.css")
  .js(
    [
      "assets/js/owl.carousel.min.js",
      "assets/js/script.js"
    ],
    "assets/js/script.min.js"
  )
  .sass("assets/css/admin-style.scss", "assets/css/admin-style.css")
  .js(["assets/js/admin-script.js"], "assets/js/admin-script.min.js")
