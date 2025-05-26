<template>
	<info-bar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link to="/news">Новости</router-link>
	</info-bar>

	<block :minHeight="500">
		<template v-if="loading.sections.news">
			<div class="news__once">				
				<img :src="path ?? '/storage/default/image-none-default.png'" alt="Картинка" />
				<TipTap :editable="false" :limit="10_000" v-model="title" />
				<TipTap :editable="false" :limit="10_000" v-model="description" />
			</div>
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
import Block from "../../../components/ui/main/Block.vue";

import TipTap from "../../../components/modules/Tiptap.vue";
import Empty from "../../../components/modules/Empty.vue";

import api from "../../../services/api";

export default {
	components: {
		InfoBar,
		LoaderChild,

		Block,

		TipTap,
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

	animation: show-bottom-to-top-15 0.5s ease-in-out;
}

.news__once > img {
	width: 100%;
	object-fit: contain;
	height: 600px;
}

@media (max-width: 1000px) {
	.news__once > img {
		height: auto;
	}
}

@media (max-width: 1450px) {
	.news__once {
		width: 100%;
	}
}
</style>
