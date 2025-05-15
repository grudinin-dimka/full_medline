<template>
	<!-- Дебагер -->
	<debugger />

	<!-- Шапка -->
	<main-header ref="mainHeader" />
	<!-- Навигация -->
	<main-nav @openModal="openModal" />
	<!-- Содержимое -->
	<router-view />
	<!-- Подвал -->
	<main-footer />
</template>

<script>
import Debugger from "../components/modules/Debugger.vue";

// NOTE: Модули на главной
import MainHeader from "../components/includes/main/medline/MainHeader.vue";
import MainNav from "../components/includes/main/medline/MainNav.vue";
import MainFooter from "../components/includes/main/medline/MainFooter.vue";

import axios from "axios";

export default {
	data() {
		return {
			loader: {
				loading: true,
				other: false,
			},
		};
	},
	components: {
		Debugger,
		MainHeader,
		MainNav,
		MainFooter,
	},
	methods: {
		openModal(title) {
			this.$refs.mainHeader.openModalEdite(title);
		},
	},
	created() {
		axios({
			method: "post",
			url: `${this.$store.getters.urlApi}` + `create-track`,
			headers: {
				Accept: "application/json",
				Authorization: `Bearer ${localStorage.getItem("token")}`,
			},
			data: {
				type: "page_load",
				meta: "Главная страница",
			},
		}).catch((error) => {
			this.$store.commit("addDebugger", {
				title: "Ошибка.",
				body: error,
				type: "error",
			});
		});
	},
};
</script>

<style>
.router-link-active {
	font-size: 20px;
	text-decoration: none;
	color: black;
	transition: color 0.15s;
}

.router-link-active:hover {
	color: var(--primary-color);
}

.link-arrow {
	font-size: 20px;
}
</style>
