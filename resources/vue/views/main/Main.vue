<template>
	<VueSnow />

	<!-- Дебагер -->
	<VueDebugger />

	<!-- Шапка -->
	<MainHeader ref="mainHeader" />

	<!-- Навигация -->
	<MainNav ref="mainNav" />

	<MainAside />

	<!-- События -->
	<MainActions ref="mainActions" />

	<!-- Содержимое -->
	<RouterView />

	<!-- Подвал -->
	<MainFooter ref="mainFooter" />
</template>

<script>
import VueSnow from "../../components/ui/VueSnow.vue"

// NOTE: Модули на главной
import MainHeader from "../../components/includes/main/medline/MainHeader.vue";
import MainNav from "../../components/includes/main/medline/MainNav.vue";
import MainFooter from "../../components/includes/main/medline/MainFooter.vue";
import MainActions from "../../components/includes/main/medline/MainActions.vue";
import MainAside from "../../components/includes/main/medline/MainAside.vue";

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
		VueSnow,

		MainHeader,
		MainNav,
		MainFooter,
		MainActions,
		MainAside,
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
