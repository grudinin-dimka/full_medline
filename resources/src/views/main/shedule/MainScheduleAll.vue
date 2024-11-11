<template>
	<info-bar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link to="/schedule">Расписание</router-link>
	</info-bar>

	<block-hide :minHeight="400">
		<template v-if="loading.sections.schedule">
			<div class="title-table">Расписание на {{ getDateNow() }}</div>
			<div class="filter-list">
				<div class="container">
					<div class="body">
						<div
							class="item"
							v-for="(filter, index) in clinics"
							:key="filter.id"
							:class="{ active: filter.status }"
							@click="changeActiveClinic(filter)"
						>
							<div>{{ filter.name }}</div>
						</div>
					</div>
					<div class="footer">
						<div
							class="filter-button"
							@click="filters.options.status = !filters.options.status"
							:class="{ active: filters.options.status }"
						>
							<svg
								width="18"
								height="12"
								viewBox="0 0 18 12"
								fill="none"
								xmlns="http://www.w3.org/2000/svg"
								v-if="getActiveFilters === 0"
							>
								<path d="M7 12V10H11V12H7ZM3 7V5H15V7H3ZM0 2V0H18V2H0Z" fill="black" />
							</svg>
							<span v-else>{{ getActiveFilters !== 0 ? "(" + getActiveFilters + ")" : "" }}</span>
							Фильтры
						</div>
					</div>
				</div>
			</div>
			<div class="filter-blocks" v-show="filters.options.status">
				<div class="item" :class="{ active: filters.sections.fio.status }">
					<div
						class="check"
						@click="filters.sections.fio.status = !filters.sections.fio.status"
					>
						<div
							class="point"
							:class="{
								default: !filters.sections.fio.status,
								active: filters.sections.fio.status,
							}"
						></div>
					</div>
					<container-input-once :type="filters.sections.fio.status ? 'default' : 'disabled'">
						<template #title>
							<span>Ф.И.О.</span>
						</template>
						<template #input>
							<input
								type="text"
								placeholder="Введите Ф.И.О."
								v-model="filters.sections.fio.data.body"
							/>
						</template>
					</container-input-once>
				</div>
				<div class="item" :class="{ active: filters.sections.specialization.status }">
					<div
						class="check"
						@click="
							filters.sections.specialization.status =
								!filters.sections.specialization.status
						"
					>
						<div
							class="point"
							:class="{
								default: !filters.sections.specialization.status,
								active: filters.sections.specialization.status,
							}"
						></div>
					</div>
					<container-input-once
						:type="filters.sections.specialization.status ? 'default' : 'disabled'"
					>
						<template #title>
							<span>СПЕЦИАЛИЗАЦИЯ</span>
						</template>
						<template #input>
							<select v-model="filters.sections.specialization.data.body">
								<option value="" selected>Ничего не выбрано</option>
								<option value="Хирург">Хирург</option>
								<option value="Отоларинголог">Отоларинголог</option>
								<option value="Терапевт">Терапевт</option>
							</select>
						</template>
					</container-input-once>
				</div>
			</div>
			<!-- TODO надо бы сделать вывод значений в одну строку, которые одинаковые по типу -->
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
						<td>{{ shedule.specializations }}</td>
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
										v-if="getClinicStatus(shedule.id, clinic.id)"
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
									<div
										class="time"
										v-for="blob in getDayTime(shedule.id, day.id, activeClinic.id)"
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

import ContainerInputOnce from "../../../components/ui/admin/containers/input/ContainerInputOnce.vue";

import sorted from "../../../services/sorted.js";

export default {
	components: {
		InfoBar,
		LoaderChild,
		Block,
		BlockHide,
		ContainerInputOnce,
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
				options: {
					status: false,
				},
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
					specializations: "Хирург",
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
							status: true,
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
									time: ["08:00-09:00"],
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
									time: ["08:00-09:00"],
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
					specializations: "Отоларинголог",
					weeks: [
						{
							clinicId: 1,
							status: false,
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
							clinicId: 3,
							status: false,
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
					specializations: "Терапевт",
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
					specializations: "Терапевт",
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
							status: false,
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
					specializations: "Терапевт",
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
							status: false,
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
							clinicId: 4,
							status: false,
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
					specializations: "Терапевт",
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

					if (this.filters.sections.fio.status) {
						if (this.filters.sections.fio.data.body !== "") {
							filteredShedules = filteredShedules.filter((item) => {
								if (item.name.indexOf(this.filters.sections.fio.data.body) !== -1) {
									return item;
								}
							});
						} else {
							sorted.sortByName("up", filteredShedules);
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
						} else {
							sorted.sortBySpecialization("up", filteredShedules);
						}
					}

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
		getDayTime(sheduleId, dayId, clinicId) {
			let shedule = this.shedules.find((item) => item.id === sheduleId);
			let week = shedule.weeks.find((item) => item.clinicId === clinicId);
			let day = week.content.find((item) => item.id === dayId);

			if (this.activeClinic.id === 0) {
				return day.time;
			} else {
				return day.time;
			}
		},
		getClinicStatus(sheduleId, clinicId) {
			let shedule = this.shedules.find((item) => item.id === sheduleId);
			let week = shedule.weeks.find((item) => item.clinicId === clinicId);
			if (week.status === true) {
				return true;
			}
			return false;
		},
	},
	mounted() {
		this.loading.loader.schedule = false;
	},
};
</script>

<style scoped>
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

@media screen and (width <= 600px) {
	.filter-list .container > .item {
		flex: 1 0 250px;
	}
}

/* Блоки фильтров */
.filter-blocks {
	display: flex;
	gap: 10px;

	width: 1350px;
}

.filter-blocks > .item {
	position: relative;
	flex: 1 0 300px;

	border: 2px solid var(--input-border-color-inactive);
	border-radius: 10px;
	padding: 20px;

	background-color: rgba(240, 240, 240, 1);

	transition: all 0.2s;
}

.filter-blocks > .item.active {
	border: 2px solid var(--input-border-color-inactive);
	background-color: rgba(235, 235, 235, 0);
}

.filter-button.active {
	background-color: #f2feff;
	border: 2px solid var(--input-border-color-active);
}

.check {
	position: absolute;
	cursor: pointer;

	top: 12px;
	right: 20px;

	width: 50px;
	height: 20px;
	border: 2px solid var(--input-border-color-inactive);
	background-color: white;
	border-radius: 20px;
}

.check > .point {
	position: absolute;
	top: 2px;

	border-radius: 30px;

	width: 16px;
	height: 16px;
}

.check > .point.default {
	left: 2px;
	background-color: #b6b6b6;
}

.check > .point.active {
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

@media screen and (max-width: 1400px) {
	table {
		width: auto;
		display: block;
		overflow-x: scroll;
	}
}
</style>
