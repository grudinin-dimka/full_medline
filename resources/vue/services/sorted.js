export default {
	/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
	/* |            УНИВЕРСАЛЬНАЯ СОРТИРОВКА               |*/
	/* |___________________________________________________|*/
	/* Универсальная сортировка строковых полей */
	sortDateByKey(type = "up", array, key) {
		switch (type) {
			case "up":
				array.sort((a, b) => {
					return new Date(a[key]) - new Date(b[key]);
				});
				break;
			case "down":
				array.sort((a, b) => {
					return new Date(b[key]) - new Date(a[key]);
				});
				break;
		}
	},

	/* Универсальная сортировка строковых полей */
	sortStringByKey(type = "up", array, key) {
		switch (type) {
			case "up":
				array.sort((a, b) => String(a[key]).localeCompare(b[key]));
				break;
			case "down":
				array.sort((a, b) => String(b[key]).localeCompare(a[key]));
				break;
		}
	},

	/* Универсальная сортировка числовых полей */
	sortNumberByKey(type = "up", array, key) {
		switch (type) {
			case "up":
				array.sort((a, b) => {
					return Number(a[key]) - Number(b[key]);
				});
				break;
			case "down":
				array.sort((a, b) => Number(b[key]) - Number(a[key]));
				break;
		}
	},

	/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
	/* |               ПРОСТАЯ СОРТИРОВКА                  |*/
	/* |___________________________________________________|*/
	/* Сортировка строк */
	sortString(type = "up", array) {
		switch (type) {
			case "up":
				array.sort((a, b) => String(a).localeCompare(String(b)));
				break;
			case "down":
				array.sort((a, b) => String(b).localeCompare(String(a)));
				break;
		}
	},

	/* Сортировка чисел */
	sortNumber(type = "up", array) {
		switch (type) {
			case "up":
				array.sort((a, b) => Number(a) - Number(b));
				break;
			case "down":
				array.sort((a, b) => Number(b) - Number(a));
				break;
		}
	},
};
