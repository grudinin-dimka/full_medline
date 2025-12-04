import { createStore } from "vuex";

/* Импорт модулей */
import user from "./user.js";	
import other from "./other.js";
import debug from "./debug.js";
import modals from "./modals.js";
import wiki from "./wiki.js";
import site from "./site.js";
import shared from "./shared.js";

/* Создание хранилища */
const store = createStore({
	modules: {
		user,
		other,
		debug,
		modals,
		wiki,
		site,
		shared,
	},
});

export default store;
