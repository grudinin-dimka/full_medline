<template>
	<InfoBar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link :to="`/vacancies`">Вакансии</router-link>
	</InfoBar>

	<Block :minHeight="100">
		<div class="vacancies" v-if="vacancies.length > 0">
			<div
				class="vacancies__item"
				v-for="vacancy in vacancies"
				:class="{ 'skeleton': loading.loader.vacancies }"
			>
				<div class="vacancies__item-header">
					<div class="vacancies__item-title">
						{{ vacancy.title }}
					</div>

					<div class="vacancies__item-salary" v-if="vacancy.salary">
						<VueIcon
							:name="'Payments'"
							:fill="'var(--primary-color)'"
							:width="'26px'"
							:height="'26px'"
						/>
						{{ vacancy.salary.toLocaleString("ru-RU") }} ₽
					</div>
				</div>
				<div class="vacancies__item-body">
					<div class="vacancies__item-address" v-if="vacancy.address">
						<VueIcon
							:name="'Location On'"
							:fill="'var(--primary-color)'"
							:width="'24px'"
							:height="'24px'"
						/>
						{{ vacancy.address }}
					</div>
					<div class="vacancies__item-schedule" v-if="vacancy.schedule">
						<VueIcon
							:name="'Calendar Month'"
							:fill="'var(--primary-color)'"
							:width="'24px'"
							:height="'24px'"
						/>
						{{ vacancy.schedule }}
					</div>
					<div class="vacancies__item-info">
						<div class="vacancies__item-requirements" v-if="vacancy.requirements">
							<div class="vacancies__requirements-title">Требования:</div>
							<div class="vacancies__requirements-body">
								<VueTiptap
									:editable="false"
									:limit="1_000"
									v-model="vacancy.requirements"
								/>
							</div>
						</div>
						<div class="vacancies__item-conditions" v-if="vacancy.conditions">
							<div class="vacancies__conditions-title">Условия:</div>
							<div class="vacancies__conditions-body">
								<VueTiptap :editable="false" :limit="1_000" v-model="vacancy.conditions" />
							</div>
						</div>
					</div>

					<div class="vacancies__item-description" v-if="vacancy.description">
						<VueTiptap :editable="false" :limit="1_000" v-model="vacancy.description" />
					</div>

					<div class="vacancies__item-salary" v-if="vacancy.salary">
						<VueIcon
							:name="'Payments'"
							:fill="'var(--primary-color)'"
							:width="'26px'"
							:height="'26px'"
						/>
						{{ vacancy.salary.toLocaleString("ru-RU") }} ₽
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
					title: "",
					address: "",
					schedule: "",
					requirements: "",
					conditions: "",
					description: "",
					salary: 0,
				},
				{
					id: 2,
					title: "",
					address: "",
					schedule: "",
					requirements: "",
					conditions: "",
					description: "",
					salary: 0,
				},
				{
					id: 3,
					title: "",
					address: "",
					schedule: "",
					requirements: "",
					conditions: "",
					description: "",
					salary: 0,
				},
			],
		};
	},
	created() {
		fakeDelay(this.$store.getters.timeout, () =>
			api({
				method: "get",
				url: this.$store.getters.urlApi + `get-vacancies`,
			})
		).then((response) => {
			if (!response) return;

			try {
				for (let i = 0; i < response.data.result.length; i++) {
					this.vacancies[i] = response.data.result[i];
				}

				this.vacancies.splice(response.data.result.length, this.vacancies.length);

				this.loading.loader.vacancies = false;
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

.vacancies__item.skeleton {
	min-height: 400px;
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
	width: min(100%, 200px);
	align-self: flex-end;

	color: var(--primary-color);
	font-size: 1.5rem;
	font-weight: 500;
}

.vacancies__item-body .vacancies__item-salary {
	display: none;
}

.vacancies__item-body {
	display: flex;
	flex-direction: column;
	gap: calc(var(--default-gap) / 2);
}

.vacancies__item-address, .vacancies__item-schedule {
	display: flex;
	align-items: center;
	gap: 5px;
}

:is(.vacancies__item-requirements, .vacancies__item-conditions) {
	box-sizing: border-box;
	width: 100%;
	display: grid;
	grid-template-columns: 150px 1fr;
	gap: calc(var(--default-gap) / 2);

	padding: calc(var(--default-padding) / 2);
	border-radius: calc(var(--default-border-radius) / 2);

	background-color: white;

	animation: show 0.5s ease;
}

@media screen and (max-width: 1450px) {
	.vacancies {
		width: 100%;
	}
}

@media screen and (max-width: 1000px) {
	.vacancies__item-info {
		flex-direction: column;
	}
}

@media screen and (max-width: 700px) {
	.vacancies__item-header > .vacancies__item-salary {
		display: none;
	}

	.vacancies__item-body > .vacancies__item-salary {
		display: flex;
	}

	:is(.vacancies__item-requirements, .vacancies__item-conditions) {
		grid-template-columns: 1fr;
	}

	:is(.vacancies__requirements-title, .vacancies__conditions-title) {
		border: var(--default-border);
		border-radius: calc(var(--default-border-radius) / 2);
		padding: 5px 10px;
	}
}
</style>
