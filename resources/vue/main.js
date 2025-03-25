/* Импорт стилей */
import "./assets/css/main.css";

import { createApp } from "vue";
import App from "./App.vue";

/* Роутера */
import router from "./router/index.js";

/* Маски полей */
import VueTheMask from "vue-the-mask";

/* Хранилище */
import store from "./store/index.js";

const app = createApp(App);

app.use(store);
app.use(router);
app.use(VueTheMask);

app.mount("#app");
