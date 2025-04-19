import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    build: {
        manifest: true,
        outDir: 'public/build',
        emptyOutDir: true,
        rollupOptions: {
            output: {
                // Fuerza los nombres de archivo planos (sin hashes)
                entryFileNames: 'app.js',
                assetFileNames: 'app.[ext]',
                // Desactiva la subcarpeta .vite para el manifest
                chunkFileNames: '[name].js',
                manualChunks: undefined,
            }
        }
    }
});