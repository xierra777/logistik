import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        host: '0.0.0.0', // Penting agar Vite bisa diakses dari luar container
        port: 5173,
        hmr: {
            host: 'localhost', // Ganti sesuai hostname/ip host kamu
        },
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
