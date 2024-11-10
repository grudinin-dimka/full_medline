<template>
	<info-bar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link to="/schedule">Расписание</router-link>
	</info-bar>

	<block-hide :minHeight="400">
		<template v-if="loading.sections.schedule">
			<filters :filters="clinics" @changeActiveFilter="changeActiveClinic"></filters>
			<table>
				<thead>
					<tr>
						<th width="300px">Ф.И.О.</th>
						<th>Специализация</th>
						<th v-for="day in week">{{ day.date + " " + day.name }}</th>
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
									class="item all"
									v-for="clinic in getClinicsWithoutAll"
									:key="clinic.id"
									v-if="activeClinic.id === 0"
								>
									<div
										class="time"
										v-for="blob in getDayTime(shedule.id, day.id, clinic.id)"
										:class="{ clear: blob === '-' }"
									>
										{{ blob !== "-" ? blob : "" }}
									</div>
								</div>
								<div
									class="item"
									v-if="activeClinic.id > 0"
									:class="{
										one: activeClinic.id === 1,
										two: activeClinic.id === 2,
										three: activeClinic.id === 3,
										four: activeClinic.id === 4,
									}"
								>
									<div class="time">
										{{ getDayTime(shedule.id, day.id, activeClinic.id) }}
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
	</block-hide>
</template>

<script>
import InfoBar from "../../../components/ui/main/InfoBar.vue";
import LoaderChild from "../../../components/includes/LoaderChild.vue";
import Block from "../../../components/ui/main/blocks/Block.vue";
import BlockHide from "../../../components/ui/main/blocks/BlockHide.vue";

import Filters from "../../../components/ui/main/Filters.vue";

