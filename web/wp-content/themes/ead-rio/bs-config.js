module.exports = {
  proxy: "https://wp-ead-rio.ddev.site", // DDEV WordPress site URL
  files: [
    "dist/css/**/*.css",
    "dist/js/**/*.js",
    "**/*.php"
  ],
  watchEvents: ["change"],
  ignore: [
    "node_modules",
    "*.map"
  ],
  injectChanges: true,
  open: false,
  notify: true,
  reloadDelay: 100
};