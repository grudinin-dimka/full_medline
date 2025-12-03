<template>
	<vue-input-container>
		<vue-input-label v-if="$slots.label">
			<slot name="label"></slot>
		</vue-input-label>

		<div class="vue-time" ref="time">
			<!-- Поле ввода -->
			<div class="vue-time__input">
				<input
					class="vue-time__input-label"
					v-imask="maskOptions"
					:value="modelValue"
					placeholder="--:--"
					@accept="onAccept"
					@complete="onComplete"
				/>

				<input
					class="vue-time__input-input"
					type="text"
					readonly
					:value="modelValue"
					@input="$emit('update:modelValue', $event.target.value.trim())"
					@focus="showPicker = true"
					ref="timeInput"
				/>

				<div class="vue-time__input-icon" @click="showPicker = !showPicker">
					<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px">
						<path
							d="m612-292 56-56-148-148v-184h-80v216l172 172ZM480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-400Zm0 320q133 0 226.5-93.5T800-480q0-133-93.5-226.5T480-800q-133 0-226.5 93.5T160-480q0 133 93.5 226.5T480-160Z"
						/>
					</svg>
				</div>
			</div>

			<!-- Выпадающее меню -->
			<div v-if="showPicker" class="vue-time__dropdown">
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

				<div class="vue-time__buttons">
					<button class="vue-time__buttons-subbutton" @click="clearTIme">Удалить</button>

					<button class="vue-time__buttons-subbutton" @click="setTimeCurrent">Сейчас</button>
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
import IMask from "imask";

import VueInputContainer from "./ui/VueInputContainer.vue";
import VueInputLabel from "./ui/VueInputLabel.vue";
import VueInputError from "./ui/VueInputError.vue";

