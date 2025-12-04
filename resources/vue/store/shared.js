export default {
	state: {
		isHide: false,
	},
	mutations: {
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
