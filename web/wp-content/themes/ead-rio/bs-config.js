module.exports = {
  proxy: "localhost:8000", // Change this to your local WordPress URL
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