
import { createApp, h } from 'vue'
import './bootstrap.js'
import '../css/app.css'
import 'sweetalert2/dist/sweetalert2.min.css'
import 'vue-final-modal/style.css'

// 🔁 Usa v-money3 para Vue 3 (v-money es para Vue 2)
import money from 'v-money3'
import VueSweetalert2 from 'vue-sweetalert2'
import VueTheMask from 'vue-the-mask'
import { createVfm } from 'vue-final-modal'

// ⚠️ ZiggyVue necesita el objeto Ziggy si no usas @routes en Blade
// Si tienes @routes en tu layout, puedes usar solo ZiggyVue.
// Si NO, importa Ziggy generado:
import { ZiggyVue } from 'ziggy-js'
import {createInertiaApp} from "@inertiajs/vue3";
// import { Ziggy } from './ziggy' // si generaste routes.js con Ziggy

const vfm = createVfm()

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', {eager: true})
        const page = pages[`./Pages/${name}.vue`]
        // 👇 con eager:true, debes devolver la exportación default (el componente)
        return page?.default ?? page
    },
    setup({el, App, props, plugin}) {
        createApp({render: () => h(App, props)})
            .use(plugin)
            .use(VueSweetalert2)
            .use(money)         // v-money3
            .use(VueTheMask)
            .use(ZiggyVue /*, Ziggy */)  // si no usas @routes, pasa Ziggy aquí
            .use(vfm)
            .mount(el)
    },
}).then(r =>{
    console.log(r)
})
