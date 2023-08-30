
import Vue from 'vue'
import VueRouter from "vue-router";
import store from "./store";
import HeyUI from "heyui";
import ru from './ru-Ru'



Vue.use(HeyUI,{locale:ru})
Vue.use(VueRouter)
// Vue.config.productionTip = false




import Index from "./views/Index.vue";
import {menu} from "./helpers";
import Root from "./views/Root.vue";
const routes = [
    {
        path: '/admin/v/index',
        name:'admin.index',
        component: Root,
        meta: {title: 'Домашняя страница',icon: 'h-icon-home', menu: true},
        children: [
            {
                path: '', // страница по умолчинию
                name: 'admin.index.list',
                component: Index,
                meta: {title: 'Список анкет', icon: '', menu: true}
            }
        ]

    },

]

const router = new VueRouter({
    mode: 'history',
    routes: routes
})


const app = new Vue({
    el: '#app',
    store,
    components: { Index },
    router
});

export const indexRoutes = menu(routes)
