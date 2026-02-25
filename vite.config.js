import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/scss/app.scss', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    css: {
        preprocessorOptions: {
            scss: {
                // Silencia warnings vindos de dependências (Bootstrap dentro do node_modules)
                quietDeps: true,
                // Especifica quais avisos de depreciação devem ser ignorados no console
                silenceDeprecations: ['import', 'global-builtin', 'color-functions', 'if-function', 'mixed-decls'],
            },
        },
    },
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
