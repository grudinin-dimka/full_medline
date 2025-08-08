<template>
	<!-- Модальное окно: Видео -->
	<VueModal ref="modalVideo" :settings="modalVideo">
		<template #title>
			<template v-if="modalVideo.values.look == 'default' && !currentVideo.data.delete.value">
				<VueIcon
					:name="'arrow'"
					:fill="'var(--icon-multi-fill)'"
					:hover="'var(--icon-nulti-fill-hover)'"
					:width="'16px'"
					:height="'16px'"
					:rotate="-90"
					:cursor="'pointer'"
					@click="changeOrderItem('down')"
				/>
				#{{ currentVideo.data.order.value }}
				<VueIcon
					:name="'arrow'"
					:fill="'var(--icon-multi-fill)'"
					:hover="'var(--icon-nulti-fill-hover)'"
					:width="'16px'"
					:height="'16px'"
					:rotate="90"
					:cursor="'pointer'"
					@click="changeOrderItem('up')"
				/>
			</template>
			<template v-else>
				{{ modalVideo.values.title }}
			</template>
		</template>

		<template #body>
			<div class="modal__video">
				<div
					v-if="currentVideo.data.path.value === ''"
					class="modal__video-clear"
					:class="{ error: currentVideo.errors.file.status }"
					:style="{ backgroundImage: `url(/storage/default/video-none-default.webp)` }"
				></div>

				<video v-else ref="videoPlayer" controls>
					<source
						:src="`${currentVideo.data.path.value}`"
						:type="`video/${getVideoType(currentVideo.data.video.value)}`"
					/>
				</video>

				<div class="modal__video-buttons">
					<template v-if="currentVideo.data.path.value === ''">
						<div class="modal__buttons-icon create" @click="openVideoUpload">
							<VueIcon
								:name="'Add'"
								:fill="'var(--icon-create-fill)'"
								:width="'24px'"
								:height="'24px'"
							/>
						</div>
					</template>

					<template v-else>
						<div class="modal__buttons-icon edit" @click="openVideoUpload">
							<VueIcon
								:name="'Edit'"
								:fill="'var(--icon-edit-fill)'"
								:width="'24px'"
								:height="'24px'"
							/>
						</div>
					</template>
				</div>
			</div>

			<VueTiptap
				ref="tiptapDescription"
				v-model="currentVideo.data.description.value"
				:editable="true"
				:limit="10_000"
				:minHeight="200"
				:options="['format', 'align', 'list', 'link']"
				:placeholder="'Текст новости'"
				:error="currentVideo.errors.description.status"
			>
				<template #error>
					{{ currentVideo.errors.description.message }}
				</template>
			</VueTiptap>
		</template>

		<template #footer>
			<VueButton @click="addVideo" v-if="modalVideo.values.look == 'create'">
				<VueIcon :name="'Add'" :fill="'white'" :width="'26px'" :height="'26px'" />
				Добавить
			</VueButton>

			<template v-else>
				<VueButton
					:look="'delete'"
					@click="deleteItem"
					v-if="!currentVideo.data.delete.value && !currentVideo.data.create.value"
				>
					<VueIcon :name="'Delete'" :fill="'white'" :width="'28px'" :height="'28px'" />
					Удалить
				</VueButton>

				<VueButton @click="updateVideo" v-if="!currentVideo.data.delete.value">
					<VueIcon :name="'Edit'" :fill="'white'" :width="'28px'" :height="'28px'" />
					Обновить
				</VueButton>

				<VueButton @click="deleteItem" v-if="currentVideo.data.delete.value">
					<VueIcon :name="'Restore From Trash'" :fill="'white'" :width="'28px'" :height="'28px'" />
					Вернуть
				</VueButton>
			</template>
		</template>
	</VueModal>

	<!-- Модальное окно: Видео -> Загрузка -->
	<VueModal ref="modalVideoUpload" :settings="modalVideoUpload">
		<template #title>{{ modalVideoUpload.values.title }}</template>
		<template #body>
			<VueInput
				v-model="currentVideo.data.file.value"
				ref="fileVideo"
				:type="'file'"
				:placeholder="'Загрузите видео файл'"
				:error="currentVideo.errors.file.status"
			>
				<template #label> ВИДЕО ФАЙЛ </template>
				<template #error>
					{{ currentVideo.errors.file.message }}
				</template>
			</VueInput>
		</template>
		<template #footer>
			<VueButton
				v-if="modalVideoUpload.values.look == 'default'"
				@click="uploadVideo"
				:disabled="disabled.video.upload"
			>
				<VueIcon :name="'Add'" :fill="'white'" :width="'28px'" :height="'28px'" />
				Добавить
			</VueButton>

			<VueButton v-else @click="uploadVideo" :disabled="disabled.video.upload">
				<VueIcon :name="'Edit'" :fill="'white'" :width="'28px'" :height="'28px'" />
				Обновить
			</VueButton>
		</template>
	</VueModal>

	<!-- Новости -->
	<info-bar>
		<template v-slot:title>Новости</template>
		<template v-slot:addreas>video</template>
	</info-bar>

	<block-once :minHeight="200">
		<template #title>ВИДЕО</template>

		<template #options>
			<VueButton
				@click.prevent="saveVideosChanges"
				:disabled="disabled.video.save"
				:look="'inverse'"
			>
				<VueIcon
					:name="'save'"
					:fill="'var(--primary-color)'"
					:width="'28px'"
					:height="'28px'"
				/>
				Сохранить
			</VueButton>

			<VueButton
				:look="'inverse'"
				@click="
					$store.commit('openWiki', {
						title: 'ВИДЕО',
						component: 'VideoAll',
					})
				"
				:minWidth="'30px'"
			>
				<VueIcon
					:name="'Info'"
					:fill="'var(--primary-color)'"
					:width="'30px'"
					:height="'30px'"
				/>
			</VueButton>
		</template>

		<template #body>
			<template v-if="loading.sections.video">
				<div class="evideo" v-if="videos.length > 0">
					<template v-for="video in videos">
						<div
							class="evideo__item"
							:class="{ create: video.create, delete: video.delete }"
							@click="openVideoEdite(video)"
						>
							<div class="evideo__item-head">
								<!-- <div class="evideo__head-id">id: {{ video.create ? "?" : video.id }}</div> -->
								<div class="evideo__head-id">id: {{ video.create ? "?" : video.id }}</div>
								<div class="evideo__head-order">order: {{ video.order }}</div>
							</div>
							<div class="evideo__item-video">
								<video preload="metadata" :key="video.video">
									<source :src="video.path" :type="`video/${getVideoType(video.video)}`" />
								</video>
							</div>
							<div class="evideo__item-description">
								<VueTiptap :editable="false" :limit="10_000" v-model="video.description" />
							</div>
						</div>
					</template>
				</div>

				<!-- Элемент пустой страницы -->
				<empty :minHeight="300" v-else />
			</template>

			<VueLoader
				:isLoading="loading.loader.video"
				:isChild="true"
				:minHeight="300"
				@afterLeave="loaderChildAfterLeave"
			/>
		</template>

		<template #buttons>
			<VueButton @click="openVideoCreate">
				<VueIcon :name="'Add'" :fill="'white'" :width="'26px'" :height="'26px'" />
				Добавить
			</VueButton>
		</template>
	</block-once>
