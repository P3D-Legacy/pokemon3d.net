import inertia from '@inertiajs/vite';
import mdx from '@mdx-js/rollup';
import { wayfinder } from '@laravel/vite-plugin-wayfinder';
import tailwindcss from '@tailwindcss/vite';
import react from '@vitejs/plugin-react';
import laravel from 'laravel-vite-plugin';
import { resolve } from 'node:path';
import remarkGfm from 'remark-gfm';
import { defineConfig } from 'vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.tsx', 'resources/js/app.js'],
            ssr: 'resources/js/app.tsx',
            refresh: true,
            detectTls: 'pokemon3d.net.test',
        }),
        inertia({
            ssr: {
                entry: 'resources/js/app.tsx',
                host: '127.0.0.1',
            },
        }),
        {
            enforce: 'pre',
            ...mdx({
                remarkPlugins: [remarkGfm],
                providerImportSource: '@mdx-js/react',
            }),
        },
        react({
            babel: {
                plugins: ['babel-plugin-react-compiler'],
            },
        }),
        tailwindcss(),
        wayfinder({
            formVariants: true,
        }),
    ],
    resolve: {
        alias: {
            '@': resolve('resources/js'),
            '@markdown': resolve('resources/markdown'),
        },
    },
});
