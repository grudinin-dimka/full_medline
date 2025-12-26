import axios from "axios";
import * as check from "./chek-request.js";

/* Создание axios */
const api = axios.create({
	// Базовый адрес
	baseURL: "/api/",

	// Время запроса
	timeout: 10000,
});

// Перехватчик запросов (добавление токена)
api.interceptors.request.use((config) => {
	const token = localStorage.getItem("atoken");
	if (token) {
		config.headers.Authorization = `Bearer ${token}`;
	}
	return config;
});

/* Перехват ответа с сервера */
api.interceptors.response.use(
	(response) => check.checkRequest(response),
	(error) => check.checkError(error, { type: "admin" })
);

export default api;
