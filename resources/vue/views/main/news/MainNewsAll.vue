<template>
	<info-bar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link to="/news">Новости</router-link>
	</info-bar>

	<block :minHeight="500">
		<template v-if="loading.sections.news">
			<div class="news__all" v-if="news.length > 0">
				<div class="news__all-body">
					<MainNewsItem v-for="item in news" :key="item.id" :item="item" />
				</div>

				<ButtonDefault
					v-if="isLoadMoreNews"
					:look="'white'"
					:disabled="disabled.news.more"
					@click.prevent="loadMoreNews"
				>
					Загрузить ещё
				</ButtonDefault>
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

import ButtonDefault from "../../../components/ui/admin/buttons/ButtonDefault.vue";

import Empty from "../../../components/modules/Empty.vue";
import MainNewsItem from "./MainNewsItem.vue";

import axios from "axios";

export default {
	components: {
		InfoBar,
		LoaderChild,
		Block,

		ButtonDefault,

		Empty,
		MainNewsItem,
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
			news: [],
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

			axios({
				method: "post",
				url: `${this.$store.getters.urlApi}` + `get-news-more`,
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
					if (response.data.status) {
						this.news.push(...response.data.data.news);
						this.count = response.data.data.count;
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
					this.disabled.news.more = false;
				});
		},
	},
	mounted() {
		axios({
			method: "post",
			url: `${this.$store.getters.urlApi}` + `get-news-short`,
			headers: {
				Accept: "application/json",
			},
			data: {
				limit: 9,
			},
		})
			.then((response) => {
				if (response.data.status) {
					this.news = response.data.data.news;
					this.count = response.data.data.count;
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
	display: flex;
	flex-direction: column;
	gap: 30px;

	width: 1350px;

	animation: show-bottom-to-top-15 0.5s ease-in-out;
}

.news__all-body {
	display: grid;
	grid-template-columns: repeat(3, 1fr);
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
		width: auto;
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
