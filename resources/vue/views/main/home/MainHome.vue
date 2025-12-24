<template>
	<block :minHeight="400">
		<MainHomeSlides v-if="slides.length > 0" class="slider__loader" :slides="slides" />
		<Empty :minHeight="400" v-else />

		<div class="section-info">
			<p class="section-info-title">Добро Пожаловать в {{ name }}</p>
			<p class="section-info-sub-title">Индивидуальный подход и лучшие врачи</p>
		</div>
	</block>

	<block :minHeight="450">
		<div class="news__main">
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
	</block>
</template>

<script>
import Block from "../../../components/ui/main/Block.vue";
import Empty from "../../../components/modules/Empty.vue";

import MainHomeSlides from "./MainHomeSlides.vue";
import MainNewsItem from "../news/MainNewsItem.vue";

import api from "../../../mixin/api.js";
import sorted from "../../../services/sorted.js";

import fakeDelay from "../../../mixin/fake-delay.js";

export default {
	components: {
		Block,
		Empty,

		MainHomeSlides,
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

			name: import.meta.env.VITE_MC_NAME,

			/* Данные */
			slides: [
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
			],
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
			],
		};
	},
	mounted() {
		fakeDelay(this.$store.getters.timeout, () =>
			api({
				method: "post",
				url: this.$store.getters.urlApi + "get-news-short",
				headers: {
					Accept: "application/json",
				},
				data: {
					limit: 6,
					tracking: false,
				},
			})
		).then((response) => {
			if (!response) return;

			try {
				sorted.sortNumberByKey("up", response.data.result.news, "order");

				for (let i = 0; i < response.data.result.news.length; i++) {
					this.news[i] = response.data.result.news[i];
				}

				this.news.splice(response.data.result.news.length, this.news.length);

				this.loading.loader.news = false;
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			}
		});

		fakeDelay(this.$store.getters.timeout, () =>
			api({
				method: "get",
				url: this.$store.getters.urlApi + "get-slides-not-hide",
			})
		).then((response) => {
			if (!response) return;

			try {
				sorted.sortNumberByKey("up", response.data.result, "order");

				for (let i = 0; i < response.data.result.length; i++) {
					this.slides[i] = response.data.result[i];
				}

				this.slides.splice(response.data.result.length, this.slides.length);

				this.loading.loader.slider = false;
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			}
		});
	},
};
</script>

<style scoped>
p {
	margin: 0;
	padding: 0;
}

.section-info {
	text-align: center;
	text-transform: uppercase;
}

.section-info-title {
	color: var(--primary-color);
	font-size: 3.5rem;
	font-weight: 700;
}

.section-info-sub-title {
	font-size: 2rem;
	font-weight: 700;
}

.news__main {
	display: flex;
	flex-direction: column;
	gap: 20px;

	width: 1350px;
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
		width: 100%;
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

@media screen and (max-width: 850px) {
	.slider__loader {
		margin-top: 70px;
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
