<template>
	<vue-input-container>
		<vue-input-label v-if="$slots.label">
			<slot name="label"></slot>
		</vue-input-label>

		<template v-if="type === 'month'">
			<div class="vue-date vue-date--month" ref="dateMonth">
				<!-- Поле ввода -->
				<div class="vue-date__input">
					<div class="vue-date__input-label" @click="showPicker = true">
						{{ getDateName(modelValue) }}
					</div>

					<input
						class="vue-date__input-input"
						type="text"
						readonly
						:value="modelValue"
						@input="$emit('update:modelValue', $event.target.value.trim())"
						@focus="showPicker = true"
						ref="dateInput"
					/>

					<div class="vue-date__input-icon" @click="showPicker = !showPicker">
						<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px">
							<path
								d="M200-80q-33 0-56.5-23.5T120-160v-560q0-33 23.5-56.5T200-800h40v-80h80v80h320v-80h80v80h40q33 0 56.5 23.5T840-720v560q0 33-23.5 56.5T760-80H200Zm0-80h560v-400H200v400Zm0-480h560v-80H200v80Zm0 0v-80 80Zm280 240q-17 0-28.5-11.5T440-440q0-17 11.5-28.5T480-480q17 0 28.5 11.5T520-440q0 17-11.5 28.5T480-400Zm-160 0q-17 0-28.5-11.5T280-440q0-17 11.5-28.5T320-480q17 0 28.5 11.5T360-440q0 17-11.5 28.5T320-400Zm320 0q-17 0-28.5-11.5T600-440q0-17 11.5-28.5T640-480q17 0 28.5 11.5T680-440q0 17-11.5 28.5T640-400ZM480-240q-17 0-28.5-11.5T440-280q0-17 11.5-28.5T480-320q17 0 28.5 11.5T520-280q0 17-11.5 28.5T480-240Zm-160 0q-17 0-28.5-11.5T280-280q0-17 11.5-28.5T320-320q17 0 28.5 11.5T360-280q0 17-11.5 28.5T320-240Zm320 0q-17 0-28.5-11.5T600-280q0-17 11.5-28.5T640-320q17 0 28.5 11.5T680-280q0 17-11.5 28.5T640-240Z"
							/>
						</svg>
					</div>
				</div>

				<!-- Выпадающее меню -->
				<div v-if="showPicker" class="vue-date__dropdown">
					<div class="vue-date__year-controls">
						<button @click="prevYear">
							<svg width="18" height="18" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
								<path d="M14 18L8 12L14 6L15.4 7.4L10.8 12L15.4 16.6L14 18Z" />
							</svg>
						</button>

						<span class="year">{{ currentYear }}</span>

						<button @click="nextYear">
							<svg width="18" height="18" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
								<path d="M12.6 12L8 7.4L9.4 6L15.4 12L9.4 18L8 16.6L12.6 12Z" />
							</svg>
						</button>
					</div>

					<div class="vue-date__grid">
						<button
							v-for="(m, i) in months"
							:key="m"
							@click="selectMonth(i)"
							:class="['month-btn', isSelected(i) ? 'active' : '']"
						>
							{{ m.short }}
						</button>
					</div>

					<div class="vue-date__buttons">
						<button class="vue-date__buttons-subbutton" @click="clearDate">Удалить</button>

						<button class="vue-date__buttons-subbutton" @click="setMonthCurrent">Сейчас</button>
					</div>
				</div>
			</div>
		</template>

		<template v-else-if="type === 'date'">
			<div class="vue-date vue-date--full" ref="dateFull">
				<!-- Поле ввода -->
				<div class="vue-date__input">
					<input
						class="vue-date__input-label"
						v-imask="maskOptions"
						:value="formatModel"
						placeholder="дд:мм:гггг"
						@accept="onAccept"
						@complete="onComplete"
					/>

					<input
						class="vue-date__input-input"
						type="text"
						readonly
						:value="modelValue"
						@input="$emit('update:modelValue', $event.target.value.trim())"
						@focus="showPicker = true"
						ref="dateInput"
					/>

					<div class="vue-date__input-icon" @click="showPicker = !showPicker">
						<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px">
							<path
								d="M200-80q-33 0-56.5-23.5T120-160v-560q0-33 23.5-56.5T200-800h40v-80h80v80h320v-80h80v80h40q33 0 56.5 23.5T840-720v560q0 33-23.5 56.5T760-80H200Zm0-80h560v-400H200v400Zm0-480h560v-80H200v80Zm0 0v-80 80Zm280 240q-17 0-28.5-11.5T440-440q0-17 11.5-28.5T480-480q17 0 28.5 11.5T520-440q0 17-11.5 28.5T480-400Zm-160 0q-17 0-28.5-11.5T280-440q0-17 11.5-28.5T320-480q17 0 28.5 11.5T360-440q0 17-11.5 28.5T320-400Zm320 0q-17 0-28.5-11.5T600-440q0-17 11.5-28.5T640-480q17 0 28.5 11.5T680-440q0 17-11.5 28.5T640-400ZM480-240q-17 0-28.5-11.5T440-280q0-17 11.5-28.5T480-320q17 0 28.5 11.5T520-280q0 17-11.5 28.5T480-240Zm-160 0q-17 0-28.5-11.5T280-280q0-17 11.5-28.5T320-320q17 0 28.5 11.5T360-280q0 17-11.5 28.5T320-240Zm320 0q-17 0-28.5-11.5T600-280q0-17 11.5-28.5T640-320q17 0 28.5 11.5T680-280q0 17-11.5 28.5T640-240Z"
							/>
						</svg>
					</div>
				</div>

				<!-- Выпадающее меню -->
				<div v-if="showPicker" class="vue-date__dropdown">
					<div class="vue-date__year-controls">
						<div class="vue-date__year-buttons">
							<button @click="prevDateYear">
								<svg width="18" height="18" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
									<path
										d="M11 18L5 12L11 6L12.4 7.4L7.825 12L12.4 16.6L11 18ZM17.6 18L11.6 12L17.6 6L19 7.4L14.425 12L19 16.6L17.6 18Z"
									/>
								</svg>
							</button>

							<button @click="prevDateMonth">
								<svg width="18" height="18" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
									<path d="M14 18L8 12L14 6L15.4 7.4L10.8 12L15.4 16.6L14 18Z" />
								</svg>
							</button>
						</div>

						<span class="year">{{ getDateName(new Date(currentYear, currentMonth - 1), "short") }}</span>

						<div class="vue-date__year-buttons">
							<button @click="nextDateMonth">
								<svg width="18" height="18" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
									<path d="M12.6 12L8 7.4L9.4 6L15.4 12L9.4 18L8 16.6L12.6 12Z" />
								</svg>
							</button>

							<button @click="nextDateYear">
								<svg width="18" height="18" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
									<path
										d="M9.575 12L5 7.4L6.4 6L12.4 12L6.4 18L5 16.6L9.575 12ZM16.175 12L11.6 7.4L13 6L19 12L13 18L11.6 16.6L16.175 12Z"
									/>
								</svg>
							</button>
						</div>
					</div>

					<div class="vue-date__calendar">
						<div class="vue-date__calendar-tr">
							<div class="vue-date__calendar-th">Пн</div>
							<div class="vue-date__calendar-th">Вт</div>
							<div class="vue-date__calendar-th">Ср</div>
							<div class="vue-date__calendar-th">Чт</div>
							<div class="vue-date__calendar-th">Пт</div>
							<div class="vue-date__calendar-th vue-date__calendar-th--weekend">Сб</div>
							<div class="vue-date__calendar-th vue-date__calendar-th--weekend">Вс</div>
						</div>

						<div class="vue-date__calendar-tr" v-for="week in 6" :key="week">
							<template v-for="day in 7" :key="day">
								<div class="vue-date__calendar-td">
									<button class="date-btn" :class="getStyleOfDay(week, day)" @click="changeDay(week, day)">
										{{ getDay(week, day) }}
									</button>
								</div>
							</template>
						</div>
					</div>

					<div class="vue-date__buttons">
						<button class="vue-date__buttons-subbutton" @click="clearDate">Удалить</button>

						<button class="vue-date__buttons-subbutton" @click="setDateCurrent">Сейчас</button>
					</div>
				</div>
			</div>
		</template>

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
		type: {
			type: String,
			default: "date",
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
			currentYear: this.modelValue ? new Date(this.modelValue).getFullYear() : new Date().getFullYear(),

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
			currentMonth: this.modelValue ? new Date(this.modelValue).getMonth() + 1 : new Date().getMonth() + 1,

			maskOptions: {
				mask: "DD.MM.YYYY", // шаблон
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
				},
				autofix: true, // исправляет 29:99 → 23:59
				lazy: false,
			},
		};
	},
	watch: {
		modelValue() {
			this.currentYear = new Date(this.modelValue).getFullYear();
			this.currentMonth = new Date(this.modelValue).getMonth() + 1;
		},
	},
	computed: {
		formatModel() {
			if (!this.modelValue) return "";

			let [year, month, day] = this.modelValue.split("-");

			return `${day}.${month}.${year}`;
		},
	},
	methods: {
		/* Закрытие месяца */
		handleClickOutside(e) {
			let refName = "";

			switch (this.type) {
				case "month":
					refName = "dateMonth";
					break;
				case "date":
					refName = "dateFull";
					break;
			}

			if (this.$refs[refName] && !this.$refs[refName].contains(e.target)) {
				this.showPicker = false;
			}
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                      Месяц                        |*/
		/* |___________________________________________________|*/
		/* Предыдущий год */
		prevYear() {
			this.currentYear--;
		},

		/* Следующий год */
		nextYear() {
			this.currentYear++;
		},

		/* Очистка месяца */
		clearDate() {
			this.$emit("update:modelValue", "");

			this.showPicker = false;
		},

		/* Выбор текущего месяца */
		setMonthCurrent() {
			let date = new Date();
			this.$emit("update:modelValue", `${date.getFullYear()}-${date.getMonth() + 1}`);

			this.showPicker = false;
		},

		/* Выбор месяца */
		selectMonth(index) {
			const monthNum = String(index + 1).padStart(2, "0");
			this.$emit("update:modelValue", `${this.currentYear}-${monthNum}`);
		},

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

		/* Проверка выбранного месяца */
		isSelected(index) {
			let [year, month] = this.modelValue.split("-");

			return parseInt(year, 10) === this.currentYear && parseInt(month, 10) === index + 1;
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                    Календарь                      |*/
		/* |___________________________________________________|*/
		onAccept(e) {
			const maskRef = e.detail;

			if (/дд.мм.гггг/.test(maskRef.value)) {
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

			if (/\d{2}.\d{2}.\d{4}/.test(maskRef.value)) {
				let [day, month, year] = maskRef.value.split(".");

				this.currentYear = Number(year);
				this.currentMonth = Number(month);
				this.$emit("update:modelValue", `${year}-${month}-${day}`);
			}
		},

		getDateFull(value) {
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
				this.currentYear == new Date(this.modelValue).toLocaleString("default", { year: "numeric" }) &&
				this.currentMonth == new Date(this.modelValue).toLocaleString("default", { month: "numeric" })
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
			let dayOfMonth = this.getDay(day, week);

			if (!dayOfMonth) {
				return;
			}

			this.$emit(
				"update:modelValue",
				`${this.currentYear}-${String(this.currentMonth).padStart(2, "0")}-${String(dayOfMonth).padStart(2, "0")}`
			);

			this.showPicker = false;
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

		/* Выбор текущей даты */
		setDateCurrent() {
			this.$emit("update:modelValue", new Date().toISOString().slice(0, 10));

			this.showPicker = false;
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
/* Месяц */
.vue-date {
	flex-grow: 1;

	position: relative;
	display: flex;
	flex-direction: column;
	gap: var(--input-gap);

	width: 100%;
}

.vue-date__input {
	position: relative;
	display: flex;
	align-items: center;
}

.vue-date__input-label {
	cursor: text;
	position: absolute;
	z-index: var(--date-label-z-index);
	left: var(--date-label-left);

	border: var(--date-label-border);
	border-radius: var(--date-label-border-radius);
	caret-color: var(--date-label-caret-color);
	display: flex;
	align-items: center;
	height: var(--date-label-height);

	width: var(--date-label-width);
	font: var(--date-label-font);
	background-color: var(--date-label-background-color);
}

.vue-date__input-label:focus {
	border: var(--date-label-border-focus) !important;
}

.vue-date__input-input {
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

.vue-date__input-input:focus,
.vue-date__input-label:focus + .vue-date__input-input {
	border: var(--input-border-focus);
}

.vue-date__input-icon {
	cursor: pointer;
	position: absolute;
	right: 10px;

	font-size: 16px;

	fill: var(--input-icon-color);
}

.vue-date__input-icon:hover {
	fill: var(--input-icon-hover-color);
}

/* Месяц: Выпадающее меню */
.vue-date__dropdown {
	position: absolute;
	z-index: var(--month-dropdown-z-index);
	top: var(--month-dropdown-top);
	display: flex;
	flex-direction: column;
	gap: var(--month-dropdown-gap);

	border: var(--month-dropdown-border);
	border-radius: var(--month-dropdown-border-radius);
	padding: var(--month-dropdown-padding);

	width: var(--month-dropdown-width);
	background: var(--month-dropdown-background-color);

	box-shadow: var(--month-dropdown-box-shadow);
	animation: var(--month-dropdown-animation);
}

/* Месяц: Контролы года */
.vue-date__year-controls {
	display: flex;
	justify-content: space-between;
	align-items: center;
	gap: var(--date-year-controls-gap);
}

.vue-date__year-controls button {
	cursor: pointer;
	display: flex;
	justify-content: center;
	align-items: center;

	border: var(--date-year-button-border);
	border-radius: var(--date-year-button-border-radius);

	width: var(--date-year-button-width);
	min-height: var(--date-year-button-height);
	max-height: var(--date-year-button-height);
	background: var(--date-year-button-background-color);
	color: var(--date-year-button-color);

	transition: all 0.2s ease;
}
.vue-date__year-controls button:hover {
	border: var(--date-year-button-border--hover);
	background: var(--date-year-button-background-color--hover);

	color: var(--date-year-button-color--hover);
}

/* Месяц: Сетка */
.vue-date__grid {
	display: grid;
	grid-template-columns: repeat(3, 1fr);
	gap: var(--month-grid-gap);
}

.month-btn {
	display: flex;
	align-items: center;
	justify-content: center;
	cursor: pointer;

	border: var(--month-button-border);
	border-radius: var(--month-button-border-radius);
	padding: var(--month-button-padding);

	width: var(--month-button-width);
	height: var(--month-button-height);

	color: var(--month-button-color);
	background: var(--month-button-background-color);
	font-size: var(--month-button-font-size);

	transition: var(--month-button-transition);
}

.month-btn:hover {
	border: var(--month-button-border-hover);
	background: var(--month-button-background-color-hover);
}

.month-btn.active {
	border: var(--month-button-active-border);
	background: var(--month-button-active-background-color);

	color: var(--month-button-active-color);
}

/* Месяц: Кнопки */
.vue-date__buttons {
	display: flex;
	justify-content: space-between;
}

.vue-date__buttons-subbutton {
	cursor: pointer;

	border: var(--month-subbutton-border);
	border-radius: var(--month-subbutton-border-radius);
	padding: var(--month-subbutton-padding);

	color: var(--month-subbutton-color);
	background: var(--month-subbutton-background-color);
	font-size: var(--month-subbutton-font-size);

	transition: var(--month-subbutton-transition);
}

.vue-date__buttons-subbutton:hover {
	background: var(--month-subbutton-background-color-hover);
}

/* Дата: Контролы года */
.vue-date__year-buttons {
	display: flex;
	justify-content: space-between;
	gap: var(--date-year-buttons-gap);
}

/* Дата: Календарь */
.vue-date__calendar {
	display: flex;
	flex-direction: column;
	gap: var(--date-calendar-gap);
}

.vue-date__calendar-tr {
	display: grid;
	grid-template-columns: repeat(7, 1fr);

	gap: var(--date-calendar-tr-gap);
}

.vue-date__calendar-th {
	display: flex;
	justify-content: center;
	align-items: center;

	width: var(--date-calendar-th-width);
	height: var(--date-calendar-th-height);

	color: var(--date-calendar-th-color);
	font: var(--date-calendar-th-font);
}

.vue-date__calendar-th.vue-date__calendar-th--weekend {
	color: var(--date-calendar-th-weekend-color);
}

.date-btn {
	display: flex;
	justify-content: center;
	align-items: center;
	flex-grow: 1;
	cursor: pointer;

	border: var(--date-button-border);
	border-radius: var(--date-button-border-radius);
	padding: var(--date-button-padding);

	width: var(--date-button-width);
	height: var(--date-button-height);
	color: var(--date-button-color);
	background-color: var(--date-button-background-color);
	font: var(--date-button-font);

	transition: var(--date-button-transition);
}

.date-btn:hover {
	border: var(--date-button-border--hover);
	color: var(--date-button-color--hover);
	background-color: var(--date-button-background-color--hover);
}

.date-btn.active {
	border: var(--date-button-border--active);
	color: var(--date-button-color--active);
	background-color: var(--date-button-background-color--active);
}

.date-btn.active:hover {
	border: var(--date-button-border--active--hover);
	color: var(--date-button-color--active--hover);
	background-color: var(--date-button-background-color--active--hover);
}

.date-btn.empty {
	cursor: default;

	border: var(--date-button-border--empty);
	background-color: var(--date-button-background-color--empty);
}

.date-btn.empty:hover {
	border: var(--date-button-border--empty--hover);
	background-color: var(--date-button-background-color--empty--hover);
}

.vue-date--month .vue-date__input-label {
	user-select: none;
	cursor: default;
}
</style>
