<template>
	<VueInput :error="error">
		<template #label v-if="$slots.label">
			<slot name="label"></slot>
		</template>

		<template #input>
			<template v-if="view === 'dropzone'">
				<div
					class="dropzone"
					:class="{ active: isDragOver }"
					@dragover.prevent="onDragOver"
					@dragleave.prevent="onDragLeave"
					@drop.prevent="onDrop"
					@click="openFileDialog"
				>
					<div class="dropzone__label" :class="{ 'has-preview': file }">
						<div class="dropzone__label-text">
							<VueIcon
								v-if="type === 'image'"
								class="dropzone__icon"
								:name="'Image'"
								:width="'28px'"
								:height="'28px'"
							/>

							<VueIcon
								v-else-if="type === 'document'"
								class="dropzone__icon"
								:name="'Article'"
								:width="'40px'"
								:height="'40px'"
							/>

							<VueIcon
								v-else-if="type === 'video'"
								class="dropzone__icon"
								:name="'Videocam'"
								:width="'40px'"
								:height="'40px'"
							/>

							<VueIcon v-else class="dropzone__icon" :name="'Attach File'" :width="'40px'" :height="'40px'" />

							<template v-if="file">
								{{ file.name ?? "Файл загружен" }}
							</template>
							<template v-else>
								{{ placeholder ? placeholder : "Файл не загружен" }}
							</template>
						</div>

						<template v-if="type === 'image'">
							<img class="dropzone__label-preview" ref="preview" alt="Превью" />
						</template>
					</div>
				</div>

				<input name="file" type="file" ref="fileInput" class="hidden" :accept="getAccept" @change="onFileSelect" />
			</template>

			<template v-else>
				<div
					class="vue-input__wrapper"
					:class="{ active: isDragOver }"
					@dragover.prevent="onDragOver"
					@dragleave.prevent="onDragLeave"
					@drop.prevent="onDrop"
				>
					<label class="vue-input__file-button">
						<input
							name="file"
							type="file"
							class="vue-input vue-input__file"
							ref="fileInput"
							:accept="getAccept"
							@change="onFileSelect"
						/>

						<span class="vue-input__file-icon-wrapper" ref="imageWrapper">
							<span class="vue-input__file-text" ref="imageText">
								<VueIcon
									v-if="type === 'image'"
									class="vue-input__file-icon"
									:name="'Image'"
									:width="'26px'"
									:height="'26px'"
								/>

								<VueIcon
									v-else-if="type === 'document'"
									class="vue-input__file-icon"
									:name="'Article'"
									:width="'26px'"
									:height="'26px'"
								/>

								<VueIcon
									v-else-if="type === 'video'"
									class="vue-input__file-icon"
									:name="'Videocam'"
									:width="'26px'"
									:height="'26px'"
								/>

								<VueIcon
									v-else
									class="vue-input__file-icon"
									:name="'Attach File'"
									:width="'26px'"
									:height="'26px'"
								/>

								<template v-if="file">
									{{ file.name ?? "Файл загружен" }}
								</template>
								<template v-else>
									{{ placeholder ? placeholder : "Файл не загружен" }}
								</template>
							</span>
						</span>
					</label>
					<div class="input__file-button-error" v-if="false">error</div>
				</div>
			</template>
		</template>

		<template #error v-if="error">
			<slot name="error"></slot>
		</template>
	</VueInput>
</template>

<script>
import VueInput from "./VueInput.vue";

export default {
	name: "VueFile",
	emits: ["update:modelValue", "guard"],
	components: { VueInput },
	props: {
		modelValue: {
			type: [String, Number, Boolean, null, Object],
			default: "",
			required: true,
		},
		type: {
			type: String,
			default: "file",
		},
		view: {
			type: String,
			default: "field",
		},
		accept: {
			type: Array,
			default: [],
		},
		placeholder: {
			type: String,
			default: "Загрузите файл",
		},
		error: {
			type: Boolean,
			default: false,
		},
	},
	data() {
		return {
			file: null,
			isDragOver: false,
		};
	},
	computed: {
		getAccept() {
			return this.accept.join(", ");
		},
	},
	methods: {
		/* Получение массива файлов */
		files() {
			return this.$refs.fileInput.files ?? undefined;
		},

		/* Очистка файлов в поле ввода */
		clear() {
			this.file = null;
			this.$refs.fileInput.value = "";
		},

		/* Мышка над полем */
		onDragOver() {
			this.isDragOver = true;
		},

		/* Мышка вышла за пределы поля */
		onDragLeave() {
			this.isDragOver = false;
		},

		/* Открытие диалогового окна */
		openFileDialog() {
			this.$refs.fileInput.click();
		},

		/* При отпускании мышки */
		onDrop(e) {
			this.isDragOver = false;

			this.guardFile(e.dataTransfer);
		},

		/* Выбор файла */
		onFileSelect(e) {
			this.guardFile(e.target);
		},

		guardFile(object) {
			if (!object && !object.files && !object.files[0]) {
				return;
			}

			switch (this.type) {
				case "image":
					this.guardFileImage(object.files);
					break;
				case "video":
					this.guardFileVideo(object.files);
					break;
				case "document":
					this.guardFileDocument(object.files);
					break;
				default:
					this.file = object.files[0];
					this.$refs.fileInput.files = object.files;
					break;
			}
		},

		guardFileImage(images) {
			if (!images[0]?.type.startsWith("image")) {
				this.$refs.fileInput.value = null;
				this.file = null;

				this.$emit("guard", {
					status: true,
					message: "Загрузите изображение",
				});

				return;
			}

			this.$emit("guard", {
				status: false,
				message: "",
			});

			if (this.view === "dropzone" && this.$refs.preview) {
				this.$refs.preview.src = URL.createObjectURL(images[0]);
			}

			this.file = images[0];
			this.$refs.fileInput.files = images;
		},

		guardFileVideo(videos) {
			if (!videos[0]?.type.startsWith("video")) {
				this.$refs.fileInput.value = null;
				this.file = null;

				this.$emit("guard", {
					status: true,
					message: "Загрузите видео",
				});
				return;
			}

			this.$emit("guard", {
				status: false,
				message: "",
			});

			this.file = videos[0];
			this.$refs.fileInput.files = videos;
		},

		guardFileDocument(documents) {
			if (!documents[0].type.startsWith("application")) {
				this.$refs.fileInput.value = null;
				this.file = null;

				this.$emit("guard", {
					status: true,
					message: "Загрузите документом",
				});
				return;
			}

			this.$emit("guard", {
				status: false,
				message: "",
			});

			this.file = documents[0];
			this.$refs.fileInput.files = documents;
		},
	},
};
</script>

