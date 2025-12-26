<template>
	<info-bar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link to="/specialists">Специалисты</router-link>
	</info-bar>

	<block :minHeight="500">
		<div class="filters">
			<div class="filters__item filters__item--default">
				<VueValues v-model.trim="filters.name" :type="'search'" :placeholder="'Введите ФИО'">
					<template #label>
						<VueIcon :name="'Id Card'" :fill="'var(--primary-color)'" :width="'20px'" :height="'20px'" />
						ФИО
					</template>
				</VueValues>
			</div>
			<div class="filters__item filters__item--block">
				<VueSelector v-model="filters.clinic" :list="calcClinics" :size="4" :isSearch="true" :isClear="true">
					<template #label>
						<VueIcon :name="'Location On'" :fill="'var(--primary-color)'" :width="'20px'" :height="'20px'" />
						КЛИНИКА
					</template>
				</VueSelector>

				<VueSelector
					v-model="filters.specialization"
					:list="calcSpecializations"
					:size="4"
					:isSearch="true"
					:isClear="true"
				>
					<template #label>
						<VueIcon :name="'Category'" :fill="'var(--primary-color)'" :width="'20px'" :height="'20px'" />
						СПЕЦИАЛИЗАЦИЯ
					</template>
				</VueSelector>

				<!-- <VueSelector v-model="filters.clinic" :placeholder="'Выберите клинику'" :list="calcClinics" />
				<VueSelector
					v-model="filters.specialization"
					:placeholder="'Выберите специализацию'"
					:list="calcSpecializations"
				/> -->
			</div>
		</div>

		<div class="specialists" v-if="getFilteredSpecialists.length > 0">
			<div class="specialists__header">
				<div></div>
				<div>Врач</div>
				<div>Клиника</div>
				<div></div>
			</div>

			<specialists-list :specialists="getFilteredSpecialists" />
		</div>

		<Empty v-else />
	</block>
</template>

<script>
import SpecialistsList from "./MainSpecialistsAllList.vue";

import Block from "../../../components/ui/main/Block.vue";
import InfoBar from "../../../components/ui/main/InfoBar.vue";
import Empty from "../../../components/modules/Empty.vue";

import { RouterLink } from "vue-router";

import api from "../../../mixin/api";
import sorted from "../../../services/sorted";

import fakeDelay from "../../../mixin/fake-delay";

