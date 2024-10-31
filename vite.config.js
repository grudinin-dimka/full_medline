import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import { fileURLToPath } from "url";

export default defineConfig({
	plugins: [
		vue(),
		laravel({
			input: ["resources/src/main.js"],
			refresh: true,
		}),
	],
	resolve: {
		alias: {
			"@": fileURLToPath(new URL("/resources/src/", import.meta.url)),
		},
	},
});
