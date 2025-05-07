import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import { fileURLToPath } from "url";

export default defineConfig({
	/* Плагины */
	plugins: [
		vue(),
		laravel({
			input: ["resources/vue/main.js"],
			refresh: true,
		}),
	],

	/* Алиасы */
	resolve: {
		alias: {
			"@": fileURLToPath(new URL("/resources/vue/", import.meta.url)),
		},
	},

	/* Настройка сборки */
	build: {
		chunkSizeWarningLimit: 10000, // теперь предупреждение будет при 10 МБ
	},
});
