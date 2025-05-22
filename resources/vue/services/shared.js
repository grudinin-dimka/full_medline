import sorted from "./sorted";

export default {
	/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
	/* |                    ЗАГРУЗЧИК                      |*/
	/* |___________________________________________________|*/
	/* Поиск максимального id */
	switchAssociatedValues(obj, reference, result) {
		for (let key in obj[reference]) {
			if (!obj[reference][key]) {
				obj[result][key] = true;
			}
		}
	},

	/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
	/* |               ПОЛУЧЕНИЕ ЗНАЧЕНИЙ                  |*/
	/* |___________________________________________________|*/
	/* Поиск максимального id */
	getMaxId(array) {
		let maxId = 0;

		array.forEach((item) => {
			if (item.id > maxId) {
				maxId = item.id;
			}
		});

		return Number(maxId);
	},

	/* Поиск максимального order */
	getMaxOrder(array) {
		let maxOrder = 0;

		array.forEach((item) => {
			if (item.order > maxOrder) {
				maxOrder = item.order;
			}
		});

		return Number(maxOrder);
	},

	/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
	/* |                 ЗАПИСЬ ЗНАЧЕНИЙ                   |*/
	/* |___________________________________________________|*/
	/* Запись значений */
	setData(original, copy, section = "data", option = "value") {
		for (let key in original) {
			if (typeof original[key] == "object") {
				copy[section][key][option] = this.getRecursiveCopy(original[key]);
				continue;
			}

			if (copy[section].hasOwnProperty(key)) {
				copy[section][key][option] = original[key];
			}
		}
	},

	/* Рекурсивное копирование */
	getRecursiveCopy(obj) {
		if (obj === null || typeof obj !== "object") {
			return obj;
		}

		if (Array.isArray(obj)) {
			let arrCopy = [];
			for (let i = 0; i < obj.length; i++) {
				arrCopy[i] = this.getRecursiveCopy(obj[i]);
			}
			return arrCopy;
		}

		let objCopy = {};
		for (let key in obj) {
			if (obj.hasOwnProperty(key)) {
				objCopy[key] = this.getRecursiveCopy(obj[key]);
			}
		}

		return objCopy;
	},

	getRecursiveCopyClear(obj) {
		if (typeof obj !== "object") {
			switch (typeof obj) {
				case "boolean":
					return false;
				case "function":
					return obj;
				default:
					return "";
			}
		}

		if (Array.isArray(obj)) {
			return [];
		}

		let objCopy = {};
		for (let key in obj) {
			if (obj.hasOwnProperty(key)) {
				objCopy[key] = this.getRecursiveCopyClear(obj[key]);
			}
		}

		return objCopy;
	},

	/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
	/* |              ОБНОВЛЕНИЕ ЗНАЧЕНИЙ                  |*/
	/* |___________________________________________________|*/
	/* Обновление значений id */
	updateId(array, arrayId) {
		let elementsCreate = array.filter((item) => {
			if (item.create) return item;
		});

		// Изменение значений со старых id на новые из б.д.
		for (let key in elementsCreate) {
			elementsCreate[key].id = arrayId.find((item) => {
				if (item.old == elementsCreate[key].id) {
					return item;
				}
			}).new;
		}
	},

	/* Обновление значений order */
	updateOrders(array) {
		let count = 0;

		array.forEach((item) => {
			count++;
			item.order = count;
		});
	},

	/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
	/* |               ИЗМЕНЕНИЕ ЗНАЧЕНИЙ                  |*/
	/* |___________________________________________________|*/
	/* Изменение свойства order */
	changeOrder(array, obj, type) {
		if (array.length <= 1) {
			return;
		}

		// Является ли текущий элемент первым
		let firstItemStatus = obj.data.order.value == 1;

		// Предидущей элемент
		let itemPrevious = null;

		if (firstItemStatus) {
			itemPrevious = array.find((block) => block.order === array.length);
		} else {
			itemPrevious = array.find((block) => block.order === obj.data.order.value - 1);
		}

		// Текущий элемент
		let itemCurrent = array.find((block) => block.order === obj.data.order.value);

		// Является ли текущий элемент последним
		let lastItemStatus = obj.data.order.value == array.length;

		// Следующий элемент
		let itemNext = null;

		if (lastItemStatus) {
			itemNext = array.find((block) => block.order === 1);
		} else {
			itemNext = array.find((block) => block.order === obj.data.order.value + 1);
		}

		// Изменение порядка
		switch (type) {
			case "up":
				{
					if (lastItemStatus) {
						obj.data.order.value = 1;
						itemCurrent.order = 1;
						itemNext.order = array.length;
					} else {
						obj.data.order.value++;
						itemCurrent.order++;
						itemNext.order--;
					}
					sorted.sortByOrder("up", array);
				}
				break;
			case "down":
				{
					if (firstItemStatus) {
						obj.data.order.value = array.length;
						itemCurrent.order = array.length;
						itemPrevious.order = 1;
					} else {
						obj.data.order.value--;
						itemCurrent.order--;
						itemPrevious.order++;
					}
					sorted.sortByOrder("up", array);
				}
				break;
		}
	},

	/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
	/* |                ОЧИСТКА ЗНАЧЕНИЙ                   |*/
	/* |___________________________________________________|*/
	/* Выборочная очистка объекта */
	clearObjectSelective(obj, section = "data", option = ["value"]) {
		for (let key in obj[section]) {
			for (let i = 0; i < option.length; i++) {
				switch (typeof obj[section][key][option[i]]) {
					case "object":
						if (Array.isArray(obj[section][key][option[i]])) {
							obj[section][key][option[i]] = [];
						}
						break;
					case "function":
						continue;
						break;
					case "boolean":
						obj[section][key][option[i]] = false;
						break;
					default:
						obj[section][key][option[i]] = "";
						break;
				}
			}
		}
	},

	/* Полная очистка объекта */
	clearObjectFull(obj) {
		for (let key in obj) {
			switch (typeof obj[key]) {
				case "object":
					if (Array.isArray(obj[key])) {
						obj[key] = [];
						continue;
					}

					this.clearObjectFull(obj[key]);
					break;
				case "boolean":
					obj[key] = false;
					break;
				case "function":
					continue;
					break;
				default:
					obj[key] = "";
					break;
			}
		}
	},

	/* Удаление элементов */
	clearDeletes(array) {
		let elementsDelete = array.filter((item) => {
			if (item.delete == true) {
				return Object.assign({}, item);
			}
		});

		while (elementsDelete.length > 0) {
			array.splice(array.indexOf(elementsDelete[0]), 1);

			elementsDelete = array.filter((item) => {
				if (item.delete == true) {
					return Object.assign({}, item);
				}
			});
		}
	},

	/* Очистка пометок на удаление и сохранение */
	clearFlags(array) {
		array.forEach((item) => {
			item.create = false;
			item.delete = false;
		});
	},
	
	/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
	/* |               ГЕНЕРАЦИЯ ЗНАЧЕНИЙ                  |*/
	/* |___________________________________________________|*/
	/* Генерация каптчи */
	generateRandomString(length = 5, numbers = true, letters = true, symbols = false) {
		let chars = "";
		let captcha = "";

		if (numbers) chars += "0123456789";
		if (letters) chars += "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		if (symbols) chars += "!@#$%^&*()_+-=[]{}|;:,.<>?/";

		for (let i = 0; i < length; i++) {
			captcha += chars.charAt(Math.floor(Math.random() * chars.length));
		}

		return captcha;
	},
	/* Генерация угла */
	generateRandomAngle(range = 25) {
		let chance = Math.floor(Math.random() * 10);

		if (chance >= 5) {
			return Math.floor(Math.random() * Math.abs(range));
		} else {
			return Math.floor(Math.random() * -Math.abs(range));
		}
	},
};
