<template>
	<!-- Дебагер -->
	<VueDebugger />

	<!-- Шапка -->
	<MainHeader ref="mainHeader" />

	<!-- Навигация -->
	<MainNav ref="mainNav" />

	<!-- Содержимое -->
	<RouterView />

	<!-- Подвал -->
	<MainFooter ref="mainFooter" />
</template>

<script>
import VueDebugger from "../components/modules/VueDebugger.vue";
import VueIcon from "../components/modules/icon/VueIcon.vue";

// NOTE: Модули на главной
import MainHeader from "../components/includes/main/medline/MainHeader.vue";
import MainNav from "../components/includes/main/medline/MainNav.vue";
import MainFooter from "../components/includes/main/medline/MainFooter.vue";

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
		VueDebugger,
		VueIcon,

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
