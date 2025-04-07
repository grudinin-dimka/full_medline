export default {
	state: {
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
		/* Закрытие деббагера */
		setDebuggerClose(state) {
			state.debugger.show = false;
		},
		/* Отображение деббагера */
		debuggerState(state, debbugStory) {
			// Проверка на существующее обращение
			if (state.debugger.show === true) {
				state.debugger.show = false;

				setTimeout(() => {
					this.commit("debuggerState", debbugStory);
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
	getters: {
		debuggerTitle(state) {
			return state.debugger.title;
		},

		debuggerBody(state) {
			return state.debugger.body;
		},

		debuggerTypeCompleted(state) {
			return state.debugger.type.completed;
		},

		debuggerTypeError(state) {
			return state.debugger.type.error;
		},

		debuggerShow(state) {
			return state.debugger.show;
		},
	},
};
