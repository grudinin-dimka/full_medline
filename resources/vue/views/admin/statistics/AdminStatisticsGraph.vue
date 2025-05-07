<template>
	<block-once :minHeight="100">
		<template #title> ГРАФИК СТАТИСТИКИ САЙТА </template>
		<template #body>
			<div class="apexchart__buttons">
				<ContainerInputOnce
					v-model.trim="currentDate.data.dateStart.value"
					:type="'datetime-local'"
					:error="currentDate.errors.dateStart.status"
					@input="currentDate.data.dateStart.edited = true"
				>
					<template #error> {{ currentDate.errors.dateStart.message }} </template>
				</ContainerInputOnce>

				<ContainerInputOnce
					v-model.trim="currentDate.data.dateEnd.value"
					:type="'datetime-local'"
					:error="currentDate.errors.dateEnd.status"
					@input="currentDate.data.dateEnd.edited = true"
				>
					<template #error> {{ currentDate.errors.dateEnd.message }} </template>
				</ContainerInputOnce>

				<button-default
					@click.prevent="getStatsValues"
					:disabled="disabled.apexchart.load"
					:look="'white'"
				>
					<IconPublicsh :width="28" :height="28" />
					Загрузить
				</button-default>
			</div>

			<apexchart
				class="apex"
				width="100%"
				height="500px"
				type="area"
				:options="apexchart.options"
				:series="apexchart.series"
			></apexchart>
		</template>
	</block-once>

	<block-once :minHeight="100">
		<template #title> НЕДЕЛЯ </template>
		<template #body>
			<template v-if="loading.sections.week">
				<apexchart
					class="apex"
					width="100%"
					height="500px"
					type="area"
					:options="week.options"
					:series="week.series"
				></apexchart>
			</template>

			<LoaderChild
				:isLoading="loading.loader.week"
				:minHeight="300"
				@loaderChildAfterLeave="loaderChildAfterLeave"
			></LoaderChild>
		</template>
	</block-once>

	<block-once :minHeight="100">
		<template #title> МЕСЯЦ </template>
		<template #body>
			<template v-if="loading.sections.month">
				<apexchart
					class="apex"
					width="100%"
					height="500px"
					type="area"
					:options="month.options"
					:series="month.series"
				></apexchart>
			</template>

			<LoaderChild
				:isLoading="loading.loader.month"
				:minHeight="300"
				@loaderChildAfterLeave="loaderChildAfterLeave"
			></LoaderChild>
		</template>
	</block-once>

	<block-once :minHeight="100">
		<template #title> ГОД </template>
		<template #body>
			<template v-if="loading.sections.year">
				<apexchart
					class="apex"
					width="100%"
					height="500px"
					type="area"
					:options="year.options"
					:series="year.series"
				></apexchart>
			</template>

			<LoaderChild
				:isLoading="loading.loader.year"
				:minHeight="300"
				@loaderChildAfterLeave="loaderChildAfterLeave"
			></LoaderChild>
		</template>
	</block-once>
</template>

<script>
import BlockOnce from "../../../components/ui/admin/blocks/BlockOnce.vue";
import LoaderChild from "../../../components/modules/LoaderChild.vue";

import ContainerInputOnce from "../../../components/modules/input/ContainerInputOnce.vue";
import BaseTable from "../../../components/modules/table/BaseTable.vue";

import ButtonDefault from "../../../components/ui/admin/buttons/ButtonDefault.vue";
import IconPublicsh from "../../../components/icons/buttons/IconPublicsh.vue";

import axios from "axios";
import validate from "../../../services/validate";

