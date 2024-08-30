<template>
	<!-- Дебагер -->
	<debugger />

	<!-- Модальное окно -->
	<modal/>
	<!-- Шапка -->
	<main-header />
	<!-- Навигация -->
	<main-nav />
	<!-- Содержимое -->
	<router-view />
	<!-- Подвал -->
	<main-footer />
</template>

<script>
import Debugger from "./includes/Debugger.vue";
import Modal from "./includes/main/MainModal.vue";
import MainHeader from "./includes/main/MainHeader.vue";
import MainNav from "./includes/main/MainNav.vue";
import MainFooter from "./includes/main/MainFooter.vue";
import Loader from "./includes/loader.vue";

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
		Modal,
		MainHeader,
		MainNav,
		MainFooter,
	},
};
</script>

<style scoped></style>
