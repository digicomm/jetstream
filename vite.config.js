import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
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
    ],
    server: {
        host: 'vite.godigismart.net',
        https: {
            cert: '/etc/letsencrypt/live/godigismart.net/fullchain.pem',
            key: '/etc/letsencrypt/live/godigismart.net/privkey.pem',
        },
    },
    resolve: {
        alias: [
            {find: '@', replacement: '/resources'}
        ]
    }
});
