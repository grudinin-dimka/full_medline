<template>
	<info-bar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link to="/schedule">Расписание</router-link>
	</info-bar>

	<template v-if="false">
		<button class="shedule-refresh" @click="updateSheduleFromServe">
			<svg
				xmlns="http://www.w3.org/2000/svg"
				height="45px"
				viewBox="0 -960 960 960"
				width="45px"
				fill="white"
			>
				<path
					d="M480-160q-134 0-227-93t-93-227q0-134 93-227t227-93q69 0 132 28.5T720-690v-110h80v280H520v-80h168q-32-56-87.5-88T480-720q-100 0-170 70t-70 170q0 100 70 170t170 70q77 0 139-44t87-116h84q-28 106-114 173t-196 67Z"
				/>
			</svg>
		</button>
	</template>

	<block-hide>
		<template v-if="loading.sections.schedule">
			<div class="filter-list">
				<div class="container">
					<div class="body">
						<div
							class="item"
							:class="{ active: activeClinic.name === 'Все' }"
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
							class="item"
							v-for="clinic in clinics"
							:key="clinic.id"
							:class="{ active: clinic.status }"
							@click="changeActiveClinic(clinic)"
						>
							<div>{{ clinic.name }}</div>
						</div>
					</div>
				</div>
			</div>
			<fieldset class="filter-blocks">
				<div class="item">
					<div
						class="clear-filter"
						v-if="filters.sections.fio.status && filters.sections.fio.data.body !== ''"
						@click="clearFilter('fio')"
					>
						Очистить
					</div>
					<container-input-once :type="'default'">
						<template #title>
							<span>ВРАЧ (Ф.И.О.)</span>
						</template>
						<template #input>
							<input
								type="text"
								placeholder="Введите Ф.И.О."
								v-model="filters.sections.fio.data.body"
								@input="filters.sections.fio.status = true"
							/>
						</template>
					</container-input-once>
				</div>
				<div class="item">
					<div
						class="clear-filter"
						v-if="
							filters.sections.specialization.status &&
							filters.sections.specialization.data.body !== ''
						"
						@click="clearFilter('specialization')"
					>
						Очистить
					</div>
					<container-select-once :type="'default'">
						<template #title>
							<span>СПЕЦИАЛИЗАЦИЯ</span>
						</template>
						<template #select>
							<select
								v-model="filters.sections.specialization.data.body"
								@input="filters.sections.specialization.status = true"
							>
								<option value="" selected>Ничего не выбрано</option>
								<option
									:value="specialization"
									v-for="specialization in getAllSpecializations"
								>
									{{ specialization }}
								</option>
							</select>
						</template>
					</container-select-once>
				</div>
			</fieldset>
			<!-- TODO надо бы сделать вывод значений в одну строку, которые одинаковые по типу -->
			<table>
				<thead>
					<tr>
						<th width="300px">Ф.И.О.</th>
						<th>Специализация</th>
						<th width="110px" v-for="day in week">
							{{ getDateDayNumberMonth(day.date) + " " + getDateDayWeekName(day.date) }}
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
									class="item all"
									v-for="clinic in getClinicsWithoutAll"
									:key="clinic.id"
									v-if="activeClinic.name === 'Все'"
								>
									<div
										class="time"
										v-for="blob in getDayTime(shedule.id, day.date, clinic.id)"
										:class="{ clear: blob === '-' }"
										v-if="getClinicStatus(shedule.id, clinic.id)"
									>
										{{ blob !== "-" ? blob : "" }}
									</div>
								</div>
								<div
									class="item"
									v-else
									:class="{
										one: activeClinic.id === clinics[0].id,
										two: activeClinic.id === clinics[1].id,
										three: activeClinic.id === clinics[2].id,
										four: activeClinic.id === clinics[3].id,
									}"
								>
									<div
										class="time"
										v-for="blob in getDayTime(shedule.id, day.date, activeClinic.id)"
										:class="{ clear: blob === '-' }"
									>
										{{ blob !== "-" ? blob : "" }}
									</div>
								</div>
							</div>
						</td>
					</tr>
					<tr class="empty" v-else>
						<td colspan="9">Ничего нет.</td>
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
import InfoBar from "../../../components/ui/main/InfoBar.vue";
import LoaderChild from "../../../components/includes/LoaderChild.vue";
import Block from "../../../components/ui/main/blocks/Block.vue";
import BlockHide from "../../../components/ui/main/blocks/BlockHide.vue";

