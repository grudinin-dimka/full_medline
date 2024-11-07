<template>
	<info-bar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link to="/schedule">Расписание</router-link>
	</info-bar>
	<block :minHeight="400">
		<template v-if="loading.sections.schedule">
			<filters :filters="clinics" @changeActiveFilter="changeActiveClinic"></filters>
			<table class="schedule">
				<thead>
					<tr>
						<th width="250px">Ф.И.О.</th>
						<th>Специализация</th>
						<th width="110px" v-for="day in week">{{ day.date + " " + day.name }}</th>
					</tr>
				</thead>
				<tbody>
					<tr
						v-for="shedule in getFilteredShedules"
						:key="shedule.id"
						v-if="getFilteredShedules.length > 0"
					>
						<td>{{ shedule.name }}</td>
						<td>{{ shedule.specialization }}</td>
						<!-- Столбики дней недели -->
						<td v-for="day in week" :key="day.id">
							<div class="days">
								<!-- Вывод расписания на несколько -->
								<div
									class="item"
									v-for="clinic in getClinicsWithoutAll"
									:key="clinic.id"
									v-if="activeClinic.id === 0"
								>
									<div
										class="time"
										v-for="blob in getDayTime(shedule.id, day.id, clinic.id)"
									>
										{{ blob }}
									</div>
								</div>
								<div class="item" v-if="activeClinic.id > 0">
									<div class="time" v-for="blob in getDayTime(shedule.id, day.id, 1)">
										test
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
			:minHeight="397"
			@loaderChildAfterLeave="loaderChildAfterLeave"
		/>
	</block>
</template>

<script>
import InfoBar from "../../../components/ui/main/InfoBar.vue";
import LoaderChild from "../../../components/includes/LoaderChild.vue";
import Block from "../../../components/ui/main/blocks/Block.vue";

import Filters from "../../../components/ui/main/Filters.vue";

