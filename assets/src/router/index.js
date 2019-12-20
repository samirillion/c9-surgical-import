import Vue from 'vue'
import Router from 'vue-router'
import Import from '@/router/routes/Import.vue'
import Settings from '@/router/routes/Settings.vue'

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
