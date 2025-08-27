import { defineConfig } from "vite"

import laravel from 'laravel-vite-plugin'
import vue from "@vitejs/plugin-vue";

export default defineConfig({
	/* Плагины */
	plugins: [
		vue(),
		laravel({
			input: ["resources/vue/main.js"],
			refresh: true,
		}),
	],

	/* Настройка локального сервера */
	server: {
		cors: {
         origin: "https://medline.local",
         methods: ["GET", "POST", "PUT", "DELETE"],
      },
	},

	/* Настройка сборки */
	build: {
		chunkSizeWarningLimit: 10000, // теперь предупреждение будет при 10 МБ
	},
});
