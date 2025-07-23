<template>
	<info-bar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link to="/schedule">Расписание</router-link>
	</info-bar>

	<Block :minHeight="100">
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
				:class="{ skeleton: !clinic.name }"
				:style="getClinicStyle(clinic.id)"
				v-for="clinic in clinics"
				:key="clinic.id"
				@click="changeActiveClinic(clinic)"
			>
				<div>{{ clinic.name }}</div>
			</div>
		</div>
		<div class="filter__blocks">
			<VueInput
				v-model="filters.fio.data.body"
				:type="'search'"
				:placeholder="'Введите имя специалиста'"
			/>

			<VueSelector
				v-model="filters.specialization.data.body"
				:placeholder="'Выберите специализацию'"
				:list="getFormatSpecializations"
			/>
		</div>
	</Block>

	<Block :minHeight="400">
		<table class="shedule__table">
			<thead>
				<tr class="shedule__table-tr">
					<th class="shedule__table-th" width="400px"></th>
					<th class="shedule__table-th" width="110px" v-for="day in week">
						<div class="shedule__table-date" :class="{ skeleton: loading.loader.schedule }">
							{{ day.date ? getDateDayNumberMonth(day.date) : "" }}
						</div>
						<div class="shedule__table-date" :class="{ skeleton: loading.loader.schedule }">
							{{ day.date ? getDateDayWeekName(day.date) : "" }}
						</div>
					</th>
				</tr>
			</thead>
			<tbody>
				<tr
					class="shedule__table-tr"
					v-for="shedule in getFilteredShedules"
					:key="shedule.id"
					v-if="getFilteredShedules.length > 0"
				>
					<td class="shedule__table-td">
						<div class="shedule__specialist">
							<div class="shedule__specialist-head">
								<div
									class="specialist__head-img"
									:class="{ skeleton: loading.loader.schedule }"
								>
									<template v-if="!loading.loader.schedule">
										<template
											v-if="
												['кт', 'мрт', 'рентген', 'маммограф'].includes(
													shedule.name.trim().toLowerCase()
												)
											"
										>
											<img
												v-if="shedule.name.trim().toLowerCase() === 'кт'"
												:src="`/storage/img/kt.webp`"
												alt="КТ"
												loading="lazy"
											/>

											<img
												v-if="shedule.name.trim().toLowerCase() === 'мрт'"
												:src="`/storage/img/mrt.webp`"
												alt="МРТ"
												loading="lazy"
											/>

											<img
												v-if="shedule.name.trim().toLowerCase() === 'рентген'"
												:src="`/storage/img/rentgen.webp`"
												alt="рентген"
												loading="lazy"
											/>

											<img
												v-if="shedule.name.trim().toLowerCase() === 'маммограф'"
												:src="`/storage/img/mammograph.webp`"
												alt="маммограф"
												loading="lazy"
											/>
										</template>

										<template v-else>
											<img
												v-if="!shedule.image"
												:src="`/storage/default/specialits-schedule.webp`"
												alt="Врач"
												loading="lazy"
											/>

											<a
												class="specialist__head-link"
												@click.prevent="
													$router.push({
														name: 'specialists-profile',
														params: {
															name: shedule.link,
															catagory: null,
														},
													})
												"
												:href="`/specialists/${shedule.link}`"
												v-else
											>
												<img :src="shedule.image" alt="Врач" loading="lazy" />
											</a>
										</template>
									</template>
								</div>

								<div class="shedule__specialist-info">
									<div class="shedule__specialist-specializations">
										{{ shedule.specializations ?? "" }}
									</div>
									<div class="shedule__specialist-name">
										<a
											v-if="shedule.image"
											@click.prevent="
												$router.push({
													name: 'specialists-profile',
													params: {
														name: shedule.link,
														catagory: null,
													},
												})
											"
											:href="`/specialists/${shedule.link}`"
										>
											{{ shedule.name }}
										</a>

										<template v-else>
											{{ shedule.name }}
										</template>
									</div>
								</div>
							</div>

							<div class="shedule__specialist-table" v-if="isMobile">
								<div class="specialist__table-tbody">
									<div class="specialist__table-tr" v-for="day in week" :key="day.id">
										<template v-if="loading.loader.schedule">
											<div class="specialist__table-td">
												<div class="skeleton">&nbsp;</div>
											</div>

											<div class="specialist__table-td days">
												<div class="days__item all">
													<div class="days__item-content skeleton">&nbsp;</div>
												</div>
											</div>
										</template>

										<template v-else>
											<div class="specialist__table-td">
												<div>
													{{ getDateDayNumberMonth(day.date) }}
												</div>
											</div>

											<div class="specialist__table-td days">
												<div
													class="days__item all"
													v-for="clinic in getClinicsWithoutAll"
													:key="clinic.id"
													v-if="activeClinic.name === 'Все'"
												>
													<div
														class="days__item-content"
														v-for="time in getDayTime(
															shedule.id,
															day.date,
															clinic.id
														)"
														v-if="getClinicStatus(shedule.id, clinic.id)"
														:style="getTimeStyle(clinic.id, time)"
													>
														{{ time }}
														<div class="days__content-help" v-if="time !== '-'">
															{{ clinic.name }}
														</div>
													</div>
												</div>
												<div class="days__item" v-else>
													<div
														class="days__item-content"
														v-for="time in getDayTime(
															shedule.id,
															day.date,
															activeClinic.id
														)"
														:style="getTimeStyle(activeClinic.id, time)"
													>
														{{ time }}
													</div>
												</div>
											</div>
										</template>
									</div>
								</div>
							</div>
						</div>
					</td>

					<td class="shedule__table-td" v-for="day in week" :key="day.id">
						<div class="days">
							<template v-if="loading.loader.schedule">
								<div class="days__item">
									<div class="days__item-content" :class="{ skeleton: true }">&nbsp;</div>
								</div>
							</template>

							<template v-else>
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
										{{ time }}
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
										{{ time }}
									</div>
								</div>
							</template>
						</div>
					</td>
				</tr>
				<tr class="shedule__table-tr empty" v-else>
					<td class="shedule__table-td" colspan="10">Ничего нет.</td>
				</tr>
			</tbody>
		</table>
	</Block>
