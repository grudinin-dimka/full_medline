<template>
	<vue-input-container>
		<vue-input-label v-if="$slots.label">
			<slot name="label"></slot>
		</vue-input-label>

		<div class="vue-datetime vue-datetime--full" ref="dateTime">
			<!-- Поле ввода -->
			<div class="vue-datetime__input">
				<input
					class="vue-datetime__label"
					v-imask="maskOptions"
					:value="formatModel"
					placeholder="дд:мм:гггг чч:мм"
					@accept="onAccept"
					@complete="onComplete"
				/>

				<input
					class="vue-datetime__input-input"
					type="text"
					readonly
					:value="modelValue"
					@input="$emit('update:modelValue', $event.target.value.trim())"
					@focus="showPicker = true"
					ref="dateInput"
				/>

				<div class="vue-datetime__input-icon" @click="showPicker = !showPicker">
					<svg
						xmlns="http://www.w3.org/2000/svg"
						height="24px"
						viewBox="0 -960 960 960"
						width="24px"
					>
						<path
							d="M200-80q-33 0-56.5-23.5T120-160v-560q0-33 23.5-56.5T200-800h40v-80h80v80h320v-80h80v80h40q33 0 56.5 23.5T840-720v560q0 33-23.5 56.5T760-80H200Zm0-80h560v-400H200v400Zm0-480h560v-80H200v80Zm0 0v-80 80Zm280 240q-17 0-28.5-11.5T440-440q0-17 11.5-28.5T480-480q17 0 28.5 11.5T520-440q0 17-11.5 28.5T480-400Zm-160 0q-17 0-28.5-11.5T280-440q0-17 11.5-28.5T320-480q17 0 28.5 11.5T360-440q0 17-11.5 28.5T320-400Zm320 0q-17 0-28.5-11.5T600-440q0-17 11.5-28.5T640-480q17 0 28.5 11.5T680-440q0 17-11.5 28.5T640-400ZM480-240q-17 0-28.5-11.5T440-280q0-17 11.5-28.5T480-320q17 0 28.5 11.5T520-280q0 17-11.5 28.5T480-240Zm-160 0q-17 0-28.5-11.5T280-280q0-17 11.5-28.5T320-320q17 0 28.5 11.5T360-280q0 17-11.5 28.5T320-240Zm320 0q-17 0-28.5-11.5T600-280q0-17 11.5-28.5T640-320q17 0 28.5 11.5T680-280q0 17-11.5 28.5T640-240Z"
						/>
					</svg>
				</div>
			</div>

			<!-- Выпадающее меню -->
			<div v-if="showPicker" class="vue-datetime__dropdown">
				<div class="vue-datetime__date">
					<div class="vue-datetime__year-controls">
						<div class="vue-datetime__year-buttons">
							<button @click="prevDateYear">
								<svg
									width="18"
									height="18"
									viewBox="0 0 24 24"
									xmlns="http://www.w3.org/2000/svg"
								>
									<path
										d="M11 18L5 12L11 6L12.4 7.4L7.825 12L12.4 16.6L11 18ZM17.6 18L11.6 12L17.6 6L19 7.4L14.425 12L19 16.6L17.6 18Z"
									/>
								</svg>
							</button>

							<button @click="prevDateMonth">
								<svg
									width="18"
									height="18"
									viewBox="0 0 24 24"
									xmlns="http://www.w3.org/2000/svg"
								>
									<path d="M14 18L8 12L14 6L15.4 7.4L10.8 12L15.4 16.6L14 18Z" />
								</svg>
							</button>
						</div>

						<span class="year">
							{{ getDateName(new Date(currentYear, currentMonth - 1), "short") }}
						</span>

						<div class="vue-datetime__year-buttons">
							<button @click="nextDateMonth">
								<svg
									width="18"
									height="18"
									viewBox="0 0 24 24"
									xmlns="http://www.w3.org/2000/svg"
								>
									<path d="M12.6 12L8 7.4L9.4 6L15.4 12L9.4 18L8 16.6L12.6 12Z" />
								</svg>
							</button>

							<button @click="nextDateYear">
								<svg
									width="18"
									height="18"
									viewBox="0 0 24 24"
									xmlns="http://www.w3.org/2000/svg"
								>
									<path
										d="M9.575 12L5 7.4L6.4 6L12.4 12L6.4 18L5 16.6L9.575 12ZM16.175 12L11.6 7.4L13 6L19 12L13 18L11.6 16.6L16.175 12Z"
									/>
								</svg>
							</button>
						</div>
					</div>

					<div class="vue-datetime__calendar">
						<div class="vue-datetime__calendar-tr">
							<div class="vue-datetime__calendar-th">Пн</div>
							<div class="vue-datetime__calendar-th">Вт</div>
							<div class="vue-datetime__calendar-th">Ср</div>
							<div class="vue-datetime__calendar-th">Чт</div>
							<div class="vue-datetime__calendar-th">Пт</div>
							<div class="vue-datetime__calendar-th vue-datetime__calendar-th--weekend">
								Сб
							</div>
							<div class="vue-datetime__calendar-th vue-datetime__calendar-th--weekend">
								Вс
							</div>
						</div>

						<div class="vue-datetime__calendar-tr" v-for="week in 6" :key="week">
							<template v-for="day in 7" :key="day">
								<div class="vue-datetime__calendar-td">
									<button
										class="date-btn"
										:class="getStyleOfDay(week, day)"
										@click="changeDay(week, day)"
									>
										{{ getDay(week, day) }}
									</button>
								</div>
							</template>
						</div>
					</div>

					<div class="vue-datetime__buttons" :style="{ justifyContent: 'flex-start' }">
						<button class="vue-datetime__buttons-subbutton" @click="clearDate">
							Удалить
						</button>
					</div>
				</div>

				<div class="vue-datetime__time">
					<div class="vue-time__grid">
						<div class="vue-time__grid-item" ref="hours">
							<button
								v-for="(h, i) in hours"
								:key="h"
								@click="selectHour(h)"
								:class="['time-btn', isSelectedHour(h) ? 'active' : '']"
							>
								{{ h }}
							</button>
						</div>
						<div class="vue-time__grid-item" ref="minutes">
							<button
								v-for="(m, i) in minutes"
								:key="m"
								@click="selectMinute(m)"
								:class="['time-btn', isSelectedMinute(m) ? 'active' : '']"
							>
								{{ m }}
							</button>
						</div>
					</div>

					<div class="vue-datetime__buttons" :style="{ justifyContent: 'flex-end' }">
						<button class="vue-datetime__buttons-subbutton" @click="setDateTimeCurrent">
							Сейчас
						</button>
					</div>
				</div>
			</div>
		</div>

		<VueInputError v-if="error" :is-top="!$slots.label">
			<slot name="error"></slot>
		</VueInputError>
	</vue-input-container>
