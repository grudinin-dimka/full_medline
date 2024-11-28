export default {
	/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
	/* |                 ОБЩИЕ ПРОВЕРКИ                    |*/
	/* |___________________________________________________|*/
	/* Проверка введенного текстового */
	checkInputText(value) {
		if (this.checkEmpty(value)) {
			return {
				status: true,
				message: "Поле не может быть пустым.",
			};
		}

		if (this.checkTypeString(value)) {
			return {
				status: true,
				message: "Тип данных не совпадает.",
			};
		}

		return {
			status: false,
			message: "Ошибок нет.",
		};
	},
	// Проверка введенного текстового значения
	checkInputNumber(value) {
		/* Проверка на пустую строку */
		if (this.checkEmpty(value)) {
			return {
				status: true,
				message: "Поле не может быть пустым.",
			};
		}

		/* Проверка на пустую строку */
		if (this.checkTypeNumber(value)) {
			return {
				status: true,
				message: "Тип данных не совпадает.",
			};
		}

		return {
			status: false,
			message: "Ошибок нет.",
		};
	},
	/* Проверка введенной почты */
	checkInputEmail(value) {
		let chekText = this.checkInputText(value);

		if (chekText.status) {
			return chekText;
		}

		if (!this.checkMail(value)) {
			return {
				status: true,
				message: "Почта не прошла проверку.",
			};
		}

		return {
			status: false,
			message: "Ошибок нет.",
		};
	},
	/* Проверка введенного телефона */
	checkInputPhone(value) {
		let chekText = this.checkInputText(value);

		if (chekText.status) {
			return chekText;
		}

		if (!this.checkPhone(value)) {
			return {
				status: true,
				message: "Телефон не прошел проверку.",
			};
		}

		return {
			status: false,
			message: "Ошибок нет.",
		};
	},
	/* Проверка введенного файла */
	checkInputFile(value, types = ["image/png"]) {
		/* Проверка на загрузку файла пользователем */
		if (!value) {
			return {
				status: true,
				message: "Поле не может быть пустым.",
			};
		}

		/* Проверка на тип загруженного файла */
		if (!types.includes(value.type)) {
			let typesStr = types.join(", ");

			return {
				status: true,
				message: `Разрешённые типы файлов: ${typesStr}.`,
			};
		}

		/* Проверка на размер загруженного файла */
		if (value.size / 1024 / 1024 > 10) {
			return {
				status: true,
				message: "Разрешённые размер файлов: не более 10 МБ.",
			};
		}

		return {
			status: false,
			message: "Ошибок нет.",
		};
	},
	/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
	/* |                ТОЧЕЧНЫЕ ПРОВЕРКИ                  |*/
	/* |___________________________________________________|*/
	/* Проверка на пустоту */
	checkEmpty(value) {
		if (value === "" || value === null) {
			return true;
		}

		return false;
	},
	/* Проверка на тип данных: string */
	checkTypeString(value) {
		if (typeof value != "string") {
			return true;
		}

		return false;
	},
	/* Проверка на тип данных: number */
	checkTypeNumber(value) {
		if (Number.isNaN(Number(value))) {
			return true;
		}

		return false;
	},
	/* Валидация почты */
	checkMail(mail) {
		let mailRegexp =
			/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

		return mailRegexp.test(mail);
	},
	/* Валидация телефона */
	checkPhone(phone) {
		let phoneRegexp = /^\+7\(\d{3}\)-\d{3}-\d{2}-\d{2}$/;

		return phoneRegexp.test(phone);
	},
};
