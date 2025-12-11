<template>
	<div class="calendar">
		<div class="calendar__year" v-if="this.month === 1">
			<div class="calendar__year-year">
				<div class="calendar__year-name">{{ year }}</div>
				<div class="calendar__year-arrow"></div>
			</div>
			<div class="calendar__year-line"></div>
		</div>

		<div class="calendar__table">
			<div class="calendar__thead">
				<div class="calendar__tr">
					<div class="calendar__th">Пн</div>
					<div class="calendar__th">Вт</div>
					<div class="calendar__th">Ср</div>
					<div class="calendar__th">Чт</div>
					<div class="calendar__th">Пт</div>
					<div class="calendar__th weekend">Сб</div>
					<div class="calendar__th weekend">Вс</div>
				</div>
			</div>

			<div class="calendar__tbody">
				<div class="calendar__tr" v-for="week in 6" :key="week">
					<template v-for="day in 7" :key="day">
						<div
							class="calendar__td"
							:class="getStyleOfDay(day, week, this.month, this.year)"
							@click="clickDay(day, week, this.month, this.year)"
						>
							{{ getDay(day, week, this.month, this.year) }}
						</div>
					</template>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
export default {
	props: {
		selected: {
			type: String,
			default: "",
		},
		month: {
			type: Number,
			default: new Date().getMonth() + 1,
		},
		year: {
			type: Number,
			default: new Date().getFullYear(),
		},
		days: {
			type: Array,
			default: () => [],
		},
		disabled: {
			type: Boolean,
			default: false,
		},
	},
	methods: {
		/* Возвращает количество дней в текущем месяце. */
		getDaysInMonth(year, month) {
			return new Date(year, month + 1, 0).getDate();
		},

		/* Возвращает первый день месяца */
		getFirstDayOfMonth(year, month) {
			return new Date(year, month, 1).getDay() || 7; // Возвращает 1 для понедельника, 2 для вторника и т.д.
		},

		/* Возвращает день */
		getDay(day, week, month, year) {
			if (!month || !year) {
				return "";
			}

			const firstDay = this.getFirstDayOfMonth(year, month - 1);
			const daysInMonth = this.getDaysInMonth(year, month - 1);
			const currentDay = (week - 1) * 7 + day - firstDay + 1;

			if (currentDay > 0 && currentDay <= daysInMonth) {
				return currentDay;
			}

			return "";
		},

		/* Получение стиля дня */
		getStyleOfDay(week, day, month, year) {
			let dayOfMonth = this.getDay(week, day, month, year);
			let style = "";

			// Если день не входит в текущий месяц
			if (dayOfMonth === "") {
				style += "empty";
			}

			if (this.selected) {
				let [sYear, sMonth, sDay] = this.selected.toString().split("-");

				if (Number(sDay) === dayOfMonth && Number(sMonth) === this.month && Number(sYear) === this.year) {
					style += " active";
				}
			}

			month = String(month).padStart(2, "0");

			if (this.days.includes(`${year}-${String(month).padStart(2, "0")}-${String(dayOfMonth).padStart(2, "0")}`)) {
				style += " work";
			}

			if (this.disabled) {
				style += " disabled";
			}

			return style;
		},

		clickDay(day, week, month, year) {
			let dayOfMonth = this.getDay(day, week, month, year);

			// Если день не входит в текущий месяц
			if (dayOfMonth === "") {
				return;
			}

			this.$emit("touch", dayOfMonth);
		},
	},
};
</script>

<style>
/* Календарь */
.calendar {
	position: relative;
	display: flex;
	flex-direction: column;
	gap: 20px;

	border-radius: 7.5px;
}

/* Календарь -> Год */
.calendar__year {
	z-index: 1;
	position: absolute;
	left: -4px;

	height: 100%;
}

.calendar__year-year {
	position: absolute;
	margin: auto;
	top: -10px;

	padding: 5px 10px;
	height: 30px;
	border-radius: 0px 0px 0px 0px;

	width: auto;
	background-color: var(--primary-color);
	color: white;
}

.calendar__year-name {
	position: relative;
	z-index: 2;
}

.calendar__year-arrow {
	position: absolute;
	width: 21px;
	height: 21px;
	top: 5px;
	right: -11px;

	transform: rotate(45deg);
	border-radius: 0px 0px 0px 0px;

	background-color: var(--primary-color);
}

.calendar__year-line {
	width: 3px;
	height: 100%;
	background-color: var(--primary-color);
}

.calendar__table {
	flex-grow: 1;
	display: flex;
	flex-direction: column;
	gap: 2px;
}

.calendar__thead {
	display: flex;
	flex-direction: column;
	gap: 5px;
}

.calendar__tbody {
	display: flex;
	flex-direction: column;
	gap: 5px;
}

.calendar__tr {
	display: flex;
	gap: 5px;
}

.calendar__th {
	cursor: default;
	color: кув;
}

.calendar__th,
.calendar__td {
	user-select: none;

	display: flex;
	justify-content: center;
	align-items: center;

	border: var(--default-border);
	background-color: var(--item-background-color);
	border-radius: 100px;

	height: 39px;
	width: 39px;
	background-color: white;
	font-size: 1.125rem;
}

.calendar__td {
	background-color: white;
	color: black;
	cursor: pointer;

	transition: all 0.2s;
}

.calendar__td.work {
	border: 1px solid var(--primary-color);
	background-color: var(--primary-color);
	color: white;
}

.calendar__td:is(:hover, .active) {
	border: var(--default-border-active);
	background-color: var(--item-background-color-active);

	color: black;
}

.calendar__td.empty {
	cursor: default;
	background-color: rgba(0, 0, 0, 0.1);
}

.calendar__td.empty:hover {
	border: var(--default-border);
}

.weekend {
	color: var(--primary-color);
}

.calendar__thead .calendar__tr .calendar__th {
	border: 0px;
}

.calendar__tr {
	flex-grow: 1;

	display: flex;
	justify-content: center;
	align-items: center;

	border-radius: 7.5px;
	font-size: 1.125rem;

	transition: all 0.2s;
}

.calendar__table > div.active {
	background-color: var(--primary-color);
	color: white;
}

.calendar__td.disabled {
	cursor: default;

	opacity: 0.5;
}
</style>
