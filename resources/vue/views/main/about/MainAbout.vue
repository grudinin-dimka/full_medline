<template>
	<info-bar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link to="/about">О нас</router-link>
	</info-bar>

	<block :minHeight="400">
		<template v-if="loading.sections.about">
			<MainAboutList :abouts="abouts" v-if="abouts.length > 0" />
			<Empty :minHeight="300" v-else />
		</template>

		<loader-child
			:isLoading="loading.loader.about"
			@loaderChildAfterLeave="loaderChildAfterLeave"
		/>
	</block>
</template>

<script>
import InfoBar from "../../../components/ui/main/InfoBar.vue";
import LoaderChild from "../../../components/includes/LoaderChild.vue";
import Block from "../../../components/ui/main/blocks/Block.vue";
import MainAboutList from "./MainAboutList.vue";

import Empty from "../../../components/includes/Empty.vue";

import axios from "axios";

export default {
	components: {
		InfoBar,
		LoaderChild,
		Block,
		MainAboutList,
		Empty,
		axios,
	},
	methods: {
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                   Загрузчик                       |*/
		/* |___________________________________________________|*/
		/* После скрытия элементы */
		loaderChildAfterLeave() {
			this.loading.sections.about = true;
		},
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
			abouts: [],
		};
	},
	mounted() {
		this.loading.loader.about = false;

		axios({
			method: "post",
			url: `${this.$store.state.axios.urlApi}` + `get-abouts-all`,
		})
			.then((response) => {
				if (response.data.status) {
					this.abouts = response.data.data;

					this.abouts.sort((a, b) => {
						return a.order - b.order;
					});
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
					body: "Произошла ошибка при получении данных о странице.",
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

<style scoped>
.about {
	display: flex;
	flex-direction: column;
	gap: 20px;

	width: 1350px;
	font-size: 18px;

	animation: show-bottom-to-top-15 0.5s ease-in-out;
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

@media screen and (width <= 1425px) {
	.about {
		width: 100%;
	}
}
</style>
