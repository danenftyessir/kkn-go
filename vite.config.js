import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/css/auth.css', 'resources/css/auth-student.css', 'resources/css/auth-institution.css','resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
