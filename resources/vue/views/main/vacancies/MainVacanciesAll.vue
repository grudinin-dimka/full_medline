<template>
	<InfoBar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link :to="`/vacancies`">Вакансии</router-link>
	</InfoBar>

	<Block :minHeight="100">
		<div class="vacancies" v-if="vacancies.length > 0">
			<div class="vacancies__item" v-for="vacancy in vacancies" :class="{ 'skeleton': false }">
				<div class="vacancies__item-header">
					<div class="vacancies__item-title">
						{{ vacancy.title }}
					</div>

					<div class="vacancies__item-salary">
						<VueIcon
							:name="'Payments'"
							:fill="'var(--primary-color)'"
							:width="'26px'"
							:height="'26px'"
						/>
						{{ vacancy.salary }} ₽
					</div>
				</div>
				<div class="vacancies__item-body">
					<div class="vacancies__item-address">Адресс: {{ vacancy.address }}</div>
					<div class="vacancies__item-schedule">График: {{ vacancy.schedule }}</div>
					<div class="vacancies__item-info">
						<div class="vacancies__item-requirements">
							<div class="vacancies__requirements-title">Требования:</div>
							<div class="vacancies__requirements-body">
								<VueTiptap
									:editable="false"
									:limit="1_000"
									v-model="vacancy.requirements"
								/>
							</div>
						</div>
						<div class="vacancies__item-conditions">
							<div class="vacancies__conditions-title">Условия:</div>
							<div class="vacancies__conditions-body">
								<VueTiptap :editable="false" :limit="1_000" v-model="vacancy.conditions" />
							</div>
						</div>
					</div>

					<div class="vacancies__item-description">
						<VueTiptap :editable="false" :limit="1_000" v-model="vacancy.description" />
					</div>
				</div>
			</div>
		</div>

		<Empty :minHeight="300" v-else />
	</Block>
</template>

<script>
import VueIcon from "../../../components/modules/icon/VueIcon.vue";
import VueTiptap from "../../../components/modules/VueTiptap.vue";
import VueButton from "../../../components/ui/VueButton.vue";

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
		VueButton,

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
					title: "Врач травматолог-ортопед",
					address: "г. Москва: ул. Угрешская, д.2, стр.7",
					schedule: "Сменный",
					requirements: `
						<p>Оказание квалифицированной медицинской помощи по своей специальности, используя современные методы диагностики и лечения, разрешенные для применения в медицинской практике.</p>
						<p>В качестве требований предлагаются:</p>
						<ul>
							<li>Требования 1</li>	
							<li>Требования 1</li>	
							<li>Требования 1</li>	
							<li>Требования 1</li>	
						</ul>
					`,
					conditions: `
						<p>Оказание квалифицированной медицинской помощи по своей специальности, используя современные методы диагностики и лечения, разрешенные для применения в медицинской практике.</p>
						<p>В качестве требований предлагаются:</p>
						<ul>
							<li>Требования 1</li>	
							<li>Требования 1</li>	
						</ul>
					`,
					description: `
						<p style="text-align: right;">
							Почта (для резюме):
							<a href="mailto:vlasovpa@medline45.ru">vlasovpa@medline45.ru</a>
						</p>
						<p style="text-align: right;">
							Телефон (для вопросов):
							<a href="tel:+7 963 007 00 13">+7 963 007 00 13</a>
						</p>
					`,
					salary: 45_000,
				},
				{
					id: 2,
					title: "Врач онкогинеколог",
					address: "Адрес 1",
					schedule: "График 1",
					requirements: "<p>Требования 2</p>",
					conditions: "Условия 1",
					description: `
						<p style="text-align: right;">
							Почта (для резюме):
							<a href="mailto:vlasovpa@medline45.ru">vlasovpa@medline45.ru</a>
						</p>
						<p style="text-align: right;">
							Телефон (для вопросов):
							<a href="tel:+7 963 007 00 13">+7 963 007 00 13</a>
						</p>
					`,
					salary: 50_000,
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
	grid-template-columns: repeat(1, 1fr);
	gap: var(--default-gap);

	margin: 0 auto;

	width: 1350px;
}

.vacancies__item {
	display: flex;
	flex-direction: column;
	gap: calc(var(--default-gap) / 2);

	padding: var(--default-padding);
	border-radius: var(--default-border-radius);

	font-size: 1.125rem;

	background-color: var(--skeleton-background-color);
}

.vacancies__item-header {
	display: flex;
	justify-content: space-between;
	gap: 20px;
}

.vacancies__item-info {
	display: flex;
	gap: calc(var(--default-gap) / 2);
}

.vacancies__item-title {
	font-size: 1.5rem;
	font-weight: 500;

	color: var(--primary-color);
}

.vacancies__item-salary {
	display: flex;
	justify-content: space-between;
	align-items: center;
	width: min(100%, 150px);
	align-self: flex-end;

	color: var(--primary-color);
	font-size: 1.5rem;
	font-weight: 500;
}

.vacancies__item-body {
	display: flex;
	flex-direction: column;
	gap: calc(var(--default-gap) / 2);
}

:is(.vacancies__item-requirements, .vacancies__item-conditions) {
	flex-grow: 1;
	display: grid;
	grid-template-columns: 150px 1fr;
	gap: calc(var(--default-gap) / 2);

	padding: calc(var(--default-padding) / 2);
	border-radius: calc(var(--default-border-radius) / 2);

	background-color: white;
}

@media screen and (max-width: 1450px) {
	.vacancies {
		width: 100%;
	}
}
</style>
