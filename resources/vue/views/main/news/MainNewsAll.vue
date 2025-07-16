<template>
	<info-bar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link to="/news">Новости</router-link>
	</info-bar>

	<Block>
		<div class="news__all" v-if="news.length > 0">
			<div class="news__all-body">
				<MainNewsItem v-for="item in news" :key="item.id" :item="item" />
			</div>

			<VueButton
				v-if="isLoadMoreNews"
				:look="'inverse'"
				:disabled="disabled.news.more"
				@click.prevent="loadMoreNews"
			>
				Загрузить ещё
			</VueButton>
		</div>

		<Empty :minHeight="300" v-else />
	</Block>
</template>

<script>
import VueButton from "../../../components/ui/VueButton.vue";

import MainNewsItem from "./MainNewsItem.vue";

import InfoBar from "../../../components/ui/main/InfoBar.vue";
import Block from "../../../components/ui/main/Block.vue";
import Empty from "../../../components/modules/Empty.vue";

import api from "../../../services/api";

export default {
	components: {
		VueButton,

		MainNewsItem,

		InfoBar,
		Block,
		Empty,
	},
	data() {
		return {
			disabled: {
				news: {
					more: false,
				},
			},

			loading: {
				loader: {
					news: true,
				},
				sections: {
					news: false,
				},
			},

			count: 0,
			news: [
				{
					id: 1,
					name: null,
					link: null,
					path: null,
					hide: 0,
					order: 1,
				},
				{
					id: 2,
					name: null,
					link: null,
					path: null,
					hide: 0,
					order: 2,
				},
				{
					id: 3,
					name: null,
					link: null,
					path: null,
					hide: 0,
					order: 3,
				},
				{
					id: 4,
					name: null,
					link: null,
					path: null,
					hide: 0,
					order: 4,
				},
				{
					id: 5,
					name: null,
					link: null,
					path: null,
					hide: 0,
					order: 5,
				},
				{
					id: 6,
					name: null,
					link: null,
					path: null,
					hide: 0,
					order: 6,
				},
			],
		};
	},
	computed: {
		isLoadMoreNews() {
			return this.news.length < this.count;
		},
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

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                    Новости                        |*/
		/* |___________________________________________________|*/
		/* Загрузка новостей */
		loadMoreNews() {
			this.disabled.news.more = true;

			api({
				method: "post",
				url: this.$store.getters.urlApi + `get-news-more`,
				headers: {
					Accept: "application/json",
				},
				data: {
					date: this.news[this.news.length - 1].url_date,
					time: this.news[this.news.length - 1].url_time,
					limit: 3,
				},
			})
				.then((response) => {
					if (!response) return;

					this.news.push(...response.data.result.news);
					this.count = response.data.result.count;
				})
				.catch((error) => {
					this.$store.commit("addDebugger", {
						title: "Ошибка.",
						body: error,
						type: "error",
					});
				})
				.finally(() => {
					this.disabled.news.more = false;
				});
		},
	},
	mounted() {
		api({
			method: "post",
			url: this.$store.getters.urlApi + `get-news-short`,
			headers: {
				Accept: "application/json",
			},
			data: {
				limit: 9,
			},
		})
			.then((response) => {
				if (!response) return;

				this.count = response.data.result.count;

				for (let i = 0; i < response.data.result.news.length; i++) {
					this.news[i] = response.data.result.news[i];
				}

				this.news.splice(response.data.result.news.length, this.news.length);
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
	display: flex;
	flex-direction: column;
	justify-content: justify;
	gap: 30px;

	width: 1350px;
}

.news__all-body {
	display: grid;
	grid-template-columns: repeat(3, 1fr);
	justify-content: justify;
	gap: 30px;

	width: 100%;
}

.news__all-button {
	cursor: pointer;
	width: 100%;

	border: var(--default-border);
	border-radius: var(--default-border-radius);
	padding: 20px;

	background-color: white;
	font-size: 1.125rem;

	transition: all 0.2s ease-out;
}

.news__all-button:hover {
	border: var(--input-border-focus);
	background-color: var(--item-background-color-active);
}

@media screen and (max-width: 1450px) {
	.news__all {
		width: 100%;
	}
}

@media screen and (max-width: 1000px) {
	.news__all-body {
		grid-template-columns: repeat(2, 1fr);
	}
}

@media screen and (max-width: 700px) {
	.news__all-body {
		grid-template-columns: repeat(1, 1fr);
	}
}
</style>
