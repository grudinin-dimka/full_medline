<template>
	<info-bar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link to="/specialists">Специалисты</router-link>
	</info-bar>

	<block>
		<template v-if="loading.sections.specialists">
			<div class="specialists-filters" v-if="loading.sections.specialists">
				<div
					class="item"
					:class="{ active: filterSpecialization.all }"
					@click="setActiveFilter('all')"
				>
					Все
				</div>
				<div
					class="item"
					v-for="specalization in calcSpecializations"
					:key="specalization"
					:class="{ active: specalization == filterSpecialization.selected }"
					@click="setActiveFilter(specalization)"
				>
					{{ specalization }}
				</div>
			</div>

			<div class="animation-list" v-if="getFilteredSpecialists.length > 0">
				<specialists-list :specialists="getFilteredSpecialists" />
			</div>

			<Empty v-else />
		</template>

		<loader-child
			:isLoading="loading.loader.specialists"
			@loaderChildAfterLeave="loaderChildAfterLeave"
		/>
	</block>
</template>

<script>
import Block from "../../../components/ui/main/blocks/Block.vue";
import InfoBar from "../../../components/ui/main/InfoBar.vue";
import Filters from "../../../components/ui/main/Filters.vue";

import LoaderChild from "../../../components/includes/LoaderChild.vue";
import SpecialistsList from "./MainSpecialistsAllList.vue";
import Empty from "../../../components/includes/Empty.vue";

import { RouterLink } from "vue-router";
import axios from "axios";
import sorted from "../../../services/sorted";

export default {
	components: {
		Block,
		InfoBar,
		Filters,
		LoaderChild,
		SpecialistsList,
		RouterLink,
		axios,
		Empty,
	},
	data() {
		return {
			specialists: [],
			loading: {
				loader: {
					specialists: true,
				},
				sections: {
					specialists: false,
				},
			},
			filterSpecialization: {
				all: true,
				selected: null,
			},
		};
	},
	computed: {
		getFilteredSpecialists() {
			let specialists = this.specialists;

			if (this.filterSpecialization.selected !== null) {
				specialists = specialists.filter((specialist) => {
					if (Array.isArray(specialist.specialization)) {
						for (let i = 0; i < specialist.specialization.length; i++) {
							if (specialist.specialization[i].name == this.filterSpecialization.selected) {
								return true;
							}
						}
					}

					if (
						specialist.specialization !== null &&
						typeof specialist.specialization == "object"
					) {
						return specialist.specialization.name == this.filterSpecialization.selected;
					}
				});
			}

			sorted.sortByName("up", specialists);

			return specialists;
		},
		calcSpecializations() {
			let specalizations = [];

			this.specialists.forEach((specialist) => {
				if (Array.isArray(specialist.specialization)) {
					specialist.specialization.forEach((spec) => {
						if (specalizations.includes(spec.name)) {
							return;
						}

						specalizations.push(spec.name);
					});

					return;
				}

				if (
					specialist.specialization !== null &&
					typeof specialist.specialization == "object"
				) {
					specalizations.push(specialist.specialization);
				}
			});

			sorted.sortString("up", specalizations);

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
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                  СПЕЦИАЛИСТЫ                      |*/
		/* |___________________________________________________|*/
		setActiveFilter(name) {
			if (name == "all") {
				this.filterSpecialization.all = true;
				this.filterSpecialization.selected = null;
				return;
			}

			this.filterSpecialization.all = false;
			this.filterSpecialization.selected = name;
		},
	},
	mounted() {
		// Получение массива докторов с сервера
		axios({
			method: "post",
			url: `${this.$store.state.axios.urlApi}` + `get-specialists`,
		})
			.then((response) => {
				if (response.data.status) {
					this.specialists = response.data.data;
				} else {
					let debbugStory = {
						title: "Ошибка.",
						body: response.data.message,
						type: "Error",
					};
					this.$store.commit("debuggerState", debbugStory);
				}
			})
			.catch((error) => {
				let debbugStory = {
					title: "Ошибка.",
					body: "Произошла ошибка при получении данных о слайдере.",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
			})
			.finally(() => {
				this.loading.loader.specialists = false;
			});
	},
};
</script>

<style scoped>
.animation-list {
	display: flex;
	flex-direction: column;
	gap: 10px;

	width: 1350px;
	min-height: 400px;

	animation: show-bottom-to-top-15 0.5s ease-in-out;
}

.specialists-filters {
	display: flex;
	flex-wrap: wrap;
	gap: 10px;

	width: 1350px;

	animation: show-bottom-to-top-15 0.5s ease-in-out;
}

.specialists-filters > .item {
	cursor: pointer;
	padding: 5px;

	color: rgb(150, 150, 150);

	/* border-top: 2px;
	border-top-color: rgba(255, 255, 255, 0);
	border-right: 2px;
	border-right-color: rgba(255, 255, 255, 0);
	border-bottom: 2px;
	border-bottom-color: var(--input-border-color-inactive);
	border-left: 2px;
	border-left-color: rgba(255, 255, 255, 0);
	border-style: solid; */

	font-size: 1.125rem;

	transition: all 0.2s;
}

.specialists-filters > .item:is(:hover, .active) {
	color: black;
	/* border-top: 2px;
	border-top-color: rgba(255, 255, 255, 0);
	border-right: 2px;
	border-right-color: rgba(255, 255, 255, 0);
	border-bottom: 2px;
	border-bottom-color: var(--primary-color);
	border-left: 2px;
	border-left-color: rgba(255, 255, 255, 0);
	border-style: solid; */
}

@media screen and (width < 1450px) {
	.animation-list,
	.specialists-filters {
		width: 100%;
	}
}
</style>
