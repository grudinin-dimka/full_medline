<template>
	<info-bar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link to="/news">Новости</router-link>
	</info-bar>

	<block :minHeight="500">
		<div class="news__once">
			<div class="news__once-image" :class="{ skeleton: !path }">
				<img v-if="path" :src="path" alt="Картинка" />
			</div>
			<div class="news__once-title" :class="{ skeleton: !title }">
				<VueTiptap :editable="false" :limit="10_000" v-model="title" />
			</div>
			<div class="news__once-description" :class="{ skeleton: !description }">
				<VueTiptap :editable="false" :limit="10_000" v-model="description" />
			</div>
		</div>
	</block>
</template>

<script>
import VueTiptap from "../../../components/modules/VueTiptap.vue";
import VueLoader from "../../../components/modules/VueLoader.vue";

import InfoBar from "../../../components/ui/main/InfoBar.vue";
import Block from "../../../components/ui/main/Block.vue";
import Empty from "../../../components/modules/Empty.vue";

import api from "../../../services/api";

export default {
	components: {
		VueTiptap,
		VueLoader,
		
		InfoBar,
		Block,

		Empty,
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

			title: "",
			description: "",
			path: null,
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
		api({
			method: "post",
			url: this.$store.getters.urlApi + `get-news-once-without`,
			headers: {
				Accept: "application/json",
			},
			data: {
				date: this.$route.params.date,
				time: this.$route.params.time,
			},
		})
			.then((response) => {
				if (!response) return;

				this.title = response.data.result.title;
				this.description = response.data.result.description;
				this.path = response.data.result.path;
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
.news__once {
	display: flex;
	flex-direction: column;
	gap: 20px;

	width: 1350px;
}

.news__once-image {
	border-radius: var(--default-border-radius);
	min-height: 600px;
	height: 600px;

	background-color: var(--skeleton-background-color);
	padding: var(--default-padding);
}

.news__once-image > img {
	width: 100%;
	height: 100%;

	border-radius: calc(var(--default-border-radius) / 1.5);
	object-fit: contain;

	animation: show 0.5s ease-in-out;
}

.news__once-title {
	border-radius: var(--default-border-radius);

	min-height: 40px;
}

.news__once-description {
	border-radius: var(--default-border-radius);

	min-height: 100px;
}

@media (max-width: 1000px) {
	.news__once > img {
		height: auto;
	}

	.news__once-image {
		height: auto;
		min-height: 250px;
	}
}

@media (max-width: 1450px) {
	.news__once {
		width: 100%;
	}
}
</style>
