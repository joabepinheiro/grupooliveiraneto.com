import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
    plugins: [
        // Tailwind primeiro
        tailwindcss(),

        // Depois o plugin do Laravel
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',

                // Temas do Filament
                'resources/css/filament/admin/theme.css',
                'resources/css/filament/grupooliveiraneto/theme.css',
                'resources/css/filament/movelveiculos/theme.css',
                'resources/css/filament/bydconquista/theme.css',
            ],
            refresh: true,
        }),
    ],
})
