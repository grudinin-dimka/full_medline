<template>
	<BlockTwo :minHeight="100">
		<template #one-title> ПОСЕЩАЕМОСТЬ </template>
		<template #one-body>
			<apexchart
				v-if="loading.sections.attendance"
				class="apex"
				width="100%"
				height="150px"
				type="heatmap"
				:options="attendance.options"
				:series="attendance.series"
			></apexchart>

			<LoaderChild
				:isLoading="loading.loader.attendance"
				:minHeight="150"
				@loaderChildAfterLeave="loaderChildAfterLeave"
			></LoaderChild>
		</template>

		<template #two-title> ЗАПИСЬ НА ПРИЕМ </template>
		<template #two-body>
			<apexchart
				v-if="loading.sections.recordPriem"
				class="apex"
				width="100%"
				height="150px"
				type="heatmap"
				:options="recordPriem.options"
				:series="recordPriem.series"
			></apexchart>

			<LoaderChild
				:isLoading="loading.loader.recordPriem"
				:minHeight="150"
				@loaderChildAfterLeave="loaderChildAfterLeave"
			></LoaderChild>
		</template>
	</BlockTwo>

	<BlockOnce :minHeight="100">
		<template #title> ОБЩИЕ ДАННЫЕ </template>
		<template #body>
			<div class="apexchart__buttons">
				<VueInput
					v-model="currentDate.data.dateStart.value"
					:type="'date'"
					:error="currentDate.errors.dateStart.status"
				>
					<template #error> {{ currentDate.errors.dateStart.message }} </template>
				</VueInput>

				<VueInput
					v-model.trim="currentDate.data.dateEnd.value"
					:type="'date'"
					:error="currentDate.errors.dateEnd.status"
				>
					<template #error> {{ currentDate.errors.dateEnd.message }} </template>
				</VueInput>

				<button-default
					@click.prevent="getStatsValues"
					:disabled="disabled.apexchart.load"
					:look="'white'"
				>
					<VueIcon
						:name="'publish'"
						:fill="'var(--icon-edit-fill)'"
						:width="'28px'"
						:height="'28px'"
					/>
					Загрузить
				</button-default>
			</div>

			<apexchart
				v-if="loading.sections.week"
				class="apex"
				width="100%"
				height="700px"
				type="area"
				:options="apexchart.options"
				:series="apexchart.series"
			></apexchart>

			<LoaderChild
				:isLoading="loading.loader.week"
				:minHeight="700"
				@loaderChildAfterLeave="loaderChildAfterLeave"
			></LoaderChild>
		</template>
	</BlockOnce>
</template>

<script>
import BlockOnce from "../../../components/ui/admin/blocks/BlockOnce.vue";
import BlockTwo from "../../../components/ui/admin/blocks/BlockTwo.vue";
import LoaderChild from "../../../components/modules/LoaderChild.vue";
import VueInput from "../../../components/modules/input/VueInput.vue";

import ButtonDefault from "../../../components/ui/admin/buttons/ButtonDefault.vue";

import VueIcon from "../../../components/modules/icon/VueIcon.vue";

import api from "../../../services/api";
import validate from "../../../services/validate";

