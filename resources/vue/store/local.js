export default {
	mutations: {
		/* Добавление переменой в локальное хранилище */
		setValueToLocal(state, { name = "value", value }) {
			localStorage.setItem(name, value);
		},

		/* Удаление переменой из локального хранилища */
		removeValueToLocal(state, name) {
			localStorage.removeItem(name);
		},

		generateUuid(state) {
			if (localStorage.getItem("uuid")) return;

			localStorage.setItem("uuid", crypto.randomUUID());
		},
	},
	getters: {
		getUuid(state) {
			return localStorage.getItem("uuid");
		},
	},
};
