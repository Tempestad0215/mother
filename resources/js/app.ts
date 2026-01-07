import './bootstrap';
import '../css/app.css';
// If you don't need the styles, do not connect
import 'sweetalert2/dist/sweetalert2.min.css';
// import 'vue-final-modal/style.css'
import 'primeicons/primeicons.css'// THEME (elige uno)




import {createApp, h, DefineComponent} from 'vue';
import {createInertiaApp} from '@inertiajs/vue3';
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import PrimeVue from 'primevue/config';
import Aura from '@primeuix/themes/aura';
import {ZiggyVue} from 'ziggy-js';
import VueTheMask from 'vue-the-mask';
import VueSweetalert2 from 'vue-sweetalert2';
import money from 'v-money';

import {createVfm} from "vue-final-modal";


const appName:string = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';
const vfm = createVfm()

createInertiaApp({
	title: (title) => `${title} - ${appName}`,
	resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob<DefineComponent>('./Pages/**/*.vue')),
	setup({el, App, props, plugin}) {
		createApp({render: () => h(App, props)})
			.use(plugin)
			.use(VueTheMask)
			.use(vfm)
			.use(money as any)
			.use(ZiggyVue, Ziggy)
			.use(VueSweetalert2)
            .use(PrimeVue,{
                theme:{
                    preset: Aura
                }
            })
			.mount(el);
	}, progress: {
		color: '#4B5563',
	},
});
