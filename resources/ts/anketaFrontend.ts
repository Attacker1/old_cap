
import Vue from 'vue'
import VueRouter from "vue-router";
import store from './store'
const VueInputMask = require('vue-inputmask').default
import VueYandexMetrika from 'vue-yandex-metrika'
Vue.use(VueYandexMetrika, {
    id: 74453200,
    env: 'production', // process.env.NODE_ENV
    triggerEvent: true,
})
Vue.use(VueInputMask)

Vue.use(VueRouter)
Vue.config.productionTip = false

Vue.directive('number', {
    bind(el) {
        el.oninput = function(e) {
            if (!e.isTrusted) {
                return;
            }

            // @ts-ignore
            this.value = this.value.replace(/\D/g, '');
            el.dispatchEvent(new Event('input'));
        }
    },
});

Vue.directive('card', {
    bind(el) {
        el.oninput = function(e) {
            if (!e.isTrusted) {
                return;
            }

            // @ts-ignore
            let cardCode = this.value.replace(/[^\d]/g, '').substring(0,16);
            // @ts-ignore
            this.value = cardCode != '' ? cardCode.match(/.{1,4}/g).join(' ') : '';
            el.dispatchEvent(new Event('input'));
        }
    },
});

Vue.directive('cardDate', {
    bind(el) {
        el.oninput = function(e) {
            if (!e.isTrusted) {
                return;
            }
            // @ts-ignore
            // this.value = this.value.replace(/[^\d]/g, '').substring(0,2);
           let code = this.value.replace(/[^\d]/g, '').substring(0,4);
            // @ts-ignore
            this.value = code != '' ? code.match(/.{1,2}/g).join('/') : '';
            el.dispatchEvent(new Event('input'));
        }
    },
});

Vue.directive('cardCvc', {
    bind(el) {
        el.oninput = function(e) {
            if (!e.isTrusted) {
                return;
            }
            // @ts-ignore
            let code = this.value.replace(/[^\d]/g, '').substring(0,3);
            // @ts-ignore
            this.value = code != '' ? code.match(/.{1,3}/g) : '';
            el.dispatchEvent(new Event('input'));
        }
    },
});

Vue.prototype.$scrollBlocks = function (r:HTMLElement) {
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

import AnketaFrontend from "./views/anketa/front/AnketaFrontend.vue";
//import AnketaSuccess from "./views/anketa/front/AnketaSuccess.vue";
import AnketaCloudSuccess from "./views/anketa/front/AnketaCloudSuccess.vue";
const router = new VueRouter({
    mode: 'history',
    routes: [
        { // cloud payments
            path: '/payment/:leadUuid?',
            name:'frontend.anketa.cloud.success',
            component: AnketaCloudSuccess,
            props: true,
        },
        /*{ // tinkoff
            path: '/success',
            name:'frontend.anketa.payment',
            component: AnketaSuccess,
            props: true,
        },*/
        {
            path: '/:anketaSlug?',
            name:'frontend.anketa',
            component: AnketaFrontend,
            props: true
        },

    ]
})


const app = new Vue({
    el: '#app',
    components: { AnketaFrontend },
    router,
    store
});
