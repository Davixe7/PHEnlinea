import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue'
import { quasar, transformAssetUrls } from '@quasar/vite-plugin'
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        vue(),
        quasar({sassVariables: '/resources/scss/quasar-variables.scss'}),
        laravel({
            input: ['resources/scss/app.scss', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
