<template>
	<!-- Модальное окно: Видео -->
	<Modal ref="modalVideo" :settings="modalVideo">
		<template #title>
			<template v-if="modalVideo.values.look == 'default' && !currentVideo.data.delete.value">
				<icon-arrow :width="16" :height="16" :rotate="-90" @click="changeOrderItem('down')" />
				#{{ currentVideo.data.order.value }}
				<icon-arrow :width="16" :height="16" :rotate="90" @click="changeOrderItem('up')" />
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
							<icon-add :width="19" :height="19" :look="'create'"></icon-add>
						</div>
					</template>

					<template v-else>
						<div class="modal__buttons-icon edit" @click="openVideoUpload">
							<icon-edit :width="24" :height="24" :type="'edit'"></icon-edit>
						</div>
					</template>
				</div>
			</div>

			<TipTap
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
			</TipTap>
		</template>

		<template #footer>
			<ButtonDefault @click="addVideo" v-if="modalVideo.values.look == 'create'">
				<icon-add :width="23" :height="23" :look="'white'" />
				Добавить
			</ButtonDefault>

			<template v-else>
				<button-remove
					@click="deleteItem"
					v-if="!currentVideo.data.delete.value && !currentVideo.data.create.value"
				>
					<icon-remove :width="24" :height="22" :look="'white'" />
					Удалить
				</button-remove>

				<ButtonDefault @click="updateVideo" v-if="!currentVideo.data.delete.value">
					<icon-edit :width="28" :height="28" :look="'white'" />
					Обновить
				</ButtonDefault>

				<ButtonDefault @click="deleteItem" v-if="currentVideo.data.delete.value">
					<icon-unremove :width="28" :height="28" :look="'white'" />
					Вернуть
				</ButtonDefault>
			</template>
		</template>
	</Modal>

	<!-- Модальное окно: Видео -> Загрузка -->
	<Modal ref="modalVideoUpload" :settings="modalVideoUpload">
		<template #title>{{ modalVideoUpload.values.title }}</template>
		<template #body>
			<container-input-once>
				<template #input>
					<input
						type="file"
						autocomplete="off"
						ref="fileUpload"
						:class="{ error: currentVideo.errors.file.status }"
					/>
				</template>
				<template #error>
					<span class="error" v-if="currentVideo.errors.file.status">
						{{ currentVideo.errors.file.message }}
					</span>
				</template>
			</container-input-once>
		</template>
		<template #footer>
			<button-default
				v-if="modalVideoUpload.values.look == 'default'"
				@click="uploadVideo"
				:disabled="disabled.video.upload"
			>
				<icon-add :width="23" :height="23" :look="'white'" />
				Добавить
			</button-default>

			<button-default v-else @click="uploadVideo" :disabled="disabled.video.upload">
				<icon-edit :width="28" :height="28" :look="'white'" />
				Обновить
			</button-default>
		</template>
	</Modal>

	<!-- Новости -->
	<info-bar>
		<template v-slot:title>Новости</template>
		<template v-slot:addreas>video</template>
	</info-bar>

	<block-once :minHeight="200">
		<template #title>ВИДЕО</template>

		<template #options>
			<button-default
				@click.prevent="saveVideosChanges"
				:disabled="disabled.video.save"
				:look="'white'"
			>
				<icon-save :width="28" :height="28" />
				Сохранить
			</button-default>
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
								<TipTap :editable="false" :limit="10_000" v-model="video.description" />
							</div>
						</div>
					</template>
				</div>

				<!-- Элемент пустой страницы -->
				<empty :minHeight="300" v-else />
			</template>

			<loader-child
				:isLoading="loading.loader.video"
				:minHeight="200"
				@loaderChildAfterLeave="loaderChildAfterLeave"
			/>
		</template>

		<template #buttons>
			<ButtonDefault @click="openVideoCreate">
				<icon-add :width="23" :height="23" :look="'white'" />
				Добавить
			</ButtonDefault>
		</template>
	</block-once>
</template>

<script>
import Modal from "../../../components/modules/modal/Modal.vue";
import BlockOnce from "../../../components/ui/admin/blocks/BlockOnce.vue";
import LoaderChild from "../../../components/modules/LoaderChild.vue";
import InfoBar from "../../../components/ui/admin/InfoBar.vue";
import Empty from "../../../components/modules/Empty.vue";

