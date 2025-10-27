import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig(({ command }) => ({
    base: command === 'build' ? '/build/' : '/',
    build: {
        outDir: 'public/build',
        manifest: true,
        emptyOutDir: true,
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
}));
