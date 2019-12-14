// node-wpapi JS wrapper for the wp-api
var WPAPI = require('wpapi');
// import axios from 'axios';


// export const WpApi = () => {
//     return axios.create({
//         baseURL
//     })
// }

// wp-api params are defined here: http://v2.wp-api.org/reference/posts/
// using ES6 property-value shorthand for the object literal parameters 
var WpApi = new WPAPI({
    endpoint: 'http://' + c9_import_vars.baseURL + '/wp-json',
    nonce: window.WP_API_Settings.nonce
});

WpApi.myPosts = WpApi.registerRoute('ifm-importer/v1', '/author/(?P<id>)');

WpApi.getCsv = WpApi.registerRoute('ifm-importer/v1', '/getcsv');

WpApi.importPosts = WpApi.registerRoute('ifm-importer/v1', '/import-posts');


export { WpApi }