export default {
	components: {
		SpecialistsList,

		Block,
		InfoBar,
		Empty,

		RouterLink,
	},
	data() {
		return {
			specialists: [
				{
					id: 1,
					name: null,
					adultDoctor: null,
					category: null,
					clinics: null,
					link: null,
					path: null,
					rank: null,
					specialization: null,
					url: null,
				},
				{
					id: 2,
					name: null,
					adultDoctor: null,
					category: null,
					clinics: null,
					link: null,
					path: null,
					rank: null,
					specialization: null,
					url: null,
				},
				{
					id: 3,
					name: null,
					adultDoctor: null,
					category: null,
					clinics: null,
					link: null,
					path: null,
					rank: null,
					specialization: null,
					url: null,
				},
				{
					id: 4,
					name: null,
					adultDoctor: null,
					category: null,
					clinics: null,
					link: null,
					path: null,
					rank: null,
					specialization: null,
					url: null,
				},
				{
					id: 5,
					name: null,
					adultDoctor: null,
					category: null,
					clinics: null,
					link: null,
					path: null,
					rank: null,
					specialization: null,
					url: null,
				},
				{
					id: 6,
					name: null,
					adultDoctor: null,
					category: null,
					clinics: null,
					link: null,
					path: null,
					rank: null,
					specialization: null,
					url: null,
				},
				{
					id: 7,
					name: null,
					adultDoctor: null,
					category: null,
					clinics: null,
					link: null,
					path: null,
					rank: null,
					specialization: null,
					url: null,
				},
			],
			loading: {
				loader: {
					specialists: true,
				},
				sections: {
					specialists: false,
				},
			},
			filters: {
				name: "",
				specialization: "",
				clinic: "",
			},
		};
	},
	computed: {
		/* Отфильтрованные специалисты */
		getFilteredSpecialists() {
			let filteredSpecialists = [...this.specialists];

			if (!this.loading.loader.specialists) {
				if (this.filters.specialization !== "") {
					filteredSpecialists = filteredSpecialists.filter((specialist) => {
						if (Array.isArray(specialist.specialization)) {
							for (let i = 0; i < specialist.specialization.length; i++) {
								if (specialist.specialization[i].name == this.filters.specialization) {
									return true;
								}
							}
						}

						if (specialist.specialization !== "" && typeof specialist.specialization == "object") {
							return specialist.specialization.name == this.filters.specialization;
						}
					});
				}

				if (this.filters.clinic !== "") {
					filteredSpecialists = filteredSpecialists.filter((specialist) => {
						if (Array.isArray(specialist.clinics)) {
							for (let i = 0; i < specialist.clinics.length; i++) {
								if (specialist.clinics[i].name == this.filters.clinic) {
									return true;
								}
							}
						}
					});
				}

				if (this.filters.name !== "") {
					filteredSpecialists = filteredSpecialists.filter((item) => {
						if (item.name.toLowerCase().includes(this.filters.name.toLowerCase())) {
							return item;
						}
					});
				}

				sorted.sortStringByKey("up", filteredSpecialists, "name");
			}

			return filteredSpecialists;
		},

		/* Все специализации */
		calcSpecializations() {
			let specalizations = [];

			this.specialists.forEach((specialist) => {
				if (Array.isArray(specialist.specialization)) {
					specialist.specialization.forEach((spec) => {
						if (!specalizations.some((item) => item.label == spec.name)) {
							specalizations.push({
								value: spec.name,
								label: spec.name,
							});
						}
					});

					return;
				}

				if (specialist.specialization !== null && typeof specialist.specialization == "object") {
					specalizations.push({
						value: specialist.specialization.name,
						label: specialist.specialization.name,
					});
				}
			});

			sorted.sortStringByKey("up", specalizations, "label");

			return specalizations;
		},

		calcClinics() {
			let clinics = [];

			this.specialists.forEach((specialist) => {
				if (Array.isArray(specialist.clinics)) {
					specialist.clinics.forEach((clinic) => {
						if (!clinics.some((item) => item.label == clinic.name)) {
							clinics.push({
								value: clinic.name,
								label: clinic.name,
							});
						}
					});

					return;
				}

				if (specialist.clinics !== null && typeof specialist.clinics == "object") {
					clinics.push({
						value: specialist.clinics.name,
						label: specialist.clinics.name,
					});
				}
			});

			sorted.sortStringByKey("up", clinics, "label");

			return clinics;
		},
	},
	methods: {
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                   ЗАГРУЗЧИК                       |*/
		/* |___________________________________________________|*/
		/* После скрытия элементы */
		loaderChildAfterLeave() {
			this.loading.sections.specialists = true;
		},
	},
	mounted() {
		fakeDelay(this.$store.getters.timeout, () =>
			api({
				method: "get",
				url: `get-specialists`,
			})
		).then((response) => {
			if (!response) return;

			try {
				for (let i = 0; i < response.data.result.length; i++) {
					this.specialists[i] = response.data.result[i];
				}

				this.specialists.splice(response.data.result.length, this.specialists.length);

				this.loading.loader.specialists = false;
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
/* Блоки фильтров */
.filters {
	display: flex;
	flex-direction: column;
	gap: var(--default-gap);
}

.filters__item {
	box-sizing: border-box;
	gap: var(--default-gap);

	border: 0px solid var(--input-border-color-inactive);
	border-radius: 15px;

	width: 1350px;
	transition: all 0.2s;
	animation: show 0.5s ease-out;
}

.filters__item--default {
	display: flex;
}

.filters__item--block {
	display: grid;
	grid-template-columns: 1fr 1fr;
}

/* Блок специалистов */
.specialists {
	display: flex;
	flex-direction: column;
	gap: 10px;

	width: 1350px;
	min-height: 400px;
}

.specialists > .specialists__header {
	display: grid;
	grid-template-columns: 110px 1fr 1fr 150px;
	align-items: center;

	color: rgb(150, 150, 150);
	font-size: 1.125rem;
}

@media screen and (width < 1450px) {
	.specialists,
	.filters,
	.filters__item {
		width: 100%;
	}
}

@media screen and (max-width: 1100px) {
	.specialists > .specialists__header {
		display: none;
	}
}

@media screen and (max-width: 700px) {
	.filters__item--block {
		grid-template-columns: 1fr;
	}
}
</style>
