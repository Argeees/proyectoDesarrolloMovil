import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
//vite para procesar y empaquetar archivos los archivos del frontend

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),//pugin de tailwind css , para escanear los archivos
        tailwindcss(),
    ],
});