<style>
/* Файл */
.vue-input__wrapper {
	height: 100%;
	width: 100%;
	position: relative;
	text-align: center;

	display: flex;
	flex-direction: column;
}

.vue-input__file {
	opacity: 0;
	visibility: hidden;
	position: absolute;
	width: 0px;
}

.vue-input__file-icon-wrapper {
	box-sizing: border-box;
	margin-right: 15px;
	display: -webkit-box;
	display: -ms-flexbox;
	display: flex;
	flex-wrap: wrap;

	word-wrap: break-word;
	word-break: break-all;

	width: auto;
	height: 60px;
	padding: 0px 20px;

	-webkit-box-align: center;
	-ms-flex-align: center;
	align-items: center;
	-webkit-box-pack: center;
	-ms-flex-pack: center;
	justify-content: center;
}

.vue-input__file-text {
	display: grid;
	justify-content: center;
	align-items: center;
	gap: 10px;

	grid-template-columns: auto auto;
}

.vue-input__file-icon {
	fill: var(--input-file-icon-color);

	transition: all 0.2s ease;
}

.vue-input__file-button {
	box-sizing: border-box;
	user-select: none;
	cursor: pointer;
	display: -webkit-box;
	display: -ms-flexbox;
	display: flex;
	-webkit-box-align: center;
	-ms-flex-align: center;
	align-items: center;
	-webkit-box-pack: start;
	-ms-flex-pack: start;

	border: var(--default-border);
	border-radius: calc(var(--default-border-radius) / 2);
	margin: 0 auto;

	width: 100%;

	font-size: 1.25rem;
	white-space: nowrap;
	overflow: hidden;

	color: var(--input-color);
	background-color: var(--input-background-color);

	transition: all 0.2s;
}

.vue-input__file-button:is(:hover, .active, :focus) {
	border: var(--input-border-focus);
	color: var(--primary-color);
	background-color: var(--input-background-color);
}

.vue-input__wrapper.active .vue-input__file-button {
	border: var(--input-border-focus);
	color: var(--primary-color);
	background-color: var(--input-background-color);
}

.vue-input__file-button:is(:hover, .active) .vue-input__file-icon {
	fill: var(--primary-color);
}

.vue-input__wrapper.active .vue-input__file-icon {
	fill: var(--primary-color);
}

.vue-input__file-button.error {
	border: var(--input-error-border);
	border-radius: 10px 10px 0px 0px;

	color: var(--input-error-color);
	background-color: var(--input-error-background-color);
}

/* Перетаскивание */
.dropzone {
	display: flex;
	justify-content: center;
	align-items: center;
	cursor: pointer;

	border: var(--file-dropzone-border);
	border-radius: var(--file-dropzone-border-radius);
	padding: var(--file-dropzone-padding);

	height: var(--file-dropzone-height);
	min-height: var(--file-dropzone-min-height);
	background-color: var(--file-dropzone-background-color);
	text-align: center;

	transition: var(--file-dropzone-transition);
}

.dropzone:is(.active, :hover) {
	border: var(--file-dropzone-border--active);
	background: var(--file-dropzone-background-color--active);
}

.hidden {
	display: none;
}

.dropzone__label {
	display: flex;
	flex-direction: column;
	gap: var(--file-dropzone-label-gap);
	justify-content: center;
	align-items: center;

	position: relative;
	height: 100%;
	width: 100%;
	overflow: hidden;
}

/* Превью */
.dropzone__label-preview {
	position: absolute;
	inset: 0;

	width: 100%;
	height: 100%;

	object-fit: contain;
	object-position: center;

	opacity: 0;
	background-color: white;

	pointer-events: none;
}

/* когда есть файл */
.dropzone__label.has-preview .dropzone__label-preview {
	opacity: 1;
}

.dropzone__label-text {
	cursor: pointer;
	display: grid;
	grid-template-columns: auto auto;
	align-items: center;
	justify-content: center;
	gap: var(--file-dropzone-label-gap);
	word-break: break-all;

	height: var(--file-dropzone-height);
	min-height: var(--file-dropzone-min-height);

	transition: var(--file-dropzone-transition);

	font-size: 1.125rem;
	color: var(--input-border-color);
}

.dropzone:is(.active, :hover) .dropzone__label-text {
	color: var(--file-dropzone-label-color--hover);
}

.dropzone .dropzone__icon {
	fill: var(--file-dropzone-icon-fill);

	transition: var(--file-dropzone-transition);
}

.dropzone:is(.active, :hover) .dropzone__icon {
	fill: var(--file-dropzone-icon-fill--hover);
}
</style>
