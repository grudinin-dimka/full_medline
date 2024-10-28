export default {
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
	/* Обновление значений order */
	updateOrders(array) {
		let count = 0;

		array.forEach((item) => {
			count++;
			item.order = count;
		});
	},
};