import ContainerInputOnce from "../../../components/ui/admin/containers/input/ContainerInputOnce.vue";
import ContainerSelectOnce from "../../../components/ui/admin/containers/select/ContainerSelectOnce.vue";

import axios from "axios";
import sorted from "../../../services/sorted.js";

export default {
	components: {
		InfoBar,
		LoaderChild,
		Block,
		BlockHide,
		ContainerInputOnce,
		ContainerSelectOnce,
		axios,
		sorted,
	},
	data() {
		return {
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
				sections: {
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
				},
			},
			clinics: [],
			week: [],
			shedules: [],
		};
	},
	computed: {
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
			for (let key in this.filters.sections) {
				if (this.filters.sections[key].status) {
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
					sorted.sortByName("up", filteredShedules);
					break;
				default:
					filteredShedules = this.shedules.filter((item) =>
						item.weeks.some(
							(week) => week.clinicId === this.activeClinic.id && week.status === true
						)
					);
					break;
			}

			if (this.filters.sections.fio.status) {
				if (this.filters.sections.fio.data.body !== "") {
					filteredShedules = filteredShedules.filter((item) => {
						if (item.name.indexOf(this.filters.sections.fio.data.body) !== -1) {
							return item;
						}
					});
				}
			}

			if (this.filters.sections.specialization.status) {
				if (this.filters.sections.specialization.data.body !== "") {
					filteredShedules = filteredShedules.filter((item) => {
						if (
							item.specializations.indexOf(
								this.filters.sections.specialization.data.body
							) !== -1
						) {
							return item;
						}
					});
				}
			}

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
			this.filters.sections[filterName].status = false;
			this.filters.sections[filterName].data.body = "";
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
		async updateSheduleFromServe() {
			await axios({
				method: "post",
				url: `${this.$store.state.axios.urlApi}` + `save-shedules-all`,
			})
				.then((response) => {
					console.log(response.data);
				})
				.catch((error) => {
					console.log(error);
				});
		},
	},
	mounted() {
		axios({
			method: "post",
			url: `${this.$store.state.axios.urlApi}` + `get-shedules-all`,
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
					let debbugStory = {
						title: "Ошибка.",
						body: response.data.message,
						type: "Error",
					};
					this.$store.commit("debuggerState", debbugStory);
				}
			})
			.catch((error) => {
				let debbugStory = {
					title: "Ошибка.",
					body: "Произошла ошибка при получении данных.",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
			})
			.finally(() => {
				this.loading.loader.schedule = false;
			});
	},
};
</script>

<style scoped>
.shedule-refresh {
	cursor: pointer;
	position: fixed;
	right: 30px;
	bottom: 30px;
	z-index: 10;
	width: 80px;
	height: 80px;
	border-radius: 100px;
	border: none;
	background-color: var(--button-default-color);
	color: white;
}

.shedule-refresh:hover {
	background-color: var(--button-default-color-hover);
}

.title-table {
	width: 1350px;
	font-size: 24px;
}

/* Фильтры */
.filter-list {
	display: flex;
	justify-content: center;
	width: 100%;
}

.filter-list > .container {
	display: inline-flex;
	justify-content: space-between;
	flex-wrap: wrap;
	gap: 10px;

	width: 1350px;

	animation: show 0.5s ease-out;
}

.filters-button {
	cursor: pointer;
}

.filter-list > .container > .body {
	display: flex;
	flex-wrap: wrap;
	gap: 10px;
}

.filter-button {
	cursor: pointer;

	padding: 5px 10px;
	font-size: 18px;
	border: 2px solid var(--input-border-color-inactive);
	border-radius: 100px;

	transition: all 0.2s;
}

.filter-button:hover {
	border: 2px solid var(--input-border-color-active);
	background-color: #f2feff;
}

