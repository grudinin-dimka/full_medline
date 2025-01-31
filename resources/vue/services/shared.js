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
	transliterate(text) {
		return text
			.replace(/\u0401/g, "YO")
			.replace(/\u0419/g, "I")
			.replace(/\u0426/g, "TS")
			.replace(/\u0423/g, "U")
			.replace(/\u041A/g, "K")
			.replace(/\u0415/g, "E")
			.replace(/\u041D/g, "N")
			.replace(/\u0413/g, "G")
			.replace(/\u0428/g, "SH")
			.replace(/\u0429/g, "SCH")
			.replace(/\u0417/g, "Z")
			.replace(/\u0425/g, "H")
			.replace(/\u042A/g, "")
			.replace(/\u0451/g, "yo")
			.replace(/\u0439/g, "i")
			.replace(/\u0446/g, "ts")
			.replace(/\u0443/g, "u")
			.replace(/\u043A/g, "k")
			.replace(/\u0435/g, "e")
			.replace(/\u043D/g, "n")
			.replace(/\u0433/g, "g")
			.replace(/\u0448/g, "sh")
			.replace(/\u0449/g, "sch")
			.replace(/\u0437/g, "z")
			.replace(/\u0445/g, "h")
			.replace(/\u044A/g, "'")
			.replace(/\u0424/g, "F")
			.replace(/\u042B/g, "I")
			.replace(/\u0412/g, "V")
			.replace(/\u0410/g, "a")
			.replace(/\u041F/g, "P")
			.replace(/\u0420/g, "R")
			.replace(/\u041E/g, "O")
			.replace(/\u041B/g, "L")
			.replace(/\u0414/g, "D")
			.replace(/\u0416/g, "ZH")
			.replace(/\u042D/g, "E")
			.replace(/\u0444/g, "f")
			.replace(/\u044B/g, "i")
			.replace(/\u0432/g, "v")
			.replace(/\u0430/g, "a")
			.replace(/\u043F/g, "p")
			.replace(/\u0440/g, "r")
			.replace(/\u043E/g, "o")
			.replace(/\u043B/g, "l")
			.replace(/\u0434/g, "d")
			.replace(/\u0436/g, "zh")
			.replace(/\u044D/g, "e")
			.replace(/\u042F/g, "Ya")
			.replace(/\u0427/g, "CH")
			.replace(/\u0421/g, "S")
			.replace(/\u041C/g, "M")
			.replace(/\u0418/g, "I")
			.replace(/\u0422/g, "T")
			.replace(/\u042C/g, "'")
			.replace(/\u0411/g, "B")
			.replace(/\u042E/g, "YU")
			.replace(/\u044F/g, "ya")
			.replace(/\u0447/g, "ch")
			.replace(/\u0441/g, "s")
			.replace(/\u043C/g, "m")
			.replace(/\u0438/g, "i")
			.replace(/\u0442/g, "t")
			.replace(/\u044C/g, "'")
			.replace(/\u0431/g, "b")
			.replace(/\u044E/g, "yu");
	},
};
