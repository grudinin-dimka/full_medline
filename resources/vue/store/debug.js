export default {
	state: {
		debugger: [],
	},
	mutations: {
		/* Закрытие деббагера */
		clearDebugger(state) {
			state.debugger = [];
		},
		removeDebugger(state, item) {
			let index = state.debugger.indexOf(item);

			if (index !== -1) {
				state.debugger.splice(index, 1);
			}
		},
		/* Отображение деббагера */
		addDebugger(state, message) {
			let newMessage = {
				title: message?.title,
				body: message?.body,
				type: message?.type,
			};

			state.debugger.unshift(newMessage);

			if (state.debugger.length > 4) {
				state.debugger.pop();
			}

			setTimeout(() => {
				this.commit("removeDebugger", newMessage);
			}, 5000);
		},
		/* Отображение деббагера */
		debuggerCloseClick(state) {
			console.log("click");
		},
	},
	getters: {
		getDebuggers(state) {
			return state.debugger;
		},
	},
};
