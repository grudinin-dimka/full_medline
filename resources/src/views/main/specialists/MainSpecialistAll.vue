<template>
	<info-bar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link to="/specialists">Специалисты</router-link>
	</info-bar>

	<!-- TODO: Сделать фильтры, в идеале, чтобы при вводе specialists/terapevt выводились врачи -->
	<!-- <filters v-if="loading.sections.specialists" :filters="filters"></filters> -->
	<block>
		<specialists-list :specialists="specialists" v-if="loading.sections.specialists" />
	</block>

	<loader-child
		:isLoading="loading.loader.specialists"
		:minHeight="400"
		@loaderChildAfterLeave="loaderChildAfterLeave"
	/>
</template>

<script>
import Block from "../../../components/ui/main/blocks/Block.vue";
import InfoBar from "../../../components/ui/main/InfoBar.vue";
import Filters from "../../../components/ui/main/Filters.vue";
import { RouterLink } from "vue-router";
import axios from "axios";
import LoaderChild from "../../../components/includes/LoaderChild.vue";
import SpecialistsList from "./MainSpecialistsAllList.vue";

export default {
	components: {
		Block,
		InfoBar,
		Filters,
		RouterLink,
		axios,
		LoaderChild,
		SpecialistsList,
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
			filters: [
				{
					id: 1,
					name: "Хирург",
				},
				{
					id: 2,
					name: "Терапевт",
				},
			],
		};
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
					this.specialists = null;

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
				if (this.specialists != null) {
					this.loading.loader.specialists = false;					
				}
			});
	},
};
</script>

<style scoped></style>