.filter-list > .container > .body > .item {
	flex-grow: 1;
	cursor: pointer;

	height: 22px;
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

.filter-list > .container > .body > .item:nth-of-type(2) {
	border-top: 2px;
	border-top-color: rgba(255, 255, 255, 0);
	border-right: 2px;
	border-right-color: rgba(255, 255, 255, 0);
	border-bottom: 2px;
	border-bottom-color: #ffad00;
	border-left: 2px;
	border-left-color: rgba(255, 255, 255, 0);
	border-style: solid;
}

.filter-list > .container > .body > .item:nth-of-type(3) {
	border-top: 2px;
	border-top-color: rgba(255, 255, 255, 0);
	border-right: 2px;
	border-right-color: rgba(255, 255, 255, 0);
	border-bottom: 2px;
	border-bottom-color: #ff0d00;
	border-left: 2px;
	border-left-color: rgba(255, 255, 255, 0);
	border-style: solid;
}

.filter-list > .container > .body > .item:nth-of-type(4) {
	border-top: 2px;
	border-top-color: rgba(255, 255, 255, 0);
	border-right: 2px;
	border-right-color: rgba(255, 255, 255, 0);
	border-bottom: 2px;
	border-bottom-color: #49d369;
	border-left: 2px;
	border-left-color: rgba(255, 255, 255, 0);
	border-style: solid;
}

.filter-list > .container > .body > .item:nth-of-type(5) {
	border-top: 2px;
	border-top-color: rgba(255, 255, 255, 0);
	border-right: 2px;
	border-right-color: rgba(255, 255, 255, 0);
	border-bottom: 2px;
	border-bottom-color: #0036c8;
	border-left: 2px;
	border-left-color: rgba(255, 255, 255, 0);
	border-style: solid;
}

.filter-list > .container > .body > .item:nth-of-type(1).active {
	border-top: 2px;
	border-top-color: rgba(255, 255, 255, 0);
	border-right: 2px;
	border-right-color: rgba(255, 255, 255, 0);
	border-bottom: 2px;
	border-bottom-color: var(--primary-color);
	border-left: 2px;
	border-left-color: rgba(255, 255, 255, 0);
	border-style: solid;

	color: var(--primary-color);
}

.filter-list > .container > .body > .item:nth-of-type(2).active {
	color: #ffad00;
}

.filter-list > .container > .body > .item:nth-of-type(3).active {
	color: #ff0d00;
}

.filter-list > .container > .body > .item:nth-of-type(4).active {
	color: #49d369;
}

.filter-list > .container > .body > .item:nth-of-type(5).active {
	color: #0036c8;
}

/* Блоки фильтров */
.filter-blocks {
	box-sizing: border-box;
	display: flex;
	flex-wrap: wrap;
	gap: 10px;

	border: 2px solid var(--input-border-color-inactive);
	border-radius: 15px;
	padding: 10px;

	width: 1350px;
	transition: all 0.2s;
	animation: show 0.5s ease-out;
}

fieldset.filter-blocks:focus-within {
	border: 2px solid var(--input-border-color-active);
}

.filter-blocks > .item {
	position: relative;
	flex: 1 0 350px;

	transition: all 0.2s;
}

.filter-blocks > .item.active {
	border: 2px solid #44a533;
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
	word-wrap: break-word;
	text-align: center;
}

td {
	text-align: left;
	word-wrap: break-word;
}

th,
td {
	padding: 10px 5px;
	transition: all 0.2s;
	height: 30px;
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

.days > .item {
	text-align: center;

	width: 110px;
	border-radius: 10px;
}

.days > .item > .time {
	cursor: default;
	border-radius: 10px;
}

.days > .item.all > .time {
	color: black;
	cursor: default;
	border-radius: 10px;

	margin: 5px 0px;
}

.days > .item.all > .time.clear {
	margin: 0px;
	height: 30px;
}

.days > .item.all:nth-child(1) > .time,
.days > .item.one > .time {
	border: 1px solid #ffad00;
	background-color: rgba(255, 229, 172, 0.3);
}

.days > .item.all:nth-child(2) > .time,
.days > .item.two > .time {
	border: 1px solid #ff534a;
	background-color: rgba(255, 186, 183, 0.3);
}

.days > .item.all:nth-child(3) > .time,
.days > .item.three > .time {
	border: 1px solid #1bc724;
	background-color: rgba(211, 255, 211, 0.3);
}

.days > .item.all:nth-child(4) > .time,
.days > .item.four > .time {
	border: 1px solid #3d6cec;
	background-color: rgba(180, 200, 255, 0.3);
}

.days > .item.all > .time.clear,
.days > :is(.item.one, .item.two, .item.three, .item.four) > .time.clear {
	border: 0px solid #d2f2f5;
	background-color: rgba(255, 255, 255, 0);
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
	.title-table {
		width: 100%;
	}

	.filter-list > .container {
		width: 100%;
	}

	.filter-blocks {
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

@media screen and (width <= 600px) {
	.filter-list .container > .item {
		flex: 1 0 250px;
	}
}

@media screen and (width <= 500px) {
	.filter-list > .container > .body {
		flex-direction: column;
		width: 100%;
	}

	.filter-list > .container > .body > .item {
		width: 100%;
	}
}
</style>
