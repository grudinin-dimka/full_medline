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
};
