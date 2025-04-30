<template>
	<info-bar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link to="/schedule">Расписание</router-link>
	</info-bar>

	<block :minHeight="100" v-if="loading.sections.schedule">
		<div class="filter__list">
			<div
				class="filter__list-item"
				:style="getClinicStyle('Все')"
				@click="
					changeActiveClinic({
						id: 0,
						name: 'Все',
					})
				"
			>
				<div>Все</div>
			</div>
			<div
				class="filter__list-item"
				:style="getClinicStyle(clinic.id)"
				v-for="clinic in clinics"
				:key="clinic.id"
				@click="changeActiveClinic(clinic)"
			>
				<div>{{ clinic.name }}</div>
			</div>
		</div>
		<div class="filter__blocks">
			<ContainerInputSearch v-model="filters.fio.data.body" :placeholder="'Имя специалиста'">
				<template #label>Поиск</template>
			</ContainerInputSearch>
			<Selector
				v-model="filters.specialization.data.body"
				:placeholder="'Выберите специализацию'"
				:list="getFormatSpecializations"
			></Selector>
		</div>
	</block>

	<block-hide>
		<template v-if="loading.sections.schedule">
			<table>
				<thead>
					<tr>
						<th width="300px">Ф.И.О.</th>
						<th>Специализация</th>
						<th width="110px" v-for="day in week">
							<div>
								{{ getDateDayNumberMonth(day.date) }}
							</div>
							<div>
								{{ getDateDayWeekName(day.date) }}
							</div>
						</th>
					</tr>
				</thead>
				<tbody>
					<tr
						v-for="shedule in getFilteredShedules"
						:key="shedule.id"
						v-if="getFilteredShedules.length > 0"
					>
						<td>{{ shedule.name }}</td>
						<td>{{ shedule.specializations }}</td>
						<!-- Столбики дней недели -->
						<td v-for="day in week" :key="day.id">
							<div class="days">
								<!-- Вывод расписания на несколько -->
								<div
									class="days__item all"
									v-for="clinic in getClinicsWithoutAll"
									:key="clinic.id"
									v-if="activeClinic.name === 'Все'"
								>
									<div
										class="days__item-content"
										v-for="time in getDayTime(shedule.id, day.date, clinic.id)"
										v-if="getClinicStatus(shedule.id, clinic.id)"
										:style="getTimeStyle(clinic.id, time)"
									>
										{{ time !== "-" ? time : "" }}
										<div class="days__content-help" v-if="time !== '-'">
											{{ clinic.name }}
										</div>
									</div>
								</div>
								<div class="days__item" v-else>
									<div
										class="days__item-content"
										v-for="time in getDayTime(shedule.id, day.date, activeClinic.id)"
										:style="getTimeStyle(activeClinic.id, time)"
									>
										{{ time !== "-" ? time : "" }}
									</div>
								</div>
							</div>
						</td>
					</tr>
					<tr class="empty" v-else>
						<td colspan="10">Ничего нет.</td>
					</tr>
				</tbody>
			</table>
		</template>

		<loader-child
			:isLoading="loading.loader.schedule"
			@loaderChildAfterLeave="loaderChildAfterLeave"
		/>
	</block-hide>
</template>

<script>
import Selector from "../../../components/modules/Selector.vue";

import InfoBar from "../../../components/ui/main/InfoBar.vue";
import LoaderChild from "../../../components/modules/LoaderChild.vue";
import Block from "../../../components/ui/main/blocks/Block.vue";
import BlockHide from "../../../components/ui/main/blocks/BlockHide.vue";

import ContainerInputOnce from "../../../components/ui/admin/containers/input/ContainerInputOnce.vue";
import ContainerSelectOnce from "../../../components/ui/admin/containers/select/ContainerSelectOnce.vue";
import ContainerInputSearch from "../../../components/ui/admin/containers/input/ContainerInputSearch.vue";

import axios from "axios";
import sorted from "../../../services/sorted.js";

