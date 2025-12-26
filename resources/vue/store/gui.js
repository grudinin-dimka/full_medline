export default {
	state: {
		timeout: 350,

		aside: {
			isSmall: false,
			isHide: true,
		},

		burger: {
			main: {
				status: false,
			},
		},
	},
	mutations: {
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                 Боковое меню                      |*/
		/* |___________________________________________________|*/
		toggleAsideSmall(state) {
			state.aside.isSmall = !state.aside.isSmall;

			localStorage.setItem("isSmall", state.aside.isSmall);
		},

		toggleAsideHide(state) {
			state.aside.isHide = !state.aside.isHide;

			if (state.aside.isHide) {
				document.body.classList.remove("aside-open");
			} else {
				document.body.classList.add("aside-open");
			}
		},

		closeAside(state) {
			state.aside.isHide = true;

			document.body.classList.remove("aside-open");
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |               Главная страница                    |*/
		/* |___________________________________________________|*/
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

		/* Документ: Изменение заголовка */
		setDocumentTitle(state, title) {
			document.title = title;
		},

		/* Документ: Изменение метатега */
		setDocumentMeta(state, { name, value }) {
			let meta = document.querySelector(`meta[name="${name}"]`);

			// Наличие метатега
			if (meta) {
				let content = meta.getAttribute("content");

				switch (name) {
					case "description":
						content = value ? value : import.meta.env.VITE_MC_DESCRIPTION;
						break;
					case "keywords":
						if (content && value) {
							content = import.meta.env.VITE_MC_KEYWORDS + ", " + value;
						} else {
							content = value ? value : import.meta.env.VITE_MC_KEYWORDS;
						}
						break;
					default:
						content = value;
						break;
				}

				meta.setAttribute("content", content);
			} else {
				meta = document.createElement("meta");
				meta.setAttribute("name", `${name}`);
				meta.setAttribute("content", value);

				document.head.appendChild(meta);
			}
		},
	},
	getters: {
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                 Боковое меню                      |*/
		/* |___________________________________________________|*/
		getAsideSmall(state) {
			if (localStorage.getItem("isSmall")) {
				let value;

				switch (localStorage.getItem("isSmall")) {
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

				state.aside.isSmall = value;
			} else {
				localStorage.setItem("isSmall", false);

				state.aside.isSmall = false;
			}

			return state.aside.isSmall;
		},

		getAsideHide(state) {
			return state.aside.isHide;
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |               Главная страница                    |*/
		/* |___________________________________________________|*/
		burgerMainStatus(state) {
			return state.burger.main.status;
		},

		timeout(state) {
			return state.timeout;
		},
	},
};
