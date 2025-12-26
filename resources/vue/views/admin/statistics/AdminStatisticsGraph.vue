<template>
	<BlockTwo :minHeight="100">
		<template #one-title> ПОСЕЩАЕМОСТЬ </template>

		<template #one-options>
			<VueButton
				:look="'inverse'"
				:disabled="disabled.apexAttendanceWeek.load"
				@click.prevent="getApexAttendanceWeek"
			>
				<VueIcon
					:name="'Update'"
					:fill="'var(--icon-edit-fill)'"
					:width="'36px'"
					:height="'36px'"
				/>
				Обновить
			</VueButton>
		</template>

		<template #one-body>
			<div
				class="apexchart__skeleton"
				:class="{ skeleton: loading.loader.apexAttendanceWeek }"
				:style="{ minHeight: '170px' }"
			>
				<apexchart
					v-if="!loading.loader.apexAttendanceWeek"
					width="100%"
					height="150px"
					type="heatmap"
					:options="apexAttendanceWeek.options"
					:series="apexAttendanceWeek.series"
				></apexchart>
			</div>
		</template>

		<template #two-title> ЗАПИСЬ НА ПРИЕМ </template>

		<template #two-options>
			<VueButton
				:look="'inverse'"
				:disabled="disabled.apexRecordPriem.load"
				@click.prevent="getApexRecordPriem"
			>
				<VueIcon
					:name="'Update'"
					:fill="'var(--icon-edit-fill)'"
					:width="'36px'"
					:height="'36px'"
				/>
				Обновить
			</VueButton>
		</template>

		<template #two-body>
			<div
				class="apexchart__skeleton"
				:class="{ skeleton: loading.loader.apexRecordPriem }"
				:style="{ minHeight: '170px' }"
			>
				<apexchart
					v-if="!loading.loader.apexRecordPriem"
					width="100%"
					height="150px"
					type="heatmap"
					:options="apexRecordPriem.options"
					:series="apexRecordPriem.series"
				></apexchart>
			</div>
		</template>
	</BlockTwo>

	<BlockTwo :minHeight="350">
		<template #one-title> ВЫБОР КЛИНИК В ЦЕНАХ </template>

		<template #one-options>
			<VueButton
				:look="'inverse'"
				:disabled="disabled.apexClinics.load"
				@click.prevent="getApexClinics"
			>
				<VueIcon
					:name="'Update'"
					:fill="'var(--icon-edit-fill)'"
					:width="'36px'"
					:height="'36px'"
				/>
				Обновить
			</VueButton>
		</template>

		<template #one-body>
			<div
				class="apexchart__skeleton"
				:class="{ skeleton: loading.loader.apexClinics }"
				:style="{ minHeight: '350px' }"
			>
				<apexchart
					v-if="!loading.loader.apexClinics"
					width="100%"
					height="350px"
					type="donut"
					:options="apexClinics.options"
					:series="apexClinics.series"
				></apexchart>
			</div>
		</template>

		<template #two-title> СТОРОННИЕ ИСТОЧНИКИ </template>

		<template #two-options>
			<VueButton
				:look="'inverse'"
				:disabled="disabled.apexSources.load"
				@click.prevent="getApexSources"
			>
				<VueIcon
					:name="'Update'"
					:fill="'var(--icon-edit-fill)'"
					:width="'36px'"
					:height="'36px'"
				/>
				Обновить
			</VueButton>
		</template>

		<template #two-body>
			<div
				class="apexchart__skeleton"
				:class="{ skeleton: loading.loader.apexSources }"
				:style="{ minHeight: '350px' }"
			>
				<apexchart
					v-if="!loading.loader.apexSources"
					width="100%"
					height="350px"
					type="donut"
					:options="apexSources.options"
					:series="apexSources.series"
				></apexchart>
			</div>
		</template>
	</BlockTwo>

	<BlockOnce :minHeight="100">
		<template #title> ПЕРЕХОДЫ ПО САЙТУ </template>

		<template #options>
			<VueButton
				:look="'inverse'"
				:disabled="disabled.apexAttendanceAll.load"
				@click.prevent="getApexAttendanceAll"
			>
				<VueIcon
					:name="'Update'"
					:fill="'var(--icon-edit-fill)'"
					:width="'36px'"
					:height="'36px'"
				/>
				Обновить
			</VueButton>
		</template>

		<template #body>
			<VueFieldset :count="2" :gap="'20px'">
				<template #legend> ОТ И ДО </template>

				<template #inputs>
					<VueDate
						v-model.trim="currentDate.data.dateStart.value"
						:type="'date'"
						:error="currentDate.errors.dateStart.status"
					>
						<template #label> ОТ </template>
						<template #error> {{ currentDate.errors.dateStart.message }} </template>
					</VueDate>

					<VueDate
						v-model.trim="currentDate.data.dateEnd.value"
						:type="'date'"
						:error="currentDate.errors.dateEnd.status"
					>
						<template #label> ДО </template>
						<template #error> {{ currentDate.errors.dateEnd.message }} </template>
					</VueDate>
				</template>
			</VueFieldset>

			<div
				class="apexchart__skeleton"
				:class="{ skeleton: loading.loader.apexAttendanceAll }"
				:style="{ minHeight: '730px', marginTop: '20px' }"
			>
				<apexchart
					v-if="!loading.loader.apexAttendanceAll"
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

