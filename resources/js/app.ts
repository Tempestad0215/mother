import './bootstrap';
import '../css/app.css';
// If you don't need the styles, do not connect
// import 'vue-final-modal/style.css'
import 'primeicons/primeicons.css'; // THEME (elige uno)

import { createApp, DefineComponent, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { ZiggyVue } from 'ziggy-js';
import VueTheMask from 'vue-the-mask';
import money from 'v-money';

import { createVfm } from 'vue-final-modal';
import { createPinia } from 'pinia';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

const vfm = createVfm();
const pinia = createPinia();

const appName = import.meta.env.VITE_APP_NAME || 'laravel';

createInertiaApp({
  title: (title) => (title ? `${title} - ${appName}` : appName),
  resolve: (name) =>
    resolvePageComponent(
      `./Pages/${name}.vue`,
      import.meta.glob<DefineComponent>('./Pages/**/*.vue')
    ),
  layout: (name: string) => {
    console.log(name);
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(VueTheMask)
      .use(vfm)
      .use(pinia)
      .use(money as any)
      .use(ZiggyVue, Ziggy)
      .mount(el);
  },
}).then(() => {});
