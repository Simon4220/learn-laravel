import {defineConfig} from 'vite'
import laravel from 'vite-plugin-laravel'
import vue from '@vitejs/plugin-vue'
import AutoImport from 'unplugin-auto-import/vite'
import Components from 'unplugin-vue-components/vite'
import {ElementPlusResolver} from 'unplugin-vue-components/resolvers'
import {BlocksResolver} from "./resources/js/blocks-resolver";
import eslint from 'vite-plugin-eslint';
import {createPinia} from "pinia";

export default defineConfig({
    css: {
        preprocessorOptions: {
            scss: {
                additionalData: `@import "@/css/mixins.scss";`
            }
        },
    },
    plugins: [
        vue(),
        laravel(),
        {
            // Запускаем только на build, чтобы выпадало с ошибкой
            ...eslint(),
            apply: 'build',
        },
        AutoImport({
            resolvers: [
                BlocksResolver(),
                ElementPlusResolver()
            ],
        }),
        Components({
            resolvers: [
                BlocksResolver(),
                ElementPlusResolver()
            ],
        }),
    ],
})