export default {
	components: {
		BlockOnce,
		BlockTwo,
		LoaderChild,
		VueInput,

		ButtonDefault,

		VueIcon,
	},
	data() {
		return {
			/* Кно */
			disabled: {
				apexchart: {
					load: false,
				},
			},

			/* Загрузчик */
			loading: {
				loader: {
					week: true,
					attendance: true,
					recordPriem: true,
				},
				sections: {
					week: false,
					attendance: false,
					recordPriem: false,
				},
			},

			currentDate: {
				errors: {
					dateStart: {
						status: false,
						message: "",
					},
					dateEnd: {
						status: false,
						message: "",
					},
				},
				data: {
					dateStart: {
						edited: false,
						value: "",
					},
					dateEnd: {
						edited: false,
						value: "",
					},
				},
			},

			/* Данные */
			attendance: {
				options: {
					colors: [
						this.getPrimaryColor(),
						"rgb(255, 85, 77)",
						"rgb(255, 174, 0)",
						"rgb(27, 197, 35)",
						"rgb(60, 107, 236)",
					],
					chart: {
						id: "vuechart-attendance",
					},
					xaxis: {
						categories: [],
						labels: {
							rotate: 0,
						},
					},
				},
				series: [],
			},

			recordPriem: {
				options: {
					colors: [
						this.getPrimaryColor(),
						"rgb(255, 85, 77)",
						"rgb(255, 174, 0)",
						"rgb(27, 197, 35)",
						"rgb(60, 107, 236)",
					],
					chart: {
						id: "vuechart-record-priem",
					},
					xaxis: {
						categories: [],
						labels: {
							rotate: 0,
						},
					},
				},
				series: [],
			},

			apexchart: {
				options: {
					colors: [
						this.getPrimaryColor(),
						"rgb(255, 85, 77)",
						"rgb(255, 174, 0)",
						"rgb(27, 197, 35)",
						"rgb(60, 107, 236)",
					],
					chart: {
						id: "vuechart-apexchart",
						toolbar: {
							show: true,
						},
					},
					xaxis: {
						categories: [],
						labels: {
							rotate: 0,
						},
					},
				},
				series: [],
			},
		};
	},
	methods: {
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                   ЗАГРУЗЧИК                       |*/
		/* |___________________________________________________|*/
		/* После скрытия элементы */
		loaderChildAfterLeave() {
			for (let key in this.loading.loader) {
				if (!this.loading.loader[key]) {
					this.loading.sections[key] = true;
				}
			}
		},

		/* Считывание переменной CSS */
		getPrimaryColor() {
			return (
				getComputedStyle(document.documentElement).getPropertyValue("--primary-color").trim() ||
				"#00abbd"
			);
		},

		/* Получение данных из бд */
		getStatsValues() {
			if (
				validate.checkInputsAll(this.currentDate, [
					{ key: "dateStart", type: "text" },
					{ key: "dateEnd", type: "text" },
				])
			)
				return;

			let start = new Date(this.currentDate.data.dateStart.value);
			let end = new Date(this.currentDate.data.dateEnd.value);

			this.disabled.apexchart.load = true;

			// Скрываем график перед загрузкой новых данных
			this.loading.sections.week = false;
			this.loading.loader.week = true;

			api({
				method: "post",
				url: this.$store.getters.urlApi + "get-tracking-statistics-range",
				headers: {
					Accept: "application/json",
				},
				data: {
					start: start.toISOString().split("T")[0],
					end: end.toISOString().split("T")[0],
				},
			})
				.then((response) => {
					if (!response) return;

					// Полностью сбрасываем данные графика
					this.apexchart.series = [];

					// Собираем все уникальные даты
					const allDates = new Set();
					const seriesData = {};

					// Сначала собираем все даты и данные
					for (let requestType in response.data.result) {
						seriesData[requestType] = [];

						for (let key in response.data.result[requestType]) {
							allDates.add(key);
						}
					}

					// Определение количество дней для вывода по группам
					if (allDates.size / 7 > 4) {
						this.apexchart.options.xaxis.tickAmount = 7;
					} else if (allDates.size / 30 > 3) {
						this.apexchart.options.xaxis.tickAmount = 14;
					} else if (allDates.size / 30 > 6) {
						this.apexchart.options.xaxis.tickAmount = 31;
					} else {
						delete this.apexchart.options.xaxis.tickAmount;
					}

					// Сортируем даты
					const sortedDates = Array.from(allDates).sort((a, b) => new Date(a) - new Date(b));

					// Форматируем даты для отображения
					this.apexchart.options.xaxis.categories = sortedDates.map((dateStr) => {
						return new Date(dateStr).toLocaleDateString("ru", {
							day: "numeric",
							month: "short",
						});
					});

					// Заполняем данные для каждой серии
					for (let requestType in response.data.result) {
						const counts = sortedDates.map((dateStr) => {
							const dataForDate = response.data.result[requestType][dateStr];
							return Array.isArray(dataForDate) ? dataForDate.length : 0;
						});

						this.apexchart.series.push({
							name: requestType,
							data: counts,
						});
					}

					// Форсируем обновление опций
					this.apexchart = {
						...this.apexchart,
						options: {
							...this.apexchart.options,
							xaxis: {
								...this.apexchart.options.xaxis,
								categories: [...this.apexchart.options.xaxis.categories],
							},
						},
					};
				})
				.catch((error) => {
					this.$store.commit("addDebugger", {
						title: "Ошибка.",
						body: error,
						type: "error",
					});
				})
				.finally(() => {
					this.disabled.apexchart.load = false;

					// Показываем график с новыми данными
					this.loading.loader.week = false;
				});
		},
	},
	mounted() {
		/* Текущий день */
		let currentDay = new Date();
		this.currentDate.data.dateEnd.value = currentDay.toISOString().slice(0, 10);

		/* Предидущий день */
		let previousDay = new Date(currentDay.getTime());

		/* На неделю */
		previousDay.setDate(currentDay.getDate() - 7);
		this.currentDate.data.dateStart.value = previousDay.toISOString().slice(0, 10);

		api({
			method: "post",
			url: this.$store.getters.urlApi + `get-tracking-statistics-range`,
			headers: {
				Accept: "application/json",
			},
			data: {
				start: previousDay.toISOString().split("T")[0],
				end: currentDay.toISOString().split("T")[0],
			},
		})
			.then((response) => {
				if (!response) return;

				// Полностью сбрасываем данные графика
				this.apexchart.series = [];

				// Собираем все уникальные даты
				const allDates = new Set();

				// Сначала собираем все даты и данные
				for (let requestType in response.data.result) {
					for (let key in response.data.result[requestType]) {
						allDates.add(key);
					}
				}

				// Сортируем даты
				const sortedDates = Array.from(allDates).sort((a, b) => new Date(a) - new Date(b));

				// Форматируем даты для отображения
				this.apexchart.options.xaxis.categories = sortedDates.map((dateStr) => {
					return new Date(dateStr).toLocaleDateString("ru", {
						day: "numeric",
						month: "short",
					});
				});

				// Заполняем данные для каждой серии
				for (let requestType in response.data.result) {
					const counts = sortedDates.map((dateStr) => {
						const dataForDate = response.data.result[requestType][dateStr];
						return Array.isArray(dataForDate) ? dataForDate.length : 0;
					});

					this.apexchart.series.push({
						name: requestType,
						data: counts,
					});
				}
			})
			.catch((error) => {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			})
			.finally(() => {
				this.loading.loader.week = false;
			});

		api({
			method: "post",
			url: this.$store.getters.urlApi + `get-tracking-statistics-range`,
			headers: {
				Accept: "application/json",
			},
			data: {
				start: previousDay.toISOString().split("T")[0],
				end: currentDay.toISOString().split("T")[0],
				type: "page_load",
			},
		})
			.then((response) => {
				if (!response) return;

				let counts = [];

				for (let key in response.data.result) {
					counts.push({
						x: new Date(key).toLocaleDateString("ru", {
							day: "numeric",
							month: "short",
						}),
						y: response.data.result[key].length,
					});
				}

				this.attendance.series.push({
					name: "",
					data: counts,
				});
			})
			.catch((error) => {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			})
			.finally(() => {
				this.loading.loader.attendance = false;
			});

		api({
			method: "post",
			url: this.$store.getters.urlApi + `get-tracking-statistics-range`,
			headers: {
				Accept: "application/json",
			},
			data: {
				start: previousDay.toISOString().split("T")[0],
				end: currentDay.toISOString().split("T")[0],
				type: "form_request_telegram_bot",
			},
		})
			.then((response) => {
				if (!response) return;

				let counts = [];

				for (let key in response.data.result) {
					counts.push({
						x: new Date(key).toLocaleDateString("ru", {
							day: "numeric",
							month: "short",
						}),
						y: response.data.result[key].length,
					});
				}

				this.recordPriem.series.push({
					name: "",
					data: counts,
				});
			})
			.catch((error) => {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			})
			.finally(() => {
				this.loading.loader.recordPriem = false;
			});
	},
};
</script>

<style>
.apexchart__buttons {
	display: grid;
	grid-template-columns: repeat(2, 1fr) auto;
	gap: 20px;

	width: 100%;
}

.apex {
	animation: show-bottom-to-top-15 0.5s ease-in-out;
}

.apexcharts-toolbar > div:not(.apexcharts-menu) {
	display: flex;
	align-items: center;
	justify-content: center;

	width: auto !important;
	height: auto !important;
}

.apexcharts-toolbar > div > svg {
	fill: rgb(190, 190, 190);
	stroke-width: 0;
	pointer-events: all;
}

.apexcharts-selected > svg {
	fill: #00abbd !important;
}

.apexcharts-toolbar > div > svg:hover {
	fill: #00abbd !important;
}
</style>
