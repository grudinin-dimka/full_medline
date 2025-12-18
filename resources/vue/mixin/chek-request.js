import store from "../store/index.js";

export function checkRequest(response) {
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

		return Promise.reject(null);
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
}

export function checkError(error, { type = "admin" }) {
	// Нет ответа от сервера (нет интернета, таймаут)
	if (!error.response) {
		store.commit("addDebugger", {
			title: "Ошибка сети",
			body: "Сервер не отвечает.",
			type: "error",
		});

		return Promise.reject(error);
	}

	const status = error.response.status;

	// Unauthorized
	if (status == 401) {
		if (type == "admin") {
			store.commit("removeAdminToken");
		}

		if (type == "cabinet") {
			store.commit("removeCabinetToken");
		}

		return Promise.reject(error);
	}

	// Forbidden
	if (status == 403) {
		store.commit("addDebugger", {
			title: status,
			body: "Доступ запрещен.",
			type: "error",
		});

		return Promise.reject(error);
	}

	// Not Found
	if (status == 404) {
		store.commit("addDebugger", {
			title: status,
			body: "Ресурс не найден.",
			type: "error",
		});

		return Promise.reject(error);
	}

	// Method Not Allowed
	if (status == 405) {
		store.commit("addDebugger", {
			title: status,
			body: "Метод не зарегистрирован.",
			type: "error",
		});

		return Promise.reject(error);
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

		return Promise.reject(error);
	}

	// Неизвестная ошибка
	store.commit("addDebugger", {
		title: status,
		body: "Произошла неизвестная ошибка.",
		type: "error",
	});

	return Promise.reject(error);
}
