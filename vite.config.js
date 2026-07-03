import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';
import AutoImport from 'unplugin-auto-import/vite';
import Components from 'unplugin-vue-components/vite';
import { PrimeVueResolver } from '@primevue/auto-import-resolver';

export default defineConfig({
  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'resources/js'),
      '@components': path.resolve(__dirname, 'resources/js/Components'),
      '@layout': path.resolve(__dirname, 'resources/js/Layouts'),
    },
  },
  plugins: [
    laravel({
      input: ['resources/css/app.css', 'resources/js/app.ts'],
      refresh: true,
    }),
    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false,
        },
      },
    }),
    AutoImport({
      imports: [
        'vue',
        {
          '@inertiajs/vue3': ['usePage', 'useForm', 'router'],
          'ziggy-js': ['route'],
        },
      ],
      dirs: [
        'resources/js/Global',
        'resources/js/Global/Alert',
        'resources/js/Global/Helpers',
        'resources/js/Global/MsjToast',
        'resources/js/Global/SearchTable',
        'resources/js/Global/ShareData',
      ],
      dts: 'resources/js/auto-imports.d.ts',
    }),
    // 🚀 Auto-importación de Componentes y Layouts (Ignorando las páginas de Inertia)
    Components({
      // 🟢 Le decimos explícitamente que escanee tus componentes y tus layouts
      dirs: ['resources/js/Components', 'resources/js/Layouts', 'resources/js/Pages'],

      // 🟢 Evitamos de forma estricta que toque la carpeta Pages
      extensions: ['vue'],
      deep: true,
      directoryAsNamespace: true,

      resolvers: [
        PrimeVueResolver(), // Para Card, DataTable, Column, FloatLabel, etc.
      ],
      dts: 'resources/js/components.d.ts',
    }),
  ],
  optimizeDeps: {
    include: ['primevue'],
  },
});
