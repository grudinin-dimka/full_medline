<template>
	<InfoBar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link :to="`/vacancies`">Вакансии</router-link>
	</InfoBar>

	<Block :minHeight="100">
		<div class="vacancies" v-if="vacancies.length > 0">
			<div class="vacancies__item" v-for="vacancy in vacancies" :class="{ 'skeleton': false }">
				<div class="vacancies__item-title">
					{{ vacancy.title }}
				</div>
				<div class="vacancies__item-address">Адресс: {{ vacancy.address }}</div>
				<div class="vacancies__item-schedule">График: {{ vacancy.schedule }}</div>
				<div class="vacancies__item-requirements">
					<div class="vacancies__requirements-title">Требования</div>
					<div class="vacancies__requirements-body">
						<ol>
							<li>{{ vacancy.requirements }}</li>
							<li>{{ vacancy.requirements }}</li>
							<li>{{ vacancy.requirements }}</li>
							<li>{{ vacancy.requirements }}</li>
							<li>{{ vacancy.requirements }}</li>
						</ol>
					</div>
				</div>
				<div class="vacancies__item-conditions">
					<div class="vacancies__conditions-title">Условия</div>
					<div class="vacancies__conditions-body">
						{{ vacancy.conditions }}
					</div>
				</div>
				<div class="vacancies__item-email">Почта (для резюме): {{ vacancy.email }}</div>
				<div class="vacancies__item-phone">Телефон (для вопросов): {{ vacancy.phone }}</div>
				<div class="vacancies__item-salary">
					<VueIcon
						:name="'Payments'"
						:fill="'var(--primary-color)'"
						:width="'26px'"
						:height="'26px'"
					/>
					{{ vacancy.salary }}
				</div>
			</div>
		</div>

		<Empty :minHeight="300" v-else />
	</Block>
</template>

<script>
import VueIcon from "../../../components/modules/icon/VueIcon.vue";
import VueTiptap from "../../../components/modules/VueTiptap.vue";

import InfoBar from "../../../components/ui/main/InfoBar.vue";
import Block from "../../../components/ui/main/Block.vue";
import Empty from "../../../components/modules/Empty.vue";
import LoadText from "../../../components/ui/main/LoadText.vue";

import api from "../../../mixin/api";
import sorted from "../../../services/sorted";
import fakeDelay from "../../../mixin/fake-delay";

export default {
	components: {
		VueIcon,
		VueTiptap,

		InfoBar,
		Block,
		Empty,
		LoadText,
	},
	data() {
		return {
			loading: {
				loader: {
					vacancies: true,
				},
			},

			/* Данные */
			vacancies: [
				{
					id: 1,
					title: "АКУШЕР ГИНЕКОЛОГ",
					address: "Адрес 1",
					schedule: "График 1",
					email: "Email 1",
					phone: "Телефон 1",
					requirements: "Требования 1",
					conditions: "Условия 1",
					salary: "Зарплата 1",
				},
				{
					id: 2,
					title: "АКУШЕР ГИНЕКОЛОГ",
					address: "Адрес 1",
					schedule: "График 1",
					email: "Email 1",
					phone: "Телефон 1",
					requirements: "Требования 1",
					conditions: "Условия 1",
					salary: "Зарплата 1",
				},
			],
		};
	},
	created() {
		// fakeDelay(this.$store.getters.timeout, () =>
		// 	api({
		// 		method: "get",
		// 		url: this.$store.getters.urlApi + `get-travels`,
		// 	})
		// ).then((response) => {
		// 	if (!response) return;
		// 	try {
		// 		for (let i = 0; i < response.data.result.length; i++) {
		// 			this.travels[i] = response.data.result[i];
		// 		}
		// 		this.travels.splice(response.data.result.length, this.travels.length);
		// 		this.loading.loader.travels = false;
		// 	} catch (error) {
		// 		this.$store.commit("addDebugger", {
		// 			title: "Ошибка.",
		// 			body: error,
		// 			type: "error",
		// 		});
		// 	}
		// });
	},
};
</script>

<style scoped>
.vacancies {
	display: grid;
	grid-template-columns: repeat(2, 1fr);
	gap: 10px;

	margin: 0 auto;

	width: 1350px;
}

.vacancies__item {
	display: flex;
	flex-direction: column;
	gap: calc(var(--default-gap) / 2);

	padding: var(--default-padding);

	font-size: 1.125rem;

	background-color: var(--skeleton-background-color);
	border-radius: var(--default-border-radius);
}

.vacancies__item-title {
	font-size: 1.5rem;
	font-weight: 500;

	color: var(--primary-color);
}

:is(.vacancies__item-requirements, .vacancies__item-conditions) {
	display: grid;
	grid-template-columns: max(100px, 30%) 1fr;
	gap: calc(var(--default-gap) / 2);

	padding: calc(var(--default-padding) / 2);
	border-radius: calc(var(--default-border-radius) / 2);

	background-color: white;
}

.vacancies__item-salary {
	display: flex;
	justify-content: space-between;
	align-items: center;
	width: min(100%, 350px);
	align-self: flex-end;
	

	color: var(--primary-color);
	font-size: 1.5rem;
	font-weight: 500;
}

@media screen and (max-width: 1450px) {
	.vacancies {
		width: 100%;
	}
}
</style>
