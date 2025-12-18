import axios from "axios";
import * as check from "./chek-request.js";

/* Создание axios */
const capi = axios.create({
	// Время запроса
	timeout: 10000,
});

// Перехватчик запросов (добавление токена)
capi.interceptors.request.use((config) => {
	const token = localStorage.getItem("ctoken");
	if (token) {
		config.headers.Authorization = `Bearer ${token}`;
	}
	return config;
});

/* Перехват ответа с сервера */
capi.interceptors.response.use(
	(response) => check.checkRequest(response),
	(error) => check.checkError(error, { type: "cabinet" })
);

export default capi;