export default {
	components: {
		InfoBar,
		LoaderChild,
		Block,
		BlockHide,
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
			// Клиники
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
			// Дни на неделе
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
					name: "Розенбергер Дмитрий Александрович",
					specialization: "Хирург",
					weeks: [
						{
							clinicId: 1,
							status: true,
							content: [
								{
									id: 1,
									name: "Понедельник",
									time: ["08:00-09:00"],
								},
								{
									id: 2,
									name: "Вторник",
									time: ["08:00-09:00"],
								},
								{
									id: 3,
									name: "Среда",
									time: ["08:00-09:00"],
								},
								{
									id: 4,
									name: "Четверг",
									time: ["08:00-09:00"],
								},
								{
									id: 5,
									name: "Пятница",
									time: ["08:00-09:00"],
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
									time: ["06:00-07:00"],
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
									time: ["09:00-10:00"],
								},
								{
									id: 2,
									name: "Вторник",
									time: ["09:00-10:00"],
								},
								{
									id: 3,
									name: "Среда",
									time: ["-"],
								},
								{
									id: 4,
									name: "Четверг",
									time: ["09:00-10:00"],
								},
								{
									id: 5,
									name: "Пятница",
									time: ["09:00-10:00"],
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
					specialization: "Отоларингологывффыв",
					weeks: [
						{
							clinicId: 1,
							status: true,
							content: [
								{
									id: 1,
									name: "Понедельник",
									time: ["10:00-11:00"],
								},
								{
									id: 2,
									name: "Вторник",
									time: ["10:00-11:00"],
								},
								{
									id: 3,
									name: "Среда",
									time: ["10:00-11:00"],
								},
								{
									id: 4,
									name: "Четверг",
									time: ["10:00-11:00"],
								},
								{
									id: 5,
									name: "Пятница",
									time: ["10:00-11:00"],
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
									time: ["11:00-12:00"],
								},
								{
									id: 2,
									name: "Вторник",
									time: ["11:00-12:00"],
								},
								{
									id: 3,
									name: "Среда",
									time: ["11:00-12:00"],
								},
								{
									id: 4,
									name: "Четверг",
									time: ["11:00-12:00"],
								},
								{
									id: 5,
									name: "Пятница",
									time: ["11:00-12:00"],
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
									time: ["12:00-13:00"],
								},
								{
									id: 2,
									name: "Вторник",
									time: ["12:00-13:00"],
								},
								{
									id: 3,
									name: "Среда",
									time: ["12:00-13:00"],
								},
								{
									id: 4,
									name: "Четверг",
									time: ["12:00-13:00"],
								},
								{
									id: 5,
									name: "Пятница",
									time: ["12:00-13:00"],
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
									time: ["13:00-14:00"],
								},
								{
									id: 2,
									name: "Вторник",
									time: ["13:00-14:00"],
								},
								{
									id: 3,
									name: "Среда",
									time: ["13:00-14:00"],
								},
								{
									id: 4,
									name: "Четверг",
									time: ["13:00-14:00"],
								},
								{
									id: 5,
									name: "Пятница",
									time: ["13:00-14:00"],
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
									time: ["14:00-15:00"],
								},
								{
									id: 2,
									name: "Вторник",
									time: ["14:00-15:00"],
								},
								{
									id: 3,
									name: "Среда",
									time: ["14:00-15:00"],
								},
								{
									id: 4,
									name: "Четверг",
									time: ["14:00-15:00"],
								},
								{
									id: 5,
									name: "Пятница",
									time: ["14:00-15:00"],
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
					id: 4,
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
									time: ["13:00-14:00"],
								},
								{
									id: 2,
									name: "Вторник",
									time: ["13:00-14:00"],
								},
								{
									id: 3,
									name: "Среда",
									time: ["13:00-14:00"],
								},
								{
									id: 4,
									name: "Четверг",
									time: ["13:00-14:00"],
								},
								{
									id: 5,
									name: "Пятница",
									time: ["13:00-14:00"],
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
									time: ["14:00-15:00"],
								},
								{
									id: 2,
									name: "Вторник",
									time: ["14:00-15:00"],
								},
								{
									id: 3,
									name: "Среда",
									time: ["14:00-15:00"],
								},
								{
									id: 4,
									name: "Четверг",
									time: ["14:00-15:00"],
								},
								{
									id: 5,
									name: "Пятница",
									time: ["14:00-15:00"],
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
					id: 5,
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
									time: ["13:00-14:00"],
								},
								{
									id: 2,
									name: "Вторник",
									time: ["13:00-14:00"],
								},
								{
									id: 3,
									name: "Среда",
									time: ["13:00-14:00"],
								},
								{
									id: 4,
									name: "Четверг",
									time: ["13:00-14:00"],
								},
								{
									id: 5,
									name: "Пятница",
									time: ["13:00-14:00"],
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
									time: ["14:00-15:00"],
								},
								{
									id: 2,
									name: "Вторник",
									time: ["14:00-15:00"],
								},
								{
									id: 3,
									name: "Среда",
									time: ["14:00-15:00"],
								},
								{
									id: 4,
									name: "Четверг",
									time: ["14:00-15:00"],
								},
								{
									id: 5,
									name: "Пятница",
									time: ["14:00-15:00"],
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
					id: 6,
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
									time: ["13:00-14:00"],
								},
								{
									id: 2,
									name: "Вторник",
									time: ["13:00-14:00"],
								},
								{
									id: 3,
									name: "Среда",
									time: ["13:00-14:00"],
								},
								{
									id: 4,
									name: "Четверг",
									time: ["13:00-14:00"],
								},
								{
									id: 5,
									name: "Пятница",
									time: ["13:00-14:00"],
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
									time: ["14:00-15:00"],
								},
								{
									id: 2,
									name: "Вторник",
									time: ["14:00-15:00"],
								},
								{
									id: 3,
									name: "Среда",
									time: ["14:00-15:00"],
								},
								{
									id: 4,
									name: "Четверг",
									time: ["14:00-15:00"],
								},
								{
									id: 5,
									name: "Пятница",
									time: ["14:00-15:00"],
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
			let shedule = this.shedules.find((item) => item.id === sheduleId);
			let week = shedule.weeks.find((item) => item.clinicId === clinicId);
			let day = week.content.find((item) => item.id === dayId);

			if (this.activeClinic.id === 0) {
				return day.time;
			} else {
				return day.time[0];
			}
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

	width: 1350px;

	font-size: 16px;
}

th {
	white-space: pre-wrap;
	word-wrap: break-word;
	text-align: center;
}

/* th:first-of-type {
	min-width: 300px;
} */

td {
	text-align: left;
	word-wrap: break-word;
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

	width: 115px;
	color: rgb(10, 49, 53);
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

.days > .item.all > .time.clear {
	border: 0px solid #d2f2f5;
	background-color: rgba(255, 255, 255, 0);
}

tr.empty > td {
	text-align: center;
	color: #90cad1;
}

@media screen and (max-width: 1400px) {
	table {
		width: auto;
		display: block;
		overflow-x: scroll;
	}
}
</style>
