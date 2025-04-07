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
import Debugger from "./modules/Debugger.vue";
import MainHeader from "./includes/main/MainHeader.vue";
import MainNav from "./includes/main/MainNav.vue";
import MainFooter from "./includes/main/MainFooter.vue";
import Loader from "./modules/loader.vue";

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
