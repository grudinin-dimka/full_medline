/* Импорт стилей */
import "./assets/css/main.css";

import { createApp } from "vue";
import App from "./App.vue";

/* Импорт плагинов */
import router from "./router/index.js"; // Маршрутизация
import axios from "axios"; // Подключение axios
import store from "./store/index"; // Хранилище

import VueTheMask from "vue-the-mask"; // Маски полей
import VueApexCharts from "vue3-apexcharts"; // Графики
import VueJsBarcode from "vue-jsbarcode"; // Баркод

/* Импрорт глобальных переменных */
import Icons from "./assets/json/Icons.json";

/* Импрорт компонентов */
import VueModal from "./components/modules/modal/VueModal.vue";
import VueModalList from "./components/modules/modal/VueModalList.vue";
import VueTable from "./components/modules/table/VueTable.vue";
import VueTableButton from "./components/modules/table/VueTableButton.vue";
import VueTiptap from "./components/modules/VueTiptap.vue";
import VueIcon from "./components/ui/VueIcon.vue";
import VueButton from "./components/ui/VueButton.vue";
import VueLoader from "./components/modules/VueLoader.vue";
import VueDebugger from "./components/modules/VueDebugger.vue";
import VueWiki from "./components/modules/wiki/VueWiki.vue";

const app = createApp(App);

app.provide("icons", Icons);

app.component("VueModal", VueModal);
app.component("VueModalList", VueModalList);
app.component("VueTable", VueTable);
app.component("VueTableButton", VueTableButton);
app.component("VueTiptap", VueTiptap);
app.component("VueIcon", VueIcon);
app.component("VueLoader", VueLoader);
app.component("VueButton", VueButton);
app.component("VueDebugger", VueDebugger);
app.component("VueWiki", VueWiki);

app.use(store);
app.use(router);
app.use(VueTheMask);
app.use(VueApexCharts);
app.use(VueJsBarcode);

app.mount("#app");
