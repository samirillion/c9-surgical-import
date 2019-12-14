import Vue from 'vue'
import App from './App.vue'
import router from './router'
import menuFix from './utils/admin-menu-fix'

import vSelect from 'vue-select'
Vue.component('v-select', vSelect)

// import devtools from '@vue/devtools'

// if (process.env.NODE_ENV === 'development') {
//     devtools.connect('http://' + c9_import_vars.baseURL)
// }

Vue.config.productionTip = false

/* eslint-disable no-new */
new Vue({
    el: '#vue-admin-app',
    router,
    render: h => h(App)
});


// fix the admin menu for the slug "ifm-importer"
menuFix('ifm-importer');
