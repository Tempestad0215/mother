import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';


export default defineConfig({
    "resolve":{
        alias:{
            '@': path.resolve(__dirname, 'resources/js'),
            '@components': path.resolve(__dirname, 'resources/js/Components'),
            '@layout': path.resolve(__dirname, 'resources/js/Layouts'),
        }
    },
    "plugins":[
        laravel({
            "input": [
                "resources/css/app.css",
                "resources/js/app.ts"
            ],
            "refresh": true
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
			},
		 })
	 ],
 });
