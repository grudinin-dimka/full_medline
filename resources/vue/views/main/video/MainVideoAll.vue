<template>
	<info-bar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link to="/videos">Видео</router-link>
	</info-bar>

	<block :minHeight="600">
		<div class="video" v-if="videos.length > 0">
			<div class="video__item" v-for="video in videos">
				<div class="video__item-video" :class="{ skeleton: loading.loader.videos }">
					<video controls preload="metadata" :key="video.video" v-if="video.video">
						<source :src="video.path" :type="`video/${getVideoType(video.video)}`" />
					</video>
				</div>
				<div class="video__item-description" :class="{ skeleton: loading.loader.videos }">
					<VueTiptap
						v-if="!loading.loader.videos"
						:editable="false"
						:limit="10_000"
						v-model="video.description"
					/>
				</div>
			</div>
		</div>

		<Empty :minHeight="300" v-else />
	</block>
</template>

<script>
import VueTiptap from "../../../components/modules/VueTiptap.vue";

import InfoBar from "../../../components/ui/main/InfoBar.vue";
import Block from "../../../components/ui/main/Block.vue";
import Empty from "../../../components/modules/Empty.vue";

import api from "../../../mixin/api";
import files from "../../../services/files";

import TimeManager from "../../../mixin/time-manager";

export default {
	components: {
		VueTiptap,

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
			videos: [
				{
					id: 1,
					path: null,
					video: null,
					description: null,
				},
				{
					id: 2,
					path: null,
					video: null,
					description: null,
				},
				{
					id: 3,
					path: null,
					video: null,
					description: null,
				},
			],
		};
	},
	methods: {
		/* Тип видеофайла */
		getVideoType(value) {
			return files.basetype(value);
		},
	},
	mounted() {
		const timeVideo = new TimeManager();
		timeVideo.start();

		api({
			method: "get",
			url: `${this.$store.getters.urlApi}` + `get-videos-all`,
		})
			.then((response) => {
				if (!response) return;

				timeVideo.end();

				timeVideo.difference(this.$store.getters.timeout, () => {
					this.videos = response.data.result;
					
					for (let i = 0; i < response.data.result.length; i++) {
						this.videos[i] = response.data.result[i];
					}

					this.videos.splice(response.data.result.length, this.videos.length);
				
					this.loading.loader.videos = false;
				});
			})
			.catch((error) => {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
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
}

.video__item {
	display: flex;
	flex-direction: column;
	justify-content: center;
	align-items: center;

	gap: var(--default-padding);
}

.video__item-video {
	box-sizing: border-box;
	border-radius: var(--default-border-radius);
	height: 350px;
	width: 100%;

	padding: var(--default-padding);

	background-color: var(--skeleton-background-color);
}

.video__item-video > video {
	object-fit: contain;

	width: 100%;
	height: 100%;

	border-radius: var(--default-border-radius);
	background-color: black;

	animation: show 0.5s ease-in-out;
}

.video__item-description {
	border-radius: var(--default-border-radius);

	width: 100%;
	min-height: 100px;
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