export default {
	components: {
		BlockOnce,
		LoaderChild,

		ButtonDefault,
		IconPublicsh,

		ContainerInputOnce,
		BaseTable,
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
					month: true,
					year: true,
				},
				sections: {
					week: false,
					month: false,
					year: false,
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
						tickAmount: 7,
					},
				},
				series: [],
			},

			/* Данные */
			week: {
				theme: {
					palette: "palette2",
				},
				options: {
					colors: [
						this.getPrimaryColor(),
						"rgb(255, 85, 77)",
						"rgb(255, 174, 0)",
						"rgb(27, 197, 35)",
						"rgb(60, 107, 236)",
					],
					markers: {
						size: 0,
					},
					chart: {
						id: "vuechart-week",
						toolbar: {
							show: true,
							style: {
								colors: ["#00abbd"], // Аналогично для месячного графика
							},
						},
					},
					xaxis: {
						categories: [],
						labels: {
							rotate: 0,
						},
						tickAmount: 7,
					},
				},
				series: [],
			},

			month: {
				options: {
					colors: [
						this.getPrimaryColor(),
						"rgb(255, 85, 77)",
						"rgb(255, 174, 0)",
						"rgb(27, 197, 35)",
						"rgb(60, 107, 236)",
					],
					chart: {
						id: "vuechart-month",
					},
					xaxis: {
						categories: [],
						labels: {
							rotate: 0,
						},
						tickAmount: 7,
					},
				},
				series: [],
			},

			year: {
				options: {
					colors: [
						this.getPrimaryColor(),
						"rgb(255, 85, 77)",
						"rgb(255, 174, 0)",
						"rgb(27, 197, 35)",
						"rgb(60, 107, 236)",
					],
					chart: {
						id: "vuechart-year",
					},
					xaxis: {
						categories: [],
						labels: {
							rotate: 0,
						},
						tickAmount: 12,
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
					{
						key: "dateStart",
						type: "text",
					},
					{
						key: "dateEnd",
						type: "text",
					},
				])
			)
				return;

			/* Текущий день */
			let start = new Date(this.currentDate.data.dateStart.value);

			/* Предидущий день */
			let end = new Date(this.currentDate.data.dateEnd.value);

			this.disabled.apexchart.load = true;

			axios({
				method: "post",
				url: `${this.$store.getters.urlApi}` + `get-tracking-statistics-range`,
				headers: {
					Accept: "application/json",
					Authorization: `Bearer ${localStorage.getItem("token")}`,
				},
				data: {
					start: start.toLocaleString("ru", {
						day: "numeric",
						month: "numeric",
						year: "numeric",
					}),
					end: end.toLocaleString("ru", {
						day: "numeric",
						month: "numeric",
						year: "numeric",
					}),
				},
			})
				.then((response) => {
					console.log(response.data.data);

					if (response.data.status) {
						for (let requestType in response.data.data) {
							let counts = [];

							for (let key in response.data.data[requestType]) {
								let countRequests = 0;

								if (Array.isArray(response.data.data[requestType][key])) {
									countRequests = response.data.data[requestType][key].length;
								}

								counts.push(countRequests);

								this.apexchart.options.xaxis.categories.push(key);
							}

							this.apexchart.series.push({
								name: requestType,
								data: counts,
							});
						}
					} else {
						this.$store.commit("addDebugger", {
							title: "Ошибка.",
							body: response.data.message,
							type: "error",
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
					this.disabled.apexchart.load = false;
				});
		},
	},
	mounted() {
		/* Текущий день */
		let currentDay = new Date();

		/* Предидущий день */
		let previousDay = new Date(currentDay.getTime());

		/* На неделю */
		previousDay.setDate(currentDay.getDate() - 7);

		axios({
			method: "post",
			url: `${this.$store.getters.urlApi}` + `get-tracking-statistics-range`,
			headers: {
				Accept: "application/json",
				Authorization: `Bearer ${localStorage.getItem("token")}`,
			},
			data: {
				start: previousDay.toLocaleString("ru", {
					day: "numeric",
					month: "numeric",
					year: "numeric",
				}),
				end: currentDay.toLocaleString("ru", {
					day: "numeric",
					month: "numeric",
					year: "numeric",
				}),
			},
		})
			.then((response) => {
				if (response.data.status) {
					for (let requestType in response.data.data) {
						let counts = [];

						for (let key in response.data.data[requestType]) {
							let countRequests = 0;

							if (Array.isArray(response.data.data[requestType][key])) {
								countRequests = response.data.data[requestType][key].length;
							}

							counts.push(countRequests);

							this.week.options.xaxis.categories.push(key);
						}

						this.week.series.push({
							name: requestType,
							data: counts,
						});
					}
				} else {
					this.$store.commit("addDebugger", {
						title: "Ошибка.",
						body: response.data.message,
						type: "error",
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

		/* На месяц */
		previousDay.setDate(currentDay.getDate() - 31);

		axios({
			method: "post",
			url: `${this.$store.getters.urlApi}` + `get-tracking-statistics-range`,
			headers: {
				Accept: "application/json",
				Authorization: `Bearer ${localStorage.getItem("token")}`,
			},
			data: {
				start: previousDay.toLocaleString("ru", {
					day: "numeric",
					month: "numeric",
					year: "numeric",
				}),
				end: currentDay.toLocaleString("ru", {
					day: "numeric",
					month: "numeric",
					year: "numeric",
				}),
			},
		})
			.then((response) => {
				if (response.data.status) {
					for (let requestType in response.data.data) {
						let counts = [];

						for (let key in response.data.data[requestType]) {
							let countRequests = 0;

							if (Array.isArray(response.data.data[requestType][key])) {
								countRequests = response.data.data[requestType][key].length;
							}

							counts.push(countRequests);

							this.month.options.xaxis.categories.push(key);
						}

						this.month.series.push({
							name: requestType,
							data: counts,
						});
					}
				} else {
					this.$store.commit("addDebugger", {
						title: "Ошибка.",
						body: response.data.message,
						type: "error",
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
				this.loading.loader.month = false;
			});

		/* На год */
		previousDay.setDate(currentDay.getDate() - 365);

		axios({
			method: "post",
			url: `${this.$store.getters.urlApi}` + `get-tracking-statistics-range`,
			headers: {
				Accept: "application/json",
				Authorization: `Bearer ${localStorage.getItem("token")}`,
			},
			data: {
				start: previousDay.toLocaleString("ru", {
					day: "numeric",
					month: "numeric",
					year: "numeric",
				}),
				end: currentDay.toLocaleString("ru", {
					day: "numeric",
					month: "numeric",
					year: "numeric",
				}),
			},
		})
			.then((response) => {
				if (response.data.status) {
					for (let requestType in response.data.data) {
						let counts = [];

						for (let key in response.data.data[requestType]) {
							let countRequests = 0;

							if (Array.isArray(response.data.data[requestType][key])) {
								countRequests = response.data.data[requestType][key].length;
							}

							counts.push(countRequests);

							this.year.options.xaxis.categories.push(key);
						}

						this.year.series.push({
							name: requestType,
							data: counts,
						});
					}
				} else {
					this.$store.commit("addDebugger", {
						title: "Ошибка.",
						body: response.data.message,
						type: "error",
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
				this.loading.loader.year = false;
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
