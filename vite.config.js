
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import externalize from "vite-plugin-externalize-dependencies";
import { viteStaticCopy } from 'vite-plugin-static-copy';

export default defineConfig({
    plugins: [
        // Ensure that Vue is loaded through the importmap of biigle/core in dev mode.
        externalize({externals: ["vue"]}),
        viteStaticCopy({
            targets: [
                {
                    src: 'src/resources/assets/images/*',
                    dest: 'assets',
                },
            ],
        }),
        laravel({
            publicDirectory: 'src',
            buildDirectory: 'public',
            input: [
                'src/resources/assets/sass/main.scss',
                'src/resources/assets/js/main.js',
            ],
            hotFile: 'hot',
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
                compilerOptions: {
                    whitespace: 'preserve',
                },
            },
        }),
    ],
    build: {
        rollupOptions: {
            // Ensure that Vue is loaded through the importmap of biigle/core in build.
            external: ['vue'],
        },
    },
});
