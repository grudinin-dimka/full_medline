export default {
	/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
	/* |               МОДУЛЬНАЯ ПРОВЕРКА                  |*/
	/* |___________________________________________________|*/
	/* Множественная проверка полей ввода */
	checkInputsAll(obj, keys) {
		let errorCount = 0;

		for (let i = 0; i < keys.length; i++) {
			if (this.checkInput(obj, keys[i].key, keys[i].type, keys[i].formats ?? [])) {
				errorCount++;
			}
		}

		if (errorCount > 0) {
			return true;
		} else {
			return false;
		}
	},
	/* Проверка одного поля */
	checkInput(obj, key, type, formats = []) {
		let logs = {};

		switch (type) {
			case "text":
				logs = this.checkInputText(obj.data[key].value);
				break;
			case "number":
				logs = this.checkInputNumber(obj.data[key].value);
				break;
			case "boolean":
				logs = this.checkInputBoolean(obj.data[key].value);
				break;
			case "date":
				logs = this.checkInputDate(obj.data[key].value);
				break;
			case "email":
				logs = this.checkInputEmail(obj.data[key].value);
				break;
			case "phone":
				logs = this.checkInputPhone(obj.data[key].value);
				break;
			case "file":
				logs = this.checkInputFile(obj.data[key].value, formats);
				break;
			default:
				logs = {
					status: true,
					message: "Неизвестный тип проверки.",
				};
				break;
		}

		if (logs.status) {
			obj.errors[key].message = logs.message;
			obj.errors[key].status = true;

			return true;
		} else {
			obj.errors[key].message = "";
			obj.errors[key].status = false;

			return false;
		}
	},

	/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
	/* |                 ОБЩИЕ ПРОВЕРКИ                    |*/
	/* |___________________________________________________|*/
	/* Проверка введенного текстового */
	checkInputText(value) {
		if (this.isEmpty(value)) {
			return {
				status: true,
				message: "Пустое поле.",
			};
		}

		if (this.isString(value)) {
			return {
				status: true,
				message: "Введите текст.",
			};
		}

		return {
			status: false,
			message: "Ошибок нет.",
		};
	},
	/* Проверка введенного текстового значения */
	checkInputNumber(value) {
		// Проверка на пустую строку
		if (this.isEmpty(value)) {
			return {
				status: true,
				message: "Пустое поле.",
			};
		}

		// Проверка на пустую строку
		if (this.isNumber(value)) {
			return {
				status: true,
				message: "Введите число.",
			};
		}

		return {
			status: false,
			message: "Ошибок нет.",
		};
	},
	/* Проверка введенного текстового значения */
	checkInputBoolean(value) {
		// Проверка на пустую строку
		if (this.isEmpty(value)) {
			return {
				status: true,
				message: "Пустое поле.",
			};
		}

		return {
			status: false,
			message: "Ошибок нет.",
		};
	},
	/* Проверка введенной даты */
	checkInputDate(value) {
		// Проверка на пустую строку
		if (this.isEmpty(value)) {
			return {
				status: true,
				message: "Пустое поле.",
			};
		}

		if (!this.isDate(value)) {
			return {
				status: true,
				message: "Некорректная дата.",
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

		if (!this.isMail(value)) {
			return {
				status: true,
				message: "Некорректная почта.",
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

		if (!this.isPhone(value)) {
			return {
				status: true,
				message: "Некорректный телефон.",
			};
		}

		return {
			status: false,
			message: "Ошибок нет.",
		};
	},
	/* Проверка введенного файла */
	checkInputFile(value, formats = []) {
		// Проверка на загрузку файла пользователем
		if (!value) {
			return {
				status: true,
				message: "Пустое поле.",
			};
		}

		// Проверка на тип загруженного файла
		if (!formats.includes(value.type)) {
			return {
				status: true,
				message: `Тип файла не подходит.`,
			};
		}

		// Проверка на размер загруженного файла
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
	isEmpty(value) {
		if (value === "" || value === null) {
			return true;
		}

		return false;
	},
	/* Проверка на тип данных: string */
	isString(value) {
		if (typeof value != "string") {
			return true;
		}

		return false;
	},
	/* Проверка на тип данных: number */
	isNumber(value) {
		if (Number.isNaN(Number(value))) {
			return true;
		}

		return false;
	},
	isDate(value) {
		let date = new Date(value);
		return !Number.isNaN(date.getTime());
	},
	/* Валидация почты */
	isMail(mail) {
		let mailRegexp =
			/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

		return mailRegexp.test(mail);
	},
	/* Валидация телефона */
	isPhone(phone) {
		let phoneRegexp = /^\+7\(\d{3}\)-\d{3}-\d{2}-\d{2}$/;

		return phoneRegexp.test(phone);
	},
};
