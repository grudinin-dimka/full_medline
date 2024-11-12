export default {
	sortString(type = "up", array) {
		const collator = new Intl.Collator("ru");

		switch (type) {
			case "up":
				array.sort((a, b) => {
					return collator.compare(a, b);
				});
				break;
			case "down":
				array.reverse((a, b) => {
					return collator.compare(a, b);
				});
				break;
		}
	},
	/* Сортировка по id */
	sortById(type = "up", array) {
		switch (type) {
			case "up":
				array.sort((a, b) => a.id - b.id);
				break;
			case "down":
				array.sort((a, b) => b.id - a.id);
				break;
		}
	},
	/* Сортировка по order */
	sortByOrder(type = "up", array) {
		switch (type) {
			case "up":
				array.sort((a, b) => a.order - b.order);
				break;
			case "down":
				array.sort((a, b) => b.order - a.order);
				break;
		}
	},
	/* Сортировка по названию */
	sortByName(type = "up", array) {
		const collator = new Intl.Collator("ru");

		switch (type) {
			case "up":
				array.sort((a, b) => {
					return collator.compare(a.name, b.name);
				});
				break;
			case "down":
				array.reverse((a, b) => {
					return collator.compare(a.name, b.name);
				});
				break;
		}
	},
	/* Сортировка по названию */
	sortBySpecialization(type = "up", array) {
		const collator = new Intl.Collator("ru");

		switch (type) {
			case "up":
				array.sort((a, b) => {
					return collator.compare(a.specializations, b.specializations);
				});
				break;
			case "down":
				array.reverse((a, b) => {
					return collator.compare(a.specializations, b.specializations);
				});
				break;
		}
	},
	/* Сортировка по названию */
	sortByHide(type = "up", array) {
		switch (type) {
			case "up":
				array.sort((a, b) => a.hide - b.hide);
				break;
			case "down":
				array.sort((a, b) => b.hide - a.hide);
				break;
		}
	},
};
