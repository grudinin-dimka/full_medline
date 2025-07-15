<template>
	<info-bar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link to="/videos">Видео</router-link>
	</info-bar>

	<block :minHeight="600">
		<template v-if="loading.sections.videos">
			<div class="video" v-if="videos.length > 0">
				<div class="video__item" v-for="video in videos">
					<div class="video__item-video">
						<video controls preload="metadata" :key="video.video">
							<source :src="video.path" :type="`video/${getVideoType(video.video)}`" />
						</video>
					</div>
					<div class="video__item-description">
						<VueTiptap :editable="false" :limit="10_000" v-model="video.description" />
					</div>
				</div>
			</div>

			<Empty :minHeight="300" v-else />
		</template>

		<VueLoader
			:isLoading="loading.loader.videos"
			:isChild="true"
			:minHeight="600"
			@afterLeave="loaderChildAfterLeave"
		/>
	</block>
</template>

<script>
import VueLoader from "../../../components/modules/VueLoader.vue";
import VueTiptap from "../../../components/modules/VueTiptap.vue";

import InfoBar from "../../../components/ui/main/InfoBar.vue";
import Block from "../../../components/ui/main/Block.vue";
import Empty from "../../../components/modules/Empty.vue";

import api from "../../../services/api";
import files from "../../../services/files";

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
			/* Загрузчик */
			loading: {
				loader: {
					videos: true,
				},
				sections: {
					videos: false,
				},
			},

			/* Даннные */
			videos: [],
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

		/* Тип видеофайла */
		getVideoType(value) {
			return files.basetype(value);
		},
	},
	mounted() {
		api({
			method: "get",
			url: `${this.$store.getters.urlApi}` + `get-videos-all`,
		})
			.then((response) => {
				if (!response) return;

				this.videos = response.data.result;
			})
			.catch((error) => {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			})
			.finally(() => {
				this.loading.loader.videos = false;
			});
	},
};
</script>

<style scoped>
.video {
	display: flex;
	flex-direction: column;
	gap: 20px;

	width: 1350px;

	animation: show-bottom-to-top-15 0.5s ease-in-out;
}

.video__item {
	display: flex;
	flex-direction: column;
	justify-content: center;
	align-items: center;
}

.video__item-video {
	border-radius: var(--default-border-radius);
	height: 350px;
	width: 100%;

	background-color: rgba(0, 0, 0);
}

.video__item-video > video {
	width: 100%;
	height: 350px;
	object-fit: contain;
	border-radius: var(--default-border-radius);
}

.video__item-description {
	width: 100%;
}

/* Адаптив */
@media screen and (width <= 1430px) {
	.video {
		width: 100%;
	}
}

@media screen and (width <= 500px) {
	.video__item-video {
		height: auto;
	}

	.video__item-video > video {
		width: 100%;
		height: auto;
		object-fit: contain;
		border-radius: var(--default-border-radius);
	}
}
</style>