export default {
	components: {
		Selector,
		InfoBar,
		LoaderChild,
		Block,
		BlockHide,
		ContainerInputOnce,
		ContainerSelectOnce,
		ContainerInputSearch,
		axios,
		sorted,
	},
	data() {
		return {
			selected: null,
			loading: {
				loader: {
					schedule: true,
				},
				sections: {
					schedule: false,
				},
			},
			activeClinic: {
				id: 0,
				name: "Все",
			},
			// Фильтры
			filters: {
				fio: {
					status: false,
					data: {
						body: "",
						edited: false,
					},
					errors: {
						body: null,
						status: false,
					},
				},
				specialization: {
					status: true,
					data: {
						body: "",
						edited: false,
					},
					errors: {
						body: null,
						status: false,
					},
				},
			},
			clinics: [],
			week: [],
			shedules: [],

			colors: [
				{
					primary: {
						hsl: [41, 100, 50],
						rgb: [255, 173, 0],
						alpha: 1,
					},
					secondary: {
						hsl: [41, 100, 84],
						rgb: [255, 229, 172],
						alpha: 0.3,
					},
				},
				{
					primary: {
						hsl: [3, 100, 65],
						rgb: [255, 83, 74],
						alpha: 1,
					},
					secondary: {
						hsl: [3, 92, 85],
						rgb: [255, 186, 183],
						alpha: 0.3,
					},
				},
				{
					primary: {
						hsl: [123, 76, 44],
						rgb: [27, 199, 36],
						alpha: 1,
					},
					secondary: {
						hsl: [120, 100, 91],
						rgb: [120, 100, 91],
						alpha: 0.3,
					},
				},
				{
					primary: {
						hsl: [224, 82, 58],
						rgb: [61, 108, 236],
						alpha: 1,
					},
					secondary: {
						hsl: [224, 100, 85],
						rgb: [180, 200, 255],
						alpha: 0.3,
					},
				},
			],
		};
	},
	computed: {
		/* Получение всех специализаций */
		getFormatSpecializations() {
			let specializations = [];

			this.shedules.forEach((shedule) => {
				let keys = shedule.specializations.split(", ");

				keys.forEach((key) => {
					if (
						!specializations.find((item) =>
							item.value.toLowerCase().includes(key.toLowerCase())
						)
					) {
						let formatKey = key.slice(0, 1).toUpperCase() + key.slice(1);

						specializations.push({
							value: formatKey,
							label: formatKey,
						});
					}
				});
			});

			sorted.sortStringByKey("up", specializations, "label");

			return specializations;
		},
		/* Получение всех специализаций */
		getAllSpecializations() {
			let specializations = [];
			this.shedules.forEach((shedule) => {
				if (!specializations.includes(shedule.specializations))
					specializations.push(shedule.specializations);
			});
			sorted.sortString("up", specializations);
			return specializations;
		},
		/* Получение количества активных фильтров */
		getActiveFilters() {
			let count = 0;
			for (let key in this.filters) {
				if (this.filters[key].status) {
					count++;
				}
			}
			return count;
		},
		/* Получение клиник, которые не "Все" */
		getClinicsWithoutAll() {
			return this.clinics.filter((item) => item.name !== "Все");
		},
		/* Получение расписания */
		getFilteredShedules() {
			let filteredShedules = [];

			switch (this.activeClinic.name) {
				case "Все":
					filteredShedules = [...this.shedules];
					break;
				default:
					filteredShedules = this.shedules.filter((item) =>
						item.weeks.some(
							(week) => week.clinicId === this.activeClinic.id && week.status === true
						)
					);
					break;
			}

			if (this.filters.fio.data.body !== "") {
				filteredShedules = filteredShedules.filter((item) => {
					if (item.name.toLowerCase().includes(this.filters.fio.data.body.toLowerCase())) {
						return item;
					}
				});
			}

			if (this.filters.specialization.status) {
				if (this.filters.specialization.data.body !== "") {
					filteredShedules = filteredShedules.filter((item) => {
						if (
							item.specializations
								.toLowerCase()
								.includes(this.filters.specialization.data.body.toLowerCase())
						) {
							return item;
						}
					});
				}
			}

			sorted.sortByName("up", filteredShedules);

			return [...filteredShedules];
		},
	},
	methods: {
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                   ЗАГРУЗЧИК                       |*/
		/* |___________________________________________________|*/
		/* После скрытия элементы */
		loaderChildAfterLeave() {
			this.loading.sections.schedule = true;
		},
		changeActiveClinic(selectedClinic) {
			this.activeClinic = selectedClinic;

			this.clinics.forEach((clinic) => {
				if (clinic.id === selectedClinic.id) {
					clinic.status = true;
				} else {
					clinic.status = false;
				}
			});
		},
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                   РАСПИСАНИЕ                      |*/
		/* |___________________________________________________|*/
		getDateDayNumberMonth(date) {
			let dayDate = new Date(date);

			let options = {
				month: "long",
				day: "numeric",
			};

			return dayDate.toLocaleString("ru", options);
		},
		getDateDayWeekName(date) {
			let dayDate = new Date(date);

			let options = {
				weekday: "long",
			};

			return dayDate.toLocaleString("ru", options);
		},
		/* Очистка фильтра */
		clearFilter(filterName) {
			this.filters[filterName].status = false;
			this.filters[filterName].data.body = "";
		},
		/* Получение текущего дня и связка с таблицей недели */
		getDateNow() {
			let date = new Date();

			var options = {
				weekday: "long",
			};

			let dayDate = date.toLocaleString("ru", options);
			let dayNow = this.week.find((item) => {
				if (item.name.toLowerCase() === dayDate) {
					return item;
				}
			});

			return dayNow.date;
		},
		/* После скрытия элементы */
		getDayTime(sheduleId, dayDate, clinicId) {
			let shedule = this.shedules.find((item) => item.id === sheduleId);
			let week = shedule.weeks.find((item) => item.clinicId === clinicId);
			let day = week.content.find((item) => item.date === dayDate);

			return day.time;
		},
		getClinicStatus(sheduleId, clinicId) {
			let shedule = this.shedules.find((item) => item.id === sheduleId);
			let week = shedule.weeks.find((item) => item.clinicId === clinicId);
			if (week.status === true) {
				return true;
			}
			return false;
		},

		/* Получение класса у времени */
		getClinicStyle(id) {
			if (id === "Все") {
				// Если выбраны "Все"
				if (this.activeClinic.name === "Все") {
					return {
						color: "var(--primary-color)",
						borderBottomColor: "var(--primary-color)",
					};
				}

				// Если выбрана другая клиника
				return {
					borderBottomColor: "var(--primary-color)",
				};
			}

			let count = 0;
			let number = id;
			// Определение цвета в зависимости от id
			while (number > this.colors.length) {
				number -= this.colors.length;

				count++;
			}

			if (this.activeClinic.id === id) {
				return {
					color: this.getColor(number, count, "primary"),
					borderBottomColor: this.getColor(number, count, "primary"),
				};
			}

			return {
				borderBottomColor: this.getColor(number, count, "primary"),
			};
		},

		/* Получение класса у времени */
		getTimeStyle(id, time) {
			// Проверка на пустоту
			if (!id || time == "-") {
				return {
					borderColor: "rgba(255, 255, 255, 0)",
					backgroundColor: "rgba(255, 255, 255, 0)",
					height: "21px",
					margin: "5px 0px",
				};
			}

			let count = 0;
			// Определение цвета в зависимости от id
			while (id > this.colors.length) {
				id -= this.colors.length;

				count++;
			}

			return {
				borderColor: this.getColor(id, count, "primary"),
				backgroundColor: this.getColor(id, count, "secondary"),
				margin: "5px 0px",
			};
		},

		// Формирование цвета в зависимости от id
		getColor(id, count, option) {
			return (
				"hsl(" +
				this.colors.at(id - 1)[option].hsl.reduce((acc, item, index) => {
					switch (index) {
						case 1:
							return acc + ", " + (item - count * 10) + "%";
							break;
						case 2:
							return acc + ", " + (item - count * 10) + "%";
							break;
						default:
							return acc + (item - count * 20);
							break;
					}
				}, "") +
				", " +
				this.colors.at(id - 1)[option].alpha +
				")"
			);
		},
	},
	mounted() {
		axios({
			method: "get",
			url: `${this.$store.getters.urlApi}` + `get-shedules-all`,
		})
			.then((response) => {
				if (response.data.status) {
					this.clinics = response.data.data.sheduleClinics;
					this.week = response.data.data.currentDays;

					this.clinics.forEach((clinic) => {
						clinic.status = false;
					});

					this.shedules = response.data.data.shedules;
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
				this.loading.loader.schedule = false;
			});
	},
};
</script>

<style scoped>
.container-select {
	display: flex;
	flex-direction: column;
	gap: 5px;
}

.container-select > label {
	color: var(--primary-color);
	font-size: 1.125rem;
}

.title-table {
	width: 1350px;
	font-size: 24px;
}

/* Фильтры */
.filter__list {
	display: inline-flex;
	justify-content: flex-start;
	flex-wrap: wrap;
	gap: 10px;

	width: 1350px;

	animation: show 0.5s ease-out;
}

.filter__list-item {
	cursor: pointer;

	padding: 5px;
	border-top: 2px;
	border-top-color: white;
	border-right: 2px;
	border-right-color: white;
	border-bottom: 2px;
	border-bottom-color: var(--input-border-color-active);
	border-left: 2px;
	border-left-color: white;
	border-style: solid;

	font-size: 18px;

	transition: all 0.15s;
}

/* Блоки фильтров */
.filter__blocks {
	box-sizing: border-box;
	display: grid;
	grid-template-columns: 1fr 1fr;
	flex-wrap: wrap;
	gap: 10px;

	border: 0px solid var(--input-border-color-inactive);
	border-radius: 15px;

	width: 1350px;
	transition: all 0.2s;
	animation: show 0.5s ease-out;
}

.filter__blocks > .days__item {
	position: relative;
	flex: 1 0 350px;

	transition: all 0.2s;
}

.filter__blocks > .days__item.active {
	border: 2px solid #44a533;
}

.filter__blocks > .container-input {
	position: relative;
	display: grid;
	grid-template-columns: 1fr auto;
}

.filter__blocks > .container-input > input {
	box-sizing: border-box;

	padding: 15px;
	border-radius: 10px;
	border: 2px solid var(--input-border-color-inactive);
	outline: none;

	font-size: 1.125rem;

	height: 58px;

	transition: all 0.2s;
	caret-color: var(--input-border-color-active);
}

.filter__blocks > .container-input > input:focus {
	border: 2px solid var(--input-border-color-active);
}

.filter__blocks > .container-input > button {
	position: absolute;
	top: 0px;
	right: 5px;
	cursor: pointer;
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 5px;

	height: 58px;

	border-radius: 10px;
	border: 0px solid white;

	font-size: 1.125rem;
	color: white;
	background-color: rgba(0, 0, 0, 0);
}

.filter__blocks > .container-input > button > svg {
	fill: rgba(0, 0, 0, 1);
}

.filter__blocks > .container-input > button > svg:hover {
	fill: rgba(0, 0, 0, 1);
}

.filter-button.active {
	background-color: #f2feff;
	border: 2px solid var(--input-border-color-active);
}

.clear-filter {
	position: absolute;
	cursor: pointer;

	top: 0px;
	right: 0px;
	color: var(--button-remove-color);
}

.clear-filter:hover {
	text-decoration: underline;
}

.clear-filter > .point {
	position: absolute;
	top: 2px;

	border-radius: 30px;

	width: 16px;
	height: 16px;
}

.clear-filter > .point.default {
	left: 2px;
	background-color: #b6b6b6;
}

.clear-filter > .point.active {
	right: 2px;
	background-color: var(--primary-color);
}

/* Таблица */
table {
	border-collapse: collapse;
	animation: show-bottom-to-top-15 0.5s ease-in-out;

	width: 1350px;

	font-size: 16px;
}

th {
	white-space: pre-wrap;
	text-align: center;
	min-width: 110px;
}

td {
	text-align: left;
}

th,
td {
	padding: 5px 5px;
	transition: all 0.2s;
}

tr {
	background-color: #d2f2f5;
	transition: all 0.2s;
}

tr.create > td {
	border: 1px solid #3cae1f;
	background-color: #d7f5d2;
}

tr.delete > td {
	border: 1px solid #ae1f1f;
	background-color: #f5d2d2;
}

tr > th {
	position: relative;
	font-weight: 400;
	border: 1px solid #2d9aa7;
	background-color: #3fbecd;
	color: white;
}

tr > td {
	border: 1px solid #3fbecd;
}

tr > td:nth-child(2) {
	word-break: break-all;
}

tr:hover > td {
	background-color: rgba(255, 255, 255, 0.3);
}

tr.delete {
	background-color: #f5d2d2;
}

tr.delete:hover > td {
	background-color: rgba(255, 255, 255, 0.3);
}

tr.create {
	background-color: #d7f5d2;
}

tr.create:hover > td {
	background-color: rgba(255, 255, 255, 0.3);
}

.days {
	display: flex;
	flex-direction: column;
}

.days > .days__item {
	display: flex;
	flex-direction: column;

	text-align: center;
}

.days > .days__item > .days__item-content {
	position: relative;
	cursor: default;
	white-space: nowrap;

	border-radius: 10px;

	border: 1px solid rgba(0, 0, 0, 0);
}

.days > .days__item.all > .days__item-content > .days__content-help {
	position: absolute;
	top: -45px;
	right: 0px;

	white-space: nowrap;
	width: auto;
	padding: 10px;
	border-radius: 10px;

	opacity: 0;
	visibility: hidden;

	color: white;
	background-color: rgba(0, 0, 0, 0.5);
	transition: all 0.2s;
}

.days > .days__item > .days__item-content:hover > .days__content-help {
	opacity: 1;
	visibility: visible;
}

.days > .days__item.all > .days__item-content.clear {
	height: 21px;
}

tr.empty > td {
	text-align: center;
	color: #90cad1;
}

@keyframes show {
	0% {
		opacity: 0;
	}
	100% {
		opacity: 1;
	}
}

@media screen and (width <= 1430px) {
	.title-table,
	.filter__list,
	.filter__blocks {
		width: 100%;
	}

	.filter__blocks {
		width: 100%;
	}
}

@media screen and (max-width: 1400px) {
	table {
		width: auto;
		display: block;
		overflow-x: scroll;
	}
}

@media screen and (max-width: 700px) {
	.filter__blocks {
		grid-template-columns: 1fr;
	}
}

@media screen and (width <= 500px) {
	.filter__list-item {
		width: 100%;
	}
}
</style>
