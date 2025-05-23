<template>
	<info-bar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link to="/videos">Видео</router-link>
	</info-bar>

	<block>
		<template v-if="loading.sections.videos">
			<div class="video" v-if="videos.length > 0">
				<div class="video__item" v-for="video in videos">
					<div class="video__item-video">
						<video controls preload="metadata" :key="video.video">
							<source :src="video.path" :type="`video/${getVideoType(video.video)}`" />
						</video>
					</div>
					<div class="video__item-description">
						<TipTap :editable="false" :limit="10_000" v-model="video.description" />
					</div>
				</div>
			</div>

			<Empty :minHeight="300" v-else />
		</template>

		<loader-child
			:isLoading="loading.loader.videos"
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

import axios from "axios";
import files from "../../../services/files";

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
		axios({
			method: "get",
			url: `${this.$store.getters.urlApi}` + `get-videos-all`,
		})
			.then((response) => {
				if (response.data.status) {
					this.videos = response.data.data;
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
