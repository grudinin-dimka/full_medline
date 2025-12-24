/* Типы загруженных файлов */
const types = new Map([
	["jpg", "image/jpeg"],
	["jpeg", "image/jpeg"],
	["png", "image/png"],
	["webp", "image/webp"],
	["gif", "image/gif"],
	["svg", "image/svg+xml"],

	["json", "application/json"],
	["pdf", "application/pdf"],
	["doc", "application/msword"],
	["docx", "application/vnd.openxmlformats-officedocument.wordprocessingml.document"],
	["xls", "application/vnd.ms-excel"],
	["xlsx", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"],
	["ods", "application/vnd.oasis.opendocument.spreadsheet"],

	["webm", "video/webm"],
	["mp4", "video/mp4"],
	["mov", "video/quicktime"],
]);

export default {
	/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
	/* |               МОДУЛЬНАЯ ПРОВЕРКА                  |*/
	/* |___________________________________________________|*/
	/* Множественная проверка полей ввода */
	checkInputsAll(obj, keys) {
		let errorCount = 0;

		for (let i = 0; i < keys.length; i++) {
			if (this.checkInput(obj, keys[i])) {
				errorCount++;
			}
		}

		if (errorCount > 0) {
			return true;
		} else {
			return false;
		}
	},
	/* Проверка одного поля
		
		Ключи для проверки: 
			1) key - название свойства для проверки 
			2) type - тип данных
			3) value - значение, переданное вручную
			4) formats - форматы для проверки
			5) reference - данные для сравнения
 	*/
	checkInput(obj, keys) {
		let logs = {};

		switch (keys["type"]) {
			case "text":
				logs = this.checkInputText(obj.data[keys["key"]].value ?? null);
				break;
			case "number":
				logs = this.checkInputNumber(obj.data[keys["key"]].value ?? null);
				break;
			case "boolean":
				logs = this.checkInputBoolean(
					obj.data[keys["key"]].value ?? null,
					keys["reference"] ?? null
				);
				break;
			case "date":
				logs = this.checkInputDate(obj.data[keys["key"]].value ?? null);
				break;
			case "email":
				logs = this.checkInputEmail(obj.data[keys["key"]].value ?? null);
				break;
			case "phone":
				logs = this.checkInputPhone(obj.data[keys["key"]].value ?? null);
				break;
			case "mask":
				logs = this.checkInputMask(obj.data[keys["key"]].value ?? null, keys["mask"] ?? null);
				break;
			case "file":
				logs = this.checkInputFile(keys["value"] ?? null, keys["formats"] ?? []);
				break;
			case "tiptap":
				logs = this.checkInputTiptap(keys["value"] ?? null);
				break;
			case "twice":
				logs = this.checkInputTwice(
					obj.data[keys["key"]].value ?? null,
					keys["reference"] ?? null
				);
				break;
			default:
				logs = {
					status: true,
					message: "Неизвестный тип проверки",
				};
				break;
		}

		if (logs.status) {
			obj.errors[keys["key"]].message = logs.message;
			obj.errors[keys["key"]].status = true;

			return true;
		} else {
			obj.errors[keys["key"]].message = "";
			obj.errors[keys["key"]].status = false;

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
				message: "Пустое поле",
			};
		}

		if (this.isString(value)) {
			return {
				status: true,
				message: "Введите текст",
			};
		}

		return {
			status: false,
			message: "Ошибок нет",
		};
	},

	/* Проверка введенного текстового значения */
	checkInputNumber(value) {
		// Проверка на пустую строку
		if (this.isEmpty(value)) {
			return {
				status: true,
				message: "Пустое поле",
			};
		}

		// Проверка на пустую строку
		if (this.isNumber(value)) {
			return {
				status: true,
				message: "Введите число",
			};
		}

		return {
			status: false,
			message: "Ошибок нет",
		};
	},

	/* Проверка введенного логического значения */
	checkInputBoolean(value, reference) {
		// Проверка на пустую строку
		if (this.isEmpty(value)) {
			return {
				status: true,
				message: "Пустое поле",
			};
		}

		if (reference !== null) {
			if (value !== reference) {
				return {
					status: true,
					message: "Значение должно быть выбрано",
				};
			}
		}

		return {
			status: false,
			message: "Ошибок нет",
		};
	},

	/* Проверка на совпадение нескольких значений */
	checkInputTwice(value, reference) {
		// Проверка на пустую строку
		if (this.isEmpty(value) || this.isEmpty(reference)) {
			return {
				status: true,
				message: "Пустое поле",
			};
		}

		if (typeof value !== typeof reference) {
			return {
				status: true,
				message: "Разные типы данных",
			};
		}

		if (value !== reference) {
			return {
				status: true,
				message: "Совпадение отсутcтвует",
			};
		}

		return {
			status: false,
			message: "Ошибок нет",
		};
	},

	/* Проверка введенной даты */
	checkInputDate(value) {
		// Проверка на пустую строку
		if (this.isEmpty(value)) {
			return {
				status: true,
				message: "Пустое поле",
			};
		}

		if (!this.isDate(value)) {
			return {
				status: true,
				message: "Некорректная дата",
			};
		}

		return {
			status: false,
			message: "Ошибок нет",
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
				message: "Некорректная почта",
			};
		}

		return {
			status: false,
			message: "Ошибок нет",
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
				message: "Некорректный телефон",
			};
		}

		return {
			status: false,
			message: "Ошибок нет",
		};
	},

	checkInputMask(value, mask) {
		// Проверка на пустую строку
		if (this.isEmpty(value)) {
			return {
				status: true,
				message: "Пустое поле",
			};
		}

		if (!value) {
			return {
				status: true,
				message: "Введите значение",
			};
		}

		if (!mask) {
			return {
				status: true,
				message: "Отсутствует маска",
			};
		}

		if (!String(value).match(mask)) {
			return {
				status: true,
				message: "Неверное значение",
			};
		}

		return {
			status: false,
			message: "Ошибок нет",
		};
	},

	/* Проверка введенного телефона */
	checkInputTiptap(obj) {
		if (obj.getSymbols() < 1) {
			return {
				status: true,
				message: "Введите текст",
			};
		}

		return {
			status: false,
			message: "Ошибок нет",
		};
	},

	/* Проверка введенного файла */
	checkInputFile(files, formats = []) {
		// Проверка на загрузку файла пользователем
		if (!files[0]) {
			return {
				status: true,
				message: "Загрузите файл",
			};
		}

		let inFormat = false;
		for (let value of formats) {
			if (files[0].type == types.get(value)) {
				inFormat = true;
				break;
			}
		}

		if (!inFormat) {
			return {
				status: true,
				message: `Запрещенный формат.`,
			};
		}

		return {
			status: false,
			message: "Ошибок нет",
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