</template>

<script>
import VueModal from "../../../components/modules/modal/VueModal.vue";
import VueInput from "../../../components/modules/input/VueInput.vue";
import VueTiptap from "../../../components/modules/VueTiptap.vue";

import InfoBar from "../../../components/ui/admin/InfoBar.vue";
import BlockOnce from "../../../components/ui/admin/blocks/BlockOnce.vue";
import Empty from "../../../components/modules/Empty.vue";

import VueLoader from "../../../components/modules/VueLoader.vue";
import VueIcon from "../../../components/modules/icon/VueIcon.vue";
import VueButton from "../../../components/ui/VueButton.vue";

import api from "../../../mixin/api";
import shared from "../../../services/shared";
import validate from "../../../services/validate";
import files from "../../../services/files";

export default {
	components: {
		VueModal,
		VueTiptap,
		VueInput,

		InfoBar,
		BlockOnce,
		Empty,

		VueLoader,
		VueIcon,
		VueButton,
	},
	data() {
		return {
			/* Кнопки */
			disabled: {
				video: {
					save: false,
					update: false,
					upload: false,
				},
			},

			/* Загрузчик */
			loading: {
				loader: {
					video: true,
				},
				sections: {
					video: false,
				},
			},

			/* Модальное окно */
			modalVideo: {
				thin: false,
				clamped: false,
				values: {
					title: "",
					look: "default",
				},
			},

			modalVideoUpload: {
				thin: true,
				clamped: false,
				values: {
					title: "",
					look: "default",
				},
			},

			/* Форма */
			currentVideo: {
				errors: {
					file: {
						message: "",
						status: false,
					},
					description: {
						message: "",
						status: false,
					},
				},
				data: {
					id: {
						value: "",
						edited: false,
					},
					order: {
						value: "",
						edited: false,
					},
					path: {
						value: "/storage/video/IMG_8240.webm",
						edited: false,
					},
					video: {
						value: "IMG_8240.webm",
						edited: false,
					},
					file: {
						value: "",
						edited: false,
					},
					description: {
						value: "",
						edited: false,
					},
					create: {
						value: false,
						edited: false,
					},
					delete: {
						value: false,
						edited: false,
					},
				},
			},

			/* Данные */
			videos: [],
		};
	},
	methods: {
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                   Загрузчик                       |*/
		/* |___________________________________________________|*/
		/* После скрытия элементы */
		loaderChildAfterLeave() {
			this.loading.sections.video = true;
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                Модальное окно                     |*/
		/* |___________________________________________________|*/
		/* Открытие модального окна */
		openModal(name, title, look) {
			this[name].values.title = title;
			this[name].values.look = look;

			this.$refs[name].open();
		},

		/* Открытие модального окна для добавления */
		openVideoCreate() {
			shared.clearObjectFull(this.currentVideo);

			this.openModal("modalVideo", "Видео", "create");
		},

		/* Открытие модального окна для редактирования */
		openVideoEdite(video) {
			shared.clearObjectFull(this.currentVideo);
			shared.setData(video, this.currentVideo);

			// Перезагружаем видео
			this.reloadVideo();

			this.openModal("modalVideo", "Видео", "default");
		},

		/* Загрузка видео */
		openVideoUpload() {
			this.$refs.fileVideo.clear();

			this.openModal("modalVideoUpload", "Загрузка видео", "default");
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                Блоки информации                   |*/
		/* |___________________________________________________|*/
		addVideo() {
			if (
				validate.checkInputsAll(this.currentVideo, [
					{
						key: "file",
						type: "file",
						value: this.$refs.fileVideo.files(),
						formats: ["webm", "mp4", "mov"],
					},
					{
						key: "description",
						type: "tiptap",
						value: this.$refs.tiptapDescription,
					},
				])
			)
				return;

			try {
				this.videos.push({
					id: shared.getMaxId(this.videos) + 1,
					order: shared.getMaxOrder(this.videos) + 1,
					path: this.currentVideo.data.path.value,
					video: this.currentVideo.data.video.value,
					description: this.currentVideo.data.description.value,
					create: true,
					delete: false,
				});

				this.$refs.modalVideo.close();
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			}
		},

		updateVideo() {
			let errors = 0;

			if (this.$refs.fileVideo.files().length > 0) {
				if (
					validate.checkInputsAll(this.currentVideo, [
						{
							key: "file",
							type: "file",
							value: this.$refs.fileVideo.files(),
							formats: ["webm", "mp4", "mov"],
						},
					])
				)
					errors++;
			}

			if (
				validate.checkInputsAll(this.currentVideo, [
					{
						key: "description",
						type: "tiptap",
						value: this.$refs.tiptapDescription,
					},
				])
			)
				errors++;

			if (errors > 0) return;

			try {
				let selectedVideo = this.videos.find(
					(item, index) => item.id == this.currentVideo.data.id.value
				);

				selectedVideo.order = this.currentVideo.data.order.value;
				selectedVideo.path = this.currentVideo.data.path.value;
				selectedVideo.video = this.currentVideo.data.video.value;
				selectedVideo.description = this.currentVideo.data.description.value;

				this.$refs.modalVideo.close();
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			}
		},

		/* Изменение порядка */
		changeOrderItem(type) {
			shared.changeOrder(this.videos, this.currentVideo, type);
		},

		/* Удаление */
		deleteItem() {
			try {
				let block = this.videos.find((item) => item.id == this.currentVideo.data.id.value);

				block.delete = !block.delete;

				this.$refs.modalVideo.close();
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			}
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                  База данных                      |*/
		/* |___________________________________________________|*/
		uploadVideo() {
			if (
				validate.checkInputsAll(this.currentVideo, [
					{
						key: "file",
						type: "file",
						value: this.$refs.fileVideo.files(),
						formats: ["webm", "mp4", "mov"],
					},
				])
			)
				return;

			/* Загрузка файла */
			let formData = new FormData();
			formData.append("file", this.$refs.fileVideo.files()[0]);
			formData.append("type", "video");
			formData.append("formats", ["wemb", "mp4", "mov"]);

			this.disabled.video.upload = true;

			api({
				method: "post",
				url: this.$store.getters.urlApi + `upload-file`,
				headers: {
					"Content-Type": "multipart/form-data",
				},
				data: formData,
			})
				.then((response) => {
					if (!response) return;

					this.currentVideo.data.video.value = files.basename(response.data.result);
					this.currentVideo.data.path.value = response.data.result;

					// Перезагружаем видео
					this.reloadVideo();

					this.$refs.modalVideoUpload.close();
				})
				.catch((error) => {
					this.$store.commit("addDebugger", {
						title: "Ошибка.",
						body: error,
						type: "error",
					});
				})
				.finally(() => {
					this.disabled.video.upload = false;
				});
		},

		/* Сохранение всех изменений */
		saveVideosChanges() {
			this.disabled.video.save = true;

			api({
				method: "post",
				url: this.$store.getters.urlApi + `save-videos-changes`,
				headers: {
					Accept: "application/json",
				},
				data: {
					videos: this.videos,
				},
			})
				.then((response) => {
					if (!response) return;

					shared.updateId(this.videos, response.data.result);
					shared.clearDeletes(this.videos);
					shared.clearFlags(this.videos);
					shared.updateOrders(this.videos);
				})
				.catch((error) => {
					this.$store.commit("addDebugger", {
						title: "Ошибка.",
						body: error,
						type: "error",
					});
				})
				.finally(() => {
					this.disabled.video.save = false;
				});
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                     Другое                        |*/
		/* |___________________________________________________|*/
		/* Тип видеофайла */
		getVideoType(value) {
			return files.basetype(value);
		},

		/* Ручное обновление видео */
		reloadVideo(value) {
			this.$nextTick(() => {
				if (this.$refs.videoPlayer) {
					this.$refs.videoPlayer.load();
				}
			});
		},
	},
	mounted() {
		api({
			method: "get",
			url: this.$store.getters.urlApi + `get-videos-all`,
		})
			.then((response) => {
				if (!response) return;

				this.videos = response.data.result;

				for (let i = 0; i < this.videos.length; i++) {
					this.videos[i].create = false;
					this.videos[i].delete = false;
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
				this.loading.loader.video = false;
			});
	},
};
</script>

<style scoped>
/* Модальное окно */
.modal__video {
	position: relative;
	padding: 20px;
	border: var(--input-border);
	border-radius: calc(var(--default-border-radius) / 1.5);

	flex: 1 0 300px;
	height: 350px;

	background-color: var(--item-background-color);
}

.modal__video > video {
	border-radius: calc(var(--default-border-radius) / 2);
	width: 100%;
	height: 100%;

	border: var(--default-border);
	object-fit: contain;

	background-color: black;
}

.modal__video-clear {
	border-radius: calc(var(--default-border-radius) / 1.5);
	border: var(--default-border);

	background-position: center center;
	background-repeat: no-repeat;
	background-size: contain;

	width: 100%;
	height: 100%;
	background-color: white;
}

.modal__video-clear.error {
	border: var(--input-error-border);
	background-color: var(--input-error-background-color);
}

.modal__video-buttons {
	position: absolute;
	display: flex;
	gap: 5px;
	top: 30px;
	right: 30px;
}

.modal__buttons-icon {
	display: flex;
	justify-content: center;
	align-items: center;
	cursor: pointer;
	padding: 10px;
	border-radius: 100px;

	width: 25px;
	height: 25px;
}

.modal__buttons-icon.delete {
	background-color: var(--icon-delete-background-color);
}

.modal__buttons-icon.delete:hover {
	background-color: var(--icon-delete-background-color-hover);
}

.modal__buttons-icon.edit {
	background-color: var(--icon-edit-background-color);
}

.modal__buttons-icon.edit:hover {
	background-color: var(--icon-edit-background-color-hover);
}

.modal__buttons-icon.create {
	background-color: var(--icon-create-background-color);
}

.modal__buttons-icon.create:hover {
	background-color: var(--icon-create-background-color-hover);
}

/* Видео */
.evideo {
	display: grid;
	grid-template-columns: repeat(3, 1fr);
	gap: 20px;

	animation: show-bottom-to-top-15 0.5s ease-in-out;
}

.evideo__item {
	cursor: pointer;
	display: flex;
	flex-direction: column;
	gap: 10px;

	padding: var(--default-padding);
	border: var(--default-border);
	border-radius: calc(var(--default-border-radius) / 1.5);

	transition: all 0.2s;

	overflow: hidden;
}

.evideo__item:hover {
	border: var(--input-border-focus);
	background-color: var(--item-background-color-active);
}

.evideo__item-description {
	height: 100px;
	overflow: hidden;
}

/* create */
.evideo__item.create {
	border: var(--input-create-border-focus);
}

.evideo__item.create:hover {
	border: var(--input-create-border-focus);
	background-color: var(--input-create-background-color-hover);
}

/* delete */
.evideo__item.delete {
	border: var(--input-delete-border);
}

.evideo__item.delete:hover {
	border: var(--input-delete-border);
	background-color: var(--input-delete-background-color-hover);
}

.evideo__item-head {
	display: flex;
	gap: 10px;
}

/* head */
.evideo__item-head > div {
	padding: 5px 10px;
	border-radius: calc(var(--input-border-radius) / 2);
	border: var(--input-border);

	transition: all 0.2s;
}

.evideo__item:hover > .evideo__item-head > div {
	border: var(--input-border-focus);
}

.evideo__item.create > .evideo__item-head > div {
	border: var(--input-create-border);
}

.evideo__item.delete > .evideo__item-head > div {
	border: var(--input-delete-border);
}

.evideo__item.delete:hover > .evideo__item-head > div {
	border: var(--input-delete-border);
}

/* video */
.evideo__item-video > video {
	border-radius: calc(var(--default-border-radius) / 1.75);

	width: 100%;
	height: 200px;
	object-fit: cover;
}

/* Адаптив */
@media screen and (width <= 1600px) {
	.evideo {
		grid-template-columns: repeat(2, 1fr);
	}
}

@media screen and (width <= 750px) {
	.evideo {
		grid-template-columns: repeat(1, 1fr);
	}
}
</style>
