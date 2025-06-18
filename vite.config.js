import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/dashboard.js',
                'resources/js/category-dropdown.js',
                'resources/js/theme-preview.js',
                'resources/js/approval-stages.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
        vue(),
    ],
});
