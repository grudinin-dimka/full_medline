<template>
	<info-bar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link to="/specialists">Специалисты</router-link>
	</info-bar>

	<loader-child :isLoading="isLoading" />

	<filters v-if="isSpecialist"></filters>
	<block v-if="isSpecialist">
		<specialists-list :specialists="specialists"/>
	</block>
</template>

<script>
import Block from "../../../components/ui/main/Block.vue";
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
			isLoading: true,
			isSpecialist: false,
		};
	},
	methods: {
		openDoctorProfile(id) {
			window.scrollTo({
				top: 0,
				left: 0,
				behavior: "smooth",
			});
			this.$router.push("/specialists/" + id);
		},
	},
	mounted() {
		// Получение массива докторов с сервера
		axios({
			method: "post",
			url: `${this.$store.state.axios.urlApi}` + `get-specialists`,
		})
			.then((response) => {
				this.isLoading = false;
				setTimeout(() => {
					this.isSpecialist = true;					
				}, 500)
				this.specialists = response.data;
			})
			.catch((error) => {
				let debbugStory = {
					title: "Ошибка.",
					body: "Произошла ошибка при получении данных о слайдере.",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
			});
	},
};
</script>

<style scoped>
.list-enter-active,
.list-leave-active {
	transition: all 0.5s ease;
}
.list-enter-from,
.list-leave-to {
	opacity: 0;
	transform: translateX(30px);
}
</style>
