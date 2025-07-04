<template>
	<info-bar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link to="/specialists">Специалисты</router-link>
	</info-bar>

	<block :minHeight="500">
		<div class="filter-blocks">
			<VueInput v-model="filters.name" :type="'search'" :placeholder="'Введите ФИО'" />

			<Selector
				v-model="filters.specialization"
				:placeholder="'Выберите специализацию'"
				:list="calcSpecializations"
			/>
		</div>

		<div class="animation-list" v-if="getFilteredSpecialists.length > 0">
			<div class="list-header">
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
import Block from "../../../components/ui/main/Block.vue";
import InfoBar from "../../../components/ui/main/InfoBar.vue";

import Filters from "../../../components/ui/main/Filters.vue";
import Selector from "../../../components/modules/VueSelector.vue";
import VueInput from "../../../components/modules/input/VueInput.vue";

import LoaderChild from "../../../components/modules/LoaderChild.vue";
import SpecialistsList from "./MainSpecialistsAllList.vue";
import Empty from "../../../components/modules/Empty.vue";

import { RouterLink } from "vue-router";

import api from "../../../services/api";
import sorted from "../../../services/sorted";

export default {
	components: {
		Block,
		InfoBar,

		Filters,
		Selector,
		VueInput,

		LoaderChild,
		SpecialistsList,
		RouterLink,

		Empty,
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
			},
		};
	},
	computed: {
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

						if (
							specialist.specialization !== "" &&
							typeof specialist.specialization == "object"
						) {
							return specialist.specialization.name == this.filters.specialization;
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

				sorted.sortByName("up", filteredSpecialists);
			}

			return filteredSpecialists;
		},
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

				if (
					specialist.specialization !== null &&
					typeof specialist.specialization == "object"
				) {
					specalizations.push({
						value: specialist.specialization.name,
						label: specialist.specialization.name,
					});
				}
			});

			sorted.sortStringByKey("up", specalizations, "label");

			return specalizations;
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
		api({
			method: "get",
			url: this.$store.getters.urlApi + `get-specialists`,
		})
			.then((response) => {
				for (let i = 0; i < response.data.result.length; i++) {
					this.specialists[i] = response.data.result[i];
				}

				this.specialists.splice(response.data.result.length, this.specialists.length);
			})
			.catch((error) => {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			})
			.finally(() => {
				this.loading.loader.specialists = false;
			});
	},
};
</script>

<style scoped>
/* Блоки фильтров */
.filter-blocks {
	box-sizing: border-box;
	display: grid;
	grid-template-columns: 1fr 1fr;
	flex-wrap: wrap;
	gap: 10px;

	border: 0px solid var(--input-border-color-inactive);
	border-radius: 15px;

	width: 1350px;
	transition: all 0.2s;
	animation: show 0.5s ease-out;
}

.filter-blocks > .item {
	position: relative;
	flex: 1 0 350px;

	transition: all 0.2s;
}

.filter-blocks > .item.active {
	border: 2px solid #44a533;
}

.filter-blocks > .container-input {
	position: relative;
	display: grid;
	grid-template-columns: 1fr auto;
}

.filter-blocks > .container-input > input {
	box-sizing: border-box;

	padding: 15px;
	border-radius: var(--input-border-radius);
	border: var(--input-border);
	outline: none;

	font-size: 1.125rem;

	height: 58px;

	transition: all 0.2s;
	caret-color: var(--input-border-color-active);
}

.filter-blocks > .container-input > input:focus {
	border: var(--input-border-focus);
}

.filter-blocks > .container-input > button {
	position: absolute;
	top: 0px;
	right: 5px;
	cursor: pointer;
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 5px;

	height: 58px;

	border-radius: 10px;
	border: 0px solid white;

	font-size: 1.125rem;
	color: white;
	background-color: rgba(0, 0, 0, 0);
}

.filter-blocks > .container-input > button > svg {
	fill: rgba(0, 0, 0, 1);
}

.filter-blocks > .container-input > button > svg:hover {
	fill: rgba(0, 0, 0, 1);
}

.filter-button.active {
	background-color: #f2feff;
	border: 2px solid var(--input-border-color-active);
}

.clear-filter {
	position: absolute;
	cursor: pointer;

	top: 0px;
	right: 0px;
	color: var(--button-remove-color);
}

.clear-filter:hover {
	text-decoration: underline;
}

.clear-filter > .point {
	position: absolute;
	top: 2px;

	border-radius: 30px;

	width: 16px;
	height: 16px;
}

.clear-filter > .point.default {
	left: 2px;
	background-color: #b6b6b6;
}

.clear-filter > .point.active {
	right: 2px;
	background-color: var(--primary-color);
}

.animation-list {
	display: flex;
	flex-direction: column;
	gap: 10px;

	width: 1350px;
	min-height: 400px;
}

.animation-list > .list-header {
	display: grid;
	grid-template-columns: 110px 1fr 1fr 150px;
	align-items: center;

	color: rgb(150, 150, 150);
	font-size: 1.125rem;
}

.specialists-filters {
	display: grid;
	grid-template-columns: 1fr 1fr;
	gap: 10px;

	width: 1350px;

	animation: show-bottom-to-top-15 0.5s ease-in-out;
}

.specialists-filters > .title {
	display: flex;
	align-items: center;
	font-size: 1.5rem;
	font-weight: 600;
}

.specialists-filters > .item {
	cursor: pointer;
	padding: 5px;

	border-top: 2px;
	border-top-color: rgba(255, 255, 255, 0);
	border-right: 2px;
	border-right-color: rgba(255, 255, 255, 0);
	border-bottom: 2px;
	border-bottom-color: var(--input-border-color-inactive);
	border-left: 2px;
	border-left-color: rgba(255, 255, 255, 0);
	border-style: solid;

	font-size: 1.125rem;

	transition: all 0.2s;
}

.specialists-filters > .item:is(:hover, .active) {
	color: black;
	border-top: 2px;
	border-top-color: rgba(255, 255, 255, 0);
	border-right: 2px;
	border-right-color: rgba(255, 255, 255, 0);
	border-bottom: 2px;
	border-bottom-color: var(--primary-color);
	border-left: 2px;
	border-left-color: rgba(255, 255, 255, 0);
	border-style: solid;
}

@media screen and (width < 1450px) {
	.animation-list,
	.specialists-filters,
	.filter-blocks {
		width: 100%;
	}
}

@media screen and (max-width: 1100px) {
	.animation-list > .list-header {
		display: none;
	}
}

@media screen and (max-width: 700px) {
	.filter-blocks {
		grid-template-columns: 1fr;
	}
}
</style>
