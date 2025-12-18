export default {
	state: {
		timeout: 350,

		axios: {
			urlApi: "/api/",
			urlCabinet: "https://lk.medline.local/api/",
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
		/* Главная: Изменение меню навигации */
		setBurgerMain(state) {
			if (state.burger.main.status) {
				document.body.classList.remove("modal-open");
				state.burger.main.status = false;
			} else {
				document.body.classList.add("modal-open");
				state.burger.main.status = true;
			}
		},

		/* Главная: Закрытие меню навигации */
		closeBurgerMain(state) {
			document.body.classList.remove("modal-open");
			state.burger.main.status = false;
		},

		/* Админка: Изменение меню навигации */
		setBurgerAdmin(state) {
			if (state.burger.admin.status) {
				document.body.classList.remove("modal-open");
				state.burger.admin.status = false;
			} else {
				document.body.classList.add("modal-open");
				state.burger.admin.status = true;
			}
		},

		/* Админка: Закрытие меню навигации */
		closeBurgerAdmin(state) {
			document.body.classList.remove("modal-open");
			state.burger.admin.status = false;
		},
	},
	getters: {
		urlApi(state) {
			return state.axios.urlApi;
		},

		urlCabinet(state) {
			return state.axios.urlCabinet;
		},

		burgerAdminStatus(state) {
			return state.burger.admin.status;
		},

		burgerMainStatus(state) {
			return state.burger.main.status;
		},

		timeout(state) {
			return state.timeout;
		},
	},
};
