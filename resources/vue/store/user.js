import axios from "axios";
import router from "../router";

export default {
	state: {
		isLogout: false,
		user: {
			nickname: null,
			email: null,
			status: null,
			rights: null,
		},
	},
	mutations: {
		/* Обновление данных пользователя */
		updateUser(state, user) {
			for (let key in state.user) {
				state.user[key] = user[key];
			}
		},

		/* Добавление токена в локальное хранилище */
		setTokenToLocal(state, token) {
			localStorage.setItem("token", token);
		},

		/* Установка данных пользователя */
		setUser(state, user) {
			state.user.nickname = user.nickname;
			state.user.email = user.email;
			state.user.status = user.status;
			state.user.rights = user.rights;
		},

		/* Удаление токена везде */
		removeToken(state) {
			// Удаление токена
			localStorage.removeItem("token");

			// Очистка хранилища
			this.commit("updateUser", {
				nickname: null,
				email: null,
				status: null,
				rights: null,
			});

			// Удаление токена из заголовка
			delete axios.defaults.headers.common.Authorization;

			router.push({ name: "login" });
		},

		logout(state) {
			state.isLogout = true;

			axios({
				method: "post",
				url: `/api/logout`,
				headers: {
					Authorization: "Bearer " + localStorage.getItem("token"),
				},
			}).finally(() => {
				state.isLogout = false;

				this.commit("removeToken");
			});
		},
	},
	getters: {
		userRights(state) {
			return state.user.rights;
		},

		userNickname(state) {
			return state.user.nickname;
		},

		getLogoutStatus(state) {
			return state.isLogout;
		},
	},
};