import ContainerInputOnce from "../../../components/ui/admin/containers/input/ContainerInputOnce.vue";
import BaseTable from "../../../components/modules/table/BaseTable.vue";
import TipTap from "../../../components/modules/Tiptap.vue";

import ButtonDefault from "../../../components/ui/admin/buttons/ButtonDefault.vue";
import ButtonRemove from "../../../components/ui/admin/buttons/ButtonRemove.vue";

import IconArrow from "../../../components/icons/IconArrow.vue";
import IconLoad from "../../../components/icons/IconLoad.vue";
import IconAdd from "../../../components/icons/IconAdd.vue";
import IconEdit from "../../../components/icons/IconEdit.vue";
import IconRemove from "../../../components/icons/IconRemove.vue";
import IconUnremove from "../../../components/icons/IconUnremove.vue";
import IconSave from "../../../components/icons/IconSave.vue";

import shared from "../../../services/shared";
import validate from "../../../services/validate";
import files from "../../../services/files";
import axios from "axios";

export default {
	components: {
		Modal,
		BlockOnce,
		LoaderChild,
		InfoBar,
		Empty,

		ContainerInputOnce,
		BaseTable,
		TipTap,

		ButtonDefault,
		ButtonRemove,

		IconArrow,
		IconLoad,
		IconAdd,
		IconEdit,
		IconRemove,
		IconUnremove,
		IconSave,
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
				clamped: true,
				values: {
					title: "",
					look: "default",
				},
			},

			modalVideoUpload: {
				thin: true,
				clamped: true,
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
			this.$refs.fileUpload.value = "";

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
						value: this.$refs.fileUpload,
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

			if (this.$refs.fileUpload.files[0]) {
				if (
					validate.checkInputsAll(this.currentVideo, [
						{
							key: "file",
							type: "file",
							value: this.$refs.fileUpload,
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
						value: this.$refs.fileUpload,
						formats: ["webm", "mp4", "mov"],
					},
				])
			)
				return;

			/* Загрузка файла */
			let formData = new FormData();
			formData.append("file", this.$refs.fileUpload.files[0]);
			formData.append("type", "video");
			formData.append("formats", ["wemb", "mp4", "mov"]);

			this.disabled.video.upload = true;

			axios({
				method: "post",
				url: `${this.$store.getters.urlApi}` + `upload-file`,
				headers: {
					"Content-Type": "multipart/form-data",
					Authorization: `Bearer ${localStorage.getItem("token")}`,
				},
				data: formData,
			})
				.then((response) => {
					if (response.data.status) {
						this.currentVideo.data.video.value = files.basename(response.data.data);
						this.currentVideo.data.path.value = response.data.data;

						// Перезагружаем видео
						this.reloadVideo();

						this.$refs.modalVideoUpload.close();
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
					this.disabled.video.upload = false;
				});
		},

		/* Сохранение всех изменений */
		saveVideosChanges() {
			this.disabled.video.save = true;

			axios({
				method: "post",
				url: `${this.$store.getters.urlApi}` + `save-videos-changes`,
				headers: {
					Accept: "application/json",
					Authorization: `Bearer ${localStorage.getItem("token")}`,
				},
				data: {
					videos: this.videos,
				},
			})
				.then((response) => {
					if (response.data.status) {
						shared.updateId(this.videos, response.data.data);
						shared.clearDeletes(this.videos);
						shared.clearFlags(this.videos);
						shared.updateOrders(this.videos);

						this.$store.commit("addDebugger", {
							title: "Успешно!",
							body: response.data.message,
							type: "completed",
						});
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
		axios({
			method: "get",
			url: `${this.$store.getters.urlApi}` + `get-videos-all`,
		})
			.then((response) => {
				if (response.data.status) {
					this.videos = response.data.data;

					for (let i = 0; i < this.videos.length; i++) {
						this.videos[i].create = false;
						this.videos[i].delete = false;
					}
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
	background-color: rgb(255, 237, 237);
}

.modal__buttons-icon.delete:hover {
	background-color: rgb(245, 227, 227);
}

.modal__buttons-icon.edit {
	background-color: rgb(234, 253, 255);
}

.modal__buttons-icon.edit:hover {
	background-color: rgb(224, 243, 245);
}

.modal__buttons-icon.create {
	background-color: rgb(234, 255, 236);
}

.modal__buttons-icon.create:hover {
	background-color: rgb(224, 245, 226);
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
