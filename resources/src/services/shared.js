export default {
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
	/* |                ОЧИСТКА ЗНАЧЕНИЙ                   |*/
	/* |___________________________________________________|*/
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
