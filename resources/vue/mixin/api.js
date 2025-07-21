import axios from "axios";
import store from "../store/index.js";

/* Создание axios */
const api = axios.create({
	// Время запроса
	timeout: 10000,
});

// Перехватчик запросов (добавление токена)
api.interceptors.request.use((config) => {
	const token = localStorage.getItem("token");
	if (token) {
		config.headers.Authorization = `Bearer ${token}`;
	}
	return config;
});

/* Перехват ответа с сервера */
api.interceptors.response.use(
	(response) => {
		// Проверка успеха запроса
		if (!response.data.success) {
			if (response.data.message) {
				store.commit("addDebugger", {
					title: "Ошибка",
					body: response.data.message,
					type: "error",
				});
			} else {
				store.commit("addDebugger", {
					title: "Ошибка",
					body: "Запрос не выполнен.",
					type: "error",
				});
			}

			return Promise.resolve(null);
		}

		// Отладка
		if (response.data.debug) {
			store.commit("addDebugger", {
				title: "Успешно!",
				body: response.data.message,
				type: "completed",
			});
		}

		return Promise.resolve(response);
	},
	(error) => {
		// Нет ответа от сервера (нет интернета, таймаут)
		if (!error.response) {
			store.commit("addDebugger", {
				title: "Ошибка сети",
				body: "Сервер не отвечает.",
				type: "error",
			});

			return Promise.resolve(null);
		}

		const status = error.response.status;

		// Unauthorized
		if (status == 401) {
			store.commit("removeToken");
			return Promise.resolve(null);
		}

		// Forbidden
		if (status == 403) {
			store.commit("addDebugger", {
				title: status,
				body: "Доступ запрещен.",
				type: "error",
			});

			return Promise.resolve(null);
		}

		// Not Found
		if (status == 404) {
			store.commit("addDebugger", {
				title: status,
				body: "Ресурс не найден.",
				type: "error",
			});

			return Promise.resolve(null);
		}

		// Method Not Allowed
		if (status == 405) {
			store.commit("addDebugger", {
				title: status,
				body: "Метод не зарегистрирован.",
				type: "error",
			});

			return Promise.resolve(null);
		}

		// Unprocessable Entity, Internal Server Error
		if (status == 422 || status == 500) {
			if (error.response.data.errors) {
				for (let key in error.response.data.errors) {
					for (let item of error.response.data.errors[key]) {
						store.commit("addDebugger", {
							title: status,
							body: item,
							type: "error",
						});
					}
				}
			} else {
				store.commit("addDebugger", {
					title: status,
					body: error.response.data.message,
					type: "error",
				});
			}

			return Promise.resolve(null);
		}

		// Неизвестная ошибка
		store.commit("addDebugger", {
			title: status,
			body: "Произошла неизвестная ошибка.",
			type: "error",
		});

		return Promise.resolve(null);
	}
);

export default api;
