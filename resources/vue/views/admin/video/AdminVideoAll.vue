<template>
	<Modal ref="modalVideo" :settings="modalVideo">
		<template #title>{{ modalVideo.values.title }}</template>

		<template #body>
			<div class="modal__video">
				<div
					v-if="currentVideo.data.path.value === ''"
					class="modal__video-clear"
					:style="{ backgroundImage: `url(/storage/default/video-none-default.webp)` }"
				></div>

				<video v-else controls preload="metadata">
					<source
						:src="currentVideo.data.path.value"
						:type="`video/${getVideoType(currentVideo.data.path.value)}`"
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
						<div class="modal__buttons-icon delete" @click="console.log('delete')">
							<icon-remove :width="24" :height="22" :look="'delete'" />
						</div>
					</template>
				</div>
			</div>

			<Tiptap
				ref="tiptapDescription"
				v-model="currentVideo.data.title.value"
				:editable="true"
				:limit="10_000"
				:options="['format', 'align', 'list', 'link']"
				:placeholder="'Текст новости'"
				:error="false"
			>
				<template #error> Ошибка. </template>
			</Tiptap>
		</template>

		<template #footer>
			<ButtonDefault @click="openVideoCreate" v-if="modalVideo.values.look == 'create'">
				<icon-add :width="23" :height="23" :look="'white'" />
				Добавить
			</ButtonDefault>
			<ButtonDefault @click="openVideoCreate" v-else>
				<icon-edit :width="28" :height="28" :look="'white'" />
				Обновить
			</ButtonDefault>
		</template>
	</Modal>

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
				:disabled="false"
			>
				<icon-add :width="23" :height="23" :look="'white'" />
				Добавить
			</button-default>

			<button-default v-else @click="console.log('create')" :disabled="false">
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
				@click.prevent="console.log('save')"
				:disabled="disabled.video.save"
				:look="'white'"
			>
				<icon-save :width="28" :height="28" />
				Сохранить
			</button-default>
		</template>

		<template #body>
			<div class="evideo">
				<div class="evideo__item" @click="openVideoEdite">
					<div class="evideo__item-video">
						<video width="100%" height="300px" preload="metadata">
							<source :src="`/storage/video/IMG_8240.webm`" type="video/webm" />
						</video>
					</div>
					<div class="evideo__item-title">Заголовок</div>
					<div class="evideo__item-description">Описание видео блока</div>
				</div>
				<div class="evideo__item" @click="openVideoEdite">
					<div class="evideo__item-video">
						<video width="100%" height="300px" preload="metadata">
							<source :src="`/storage/video/IMG_8240.webm`" type="video/webm" />
						</video>
					</div>
					<div class="evideo__item-title">Заголовок</div>
					<div class="evideo__item-description">Описание видео блока</div>
				</div>
				<div class="evideo__item" @click="openVideoEdite">
					<div class="evideo__item-video">
						<video width="100%" height="300px" preload="metadata">
							<source :src="`/storage/video/IMG_8240.webm`" type="video/webm" />
						</video>
					</div>
					<div class="evideo__item-title">Заголовок</div>
					<div class="evideo__item-description">Описание видео блока</div>
				</div>
			</div>

			<!-- <loader-child
				:isLoading="loading.loader.video"
				:minHeight="200"
				@loaderChildAfterLeave="loaderChildAfterLeave"
			/> -->
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

import ContainerInputOnce from "../../../components/ui/admin/containers/input/ContainerInputOnce.vue";
import BaseTable from "../../../components/modules/table/BaseTable.vue";
import Tiptap from "../../../components/modules/Tiptap.vue";

import ButtonDefault from "../../../components/ui/admin/buttons/ButtonDefault.vue";

import IconLoad from "../../../components/icons/IconLoad.vue";
import IconAdd from "../../../components/icons/IconAdd.vue";
import IconEdit from "../../../components/icons/IconEdit.vue";
import IconRemove from "../../../components/icons/IconRemove.vue";
import IconSave from "../../../components/icons/IconSave.vue";

import shared from "../../../services/shared";
import validate from "../../../services/validate";
import axios from "axios";

export default {
	components: {
		Modal,
		BlockOnce,
		LoaderChild,
		InfoBar,

		ContainerInputOnce,
		BaseTable,
		Tiptap,

		ButtonDefault,

		IconLoad,
		IconAdd,
		IconEdit,
		IconRemove,
		IconSave,
	},
	data() {
		return {
			/* Кнопки */
			disabled: {
				video: {
					save: false,
					update: false,
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
					title: {
						message: "",
						status: false,
					},
					file: {
						message: "",
						status: false,
					},
				},
				data: {
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
					title: {
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
			// shared.clearObjectFull(this.currentVideo);
			// shared.setData(video, this.currentVideo);

			this.openModal("modalVideo", "Видео", "default");
		},

		/* Загрузка видео */
		openVideoUpload() {
			// shared.clearObjectFull(this.currentVideo);
			// shared.setData(video, this.currentVideo);

			this.openModal("modalVideoUpload", "Загрузка видео", "default");
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

			console.log("upload");
		},

		getVideoType(value) {
			return value.slice(value.lastIndexOf(".") + 1, value.length);
		},
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

	height: 350px;

	background-color: var(--item-background-color);
}

.modal__video > video {
	border-radius: calc(var(--default-border-radius) / 2);
	width: 100%;
	height: 100%;
	object-fit: contain;

	background-color: black;
}

.modal__video-clear {
	border-radius: calc(var(--default-border-radius) / 1.5);
	background-position: center center;
	background-repeat: no-repeat;
	background-size: contain;

	width: 100%;
	height: 100%;
	background-color: white;
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
}

.evideo__item:hover {
	border: var(--input-border-focus);
	background-color: var(--item-background-color-active);
}

.evideo__item-video {
	width: 100%;
	max-height: 400px;
	object-fit: cover;
}
</style>
