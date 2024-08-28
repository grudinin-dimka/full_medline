import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import { fileURLToPath } from 'url';

export default defineConfig({
   plugins: [  
      vue(),
      laravel({
         input: [
            "resources/css/app.css", 
            "resources/src/main.js"
         ],
         refresh: true,
      }),
   ],
});
