export default {
	state: {
		modal: {
			title: "",
			status: false,
		},
		axios: {
			urlApi: "/api/",
		},
		burger: {
			admin: {
				status: false,
			},
			main: {
				status: false,
			},
		},
	},
	mutations: {
		/* Отображение модального окна */
		changeModal(state, title) {
			state.modal.title = title;

			if (state.modal.status) {
				document.body.classList.remove("modal-open");
				state.modal.status = false;
			} else {
				document.body.classList.add("modal-open");
				state.modal.status = true;
			}
		},
		/* Изменение меню навигации (главная) */
		setBurgerMain(state) {
			if (state.burger.main.status) {
				document.body.classList.remove("modal-open");
				state.burger.main.status = false;
			} else {
				document.body.classList.add("modal-open");
				state.burger.main.status = true;
			}
		},
		closeBurgerMain(state) {
			document.body.classList.remove("modal-open");
			state.burger.main.status = false;
		},
		/* Изменение меню навигации (админка) */
		setBurgerAdmin(state) {
			if (state.burger.admin.status) {
				document.body.classList.remove("modal-open");
				state.burger.admin.status = false;
			} else {
				document.body.classList.add("modal-open");
				state.burger.admin.status = true;
			}
		},
		closeBurgerAdmin(state) {
			document.body.classList.remove("modal-open");
			state.burger.admin.status = false;
		},
	},
	getters: {
		urlApi(state) {
			return state.axios.urlApi;
		},

		burgerAdminStatus(state) {
			return state.burger.admin.status;
		},

		burgerMainStatus(state) {
			return state.burger.main.status;
		},
	},
};
