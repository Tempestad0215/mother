import '../css/app.css';
import './bootstrap';
// If you don't need the styles, do not connect
// import 'vue-final-modal/style.css'
import 'primeicons/primeicons.css'; // THEME (elige uno)

import { createInertiaApp } from '@inertiajs/vue3';

import { initializeTheme } from '@/Composable/useAppearance';
import { createPinia } from 'pinia';
import { createVfm } from 'vue-final-modal';

import 'vue-sonner/style.css';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import SettingLayout from '@/Layouts/setting/Layout.vue';
import AppLayout from '@layout/AppLayout.vue';
import { initializeFlashToast } from './lib/flashToast';

const vfm = createVfm();
const pinia = createPinia();

const appName = import.meta.env.VITE_APP_NAME || 'laravel';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    // 1. Add the resolve function to load pages
    layout: (name) => {
        switch (true) {
            case name === 'Welcome':
                return null;
            case name.startsWith('Auth/'):
                return AuthLayout;
            case name.startsWith('Profile/Show') || name.startsWith('Setting'):
                return [AppLayout, SettingLayout];
            default:
                return AppLayout;
        }
    },

    progress: {
        color: '#4B5563',
    },
});

initializeTheme();
initializeFlashToast();
