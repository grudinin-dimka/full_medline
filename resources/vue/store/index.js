import { createStore } from "vuex";

/* Импорт модулей */
import autorization from "./autorization.js";	
import url from "./url.js";
import debug from "./debug.js";
import modals from "./modals.js";
import wiki from "./wiki.js";
import gui from "./gui.js";
import local from "./local.js";

/* Создание хранилища */
const store = createStore({
	modules: {
		autorization,
		url,
		debug,
		modals,
		wiki,
		gui,
		local,
	},
});

export default store;
