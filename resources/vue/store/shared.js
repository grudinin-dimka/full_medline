export default {
	state: {
		isHide: false,
	},
	mutations: {
		/* Добавление переменой в локальное хранилище */
		setValueToLocal(state, { name = "value", value }) {
			localStorage.setItem(name, value);
		},

		/* Удаление переменой из локального хранилища */
		removeValueToLocal(state, name) {
			localStorage.removeItem(name);
		},

		setIsHide(state) {
			state.isHide = !state.isHide;

			localStorage.setItem("isHide", state.isHide);
		},
	},
	getters: {
		getIsHide(state) {
			if (localStorage.getItem("isHide")) {
				let value;

				switch (localStorage.getItem("isHide")) {
					case "true":
						value = true;
						break;
					case "false":
						value = false;
						break;
					default:
						value = false;
						break;
				}

				state.isHide = value;
			} else {
				localStorage.setItem("isHide", false);

				state.isHide = false;
			}

			return state.isHide;
		},
	},
};
