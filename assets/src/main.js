import Vue from "vue";
import App from "./App.vue";
import router from "./router";
import menuFix from "./utils/admin-menu-fix";

import VueSSE from "vue-sse";
import vSelect from "vue-select";

Vue.use(VueSSE);

Vue.component("v-select", vSelect);

Vue.config.productionTip = false;

/* eslint-disable no-new */
new Vue({
  el: "#vue-admin-app",
  router,
  render: h => h(App)
});

// fix the admin menu for the slug "ifm-importer"
menuFix("ifm-importer");
