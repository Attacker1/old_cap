import Vue from 'vue'
import VueRouter from "vue-router";
import store from './store'
import HeyUI from "heyui";
import ru from './ru-Ru.js'

const VueInputMask = require('vue-inputmask').default

Vue.use(VueInputMask)
Vue.use(HeyUI, {locale: ru})
Vue.use(VueRouter)
Vue.config.productionTip = false

import App from "./views/Root.vue"
import StockIndex from "./views/stock/StockIndex.vue";
import MyOrderList from "./views/stock/MyOrderlist.vue";
import MyCart from "./views/stock/MyCart.vue";
import Order from "./views/stock/Order.vue";
import Favorite from "./views/stock/Favorite.vue";
import Product from "./views/stock/Product.vue";
import {menu} from "./helpers";
import Root from "./views/Root.vue";

const rootName = 'admin.stock'
const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/admin/v/stock',
            name: rootName,
            component: Root,
            meta: {title: 'Сток', icon: 'icon-stack', menu: true},
            children: [
                {
                    path: '',
                    name: `${rootName}.index`,
                    component: StockIndex,
                    meta: {title: 'Каталог', menu: true}
                },
                {
                    path: `my/orderlist`,
                    name: `${rootName}.my.orderlist`,
                    component: MyOrderList,
                    meta: {title: 'Мои заказы', menu: true}
                },
                {
                    path: `my/cart`,
                    name: `${rootName}.my.cart`,
                    component: MyCart,
                    meta: {title: 'Корзина', menu: true}
                },
                {
                    path: `my/favourite`,
                    name: `${rootName}.my.favourite`,
                    component: Favorite,
                    meta: {title: 'Избранные', menu: true}
                },
                {
                    path: `order/:order_id`,
                    name: `${rootName}.order`,
                    component: Order,
                    meta: {title: 'Заказ'}
                },
                {
                    path: `product/:product_id`,
                    name: `${rootName}.product`,
                    component: Product,
                    meta: {title: 'Товар'}
                }
            ]
        },

    ]
})

const app = new Vue({
    el: '#app',
    store,
    components: {StockIndex},
    router
});
