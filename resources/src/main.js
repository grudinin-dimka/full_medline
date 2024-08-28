import "./assets/css/main.css";

import { createApp } from "vue";
import App from "./App.vue";
// Импорт роутера
import router from "./router";
// Импорт валидатора
import Vuelidate from "vuelidate";
// Импорт для создания масок у полей ввода
import VueTheMask from "vue-the-mask";
// Импорт глобальных переменных
import store from "./store/index.js";

const app = createApp(App);

app.use(store);
app.use(router);
app.use(Vuelidate);
app.use(VueTheMask);

app.mount("#app");
