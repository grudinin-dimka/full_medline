import { createStore } from "vuex";

/* Импорт модулей */
import user from "./user.js";	
import other from "./other.js";
import debug from "./debug.js";
import modal from "./modal.js";
import wiki from "./wiki.js";
import site from "./site.js";

/* Создание хранилища */
const store = createStore({
	modules: {
		user,
		other,
		debug,
		modal,
		wiki,
		site,
	},
});

export default store;
