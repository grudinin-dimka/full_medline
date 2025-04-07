import { createStore } from "vuex";

/* Импорт модулей */
import user from "./user.js";	
import other from "./other.js";
import debug from "./debug.js";

/* Создание хранилища */
const store = createStore({
	modules: {
		user,
		other,
		debug,
	},
});

export default store;
