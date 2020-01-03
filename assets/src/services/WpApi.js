// node-wpapi JS wrapper for the wp-api
var WPAPI = require("wpapi");
// import axios from 'axios';

// export const WpApi = () => {
//     return axios.create({
//         baseURL
//     })
// }

// wp-api params are defined here: http://v2.wp-api.org/reference/posts/
// using ES6 property-value shorthand for the object literal parameters
var WpApi = new WPAPI({
  endpoint: "http://" + ifm_import_vars.baseURL + "/wp-json",
  nonce: window.WP_API_Settings.nonce
});

WpApi.importMethods = WpApi.registerRoute("ifm/importer/v1", "/methods");

WpApi.myPosts = WpApi.registerRoute("ifm/importer/v1", "/author/(?P<id>)");

WpApi.getCsv = WpApi.registerRoute("ifm/importer/v1", "/getcsv");

WpApi.runImport = WpApi.registerRoute("ifm/importer/v1", "/run");

WpApi.postTypes = WpApi.registerRoute("ifm/importer/v1", "/get-post-types");

WpApi.postTypes = WpApi.registerRoute("ifm/importer/v1", "/get-acf-fields");

export { WpApi };