import api from "../../../mixin/api";
import validate from "../../../services/validate";

import fakeDelay from "../../../mixin/fake-delay";

export default {
	components: {
		VueInput,
		VueInputContainer,

		BlockOnce,
		BlockTwo,
	},
	data() {
		return {
			/* Кно */
			disabled: {
				apexAttendanceAll: {
					load: false,
				},
				apexClinics: {
					load: false,
				},
				apexAttendanceWeek: {
					load: false,
				},
				apexRecordPriem: {
					load: false,
				},
				apexSources: {
					load: false,
				},
			},

			/* Загрузчик */
			loading: {
				loader: {
					apexAttendanceWeek: true,
					apexAttendanceAll: true,
					apexRecordPriem: true,
					apexClinics: true,
					apexSources: true,
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
					colors: this.getColors(),
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
					legend: {
						position: "right",
						horizontalAlign: "center",
						formatter: function (seriesName, opts) {
							// Обрезаем длинные названия до 20 символов
							return seriesName.length > 30
								? seriesName.substring(0, 40) + "..."
								: seriesName;
						},
					},
					tooltip: {
						enabled: true,
						enabledOnSeries: undefined,
						shared: true,
						hideEmptySeries: true,
						fillSeriesColor: false,
						theme: false,
						x: {
							show: true,
						},
						y: {
							show: true,
							title: {
								formatter: function (val) {
									return val.length > 70 ? val.substring(0, 70) + "..." : val;
								},
							},
						},
					},
					plotOptions: {
						pie: {
							donut: {
								labels: {
									show: true,
									total: {
										show: true,
										label: "Всего",
									},
									name: {
										show: true,
										formatter: function (val) {
											return val.length > 25 ? val.substring(0, 25) + "..." : val;
										},
									},
								},
							},
							customScale: 0.9, // Дает немного больше места
						},
					},
				},
				series: [],
			},

			apexSources: {
				options: {
					colors: this.getColors(),
					labels: [], // Ваши исходные labels будут обработаны
					chart: {
						id: "vuechart-sources",
						type: "donut",
						animations: {
							enabled: false, // Улучшает производительность
						},
					},
					legend: {
						position: "right",
						horizontalAlign: "center",
						formatter: function (seriesName, opts) {
							// Обрезаем длинные названия до 20 символов
							return seriesName.length > 30
								? seriesName.substring(0, 40) + "..."
								: seriesName;
						},
					},
					tooltip: {
						enabled: true,
						enabledOnSeries: undefined,
						shared: true,
						hideEmptySeries: true,
						fillSeriesColor: false,
						theme: false,
						x: {
							show: true,
						},
						y: {
							show: true,
							title: {
								formatter: function (val) {
									return val.length > 70 ? val.substring(0, 70) + "..." : val;
								},
							},
						},
					},
					plotOptions: {
						pie: {
							donut: {
								labels: {
									show: true,
									total: {
										show: true,
										label: "Всего",
									},
									name: {
										show: true,
										formatter: function (val) {
											return val.length > 25 ? val.substring(0, 25) + "..." : val;
										},
									},
								},
							},
							customScale: 0.9, // Дает немного больше места
						},
					},
				},
				series: [],
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
				"rgb(170, 68, 221)",
				"rgb(255, 105, 180)",
				"rgb(40, 53, 147)",
				"rgb(144, 238, 144)",
				"rgb(100, 210, 255)",
			];
		},

		/* Получение данных из бд */
		getApexClinics() {
			this.disabled.apexClinics.load = true;

			// Скрываем график перед загрузкой новых данных
			this.loading.loader.apexClinics = true;

			api({
				method: "post",
				url: `get-tracking-statistics-diagram`,
				headers: {
					Accept: "application/json",
				},
				data: {
					type: "Клиники",
				},
			}).then((response) => {
				if (!response) return;

				try {
					if (!response) return;

					this.apexClinics.options.labels = [];
					this.apexClinics.series = [];

					for (let key in response.data.result) {
						this.apexClinics.options.labels.push(key);
						this.apexClinics.series.push(response.data.result[key]);
					}

					this.loading.loader.apexClinics = false;
					this.disabled.apexClinics.load = false;
				} catch (error) {
					this.$store.commit("addDebugger", {
						title: "Ошибка.",
						body: error,
						type: "error",
					});
				}
			});
		},

		/* Получение данных из бд */
		getApexSources() {
			this.disabled.apexSources.load = true;

			// Скрываем график перед загрузкой новых данных
			this.loading.loader.apexSources = true;

			api({
				method: "post",
				url: `get-tracking-statistics-diagram`,
				headers: {
					Accept: "application/json",
				},
				data: {
					type: "Источники",
				},
			}).then((response) => {
				if (!response) return;

				try {
					if (!response) return;

					this.apexSources.options.labels = [];
					this.apexSources.series = [];

					for (let key in response.data.result) {
						this.apexSources.options.labels.push(key);
						this.apexSources.series.push(response.data.result[key]);
					}

					this.loading.loader.apexSources = false;
					this.disabled.apexSources.load = false;
				} catch (error) {
					this.$store.commit("addDebugger", {
						title: "Ошибка.",
						body: error,
						type: "error",
					});
				}
			});
		},

		/* Получение данных из бд */
		getApexAttendanceWeek() {
			this.disabled.apexAttendanceWeek.load = true;

			// Скрываем график перед загрузкой новых данных
			this.loading.loader.apexAttendanceWeek = true;

			let currentDay = new Date();
			let previousDay = new Date(currentDay.getTime());

			/* На неделю */
			previousDay.setDate(currentDay.getDate() - 7);
			this.currentDate.data.dateStart.value = previousDay.toISOString().slice(0, 10);

			api({
				method: "post",
				url: `get-tracking-statistics-range`,
				headers: {
					Accept: "application/json",
				},
				data: {
					start: previousDay.toISOString().split("T")[0],
					end: currentDay.toISOString().split("T")[0],
					types: ["Посещение"],
				},
			}).then((response) => {
				if (!response) return;

				try {
					if (!response) return;

					this.apexAttendanceWeek.series = [];
					let counts = [];

					for (let key in response.data.result) {
						counts.push({
							x: new Date(key).toLocaleDateString("ru", {
								day: "numeric",
								month: "short",
							}),
							y: response.data.result[key],
						});
					}

					this.apexAttendanceWeek.series.push({
						name: "",
						data: counts,
					});

					this.loading.loader.apexAttendanceWeek = false;
					this.disabled.apexAttendanceWeek.load = false;
				} catch (error) {
					this.$store.commit("addDebugger", {
						title: "Ошибка.",
						body: error,
						type: "error",
					});
				}
			});
		},

		/* Получение данных из бд */
		getApexRecordPriem() {
			this.disabled.apexRecordPriem.load = true;

			// Скрываем график перед загрузкой новых данных
			this.loading.loader.apexRecordPriem = true;

			let currentDay = new Date();
			let previousDay = new Date(currentDay.getTime());

			/* На неделю */
			previousDay.setDate(currentDay.getDate() - 7);
			this.currentDate.data.dateStart.value = previousDay.toISOString().slice(0, 10);

			api({
				method: "post",
				url: `get-tracking-statistics-range`,
				headers: {
					Accept: "application/json",
				},
				data: {
					start: previousDay.toISOString().split("T")[0],
					end: currentDay.toISOString().split("T")[0],
					types: ["Запись на прием"],
				},
			}).then((response) => {
				if (!response) return;

				try {
					if (!response) return;

					this.apexRecordPriem.series = [];
					let counts = [];

					for (let key in response.data.result) {
						counts.push({
							x: new Date(key).toLocaleDateString("ru", {
								day: "numeric",
								month: "short",
							}),
							y: response.data.result[key],
						});
					}

					this.apexRecordPriem.series.push({
						name: "",
						data: counts,
					});

					this.disabled.apexRecordPriem.load = false;
					this.loading.loader.apexRecordPriem = false;
				} catch (error) {
					this.$store.commit("addDebugger", {
						title: "Ошибка.",
						body: error,
						type: "error",
					});
				}
			});
		},

		/* Получение данных из бд */
		getApexAttendanceAll() {
			if (
				validate.checkInputsAll(this.currentDate, [
					{ key: "dateStart", type: "text" },
					{ key: "dateEnd", type: "text" },
				])
			)
				return;

			let start = new Date(this.currentDate.data.dateStart.value);
			let end = new Date(this.currentDate.data.dateEnd.value);

			this.disabled.apexAttendanceAll.load = true;
			this.loading.loader.apexAttendanceAll = true;

			api({
				method: "post",
				url: "get-tracking-statistics-range",
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
						"Путевки",
						"Вакансии",
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
					// Заполняем данные для каждой серии
					for (let requestType in response.data.result) {
						const counts = sortedDates.map((dateStr) => {
							const dataForDate = response.data.result[requestType][dateStr] ?? 0;

							return dataForDate;
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
					this.disabled.apexAttendanceAll.load = false;

					// Показываем график с новыми данными
					this.loading.loader.apexAttendanceAll = false;
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
				url: `get-tracking-statistics-range`,
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
						"Путевки",
						"Вакансии",
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
						const dataForDate = response.data.result[requestType][dateStr] ?? 0;

						return dataForDate;
					});

					this.apexAttendanceAll.series.push({
						name: requestType,
						data: counts,
					});
				}

				this.loading.loader.apexAttendanceAll = false;
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
				url: `get-tracking-statistics-range`,
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
						y: response.data.result[key],
					});
				}

				this.apexAttendanceWeek.series.push({
					name: "",
					data: counts,
				});

				this.loading.loader.apexAttendanceWeek = false;
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
				url: `get-tracking-statistics-range`,
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
						y: response.data.result[key],
					});
				}

				this.apexRecordPriem.series.push({
					name: "",
					data: counts,
				});

				this.loading.loader.apexRecordPriem = false;
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
				url: `get-tracking-statistics-diagram`,
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

				this.loading.loader.apexClinics = false;
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
				url: `get-tracking-statistics-diagram`,
				headers: {
					Accept: "application/json",
				},
				data: {
					type: "Источники",
				},
			})
		).then((response) => {
			if (!response) return;

			try {
				if (!response) return;

				for (let key in response.data.result) {
					this.apexSources.options.labels.push(key);
					this.apexSources.series.push(response.data.result[key]);
				}

				this.loading.loader.apexSources = false;
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