export default {
	components: {
		InfoBar,
		LoaderChild,
		Block,
		Filters,
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
			clinics: [
				{
					id: 0,
					name: "Все",
					status: true,
				},
				{
					id: 1,
					name: "ул. Комсомольская, д. 16",
					status: false,
				},
				{
					id: 2,
					name: "ул. Карла Либкнехта, д. 10",
					status: false,
				},
				{
					id: 3,
					name: "ул. Октябрьская, д. 3",
					status: false,
				},
				{
					id: 4,
					name: "ул. Спартака, д. 8",
					status: false,
				},
			],
			week: [
				{
					id: 1,
					name: "Понедельник",
					date: "24 ноября",
				},
				{
					id: 2,
					name: "Вторник",
					date: "25 ноября",
				},
				{
					id: 3,
					name: "Среда",
					date: "26 ноября",
				},
				{
					id: 4,
					name: "Четверг",
					date: "27 ноября",
				},
				{
					id: 5,
					name: "Пятница",
					date: "28 ноября",
				},
				{
					id: 6,
					name: "Суббота",
					date: "29 ноября",
				},
				{
					id: 7,
					name: "Воскресенье",
					date: "30 ноября",
				},
			],
			shedules: [
				{
					id: 1,
					name: "Сидоров Сидор Сидорович",
					specialization: "Хирург",
					weeks: [
						{
							clinicId: 1,
							status: true,
							content: [
								{
									id: 1,
									name: "Понедельник",
									time: ["08:00-17:00"],
								},
								{
									id: 2,
									name: "Вторник",
									time: ["08:00-17:00"],
								},
								{
									id: 3,
									name: "Среда",
									time: ["08:00-17:00"],
								},
								{
									id: 4,
									name: "Четверг",
									time: ["08:00-17:00"],
								},
								{
									id: 5,
									name: "Пятница",
									time: ["08:00-17:00"],
								},
								{
									id: 6,
									name: "Суббота",
									time: ["-"],
								},
								{
									id: 7,
									name: "Воскресенье",
									time: ["-"],
								},
							],
						},
						{
							clinicId: 2,
							status: false,
							content: [
								{
									id: 1,
									name: "Понедельник",
									time: ["-"],
								},
								{
									id: 2,
									name: "Вторник",
									time: ["-"],
								},
								{
									id: 3,
									name: "Среда",
									time: ["-"],
								},
								{
									id: 4,
									name: "Четверг",
									time: ["-"],
								},
								{
									id: 5,
									name: "Пятница",
									time: ["-"],
								},
								{
									id: 6,
									name: "Суббота",
									time: ["-"],
								},
								{
									id: 7,
									name: "Воскресенье",
									time: ["-"],
								},
							],
						},
						{
							clinicId: 3,
							status: true,
							content: [
								{
									id: 1,
									name: "Понедельник",
									time: ["08:00-17:00"],
								},
								{
									id: 2,
									name: "Вторник",
									time: ["08:00-17:00"],
								},
								{
									id: 3,
									name: "Среда",
									time: ["-"],
								},
								{
									id: 4,
									name: "Четверг",
									time: ["08:00-17:00"],
								},
								{
									id: 5,
									name: "Пятница",
									time: ["08:00-17:00"],
								},
								{
									id: 6,
									name: "Суббота",
									time: ["-"],
								},
								{
									id: 7,
									name: "Воскресенье",
									time: ["-"],
								},
							],
						},
						{
							clinicId: 4,
							status: false,
							content: [
								{
									id: 1,
									name: "Понедельник",
									time: ["-"],
								},
								{
									id: 2,
									name: "Вторник",
									time: ["-"],
								},
								{
									id: 3,
									name: "Среда",
									time: ["-"],
								},
								{
									id: 4,
									name: "Четверг",
									time: ["-"],
								},
								{
									id: 5,
									name: "Пятница",
									time: ["-"],
								},
								{
									id: 6,
									name: "Суббота",
									time: ["-"],
								},
								{
									id: 7,
									name: "Воскресенье",
									time: ["-"],
								},
							],
						},
					],
				},
				{
					id: 2,
					name: "Иванов Иван Иванович",
					specialization: "Терапевт",
					weeks: [
						{
							clinicId: 1,
							status: true,
							content: [
								{
									id: 1,
									name: "Понедельник",
									time: ["08:00-17:00"],
								},
								{
									id: 2,
									name: "Вторник",
									time: ["08:00-17:00"],
								},
								{
									id: 3,
									name: "Среда",
									time: ["08:00-17:00"],
								},
								{
									id: 4,
									name: "Четверг",
									time: ["08:00-17:00"],
								},
								{
									id: 5,
									name: "Пятница",
									time: ["08:00-17:00"],
								},
								{
									id: 6,
									name: "Суббота",
									time: ["-"],
								},
								{
									id: 7,
									name: "Воскресенье",
									time: ["-"],
								},
							],
						},
						{
							clinicId: 2,
							status: false,
							content: [
								{
									id: 1,
									name: "Понедельник",
									time: ["-"],
								},
								{
									id: 2,
									name: "Вторник",
									time: ["-"],
								},
								{
									id: 3,
									name: "Среда",
									time: ["-"],
								},
								{
									id: 4,
									name: "Четверг",
									time: ["-"],
								},
								{
									id: 5,
									name: "Пятница",
									time: ["-"],
								},
								{
									id: 6,
									name: "Суббота",
									time: ["-"],
								},
								{
									id: 7,
									name: "Воскресенье",
									time: ["-"],
								},
							],
						},
						{
							clinicId: 3,
							status: true,
							content: [
								{
									id: 1,
									name: "Понедельник",
									time: ["08:00-17:00"],
								},
								{
									id: 2,
									name: "Вторник",
									time: ["08:00-17:00"],
								},
								{
									id: 3,
									name: "Среда",
									time: ["08:00-17:00"],
								},
								{
									id: 4,
									name: "Четверг",
									time: ["08:00-17:00"],
								},
								{
									id: 5,
									name: "Пятница",
									time: ["08:00-17:00"],
								},
								{
									id: 6,
									name: "Суббота",
									time: ["-"],
								},
								{
									id: 7,
									name: "Воскресенье",
									time: ["-"],
								},
							],
						},
						{
							clinicId: 4,
							status: true,
							content: [
								{
									id: 1,
									name: "Понедельник",
									time: ["08:00-17:00"],
								},
								{
									id: 2,
									name: "Вторник",
									time: ["08:00-17:00"],
								},
								{
									id: 3,
									name: "Среда",
									time: ["08:00-17:00"],
								},
								{
									id: 4,
									name: "Четверг",
									time: ["08:00-17:00"],
								},
								{
									id: 5,
									name: "Пятница",
									time: ["08:00-17:00"],
								},
								{
									id: 6,
									name: "Суббота",
									time: ["-"],
								},
								{
									id: 7,
									name: "Воскресенье",
									time: ["-"],
								},
							],
						},
					],
				},
				{
					id: 3,
					name: "Петров Петр Петрович",
					specialization: "Терапевт",
					weeks: [
						{
							clinicId: 1,
							status: false,
							content: [
								{
									id: 1,
									name: "Понедельник",
									time: ["-"],
								},
								{
									id: 2,
									name: "Вторник",
									time: ["-"],
								},
								{
									id: 3,
									name: "Среда",
									time: ["-"],
								},
								{
									id: 4,
									name: "Четверг",
									time: ["-"],
								},
								{
									id: 5,
									name: "Пятница",
									time: ["-"],
								},
								{
									id: 6,
									name: "Суббота",
									time: ["-"],
								},
								{
									id: 7,
									name: "Воскресенье",
									time: ["-"],
								},
							],
						},
						{
							clinicId: 2,
							status: true,
							content: [
								{
									id: 1,
									name: "Понедельник",
									time: ["00:08-17:00"],
								},
								{
									id: 2,
									name: "Вторник",
									time: ["00:08-17:00"],
								},
								{
									id: 3,
									name: "Среда",
									time: ["00:08-17:00"],
								},
								{
									id: 4,
									name: "Четверг",
									time: ["00:08-17:00"],
								},
								{
									id: 5,
									name: "Пятница",
									time: ["00:08-17:00"],
								},
								{
									id: 6,
									name: "Суббота",
									time: ["-"],
								},
								{
									id: 7,
									name: "Воскресенье",
									time: ["-"],
								},
							],
						},
						{
							clinicId: 3,
							status: false,
							content: [
								{
									id: 1,
									name: "Понедельник",
									time: ["-"],
								},
								{
									id: 2,
									name: "Вторник",
									time: ["-"],
								},
								{
									id: 3,
									name: "Среда",
									time: ["-"],
								},
								{
									id: 4,
									name: "Четверг",
									time: ["-"],
								},
								{
									id: 5,
									name: "Пятница",
									time: ["-"],
								},
								{
									id: 6,
									name: "Суббота",
									time: ["-"],
								},
								{
									id: 7,
									name: "Воскресенье",
									time: ["-"],
								},
							],
						},
						{
							clinicId: 4,
							status: true,
							content: [
								{
									id: 1,
									name: "Понедельник",
									time: ["08:00-17:00"],
								},
								{
									id: 2,
									name: "Вторник",
									time: ["08:00-17:00"],
								},
								{
									id: 3,
									name: "Среда",
									time: ["08:00-17:00"],
								},
								{
									id: 4,
									name: "Четверг",
									time: ["08:00-17:00"],
								},
								{
									id: 5,
									name: "Пятница",
									time: ["08:00-17:00"],
								},
								{
									id: 6,
									name: "Суббота",
									time: ["-"],
								},
								{
									id: 7,
									name: "Воскресенье",
									time: ["-"],
								},
							],
						},
					],
				},
			],
			sections: {
				clinics: [],
				days: [],
			},
		};
	},
	computed: {
		getClinicsWithoutAll() {
			return this.clinics.filter((item) => item.name !== "Все");
		},
		getFilteredShedules() {
			let filteredShedules = [];

			switch (this.activeClinic.name) {
				case "Все":
					filteredShedules = this.shedules;
					break;
				default:
					filteredShedules = this.shedules.filter((item) =>
						item.weeks.some(
							(week) => week.clinicId === this.activeClinic.id && week.status === true
						)
					);
					break;
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
		/* После скрытия элементы */
		getDayTime(sheduleId, dayId, clinicId) {
			if (this.activeClinic.id === 0) {
				let shedule = this.shedules.find((item) => item.id === sheduleId);
				let week = shedule.weeks.find((item) => item.clinicId === clinicId);
				let day = week.content.find((item) => item.id === dayId);

				return day.time;
			} else {
				return ["-"];
			};
		},
	},
	mounted() {
		this.loading.loader.schedule = false;
	},
};
</script>

<style scoped>
table {
	border-collapse: collapse;
	animation: show-bottom-to-top-15 0.5s ease-in-out;

	width: 100%;
	max-width: 1350px;
	border: 1px solid black;
	font-size: 16px;

	overflow: scroll;
}

th {
	white-space: pre-wrap;
	word-wrap: break-word;
	text-align: center;
}

td {
	text-align: left;
}

th,
td {
	padding: 10px;
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
	border: 1px solid #3fbecd;
	background-color: #3fbecd;
	color: white;
}

tr > td {
	border: 1px solid #3fbecd;
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
	gap: 10px;
}

.days > .item {
	text-align: center;

	width: 110px;
	color: rgb(10, 49, 53);
	border-radius: 10px;
	background-color: rgba(63, 190, 205, 0.3);
}

tr.empty > td {
	text-align: center;
	color: #90cad1;
}
</style>
