/* Импорт стилей */
import "./assets/css/style.css";

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

import VueCascader from "./components/modules/input/VueCascader.vue";
import VueCheckbox from "./components/modules/input/VueCheckbox.vue";
import VueDate from "./components/modules/input/VueDate.vue";
import VueTime from "./components/modules/input/VueTime.vue";
import VueDateTime from "./components/modules/input/VueDateTime.vue";
import VueNumber from "./components/modules/input/VueNumber.vue";
import VueFieldset from "./components/modules/input/VueFieldset.vue";
import VueFile from "./components/modules/input/VueFile.vue";
import VueInput from "./components/modules/input/VueInput.vue";
import VueList from "./components/modules/input/VueList.vue";
import VuePassword from "./components/modules/input/VuePassword.vue";
import VueSelect from "./components/modules/input/VueSelect.vue";
import VueSelector from "./components/modules/input/VueSelector.vue";
import VueValues from "./components/modules/input/VueValues.vue";
import VueSwitch from "./components/modules/input/VueSwitch.vue";

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

app.component("VueCascader", VueCascader);
app.component("VueCheckbox", VueCheckbox);
app.component("VueDate", VueDate);
app.component("VueTime", VueTime);
app.component("VueDateTime", VueDateTime);
app.component("VueNumber", VueNumber);
app.component("VueFieldset", VueFieldset);
app.component("VueFile", VueFile);
app.component("VueInput", VueInput);
app.component("VueList", VueList);
app.component("VuePassword", VuePassword);
app.component("VueSelect", VueSelect);
app.component("VueSelector", VueSelector);
app.component("VueValues", VueValues);
app.component("VueSwitch", VueSwitch);

app.use(store);
app.use(router);
app.use(VueTheMask);
app.use(VueApexCharts);
app.use(VueJsBarcode);

app.mount("#app");
