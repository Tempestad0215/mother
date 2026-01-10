import './bootstrap';
import '../css/app.css';
// If you don't need the styles, do not connect
// import 'vue-final-modal/style.css'
import 'primeicons/primeicons.css'// THEME (elige uno)

import {createApp, DefineComponent, h} from 'vue';
import {createInertiaApp} from '@inertiajs/vue3';
import PrimeVue from 'primevue/config';
import Aura from '@primeuix/themes/aura';
import {ZiggyVue} from 'ziggy-js';
import VueTheMask from 'vue-the-mask';
import money from 'v-money';

import {createVfm} from "vue-final-modal";
import {ConfirmationService, ToastService} from "primevue";

const vfm = createVfm()


createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob<DefineComponent>('./Pages/**/*.vue', {eager: true})
        return pages[`./Pages/${name}.vue`]
    },
    setup({el, App, props, plugin}) {
        createApp({render: () => h(App, props)})
            .use(plugin)
            .use(VueTheMask)
            .use(vfm)
            .use(money as any)
            .use(ZiggyVue, Ziggy)
            .use(PrimeVue, {
                theme: {
                    preset: Aura
                }
            })
            .use(ConfirmationService)
            .use(ToastService)
            .mount(el)
    },
}).then(() =>{

})

