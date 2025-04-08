<template>
	<!-- Дебагер -->
	<debugger />

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
import Debugger from "../components/modules/Debugger.vue";
import MainHeader from "../components/includes/main/MainHeader.vue";
import MainNav from "../components/includes/main/MainNav.vue";
import MainFooter from "../components/includes/main/MainFooter.vue";

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
