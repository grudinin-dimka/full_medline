<template>
	<info-bar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link to="/news">Новости</router-link>
	</info-bar>

	<block :minHeight="500">
		<template v-if="loading.sections.news">
			<div class="news__all" v-if="news.length > 0">
				<MainNewsItem v-for="item in news" :key="item.id" :item="item" />
			</div>

			<Empty :minHeight="300" v-else />
		</template>

		<loader-child
			:isLoading="loading.loader.news"
			@loaderChildAfterLeave="loaderChildAfterLeave"
		/>
	</block>
</template>

<script>
import InfoBar from "../../../components/ui/main/InfoBar.vue";
import LoaderChild from "../../../components/modules/LoaderChild.vue";
import Block from "../../../components/ui/main/blocks/Block.vue";

import Empty from "../../../components/modules/Empty.vue";
import MainNewsItem from "./MainNewsItem.vue";

import axios from "axios";

export default {
	components: {
		InfoBar,
		LoaderChild,

		Block,

		Empty,
		MainNewsItem,
	},
	data() {
		return {
			loading: {
				loader: {
					news: true,
				},
				sections: {
					news: false,
				},
			},

			news: [],
		};
	},
	methods: {
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                   Загрузчик                       |*/
		/* |___________________________________________________|*/
		/* После скрытия элементы */
		loaderChildAfterLeave() {
			for (let key in this.loading.loader) {
				if (!this.loading.loader[key]) {
					this.loading.sections[key] = true;
				}
			}
		},
	},
	mounted() {
		axios({
			method: "get",
			url: `${this.$store.getters.urlApi}` + `get-news-all`,
		})
			.then((response) => {
				if (response.data.status) {
					this.news = response.data.data;
				} else {
					this.$store.commit("addDebugger", {
						title: "Ошибка.",
						body: response.data.message,
						type: "error",
					});
				}
			})
			.catch((error) => {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			})
			.finally(() => {
				this.loading.loader.news = false;
			});
	},
};
</script>

<style scoped>
.news__all {
	display: grid;
	grid-template-columns: repeat(3, 1fr);
	gap: 30px;

	width: 1350px;

	animation: show-bottom-to-top-15 0.5s ease-in-out;
}

@media screen and (max-width: 1450px) {
	.news__all {
		width: auto;
	}
}

@media screen and (max-width: 1000px) {
	.news__all {
		grid-template-columns: repeat(2, 1fr);
	}
}

@media screen and (max-width: 700px) {
	.news__all {
		grid-template-columns: repeat(1, 1fr);
	}
}
</style>
