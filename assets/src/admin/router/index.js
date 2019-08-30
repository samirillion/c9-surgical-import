import Vue from 'vue'
import Router from 'vue-router'
import Import from 'admin/components/routes/Import.vue'
import Settings from 'admin/components/routes/Settings.vue'

Vue.use(Router)

export default new Router({
    routes: [
        {
            path: '/',
            name: 'Import',
            component: Import
        },
        {
            path: '/settings',
            name: 'Settings',
            component: Settings
        },
    ]
})