</template>

<script>
import VueSelector from "../../../components/modules/VueSelector.vue";
import VueInput from "../../../components/modules/input/VueInput.vue";
import VueLoader from "../../../components/modules/VueLoader.vue";

import InfoBar from "../../../components/ui/main/InfoBar.vue";
import Block from "../../../components/ui/main/Block.vue";

import api from "../../../mixin/api.js";
import sorted from "../../../services/sorted.js";

import fakeDelay from "../../../mixin/fake-delay.js";

export default {
	components: {
		VueSelector,
		VueInput,
		VueLoader,

		InfoBar,
		Block,
	},
	data() {
		return {
			isMobile: window.matchMedia("(max-width: 630px)").matches,
			selected: null,

			/* Загрузка */
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

			/* Фильтры */
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

			clinics: [
				{
					id: 1,
					name: null,
				},
				{
					id: 2,
					name: null,
				},
				{
					id: 3,
					name: null,
				},
				{
					id: 4,
					name: null,
				},
				{
					id: 5,
					name: null,
				},
			],

			week: [
				{
					id: 1,
					date: null,
				},
				{
					id: 2,
					date: null,
				},
				{
					id: 3,
					date: null,
				},
				{
					id: 4,
					date: null,
				},
				{
					id: 5,
					date: null,
				},
				{
					id: 6,
					date: null,
				},
				{
					id: 7,
					date: null,
				},
				{
					id: 8,
					date: null,
				},
			],
			shedules: [
				{
					id: 1,
					name: null,
					image: null,
					specializations: null,
					weeks: null,
				},
				{
					id: 2,
					name: null,
					image: null,
					specializations: null,
					weeks: null,
				},
				{
					id: 3,
					name: null,
					image: null,
					specializations: null,
					weeks: null,
				},
				{
					id: 4,
					name: null,
					image: null,
					specializations: null,
					weeks: null,
				},
				{
					id: 5,
					name: null,
					image: null,
					specializations: null,
					weeks: null,
				},
				{
					id: 6,
					name: null,
					image: null,
					specializations: null,
					weeks: null,
				},
				{
					id: 7,
					name: null,
					image: null,
					specializations: null,
					weeks: null,
				},
				{
					id: 8,
					name: null,
					image: null,
					specializations: null,
					weeks: null,
				},
				{
					id: 9,
					name: null,
					image: null,
					specializations: null,
					weeks: null,
				},
				{
					id: 10,
					name: null,
					image: null,
					specializations: null,
					weeks: null,
				},
			],

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
				{
					primary: {
						hsl: [267, 82, 58],
						rgb: [140, 61, 236],
						alpha: 1,
					},
					secondary: {
						hsl: [267, 66, 84],
						rgb: [213, 189, 242],
						alpha: 0.3,
					},
				},
				{
					primary: {
						hsl: [191, 82, 58],
						rgb: [61, 204, 100],
						alpha: 1,
					},
					secondary: {
						hsl: [191, 80, 88],
						rgb: [200, 240, 249],
						alpha: 0.3,
					},
				},
			],
		};
	},
	computed: {
		/* Получение всех специализаций */
		getFormatSpecializations() {
			if (this.loading.loader.schedule) return [];

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
			if (this.loading.loader.schedule) return [];

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
			if (this.loading.loader.schedule) return [...this.shedules];

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

			sorted.sortStringByKey("up", filteredShedules, "name");

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

		/* Изменение активной клиники */
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
		/* При скролле */
		handleScroll() {
			if (window.document.documentElement.clientWidth <= 630) {
				this.isMobile = true;
			} else {
				this.isMobile = false;
			}
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                   РАСПИСАНИЕ                      |*/
		/* |___________________________________________________|*/
		/* Получение даты */
		getDateDayNumberMonth(date) {
			let dayDate = new Date(date);

			let options = {
				month: "long",
				day: "numeric",
			};

			return dayDate.toLocaleString("ru", options);
		},

		/* Получение дня недели */
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

		/* Получение статуса клиники */
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
						color: "black",
						border: "1px solid var(--primary-color)",
						backgroundColor: "var(--item-background-color-active)",
					};
				}

				// Если выбрана другая клиника
				return {
					border: "1px solid rgba(255, 255, 255, 0)",
					backgroundColor: "var(--item-background-color-active)",
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
					color: "black",
					border: `1px solid ${this.getColor(number, count, "primary")}`,
					backgroundColor: this.getColor(number, count, "secondary"),
				};
			}

			return {
				border: "1px solid rgba(255, 255, 255, 0)",
				backgroundColor: this.getColor(number, count, "secondary"),
			};
		},

		/* Получение класса у времени */
		getTimeStyle(id, time) {
			let count = 0;
			let number = id;

			// Определение цвета в зависимости от id
			while (number > this.colors.length) {
				number -= this.colors.length;

				count++;
			}

			if (!number || time == "-") {
				return {
					borderColor: "rgba(255, 255, 255, 0)",
					backgroundColor: "rgba(0, 0, 0, 0.05)",
					height: "21px",
					margin: "5px 0px",
				};
			}

			if (this.activeClinic.id === id) {
				return {
					borderColor: this.getColor(number, count, "primary"),
					backgroundColor: this.getColor(number, count, "secondary"),
					margin: "5px 0px",
				};
			}

			return {
				borderColor: this.getColor(number, count, "primary"),
				backgroundColor: this.getColor(number, count, "secondary"),
				margin: "5px 0px",
			};
		},

		/* Формирование цвета в зависимости от id */
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
		fakeDelay(this.$store.getters.timeout, () =>
			api({
				method: "get",
				url: `${this.$store.getters.urlApi}` + `get-shedules-all`,
			})
		).then((response) => {
			if (!response) return;

			try {
				for (let i = 0; i < response.data.result.sheduleClinics.length; i++) {
					this.clinics[i] = response.data.result.sheduleClinics[i];
				}

				this.clinics.splice(response.data.result.sheduleClinics.length, this.clinics.length);

				this.clinics.forEach((clinic) => {
					clinic.status = false;
				});

				this.week = response.data.result.currentDays;
				this.shedules = response.data.result.shedules;

				this.loading.loader.schedule = false;
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

	margin: 0 auto;

	animation: show 0.5s ease-out;
}

.filter__list-item {
	user-select: none;
	cursor: pointer;

	padding: 5px 10px;
	border: 1px solid;
	border-radius: 100px;

	font-size: 18px;

	transition: all 0.15s;
}

.filter__list-item:not(:first-of-type) {
	min-width: 150px;
	min-height: 22px;
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

/* Таблица: список врачей */
.shedule__specialist {
	display: flex;
	flex-direction: column;
	gap: 10px;
}

.shedule__specialist-head {
	display: grid;
	grid-template-columns: 50px 1fr;
	gap: 10px;
}

.specialist__head-img {
	width: 50px;
	height: 50px;
	background-color: var(--skeleton-background-color);

	border-radius: 50%;
}

.specialist__head-link {
	width: 100%;
	height: 100%;

	border-radius: 50%;
}

.specialist__head-img img {
	width: 100%;
	height: 100%;

	border-radius: 100px;
	object-fit: contain;

	animation: show 0.5s ease-out;
}

.shedule__specialist-specializations {
	font-size: 0.875rem;
}

.shedule__specialist-name {
	font-size: 1.125rem;

	transition: all 0.2s;
}

.shedule__specialist-name > a {
	font-size: 1.125rem;
	text-decoration: none;
	color: black;

	transition: all 0.2s;
}

.shedule__specialist-name > a:hover {
	color: var(--primary-color);
}

/* Таблица: таблица */
.shedule__table {
	border-collapse: collapse;

	width: 1350px;

	font-size: 16px;
}

.shedule__specialist-info {
	display: flex;
	flex-direction: column;
	justify-content: center;
}

.shedule__table-th {
	white-space: pre-wrap;
	text-align: center;
	min-width: 110px;
	font-size: 1rem;
	font-weight: normal;
}

.shedule__table-td {
	text-align: left;
	font-size: 1rem;
}

.shedule__table-th,
.shedule__table-td {
	padding: 5px 5px;
	transition: all 0.2s;
}

.shedule__table-td,
.shedule__table-th {
	border-top: 0px;
	border-right: 0px;
	border-bottom: 1px;
	border-left: 0px;
	border-style: solid;
	border-color: rgb(200, 200, 200);

	height: 100%;

	transition: all 0.2s;
}

.shedule__table-th {
	border-top: 0px;
	border-right: 0px;
	border-bottom: 0px;
	border-left: 0px;

	height: 50px;
}

.shedule__table-tr:hover > .shedule__table-td {
	border-top: 0px;
	border-right: 0px;
	border-bottom: 1px;
	border-left: 0px;
	border-style: solid;
	border-color: var(--primary-color);

	background-color: var(--item-background-color-active);
}

.shedule__table-date {
	min-height: 20px;
	border-radius: 10px;

	margin: 5px 0px;
}

/* Таблица: дни */
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

	padding: 10px;
	border-radius: 10px;
	overflow-wrap: break-word;
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

.shedule__table-tr.empty > td {
	height: 30px;
	text-align: center;
	color: black;
}

/* Таблица для телефона */
.shedule__specialist-table {
	display: none;
}

.specialist__table-tbody {
	display: flex;
	flex-direction: column;
	gap: 10px;
}

.specialist__table-tr {
	display: grid;
	grid-template-columns: 100px 1fr;
	gap: 10px;
}

.specialist__table-td > div.skeleton {
	width: 100%;
	height: 100%;

	border-radius: 50px;
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

	.shedule__table {
		width: auto;
	}

	.shedule__table-td:nth-child(9) {
		display: none;
	}

	.shedule__table-th:nth-child(9) {
		display: none;
	}
}

@media screen and (width <= 1330px) {
	.shedule__table-td:nth-child(8) {
		display: none;
	}

	.shedule__table-th:nth-child(8) {
		display: none;
	}
}

@media screen and (width <= 1230px) {
	.shedule__table-td:nth-child(7) {
		display: none;
	}

	.shedule__table-th:nth-child(7) {
		display: none;
	}
}

@media screen and (width <= 1130px) {
	.shedule__table-td:nth-child(7) {
		display: none;
	}

	.shedule__table-th:nth-child(7) {
		display: none;
	}
}

@media screen and (width <= 1030px) {
	.shedule__table-td:nth-child(6) {
		display: none;
	}

	.shedule__table-th:nth-child(6) {
		display: none;
	}
}

@media screen and (width <= 930px) {
	.shedule__table-td:nth-child(5) {
		display: none;
	}

	.shedule__table-th:nth-child(5) {
		display: none;
	}
}

@media screen and (width <= 830px) {
	.shedule__table-td:nth-child(4) {
		display: none;
	}

	.shedule__table-th:nth-child(4) {
		display: none;
	}
}

@media screen and (width <= 730px) {
	.shedule__table-td:nth-child(3) {
		display: none;
	}

	.shedule__table-th:nth-child(3) {
		display: none;
	}
}

@media screen and (width <= 630px) {
	.shedule__table {
		width: 100%;
	}

	.shedule__table-td:nth-child(2) {
		display: none;
	}

	.shedule__table-th:nth-child(2) {
		display: none;
	}

	.shedule__specialist-table {
		display: table;
	}

	.shedule__table > thead {
		display: none;
	}

	.days > .days__item > .days__item-content {
		white-space: normal; /* Важно переопределить возможный nowrap */
		word-wrap: break-word; /* Перенос длинных слов */
		overflow-wrap: break-word; /* Современный аналог word-wrap */
		width: 100%; /* Фиксированная ширина или max-width обязательны */
	}

	.days > .days__item.all > .days__item-content > .days__content-help {
		top: 30px;
		z-index: 1;

		background-color: rgba(0, 0, 0, 0.75);
	}

	.specialist__head-img {
		flex-grow: 1;
		width: 100%;
		height: 100%;
		max-height: 100px;
		max-width: 100px;
	}

	.shedule__specialist-head {
		grid-template-columns: 100px 1fr;
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
