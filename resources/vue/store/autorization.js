import axios from "axios";
import router from "../router";
import sorted from "../services/sorted";

export default {
	state: {
		logoutCount: 0,
		isLogout: false,

		user: {
			nickname: null,
			email: null,
			status: null,
			rights: null,
			image: null,
		},

		client: {
			surname: null,
			name: null,
			patronymic: null,
			snils: null,
			devices: [],
		},
	},
	mutations: {
		/* Обновление данных пользователя */
		updateUser(state, user) {
			for (let key in state.user) {
				state.user[key] = user[key];
			}
		},

		/* Обновление данных клиента */
		updateClient(state, client) {
			for (let key in state.client) {
				state.client[key] = client[key];
			}
		},

		updateClientDevices(state, devices) {
			state.client.devices = devices;
		},

		logoutOpen(state) {
			state.logoutCount++;
		},

		/* Удаление токена везде */
		removeAdminToken(state) {
			// Удаление токена
			localStorage.removeItem("atoken");

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

		/* Удаление токена везде */
		removeCabinetToken(state) {
			// Удаление токена
			localStorage.removeItem("ctoken");

			// Очистка хранилища
			this.commit("updateClient", {
				surname: null,
				name: null,
				patronymic: null,
				snils: null,
			});

			// Удаление токена из заголовка
			delete axios.defaults.headers.common.Authorization;

			router.push({ name: "c-login" });
		},
	},
	actions: {
		logout({ state, commit, rootGetters }) {
			state.isLogout = true;

			axios({
				method: "post",
				url: `/api/logout`,
				headers: {
					Authorization: "Bearer " + localStorage.getItem("atoken"),
				},
			}).finally(() => {
				state.isLogout = false;
				state.logoutCount = 0;

				this.commit("removeAdminToken");
			});
		},

		logoutCabinet({ state, commit, rootGetters }) {
			state.isLogout = true;

			axios({
				method: "post",
				url: `/api/cabinet/logout`,
				headers: {
					Authorization: "Bearer " + localStorage.getItem("ctoken"),
				},
			}).finally(() => {
				state.isLogout = false;
				state.logoutCount = 0;

				this.commit("removeCabinetToken");
			});
		},
	},
	getters: {
		/* Пользователи */
		getUserRights(state) {
			return state.user.rights;
		},

		getUserNickname(state) {
			return state.user.nickname;
		},

		getUserEmail(state) {
			return state.user.email;
		},

		getUserImage(state) {
			return state.user.image;
		},

		getLogoutStatus(state) {
			return state.isLogout;
		},

		getLogoutCount: (state) => {
			return state.logoutCount;
		},

		/* Клиенты */
		getClientSurname(state) {
			return state.client.surname;
		},

		getClientName(state) {
			return state.client.name;
		},

		getClientPatronymic(state) {
			return state.client.patronymic;
		},

		getClientSnils(state) {
			return state.client.snils;
		},

		getClientDevices(state) {
			if (!state.client.devices?.length) return [];

			sorted.sortDateByKey("down", state.client.devices, "created_at");

			return state.client.devices;
		},
	},
};
