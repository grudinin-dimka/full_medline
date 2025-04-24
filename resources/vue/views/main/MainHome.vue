<template>
	<block :minHeight="400">
		<template v-if="loading.sections.slider">
			<template v-if="slides.length > 0">
				<Slider :slides="slides" />
			</template>
			<Empty :minHeight="400" v-else />
		</template>

		<loader-child
			:isLoading="loading.loader.slider"
			:minHeight="403"
			@loaderChildAfterLeave="loaderChildAfterLeave"
		/>

		<div class="section-info">
			<p class="section-info-title">Добро Пожаловать в "Медлайн"</p>
			<p class="section-info-sub-title">Индивидуальный подход и лучшие врачи</p>
		</div>

		<div class="news__main" v-if="loading.sections.news">
			<div class="news__main-head">
				<div class="news__head-title">Новости</div>
				<button class="news__head-button" @click="$router.push({ name: 'news-all' })">
					Смотреть все
				</button>
			</div>
			<div class="news__main-body" v-if="news.length > 0">
				<MainNewsItem v-for="item in news" :key="item.id" :item="item" />
			</div>
			<Empty :minHeight="300" v-else />
		</div>

		<loader-child
			:isLoading="loading.loader.news"
			:minHeight="403"
			@loaderChildAfterLeave="loaderChildAfterLeave"
		/>
	</block>
</template>

<script>
import Slider from "../../components/modules/Slider.vue";
import Block from "../../components/ui/main/blocks/Block.vue";
import Empty from "../../components/modules/Empty.vue";
import LoaderChild from "../../components/modules/LoaderChild.vue";

import MainNewsItem from "./news/MainNewsItem.vue";

import axios from "axios";
import sorted from "../../services/sorted.js";

export default {
	components: {
		Slider,
		Block,
		Empty,
		LoaderChild,

		MainNewsItem,
	},
	data() {
		return {
			loading: {
				loader: {
					slider: true,
					news: true,
				},
				sections: {
					slider: false,
					news: false,
				},
			},

			/* Данные */
			slides: [],
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
			method: "post",
			url: `${this.$store.getters.urlApi}` + `get-news-short`,
			headers: {
				Accept: "application/json",
			},
			data: {
				limit: 6,
			}
		})
			.then((response) => {
				if (response.data.status) {
					this.news = response.data.data.news;
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

		// Получение массива слайдов с сервера
		axios({
			method: "get",
			url: `${this.$store.getters.urlApi}` + `get-slides-not-hide`,
		})
			.then((response) => {
				this.slides = response.data;
				sorted.sortByOrder("up", this.slides);
			})
			.catch((error) => {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			})
			.finally(() => {
				this.loading.loader.slider = false;
			});
	},
};
</script>

<style scoped>
p {
	margin: 0;
	padding: 0;
	font-size: 30px;
	font-weight: 700;
}

.section-info {
	text-align: center;
	text-transform: uppercase;
}

.section-info-title {
	color: #00abbd;
	font-size: 55px;
}

.section-info-sub-title {
	font-size: 35px;
}

.news__main {
	display: flex;
	flex-direction: column;
	gap: 20px;

	width: 1350px;

	animation: show 0.5s ease-in-out;
}

.news__main-head {
	display: flex;
	justify-content: space-between;
	gap: 20px;
}

.news__head-title {
	font-size: 1.5rem;
	font-weight: bold;
	color: var(--primary-color);
}

.news__head-button {
	cursor: pointer;
	border: 0px;

	font-size: 1.25rem;

	background-color: rgba(0, 0, 0, 0);
	color: rgba(0, 0, 0, 0.5);
}

.news__head-button:hover {
	color: rgba(0, 0, 0, 1);
}

.news__main-body {
	display: grid;
	justify-content: space-between;
	gap: 20px;

	grid-template-columns: repeat(3, 1fr);
}

@media screen and (max-width: 1450px) {
	.news__main {
		width: auto;
	}
}

@media screen and (max-width: 1000px) {
	.news__main-body {
		grid-template-columns: repeat(2, 1fr);
	}
}

@media screen and (max-width: 900px) {
	.section-info-title {
		font-size: 40px;
	}

	.section-info-sub-title {
		font-size: 25px;
	}
}

@media screen and (max-width: 700px) {
	.news__main-body {
		grid-template-columns: repeat(1, 1fr);
	}
}

@media screen and (max-width: 500px) {
	.section-info-title {
		font-size: 30px;
	}

	.section-info-sub-title {
		font-size: 20px;
	}
}
</style>
