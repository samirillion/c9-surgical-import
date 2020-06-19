import Vue from "vue";
import Router from "vue-router";
import Import from "@/router/routes/Import.vue";
import Settings from "@/router/routes/Settings.vue";
import Export from "@/router/routes/Export.vue";

Vue.use(Router);

export default new Router({
  routes: [
    {
      path: "/",
      name: "Import",
      component: Import
    },
    {
      path: "/export",
      name: "Export",
      component: Export
    }
  ]
});
