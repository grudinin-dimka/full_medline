<template>
	<info-bar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link to="/about">О нас</router-link>
	</info-bar>

	<block>
		<MainAboutList />
		<loader-child :isLoading="loading.loader.about" />
	</block>
</template>

<script>
import InfoBar from "../../components/ui/main/InfoBar.vue";
import LoaderChild from "../../components/includes/LoaderChild.vue";
import Block from "../../components/ui/main/blocks/Block.vue";
import MainAboutList from "./about/MainAboutList.vue";

export default {
	components: {
		InfoBar,
		LoaderChild,
		Block,
		MainAboutList,
	},
	data() {
		return {
			loading: {
				loader: {
					about: false,
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
.about {
	display: flex;
	flex-direction: column;
	gap: 20px;

	width: 1250px;
	font-size: 18px;
}

.about > .title {
	font-size: 20px;
	font-weight: bold;

	color: var(--primary-color);
}

.about > .item {
	display: flex;
	flex-direction: column;
	gap: 10px;
}

.images {
	display: flex;
	align-items: center;
	flex-wrap: wrap;
	gap: 10px;
}

.images > .item {
	flex: 1 0 300px;
	border-radius: 10px;
	width: 300px;
	height: 300px;
	background-position: center center;
	background-repeat: no-repeat;
	background-size: cover;
}

@media screen and (width <= 1080px) {
	.about {
		width: auto;
	}
}
</style>
