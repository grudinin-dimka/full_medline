<template>
	<info-bar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link to="/prices">Цены</router-link>
	</info-bar>

	<block :minHeight="400">
		<div class="prices">
			<div class="prices-container">
				<div class="item"></div>
				<div class="name">Виртуальная эндоскопия</div>
				<div class="price">от 3000 руб.</div>
			</div>
		</div>

		<!-- <loader-child :isLoading="loading.loader.about" /> -->
	</block>
</template>

<script>
import InfoBar from "../../../components/ui/main/InfoBar.vue";
import LoaderChild from "../../../components/includes/LoaderChild.vue";
import Block from "../../../components/ui/main/blocks/Block.vue";

export default {
	components: {
		InfoBar,
		LoaderChild,
		Block,
	},
	data() {
		return {
			loading: {
				loader: {
					about: true,
				},
				sections: {
					about: false,
				},
			},
		};
	},
};
</script>

<style scoped>
.prices {
	width: 1350px;
}
</style>
