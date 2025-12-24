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
	},
};
