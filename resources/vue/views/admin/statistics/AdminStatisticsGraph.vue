<template>
	<BlockTwo :minHeight="100">
		<template #one-title> ПОСЕЩАЕМОСТЬ </template>
		<template #one-body>
			<div
				class="apexchart__skeleton"
				:class="{ skeleton: loading.loader.attendance }"
				:style="{ minHeight: '170px' }"
			>
				<apexchart
					v-if="!loading.loader.attendance"
					width="100%"
					height="150px"
					type="heatmap"
					:options="apexAttendanceWeek.options"
					:series="apexAttendanceWeek.series"
				></apexchart>
			</div>
		</template>

		<template #two-title> ЗАПИСЬ НА ПРИЕМ </template>
		<template #two-body>
			<div
				class="apexchart__skeleton"
				:class="{ skeleton: loading.loader.recordPriem }"
				:style="{ minHeight: '170px' }"
			>
				<apexchart
					v-if="!loading.loader.recordPriem"
					width="100%"
					height="150px"
					type="heatmap"
					:options="apexRecordPriem.options"
					:series="apexRecordPriem.series"
				></apexchart>
			</div>
		</template>
	</BlockTwo>

	<BlockOnce :minHeight="300">
		<template #title> ВЫБОР КЛИНИК В ЦЕНАХ </template>

		<template #body>
			<div
				class="apexchart__skeleton"
				:class="{ skeleton: loading.loader.clinics }"
				:style="{ minHeight: '300px' }"
			>
				<apexchart
					v-if="!loading.loader.clinics"
					width="100%"
					height="400px"
					type="donut"
					:options="apexClinics.options"
					:series="apexClinics.series"
				></apexchart>
			</div>
		</template>
	</BlockOnce>

	<BlockOnce :minHeight="100">
		<template #title> ПЕРЕХОДЫ ПО САЙТУ </template>

		<template #options>
			<VueButton
				:look="'inverse'"
				:disabled="disabled.apexchart.load"
				@click.prevent="getStatsValues"
			>
				<VueIcon
					:name="'publish'"
					:fill="'var(--icon-edit-fill)'"
					:width="'28px'"
					:height="'28px'"
				/>
				Загрузить
			</VueButton>
		</template>

		<template #body>
			<VueInputContainer :direction="'row'" :count="2" :gap="'10px'">
				<template #legend> ОТ И ДО </template>

				<template #inputs>
					<VueInput
						v-model="currentDate.data.dateStart.value"
						:type="'date'"
						:error="currentDate.errors.dateStart.status"
					>
						<template #label> ОТ </template>
						<template #error> {{ currentDate.errors.dateStart.message }} </template>
					</VueInput>

					<VueInput
						v-model.trim="currentDate.data.dateEnd.value"
						:type="'date'"
						:error="currentDate.errors.dateEnd.status"
					>
						<template #label> ДО </template>
						<template #error> {{ currentDate.errors.dateEnd.message }} </template>
					</VueInput>
				</template>
			</VueInputContainer>

			<div
				class="apexchart__skeleton"
				:class="{ skeleton: loading.loader.week }"
				:style="{ minHeight: '730px', marginTop: '20px' }"
			>
				<apexchart
					v-if="!loading.loader.week"
					width="100%"
					height="700px"
					type="area"
					:options="apexAttendanceAll.options"
					:series="apexAttendanceAll.series"
				/>
			</div>
		</template>
	</BlockOnce>
</template>

<script>
import VueInput from "../../../components/modules/input/VueInput.vue";
import VueInputContainer from "../../../components/modules/input/VueInputContainer.vue";

import BlockOnce from "../../../components/ui/admin/blocks/BlockOnce.vue";
import BlockTwo from "../../../components/ui/admin/blocks/BlockTwo.vue";

import VueLoader from "../../../components/modules/VueLoader.vue";
import VueIcon from "../../../components/modules/icon/VueIcon.vue";
import VueButton from "../../../components/ui/VueButton.vue";

import api from "../../../mixin/api";
import validate from "../../../services/validate";

import fakeDelay from "../../../mixin/fake-delay";

