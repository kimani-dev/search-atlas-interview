import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue'
import vuetify from 'vite-plugin-vuetify'

export default defineConfig({
    server: {
        host: '0.0.0.0',       // listen on all interfaces inside the container
        port: 5173,
        strictPort: true,
        hmr: {
            host: 'localhost',   // what the browser should connect to
            protocol: 'ws',      // or 'wss' if you front with https
            port: 5173
        }
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
        vue(),
        vuetify({ autoImport: true }),
    ],
});
