export default {
	state: {
		modals: [],
	},
	mutations: {
		registerModal(state, modal) {
			state.modals.push(modal);
		},
		unregisterModal(state, modal) {
			state.modals = state.modals.filter((m) => m !== modal);
		},
	},
	getters: {
		isOpenMoreModal(state) {
			return state.modals.length > 1;
		},
	},
};
