import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'node:path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'front/resources/css/app.css', 
                'front/resources/sass/app.scss', 
                'front/resources/js/app.js'
            ],
            refresh: true,
            publicDirectory: path.resolve(__dirname, '../back/public'),
        }),
        vue(),
    ],
});
