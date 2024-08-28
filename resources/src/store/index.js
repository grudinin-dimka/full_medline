import { createStore } from "vuex";
// Create a new store instance or import from module.
const store = createStore({
	state: {
		modal: {
			title: "",
			status: false,
		},
		mainNav: {
			status: false,
		},
		axios: {
			urlApi: "https://medline.local/api/",
		},
		burger: {
			status: false,
		},
		debugger: {
			click: false,
			show: false,
			call: 0,
			title: "",
			body: "",
			type: {
				completed: false,
				error: false,
			},
		},
	},
	mutations: {
		/* Отображение модального окна */
		changeModal(state, title) {
			state.modal.title = title;
			document.body.classList.toggle("modal-open");
			if (state.modal.status === false) {
				state.modal.status = true;
			} else {
				state.modal.status = false;
			}
		},
		/* Отображение меню навигации */
		changeMainNav(state) {
			if (state.mainNav.status === false) {
				state.mainNav.status = true;
			} else {
				state.mainNav.status = false;
			}
		},
		/* Установка токена */
		setToken(state, token) {
			state.user.token = token;
			console.log(state.user.token);
		},
		/* Отображение деббагера */
		debuggerState(state, debbugStory) {
			/* Проверка на существующее обращение */
			if (state.debugger.show === true) {
				state.debugger.show = false;

				setTimeout(() => {
					store.commit("debuggerState", debbugStory);
				}, 250);
			} else {
				state.debugger.call++;
				if (debbugStory.type === "Completed") {
					// Заголовок и текст деббагера
					state.debugger.title = debbugStory.title;
					state.debugger.body = debbugStory.body;

					// Тип деббагера
					state.debugger.type.completed = true;
					state.debugger.type.error = false;

					state.debugger.show = true;
					setTimeout(() => {
						// Проверка на количество обращений к функции
						if (state.debugger.call === 1) {
							state.debugger.show = false;
							state.debugger.call--;
						} else {
							state.debugger.call--;
						}
					}, 5000);
				} else if (debbugStory.type === "Error") {
					// Заголовок и текст деббагера
					state.debugger.title = debbugStory.title;
					state.debugger.body = debbugStory.body;

					// Тип деббагера
					state.debugger.type.completed = false;
					state.debugger.type.error = true;

					state.debugger.show = true;
					setTimeout(() => {
						if (state.debugger.call === 1) {
							state.debugger.show = false;
							state.debugger.call--;
						} else {
							state.debugger.call--;
						}
					}, 5000);
				}
			}
		},
		/* Отображение деббагера */
		debuggerCloseClick(state) {
			state.debugger.show = false;

			state.debugger.type.completed = false;
			state.debugger.type.error = false;
			state.debugger.click = true;
		},
	},
});

export default store;
