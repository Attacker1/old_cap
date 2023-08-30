import Vue from 'vue'
import VueRouter from "vue-router";
import store from './store'
import HeyUI from "heyui";
import ru from './ru-Ru.js'


import VueLodash from 'vue-lodash'
import isEqual from 'lodash/isEqual'
import random from 'lodash/random'

const VueInputMask = require('vue-inputmask').default
Vue.use(VueInputMask)
Vue.use(HeyUI, {locale: ru})
Vue.use(VueRouter)
Vue.config.productionTip = false

Vue.use(VueLodash, {name: 'custom', lodash: {isEqual, random}})

import {menu} from "./helpers";

Vue.directive('number', {
    bind(el) {
        el.oninput = function (e) {
            if (!e.isTrusted) {
                return;
            }

            // @ts-ignore
            this.value = this.value.replace(/\D/g, '');
            el.dispatchEvent(new Event('input'));
        }
    },
});


Vue.prototype.$scrollBlocks = function (r: HTMLElement) {
    const scrollBlocks = r.querySelectorAll('.scroll-block');
    let winWidth = window.innerWidth;
// @ts-ignore
    const getMaxScroll = (el) => el.scrollWidth - el.clientWidth;
// @ts-ignore
    const toggleScroll = (arr) => arr.forEach(el => {
        const scrollList = el.nextElementSibling.classList;
        (getMaxScroll(el)) ? scrollList.add('scroll_active') : scrollList.remove('scroll_active');
    })

    toggleScroll(scrollBlocks)

// @ts-ignore
    function blockMove(event) {
        const scrollBlock = event.currentTarget;
        const scroll = scrollBlock.nextElementSibling;
        const scrollBar = scroll.querySelector('.scroll-bar');
        const scrollThumb = scroll.querySelector('.scroll-thumb');
        const scrollStep = (getMaxScroll(scrollBlock)) / (scrollBar.offsetWidth - scrollThumb.offsetWidth);

        scrollThumb.style.left = `${scrollBlock.scrollLeft / scrollStep}px`;
    }

// @ts-ignore
    scrollBlocks.forEach(el => el.addEventListener('scroll', blockMove));

    window.addEventListener('resize', () => {
        if (winWidth !== window.innerWidth) {
            winWidth = window.innerWidth;
            toggleScroll(scrollBlocks);
        }
    });


    // vh fix
    let lastWidth = window.innerWidth;

    const listener = () => {
        let vh = window.innerHeight * 0.01;
        document.documentElement.style.setProperty('--vh', `${vh}px`);
    }

    window.addEventListener('resize', () => {
        if (lastWidth !== window.innerWidth) {
            lastWidth = window.innerWidth;
            listener();
        }
    })

    listener();
}


import AnketaBuilderList from "./views/anketa/builder/AnketaBuilderList.vue";
import AnketaBuilder from "./views/anketa/builder/AnketaBuilder.vue";
// import AnketaFrontend from "./views/anketa/front/AnketaFrontend.vue";
import AnketaQuestionsList from "./views/anketa/builder/AnketaQuestionsList.vue";
import Root from "./views/Root.vue";
import AnketaOldDataConverter from "./views/anketa/converter/converter.vue";

const rootName = 'admin.settings.anketas'

const routes = [
    {
        path: '/admin/v/settings/anketas',
        name: rootName,
        component: Root,
        meta: {title: 'Список анкет', icon: 'icon-shuffle', menu: true},
        children: [
            {
                path: '', // страница по умолчинию
                name: `${rootName}.list`,
                component: AnketaBuilderList,
                meta: {title: 'Список анкет', icon: '', menu: true}
            },
            {
                /*страницы со слагом не попадут в меню по признаку : */
                path: 'build/:slug',
                name: `${rootName}.build`,
                component: AnketaBuilder,
                meta: {title: 'Список анкет', icon: ''}
            },
            {
                path: 'view/:slug',
                name: `${rootName}.view`,
                component: AnketaBuilder,
                meta: {title: 'Список анкет', icon: ''}
            },
            // {
            //     path: '/admin/anketas/frontend/:slug',
            //     name: 'v/admin.anketas.frontend',
            //     component: AnketaFrontend,
            //     meta: {title: 'Список анкет', icon: ''}
            // },
            {
                path: 'questions',
                name: `${rootName}.questions`,
                component: AnketaQuestionsList,
                meta: {title: 'Список вопросов', icon: '', menu: true}
            },
            {
                path: 'converter',
                name: `${rootName}.converter`,
                component: AnketaOldDataConverter,
                meta: {title: 'Анализ и конвертация старых данных', icon: ''}
            },
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
    components: {AnketaBuilderList},
    router
});

export const anketabuilderRoutes = menu(routes)