</template>

<script>
import { IMaskDirective } from "vue-imask";
import { IMask } from "vue-imask";

import VueInputContainer from "./ui/VueInputContainer.vue";
import VueInputLabel from "./ui/VueInputLabel.vue";
import VueInputError from "./ui/VueInputError.vue";

export default {
	name: "VueDate",
	components: { VueInputContainer, VueInputLabel, VueInputError },
	props: {
		modelValue: {
			type: [String, Number, Boolean, null, Object],
			default: "",
			required: true,
		},
		error: {
			type: Boolean,
			default: false,
		},
	},
	directives: {
		imask: IMaskDirective,
	},
	data() {
		return {
			/* Общие */
			showPicker: false,
			currentYear: this.modelValue
				? new Date(this.modelValue).getFullYear()
				: new Date().getFullYear(),

			/* Месяц */
			months: [
				{
					short: "Янв",
					long: "Январь",
				},
				{
					short: "Фев",
					long: "Февраль",
				},
				{
					short: "Мар",
					long: "Март",
				},
				{
					short: "Апр",
					long: "Апрель",
				},
				{
					short: "Май",
					long: "Май",
				},
				{
					short: "Июн",
					long: "Июнь",
				},
				{
					short: "Июл",
					long: "Июль",
				},
				{
					short: "Авг",
					long: "Август",
				},
				{
					short: "Сен",
					long: "Сентябрь",
				},
				{
					short: "Окт",
					long: "Октябрь",
				},
				{
					short: "Ноя",
					long: "Ноябрь",
				},
				{
					short: "Дек",
					long: "Декабрь",
				},
			],

			/* Дата */
			currentMonth: this.modelValue
				? new Date(this.modelValue).getMonth() + 1
				: new Date().getMonth() + 1,

			/* Время */
			hours: [
				"00",
				"01",
				"02",
				"03",
				"04",
				"05",
				"06",
				"07",
				"08",
				"09",
				"10",
				"11",
				"12",
				"13",
				"14",
				"15",
				"16",
				"17",
				"18",
				"19",
				"20",
				"21",
				"22",
				"23",
			],

			minutes: [
				"00",
				"01",
				"02",
				"03",
				"04",
				"05",
				"06",
				"07",
				"08",
				"09",
				"10",
				"11",
				"12",
				"13",
				"14",
				"15",
				"16",
				"17",
				"18",
				"19",
				"20",
				"21",
				"22",
				"23",
				"24",
				"25",
				"26",
				"27",
				"28",
				"29",
				"30",
				"31",
				"32",
				"33",
				"34",
				"35",
				"36",
				"37",
				"38",
				"39",
				"40",
				"41",
				"42",
				"43",
				"44",
				"45",
				"46",
				"47",
				"48",
				"49",
				"50",
				"51",
				"52",
				"53",
				"54",
				"55",
				"56",
				"57",
				"58",
				"59",
				"60",
			],

			maskOptions: {
				mask: "DD.MM.YYYY HH:mm", // шаблон
				blocks: {
					DD: {
						mask: IMask.MaskedRange,
						from: 0,
						to: 31,
						maxLength: 2,
						placeholderChar: "д",
					},
					MM: {
						mask: IMask.MaskedRange,
						from: 1,
						to: 12,
						maxLength: 2,
						placeholderChar: "м",
					},
					YYYY: {
						mask: IMask.MaskedRange,
						from: 0,
						to: 9999,
						maxLength: 4,
						placeholderChar: "г",
					},
					HH: {
						mask: IMask.MaskedRange,
						from: 0,
						to: 23,
						maxLength: 2,
						placeholderChar: "-",
					},
					mm: {
						mask: IMask.MaskedRange,
						from: 0,
						to: 59,
						maxLength: 2,
						placeholderChar: "-",
					},
				},
				autofix: true, // исправляет 29:99 → 23:59
				lazy: false,
			},
		};
	},
	watch: {
		showPicker(val) {
			if (val) {
				this.scrollToSelected();
			}
		},
	},
	computed: {
		formatModel() {
			if (!this.modelValue) return "";

			let [date, time] = this.modelValue.split(" ");

			let [year, month, day] = date.split("-");
			let [hour, minute, second] = time.split("-");

			return `${day}.${month}.${year} ${hour}:${minute}`;
		},
	},

	methods: {
		/* Закрытие месяца */
		handleClickOutside(e) {
			if (this.$refs.dateTime && !this.$refs.dateTime.contains(e.target)) {
				this.showPicker = false;
			}
		},

		onAccept(e) {
			const maskRef = e.detail;

			if (/дд.мм.гггг чч:мм/.test(maskRef.value)) {
				this.$emit("update:modelValue", "");

				this.$nextTick(() => {
					const input = e.target;

					if (input && input.setSelectionRange) {
						input.setSelectionRange(0, 0);
					}
				});
			}
		},

		onComplete(e) {
			const maskRef = e.detail;

			if (/\d{2}.\d{2}.\d{4} \d{2}:\d{2}/.test(maskRef.value)) {
				let [date, time] = maskRef.value.split(" ");

				let [day, month, year] = date.split(".");
				let [hour, minute] = time.split(":"); // 2018-06-07T00:00

				this.currentYear = Number(year);
				this.currentMonth = Number(month);
				this.$emit("update:modelValue", `${year}-${month}-${day} ${hour}:${minute}`);
			}
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                    Календарь                      |*/
		/* |___________________________________________________|*/
		/* Получение названия месяца */
		getDateName(value, type = "long") {
			if (!value) {
				return "-------- ----";
			}

			let month = "";

			switch (type) {
				case "long":
					month = new Date(value).toLocaleString("default", {
						month: "long",
						year: "numeric",
					});
					break;
				case "short":
					month = new Date(value).toLocaleString("default", {
						month: "short",
						year: "numeric",
					});
					break;
			}

			month = month.charAt(0).toUpperCase() + month.slice(1);

			return month;
		},

		getDate(value) {
			if (!value) {
				return "дд.мм.гггг";
			}

			let month = new Date(value).toLocaleString("default", {
				day: "numeric",
				month: "numeric",
				year: "numeric",
			});

			return month;
		},

		getTime(value) {
			if (!value) {
				return "--:--";
			}

			let month = new Date(value).toLocaleString("default", {
				hour: "numeric",
				minute: "numeric",
			});

			return month;
		},

		/* Возвращает день */
		getDay(week, day) {
			const firstDay = this.getFirstDayOfMonth(this.currentYear, this.currentMonth - 1);
			const daysInMonth = this.getDaysInMonth(this.currentYear, this.currentMonth - 1);

			const currentDay = (week - 1) * 7 + day - firstDay + 1;

			if (currentDay > 0 && currentDay <= daysInMonth) {
				return currentDay;
			}

			return "";
		},

		/* Получение стиля дня */
		getStyleOfDay(week, day) {
			let dayOfMonth = this.getDay(week, day);

			// Если день не входит в текущий месяц
			if (dayOfMonth === "") {
				return "empty";
			}

			// Проверка на текущий день
			if (
				dayOfMonth == new Date(this.modelValue).toLocaleString("default", { day: "numeric" }) &&
				this.currentYear ==
					new Date(this.modelValue).toLocaleString("default", { year: "numeric" }) &&
				this.currentMonth ==
					new Date(this.modelValue).toLocaleString("default", { month: "numeric" })
			) {
				return "active";
			}

			return "";
		},

		/* Возвращает первый день месяца */
		getFirstDayOfMonth(year, month) {
			return new Date(year, month, 1).getDay() || 7; // Возвращает 1 для понедельника, 2 для вторника и т.д.
		},

		/* Возвращает количество дней в текущем месяце. */
		getDaysInMonth(year, month) {
			return new Date(year, month + 1, 0).getDate();
		},

		/* Выбор дня */
		changeDay(day, week) {
			if (!this.modelValue) {
				this.$emit(
					"update:modelValue",
					`${this.currentYear}-${String(this.currentMonth).padStart(2, "0")}-${String(
						day
					).padStart(2, "0")} 00:00:00`
				);

				return;
			}

			let dayOfMonth = this.getDay(day, week);

			if (!dayOfMonth) {
				return;
			}

			this.$emit(
				"update:modelValue",
				`${this.currentYear}-${String(this.currentMonth).padStart(2, "0")}-${String(
					dayOfMonth
				).padStart(2, "0")} ${this.modelValue.split(" ")[1]}`
			);
		},

		/* Переход на предыдущий год */
		prevDateYear() {
			this.currentYear--;
		},

		/* Переход на предыдущий месяц */
		prevDateMonth() {
			this.currentMonth--;

			if (this.currentMonth <= 0) {
				this.currentMonth = 12;
				this.currentYear--;
			}
		},

		/* Переход на следующий месяц */
		nextDateMonth() {
			this.currentMonth++;

			if (this.currentMonth > 12) {
				this.currentMonth = 1;
				this.currentYear++;
			}
		},

		/* Переход на следующий год */
		nextDateYear() {
			this.currentYear++;
		},

		/* Очистка даты */
		clearDate() {
			this.$emit("update:modelValue", "");

			this.showPicker = false;
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                      Время                        |*/
		/* |___________________________________________________|*/
		scrollToSelected() {
			this.$nextTick(() => {
				// часы
				const hoursContainer = this.$refs.hours;
				if (hoursContainer) {
					const selected = hoursContainer.querySelector(".time-btn.active");

					if (selected) {
						hoursContainer.scrollTop =
							selected.offsetTop -
							hoursContainer.clientHeight / 2 +
							selected.clientHeight / 2;
					}
				}

				// минуты
				const minutesContainer = this.$refs.minutes;
				if (minutesContainer) {
					const selected = minutesContainer.querySelector(".time-btn.active");

					if (selected) {
						minutesContainer.scrollTop =
							selected.offsetTop -
							minutesContainer.clientHeight / 2 +
							selected.clientHeight / 2;
					}
				}
			});
		},

		/* Очистка Времяа */
		clearTIme() {
			this.$emit("update:modelValue", "");

			this.showPicker = false;
		},

		/* Выбор текущего Времяа */
		setDateTimeCurrent() {
			let date = new Date();

			this.$emit(
				"update:modelValue",
				`${date.toISOString().slice(0, 10)} ${date.toLocaleTimeString().slice(0, 5)}`
			);

			this.showPicker = false;
		},

		/* Выбор Времяа */
		selectHour(hour) {
			let date = new Date();

			if (!this.modelValue) {
				this.$emit("update:modelValue", `${date.toISOString().slice(0, 10)} ${hour}:00`);

				return;
			}

			let [modelDate, modelTime] = this.modelValue.split(" ");

			let [modelYear, modelMonth, modelDay] = modelDate.split("-");
			let [modelHour, modelMinute] = modelTime.split(":");
			
			this.$emit(
				"update:modelValue",
				`${modelYear}-${modelMonth}-${modelDay} ${hour}:${modelMinute}`
			);
		},

		/* Выбор Времяа */
		selectMinute(minute) {
			let date = new Date();

			if (!this.modelValue) {
				this.$emit("update:modelValue", `${date.toISOString().slice(0, 10)} 00:${minute}`);

				return;
			}

			let [modelDate, modelTime] = this.modelValue.split(" ");

			let [modelYear, modelMonth, modelDay] = modelDate.split("-");
			let [modelHour, modelMinute] = modelTime.split(":");
			
			this.$emit(
				"update:modelValue",
				`${modelYear}-${modelMonth}-${modelDay} ${modelHour}:${minute}`
			);
		},

		/* Проверка выбранного времени: часы */
		isSelectedHour(hour) {
			let [modelHour, modelMinute] = this.modelValue.slice(-5).split(":");

			return hour === modelHour;
		},

		/* Проверка выбранной времени: минуты */
		isSelectedMinute(minute) {
			let [modelHour, modelMinute] = this.modelValue.slice(-5).split(":");

			return minute === modelMinute;
		},
	},
	mounted() {
		document.addEventListener("click", this.handleClickOutside);
	},
	beforeUnmount() {
		document.removeEventListener("click", this.handleClickOutside);
	},
};
</script>

<style>
.vue-datetime {
	flex-grow: 1;

	position: relative;
	display: flex;
	flex-direction: column;
	gap: var(--input-gap);

	width: 100%;
}

.vue-datetime__input {
	position: relative;
	display: flex;
	align-items: center;
}

.vue-datetime__label {
	cursor: text;
	position: absolute;
	z-index: var(--datetime-label-z-index);
	left: var(--datetime-label-left);

	border: var(--datetime-label-border);
	border-radius: var(--datetime-label-border-radius);
	caret-color: var(--datetime-label-caret-color);
	display: flex;
	align-items: center;
	height: var(--datetime-label-height);

	width: var(--datetime-label-width);
	font: var(--datetime-label-font);

	background-color: var(--datetime-label-background-color);
}

.vue-datetime__label:focus {
	border: var(--datetime-label-border-focus) !important;
}

.vue-datetime__input-input {
	caret-color: var(--input-caret-color);
	box-sizing: border-box;

	border-radius: var(--input-border-radius);
	border: var(--input-border);

	height: var(--input-height);

	color: var(--input-color);
	font: var(--input-font-size) var(--input-font-family);
	background-color: var(--input-background-color);

	width: 100%;

	transition: var(--input-transition);
	padding: var(--input-padding);
}

.vue-datetime__input-input:focus,
.vue-datetime__label:focus + .vue-datetime__input-input {
	border: var(--input-border-focus);
}

.vue-datetime__input-icon {
	cursor: pointer;
	position: absolute;
	right: 10px;

	font-size: 16px;

	fill: var(--input-icon-color);
}

.vue-datetime__input-icon:hover {
	fill: var(--input-icon-hover-color);
}

/* Выпадающее меню */
.vue-datetime__dropdown {
	position: absolute;
	z-index: var(--datetime-dropdown-z-index);
	top: var(--datetime-dropdown-top);
	display: flex;
	flex-direction: row;
	gap: var(--datetime-dropdown-gap);

	border: var(--datetime-dropdown-border);
	border-radius: var(--datetime-dropdown-border-radius);
	padding: var(--datetime-dropdown-padding);

	width: var(--datetime-dropdown-width);
	background: var(--datetime-dropdown-background-color);

	box-shadow: var(--datetime-dropdown-box-shadow);
	animation: var(--datetime-dropdown-animation);
}

/* Дата */
.vue-datetime__date {
	display: flex;
	flex-direction: column;
	gap: var(--datetime-date-gap);

	width: var(--datetime-date-width);
}

/* Дата: Контролы года */
.vue-datetime__year-controls {
	display: flex;
	justify-content: space-between;
	align-items: center;
	gap: var(--datetime-year-controls-gap);
}

.vue-datetime__year-controls button {
	cursor: pointer;
	display: flex;
	justify-content: center;
	align-items: center;

	border: var(--datetime-year-button-border);
	border-radius: var(--datetime-year-button-border-radius);

	width: var(--datetime-year-button-width);
	min-height: var(--datetime-year-button-height);
	max-height: var(--datetime-year-button-height);
	background: var(--datetime-year-button-background-color);
	color: var(--datetime-year-button-color);

	transition: all 0.2s ease;
}
.vue-datetime__year-controls button:hover {
	border: var(--datetime-year-button-border--hover);
	background: var(--datetime-year-button-background-color--hover);

	color: var(--datetime-year-button-color--hover);
}

/* Дата: Сетка */
.vue-datetime__grid {
	display: grid;
	grid-template-columns: repeat(3, 1fr);
	gap: var(--datetime-grid-gap);
}

.month-btn {
	display: flex;
	align-items: center;
	justify-content: center;
	cursor: pointer;

	border: var(--datetime-button-border);
	border-radius: var(--datetime-button-border-radius);
	padding: var(--datetime-button-padding);

	color: var(--datetime-button-color);
	background: var(--datetime-button-background-color);
	font-size: var(--datetime-button-font-size);

	transition: var(--datetime-button-transition);
}

.month-btn:hover {
	border: var(--datetime-button-border-hover);
	background: var(--datetime-button-background-color-hover);
}

.month-btn.active {
	border: var(--datetime-button-active-border);
	background: var(--datetime-button-active-background-color);

	color: var(--datetime-button-active-color);
}

/* Дата: Кнопки */
.vue-datetime__buttons {
	display: flex;
	justify-content: space-between;
}

.vue-datetime__buttons-subbutton {
	cursor: pointer;

	border: var(--datetime-subbutton-border);
	border-radius: var(--datetime-subbutton-border-radius);
	padding: var(--datetime-subbutton-padding);

	color: var(--datetime-subbutton-color);
	background: var(--datetime-subbutton-background-color);
	font-size: var(--datetime-subbutton-font-size);

	transition: var(--datetime-subbutton-transition);
}

.vue-datetime__buttons-subbutton:hover {
	background: var(--datetime-subbutton-background-color-hover);
}

/* Дата: Контролы года */
.vue-datetime__year-buttons {
	display: flex;
	justify-content: space-between;
	gap: var(--datetime-year-buttons-gap);
}

/* Дата: Календарь */
.vue-datetime__calendar {
	display: flex;
	flex-direction: column;
	gap: var(--datetime-calendar-gap);
}

.vue-datetime__calendar-tr {
	display: grid;
	grid-template-columns: repeat(7, 1fr);

	gap: var(--datetime-calendar-tr-gap);
}

.vue-datetime__calendar-th {
	display: flex;
	justify-content: center;
	align-items: center;

	width: var(--datetime-calendar-th-width);
	height: var(--datetime-calendar-th-height);

	color: var(--datetime-calendar-th-color);
	font: var(--datetime-calendar-th-font);
}

.vue-datetime__calendar-th.vue-datetime__calendar-th--weekend {
	color: var(--datetime-calendar-th-weekend-color);
}

.date-btn {
	display: flex;
	justify-content: center;
	align-items: center;
	flex-grow: 1;
	cursor: pointer;

	border: var(--datetime-button-border);
	border-radius: var(--datetime-button-border-radius);
	padding: var(--datetime-button-padding);

	width: var(--datetime-button-width);
	height: var(--datetime-button-height);
	color: var(--datetime-button-color);
	background-color: var(--datetime-button-background-color);
	font: var(--datetime-button-font);

	transition: var(--datetime-button-transition);
}

.date-btn:hover {
	border: var(--datetime-button-border--hover);
	color: var(--datetime-button-color--hover);
	background-color: var(--datetime-button-background-color--hover);
}

.date-btn.active {
	border: var(--datetime-button-border--active);
	color: var(--datetime-button-color--active);
	background-color: var(--datetime-button-background-color--active);
}

.date-btn.active:hover {
	border: var(--datetime-button-border--active--hover);
	color: var(--datetime-button-color--active--hover);
	background-color: var(--datetime-button-background-color--active--hover);
}

.date-btn.empty {
	cursor: default;

	border: var(--datetime-button-border--empty);
	background-color: var(--datetime-button-background-color--empty);
}

.date-btn.empty:hover {
	border: var(--datetime-button-border--empty--hover);
	background-color: var(--datetime-button-background-color--empty--hover);
}

/* Время */
.vue-datetime__time {
	display: flex;
	flex-direction: column;
	gap: var(--datetime-time-gap);

	width: var(--datetime-time-width);
}
</style>