export default {
	name: "VueTime",
	components: { VueInputContainer, VueInputLabel, VueInputError },
	props: {
		modelValue: {
			type: [String, Number, Boolean, null, Object],
			default: "",
			required: true,
		},
		step: {
			type: Number,
			default: 1,
		},
		type: {
			type: String,
			default: "text",
		},
		error: {
			type: Boolean,
			default: false,
		},
	},
	data() {
		return {
			maskOptions: {
				mask: "HH:MM", // шаблон
				blocks: {
					HH: {
						mask: IMask.MaskedRange,
						from: 0,
						to: 23,
						maxLength: 2,
						placeholderChar: "ч",
					},
					MM: {
						mask: IMask.MaskedRange,
						from: 0,
						to: 59,
						maxLength: 2,
						placeholderChar: "м",
					},
				},
				autofix: true, // исправляет 29:99 → 23:59
				lazy: false,
			},

			/* Общие */
			showPicker: false,

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
				"24",
			],

			minutes: [],
		};
	},
	watch: {
		showPicker(val) {
			if (val) {
				this.scrollToSelected();
			}
		},
	},
	directives: {
		imask: IMaskDirective,
	},
	methods: {
		onAccept(e) {
			const maskRef = e.detail;

			if (/:мм/.test(maskRef.value)) {
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

			if (/\d{2}:\d{2}/.test(maskRef.value)) {
				this.$emit("update:modelValue", maskRef.value);

				this.scrollToSelected();
			}
		},

		scrollToSelected() {
			this.$nextTick(() => {
				// часы
				const hoursContainer = this.$refs.hours;
				if (hoursContainer) {
					const selected = hoursContainer.querySelector(".time-btn.active");

					if (selected) {
						hoursContainer.scrollTop =
							selected.offsetTop - hoursContainer.clientHeight / 2 + selected.clientHeight / 2;
					}
				}

				// минуты
				const minutesContainer = this.$refs.minutes;
				if (minutesContainer) {
					const selected = minutesContainer.querySelector(".time-btn.active");

					if (selected) {
						minutesContainer.scrollTop =
							selected.offsetTop - minutesContainer.clientHeight / 2 + selected.clientHeight / 2;
					}
				}
			});
		},

		/* Закрытие Времяа */
		handleClickOutside(e) {
			if (this.$refs.time && !this.$refs.time.contains(e.target)) {
				this.showPicker = false;
			}
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                      Время                        |*/
		/* |___________________________________________________|*/
		/* Очистка Времяа */
		clearTIme() {
			this.$emit("update:modelValue", "");

			this.showPicker = false;
		},

		/* Выбор текущего Времяа */
		setTimeCurrent() {
			let date = new Date();
			this.$emit(
				"update:modelValue",
				`${String(date.getHours()).padStart(2, "0")}:${String(date.getMinutes()).padStart(2, "0")}`
			);

			this.showPicker = false;
		},

		/* Выбор Времяа */
		selectHour(hour) {
			if (!this.modelValue) {
				this.$emit("update:modelValue", `${hour}:00`);
				return;
			}

			let [modelHour, modelMinute] = this.modelValue.split(":");

			this.$emit("update:modelValue", `${hour}:${modelMinute}`);
		},

		/* Выбор Времяа */
		selectMinute(minute) {
			if (!this.modelValue) {
				this.$emit("update:modelValue", `00:${minute}`);
				return;
			}

			let [modelHour, modelMinute] = this.modelValue.split(":");

			this.$emit("update:modelValue", `${modelHour}:${minute}`);
		},

		/* Проверка выбранного времени: часы */
		isSelectedHour(hour) {
			let [modelHour, modelMinute] = this.modelValue.split(":");

			return hour === modelHour;
		},

		/* Проверка выбранной времени: минуты */
		isSelectedMinute(minute) {
			let [modelHour, modelMinute] = this.modelValue.split(":");

			return minute === modelMinute;
		},
	},
	mounted() {
		let cycles = 60 / this.step;
		for (let index = 0; index < cycles; index++) {
			this.minutes.push(String(index * this.step).padStart(2, "0"));
		}

		if (!this.minutes.includes("59")) {
			this.minutes.push("59");			
		}

		document.addEventListener("click", this.handleClickOutside);
	},
	beforeUnmount() {
		document.removeEventListener("click", this.handleClickOutside);
	},
};
</script>

<style>
/* Время */
.vue-time {
	flex-grow: 1;

	position: relative;
	display: flex;
	flex-direction: column;
	gap: var(--input-gap);

	width: 100%;
}

.vue-time__input {
	position: relative;
	display: flex;
	align-items: center;
}

.vue-time__input-label {
	cursor: text;
	position: absolute;
	z-index: var(--time-label-z-index);
	left: var(--time-label-left);

	border: var(--time-label-border);
	border-radius: var(--time-label-border-radius);
	caret-color: var(--time-label-caret-color);
	display: flex;
	align-items: center;
	height: var(--time-label-height);

	width: var(--time-label-width);
	font: var(--time-label-font);
	background-color: var(--time-label-background-color);
}

.vue-time__input-label:focus {
	border: var(--time-label-border-focus) !important;
}

.vue-time__input-input {
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

.vue-time__input-input:focus,
.vue-time__input-label:focus + .vue-time__input-input {
	border: var(--input-border-focus);
}

.vue-time__input-icon {
	cursor: pointer;
	position: absolute;
	right: 10px;

	font-size: 16px;

	fill: var(--input-icon-color);
}

.vue-time__input-icon:hover {
	fill: var(--input-icon-hover-color);
}

/* Время: Выпадающее меню */
.vue-time__dropdown {
	position: absolute;
	z-index: var(--time-dropdown-z-index);
	top: var(--time-dropdown-top);
	display: flex;
	flex-direction: column;
	gap: var(--time-dropdown-gap);

	border: var(--time-dropdown-border);
	border-radius: var(--time-dropdown-border-radius);
	padding: var(--time-dropdown-padding);

	width: var(--time-dropdown-width);
	background: var(--time-dropdown-background-color);

	box-shadow: var(--time-dropdown-box-shadow);
	animation: var(--time-dropdown-animation);
}

/* Время: Сетка */
.vue-time__grid {
	display: grid;
	grid-template-columns: repeat(2, 1fr);
	gap: var(--time-grid-gap);

	position: relative;
	height: 100%;
}

.vue-time__grid-item {
	display: flex;
	flex-direction: column;
	gap: var(--time-grid-gap);

	position: relative;
	width: 100%;
	height: 292px;
	overflow: hidden;

	overflow-y: auto; /* пусть будет auto, чтобы скролл рендерился только если нужно */
}

/* Webkit (Chrome, Edge, Safari) */
.vue-time__grid-item::-webkit-scrollbar {
	display: none;
}

.time-btn {
	display: flex;
	align-items: center;
	justify-content: center;
	cursor: pointer;
	flex: 1 0 32px;

	border: var(--time-button-border);
	border-radius: var(--time-button-border-radius);
	padding: var(--time-button-padding);

	color: var(--time-button-color);
	background: var(--time-button-background-color);
	font-size: var(--time-button-font-size);
	width: var(--time-button-width);
	min-height: var(--time-button-height);
	max-height: var(--time-button-height);

	transition: var(--time-button-transition);
}

.time-btn:hover {
	border: var(--time-button-border-hover);
	background: var(--time-button-background-color-hover);
}

.time-btn.active {
	border: var(--time-button-active-border);
	background: var(--time-button-active-background-color);

	color: var(--time-button-active-color);
}

/* Время: Кнопки */
.vue-time__buttons {
	display: flex;
	justify-content: space-between;
}

.vue-time__buttons-subbutton {
	cursor: pointer;

	border: var(--time-subbutton-border);
	border-radius: var(--time-subbutton-border-radius);
	padding: var(--time-subbutton-padding);

	color: var(--time-subbutton-color);
	background: var(--time-subbutton-background-color);
	font-size: var(--time-subbutton-font-size);

	transition: var(--time-subbutton-transition);
}

.vue-time__buttons-subbutton:hover {
	background: var(--time-subbutton-background-color-hover);
}

/* Дата: Контролы года */
.vue-time__year-buttons {
	display: flex;
	justify-content: space-between;
	gap: var(--time-year-buttons-gap);
}
</style>
