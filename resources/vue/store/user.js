export default {
	state: {
		user: {
			nickname: null,
			email: null,
			status: null,
			rights: null,
		},
	},
	mutations: {
		/* Установка токена */
		setToken(state, token) {
			state.user.token = token;
			console.log(state.user.token);
		},
		/* Установка данных пользователя */
		setUser(state, user) {
			state.user.nickname = user.nickname;
			state.user.email = user.email;
			state.user.status = user.status;
			state.user.rights = user.rights;
		},
	},
	getters: {
		userRights(state) {
			return state.user.rights;
		},
	},
};