export default {
	components: {
		VueInput,
		VueInputContainer,

		BlockOnce,
		BlockTwo,

		VueLoader,
		VueIcon,
		VueButton,
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
					clinics: true,
					sources: true,
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
			apexAttendanceWeek: {
				options: {
					colors: [
						this.getPrimaryColor(),
						"rgb(255, 85, 77)",
						"rgb(255, 174, 0)",
						"rgb(27, 197, 35)",
						"rgb(60, 107, 236)",
					],
					chart: {
						id: "vuechart-attendance-week",
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

			apexRecordPriem: {
				options: {
					colors: this.getColors(),
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

			apexClinics: {
				options: {
					colors: this.getColors(),
					labels: [],
					chart: {
						id: "vuechart-clinics",
					},
				},
				series: [],
			},

			apexSources: {
				options: {
					colors: this.getColors(),
					chart: {
						id: "vuechart-clinics",
					},
				},
				series: [44, 55, 41, 17, 15],
			},

			apexAttendanceAll: {
				options: {
					colors: this.getColors(),
					chart: {
						id: "vuechart-attendance-all",
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

		/* Считывание переменной CSS */
		getColors() {
			return [
				this.getPrimaryColor(),
				"rgb(255, 85, 77)",
				"rgb(255, 174, 0)",
				"rgb(27, 197, 35)",
				"rgb(60, 107, 236)",
			];
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
					types: [
						"Посещение",
						"Специалисты",
						"Цены",
						"Расписание",
						"Новости",
						"Видео",
						"Контакты",
						"О нас",
					],
				},
			})
				.then((response) => {
					if (!response) return;

					// Полностью сбрасываем данные графика
					this.apexAttendanceAll.series = [];

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
						this.apexAttendanceAll.options.xaxis.tickAmount = 7;
					} else if (allDates.size / 30 > 3) {
						this.apexAttendanceAll.options.xaxis.tickAmount = 14;
					} else if (allDates.size / 30 > 6) {
						this.apexAttendanceAll.options.xaxis.tickAmount = 31;
					} else {
						delete this.apexAttendanceAll.options.xaxis.tickAmount;
					}

					// Сортируем даты
					const sortedDates = Array.from(allDates).sort((a, b) => new Date(a) - new Date(b));

					// Форматируем даты для отображения
					this.apexAttendanceAll.options.xaxis.categories = sortedDates.map((dateStr) => {
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

						this.apexAttendanceAll.series.push({
							name: requestType,
							data: counts,
						});
					}

					// Форсируем обновление опций
					this.apexAttendanceAll = {
						...this.apexAttendanceAll,
						options: {
							...this.apexAttendanceAll.options,
							xaxis: {
								...this.apexAttendanceAll.options.xaxis,
								categories: [...this.apexAttendanceAll.options.xaxis.categories],
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

		fakeDelay(this.$store.getters.timeout, () =>
			api({
				method: "post",
				url: this.$store.getters.urlApi + `get-tracking-statistics-range`,
				headers: {
					Accept: "application/json",
				},
				data: {
					start: previousDay.toISOString().split("T")[0],
					end: currentDay.toISOString().split("T")[0],
					types: [
						"Посещение",
						"Специалисты",
						"Цены",
						"Расписание",
						"Новости",
						"Видео",
						"Контакты",
						"О нас",
					],
				},
			})
		).then((response) => {
			if (!response) return;

			try {
				// Полностью сбрасываем данные графика
				this.apexAttendanceAll.series = [];

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
				this.apexAttendanceAll.options.xaxis.categories = sortedDates.map((dateStr) => {
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

					this.apexAttendanceAll.series.push({
						name: requestType,
						data: counts,
					});
				}

				this.loading.loader.week = false;
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			}
		});

		fakeDelay(this.$store.getters.timeout, () =>
			api({
				method: "post",
				url: this.$store.getters.urlApi + `get-tracking-statistics-range`,
				headers: {
					Accept: "application/json",
				},
				data: {
					start: previousDay.toISOString().split("T")[0],
					end: currentDay.toISOString().split("T")[0],
					types: ["Посещение"],
				},
			})
		).then((response) => {
			if (!response) return;

			try {
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

				this.apexAttendanceWeek.series.push({
					name: "",
					data: counts,
				});

				this.loading.loader.attendance = false;
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			}
		});

		fakeDelay(this.$store.getters.timeout, () =>
			api({
				method: "post",
				url: this.$store.getters.urlApi + `get-tracking-statistics-range`,
				headers: {
					Accept: "application/json",
				},
				data: {
					start: previousDay.toISOString().split("T")[0],
					end: currentDay.toISOString().split("T")[0],
					types: ["Запись на прием"],
				},
			})
		).then((response) => {
			if (!response) return;

			try {
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

				this.apexRecordPriem.series.push({
					name: "",
					data: counts,
				});

				this.loading.loader.recordPriem = false;
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			}
		});

		fakeDelay(this.$store.getters.timeout, () =>
			api({
				method: "post",
				url: this.$store.getters.urlApi + `get-tracking-statistics-diagram`,
				headers: {
					Accept: "application/json",
				},
				data: {
					type: "Клиники",
				},
			})
		).then((response) => {
			if (!response) return;

			try {
				if (!response) return;

				for (let key in response.data.result) {
					this.apexClinics.options.labels.push(key);
					this.apexClinics.series.push(response.data.result[key]);
				}

				this.loading.loader.clinics = false;
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			}
		});
	},
};
</script>

<style>
.apexchart__skeleton {
	border-radius: calc(var(--default-border-radius) / 1.5);
}

.vue-apexcharts {
	animation: show 0.5s ease-in-out;
}

.apexchart__buttons {
	display: grid;
	grid-template-columns: repeat(2, 1fr) auto;
	gap: 20px;

	width: 100%;
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
	fill: var(--primary-color) !important;
}

.apexcharts-toolbar > div > svg:hover {
	fill: var(--primary-color-hover) !important;
}
</style>
