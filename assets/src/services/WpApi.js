// node-wpapi JS wrapper for the wp-api
var WPAPI = require("wpapi");

// wp-api params are defined here: http://v2.wp-api.org/reference/posts/
// using ES6 property-value shorthand for the object literal parameters
var WpApi = new WPAPI({
  endpoint: window.location.origin + "/wp-json",
  nonce: window.WP_API_Settings.nonce
});

WpApi.importMethods = WpApi.registerRoute("ifm/importer/v1", "/methods");

WpApi.myPosts = WpApi.registerRoute("ifm/importer/v1", "/author/(?P<id>)");

WpApi.getCsv = WpApi.registerRoute("ifm/importer/v1", "/getcsv");

WpApi.runImport = WpApi.registerRoute("ifm/importer/v1", "/run");

WpApi.importParams = WpApi.registerRoute("ifm/importer/v1", "/get-params");

WpApi.postTypes = WpApi.registerRoute("ifm/importer/v1", "/get-post-types");

WpApi.acfFields = WpApi.registerRoute("ifm/importer/v1", "/get-acf-fields");

WpApi.taxonomies = WpApi.registerRoute("ifm/importer/v1", "/get-taxonomies");

WpApi.previewCustomVar = WpApi.registerRoute("ifm/importer/v1", "/preview-custom-var");

export { WpApi };
