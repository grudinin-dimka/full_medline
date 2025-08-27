/* Импорт стилей */
import "./assets/css/main.css";

import { createApp } from "vue";
import App from "./App.vue";

/* Роутера */
import router from "./router/index.js";

import axios from "axios";

/* Маски полей */
import VueTheMask from "vue-the-mask";

/* Хранилище */
import store from "./store/index";

/* Графики */
import VueApexCharts from "vue3-apexcharts";

import VueJsBarcode from 'vue-jsbarcode';

const app = createApp(App);
цй
app.use(store);
app.use(router);
app.use(VueTheMask);
app.use(VueApexCharts);
app.use(VueJsBarcode);

app.mount("#app");
