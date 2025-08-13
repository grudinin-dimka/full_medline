<template>
	<!-- Модальное окно: Информационный блок -->
	<VueModal ref="modal" :settings="modal">
		<template #title v-if="modal.values.look == 'default' && !currentInfoBlock.data.delete.value">
			<VueIcon
				:name="'arrow'"
				:fill="'var(--icon-multi-fill)'"
				:hover="'var(--icon-nulti-fill-hover)'"
				:width="'16px'"
				:height="'16px'"
				:rotate="-90"
				:cursor="'pointer'"
				@click="changeInfoBlockOrder('down')"
			/>
			#{{ currentInfoBlock.data.order.value }}
			<VueIcon
				:name="'arrow'"
				:fill="'var(--icon-multi-fill)'"
				:hover="'var(--icon-nulti-fill-hover)'"
				:width="'16px'"
				:height="'16px'"
				:rotate="90"
				:cursor="'pointer'"
				@click="changeInfoBlockOrder('up')"
			/>
		</template>

		<template #title v-else>
			{{ modal.values.title }}
		</template>

		<template #body>
			<div class="modal-images">
				<div class="item" v-if="currentInfoBlock.data.imageOne.value">
					<div
						class="img"
						:style="{
							backgroundImage: `url(${currentInfoBlock.data.pathOne.value})`,
						}"
					></div>
					<div class="buttons" v-if="!currentInfoBlock.data.delete.value">
						<div class="icon edit" @click="openModalImageEdite('imageOne')">
							<VueIcon
								:name="'Edit'"
								:fill="'var(--icon-edit-fill)'"
								:width="'28px'"
								:height="'28px'"
							/>
						</div>
						<div class="icon delete" @click="removeInfoBlockImage('imageOne')">
							<VueIcon
								:name="'Delete'"
								:fill="'var(--delete-secondary-color)'"
								:width="'28px'"
								:height="'28px'"
							/>
						</div>
					</div>
				</div>
				<div class="item default" v-else>
					<div
						class="img"
						:style="{
							backgroundImage: `url(/storage/default/stones-none-default.png)`,
						}"
					></div>
					<div class="buttons add" v-if="!currentInfoBlock.data.delete.value">
						<div class="icon create" @click="openModalImageCreate('imageOne')">
							<VueIcon
								:name="'Add'"
								:fill="'var(--icon-create-fill)'"
								:width="'24px'"
								:height="'24px'"
							/>
						</div>
					</div>
				</div>
				<div class="item" v-if="currentInfoBlock.data.imageTwo.value">
					<div
						class="img"
						:style="{
							backgroundImage: `url(${currentInfoBlock.data.pathTwo.value})`,
						}"
					></div>
					<div class="buttons" v-if="!currentInfoBlock.data.delete.value">
						<div class="icon edit" @click="openModalImageEdite('imageTwo')">
							<VueIcon
								:name="'Edit'"
								:fill="'var(--icon-edit-fill)'"
								:width="'28px'"
								:height="'28px'"
							/>
						</div>
						<div class="icon delete" @click="removeInfoBlockImage('imageTwo')">
							<VueIcon
								:name="'Delete'"
								:fill="'var(--delete-secondary-color)'"
								:width="'28px'"
								:height="'28px'"
							/>
						</div>
					</div>
				</div>
				<div class="item default" v-else>
					<div
						class="img"
						:style="{
							backgroundImage: `url(/storage/default/stones-none-default.png)`,
						}"
					></div>
					<div class="buttons add" v-if="!currentInfoBlock.data.delete.value">
						<div class="icon create" @click="openModalImageCreate('imageTwo')">
							<VueIcon
								:name="'Add'"
								:fill="'var(--icon-create-fill)'"
								:width="'24px'"
								:height="'24px'"
							/>
						</div>
					</div>
				</div>
				<div class="item" v-if="currentInfoBlock.data.imageThree.value">
					<div
						class="img"
						:style="{
							backgroundImage: `url(${currentInfoBlock.data.pathThree.value})`,
						}"
					></div>
					<div class="buttons" v-if="!currentInfoBlock.data.delete.value">
						<div class="icon edit" @click="openModalImageEdite('imageThree')">
							<VueIcon
								:name="'Edit'"
								:fill="'var(--icon-edit-fill)'"
								:width="'28px'"
								:height="'28px'"
							/>
						</div>
						<div class="icon delete" @click="removeInfoBlockImage('imageThree')">
							<VueIcon
								:name="'Delete'"
								:fill="'var(--delete-secondary-color)'"
								:width="'28px'"
								:height="'28px'"
							/>
						</div>
					</div>
				</div>
				<div class="item default" v-else>
					<div
						class="img"
						:style="{
							backgroundImage: `url(/storage/default/stones-none-default.png)`,
						}"
					></div>
					<div class="buttons add" v-if="!currentInfoBlock.data.delete.value">
						<div class="icon create" @click="openModalImageCreate('imageThree')">
							<VueIcon
								:name="'Add'"
								:fill="'var(--icon-create-fill)'"
								:width="'24px'"
								:height="'24px'"
							/>
						</div>
					</div>
				</div>
			</div>

			<VueTiptap
				ref="tiptapTitle"
				v-model="currentInfoBlock.data.title.value"
				:editable="true"
				:limit="500"
				:minHeight="100"
				:options="['format', 'align']"
				:placeholder="'Заголовок'"
			/>

			<VueTiptap
				ref="tiptapDescription"
				v-model="currentInfoBlock.data.description.value"
				:editable="true"
				:limit="10_000"
				:minHeight="200"
				:options="['format', 'align', 'list', 'link']"
				:placeholder="'Заголовок'"
			/>
		</template>

		<template #footer>
			<template v-if="modal.values.look == 'create'">
				<VueButton @click="addInfoBlock">
					<VueIcon :name="'Add'" :fill="'white'" :width="'26px'" :height="'26px'" />
					Добавить
				</VueButton>
			</template>

			<template v-if="modal.values.look == 'default'">
				<VueButton
					:look="'delete'"
					@click="deleteInfoBlock"
					v-if="!currentInfoBlock.data.delete.value && !currentInfoBlock.data.create.value"
				>
					<VueIcon :name="'Delete'" :fill="'white'" :width="'28px'" :height="'28px'" />
					Удалить
				</VueButton>

				<VueButton @click="updateInfoBlock" v-if="!currentInfoBlock.data.delete.value">
					<VueIcon :name="'Edit'" :fill="'white'" :width="'28px'" :height="'28px'" />
					Обновить
				</VueButton>

				<VueButton @click="deleteInfoBlock" v-if="currentInfoBlock.data.delete.value">
					<VueIcon
						:name="'Restore From Trash'"
						:fill="'white'"
						:width="'28px'"
						:height="'28px'"
					/>
					Вернуть
				</VueButton>
			</template>
		</template>
	</VueModal>

	<!-- Модальное окно: Добавить картинку -->
	<VueModal ref="modalImage" :settings="modalImage">
		<template #title>
			{{ modalImage.values.title }}
		</template>

		<template #body>
			<VueInput
				v-model="currentImage.data.file.value"
				ref="fileImage"
				:type="'file'"
				:placeholder="'Загрузите изображение'"
				:error="currentImage.errors.file.status"
			>
				<template #label> ИЗОБРАЖЕНИЕ </template>
				<template #error>
					{{ currentImage.errors.file.message }}
				</template>
			</VueInput>
		</template>

		<template #footer>
			<template v-if="modalImage.values.look == 'create'">
				<VueButton @click="updateImage" :disabled="disabled.image.add">
					<VueIcon :name="'Add'" :fill="'white'" :width="'26px'" :height="'26px'" />
					Добавить
				</VueButton>
			</template>

			<template v-if="modalImage.values.look == 'default'">
				<VueButton @click="updateImage" :disabled="disabled.image.update">
					<VueIcon :name="'Edit'" :fill="'white'" :width="'28px'" :height="'28px'" />
					Обновить
				</VueButton>
			</template>
		</template>
	</VueModal>

	<!-- Модальное окно: Добавить файл -->
	<VueModal ref="modalFiles" :settings="modalFiles">
		<template #title>
			{{ modalFiles.values.title }}
		</template>

		<template #body>
			<VueInput
				v-model="currentFile.data.file.value"
				ref="fileFile"
				:type="'file'"
				:placeholder="'Загрузите изображение'"
				:error="currentFile.errors.file.status"
			>
				<template #label> ФАЙЛ </template>
				<template #error>
					{{ currentFile.errors.file.message }}
				</template>
			</VueInput>
		</template>

		<template #footer>
			<VueButton @click="uploadFile" :disabled="disabled.files.upload">
				<VueIcon :name="'Add'" :fill="'white'" :width="'26px'" :height="'26px'" />
				Загрузить
			</VueButton>
		</template>
	</VueModal>

	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|               ИНФОРМАЦИОННЫЕ БЛОКИ                |-->
	<!--|___________________________________________________|-->
	<!-- О нас -->
	<info-bar>
		<template v-slot:title>О нас</template>
		<template v-slot:addreas>about</template>
	</info-bar>

	<block-once :minHeight="300">
		<template #title>ИНФОРМАЦИОННЫЕ БЛОКИ</template>

		<template #options>
			<VueButton
				@click.prevent="saveInfoBlocks"
				:disabled="disabled.about.save"
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
						title: 'БЛОКИ',
						component: 'AboutAll',
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
			<template v-if="loading.sections.infoBlocks">
				<AdminAboutUsList
					:infoBlocks="infoBlocks"
					@touchEditBlock="openModalEdite"
					v-if="infoBlocks.length > 0"
				/>

				<!-- Элемент пустой страницы -->
				<empty :minHeight="300" v-if="infoBlocks.length == 0" />
			</template>

			<VueLoader
				:isLoading="loading.loader.infoBlocks"
				:isChild="true"
				:minHeight="300"
				@afterLeave="loaderChildAfterLeave"
			/>
		</template>

		<template #buttons>
			<VueButton @click="openModalСreate()">
				<VueIcon :name="'Add'" :fill="'white'" :width="'26px'" :height="'26px'" />
				Добавить
			</VueButton>
		</template>
	</block-once>

	<!-- Файлы -->
	<block-once :minHeight="100">
		<template #title>ФАЙЛЫ</template>

		<template #options>
			<VueButton
				@click.prevent="saveInfoFiles"
				:disabled="disabled.files.save"
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
						title: 'ФАЙЛЫ',
						component: 'AboutFiles',
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
			<VueTable
				v-if="loading.sections.infoFiles"
				:table="table"
				@create="openFilesCreate"
				@edite="downloadFile"
				@delete="markToDelete"
			/>

			<VueLoader
				:isLoading="loading.loader.infoFiles"
				:isChild="true"
				:minHeight="300"
				@afterLeave="loaderChildAfterLeave"
			/>
		</template>
	</block-once>
</template>

<script>
import VueModal from "../../../components/modules/modal/VueModal.vue";
import VueTiptap from "../../../components/modules/VueTiptap.vue";
import VueInput from "../../../components/modules/input/VueInput.vue";
import VueTable from "../../../components/modules/table/VueTable.vue";

import AdminAboutUsList from "./AdminAboutUsList.vue";

import InfoBar from "../../../components/ui/admin/InfoBar.vue";
import BlockOnce from "../../../components/ui/admin/blocks/BlockOnce.vue";
import BlockTwo from "../../../components/ui/admin/blocks/BlockTwo.vue";
import Empty from "../../../components/modules/Empty.vue";

import VueLoader from "../../../components/modules/VueLoader.vue";
import VueIcon from "../../../components/modules/icon/VueIcon.vue";
import VueButton from "../../../components/ui/VueButton.vue";

import api from "../../../mixin/api";
import shared from "../../../services/shared";
import files from "../../../services/files";
import sorted from "../../../services/sorted";
import validate from "../../../services/validate";

export default {
	components: {
		VueModal,
		VueTiptap,
		VueTable,
		VueInput,

		InfoBar,
		BlockOnce,
		BlockTwo,

		VueLoader,
		VueIcon,
		VueButton,

		Empty,
		AdminAboutUsList,
	},
	data() {
		return {
			/* Кнопки */
			disabled: {
				about: {
					update: false,
					delete: false,
					create: false,
					add: false,
					save: false,
				},
				image: {
					update: false,
					add: false,
				},
				files: {
					save: false,
					upload: false,
				},
			},

			/* Загрузчик */
			loading: {
				loader: {
					title: true,
					infoBlocks: true,
					infoFiles: true,
				},
				sections: {
					title: false,
					infoBlocks: false,
					infoFiles: false,
				},
			},

			/* Модальное окно */
			modal: {
				thin: false,
				clamped: false,
				values: {
					title: "",
					look: "default",
				},
			},

			modalImage: {
				thin: true,
				clamped: false,
				values: {
					title: "",
					look: "default",
				},
			},

			modalFiles: {
				thin: true,
				clamped: false,
				values: {
					title: "",
					look: "default",
				},
			},

			/* Форма */
			currentInfoBlock: {
				errors: {
					id: {
						message: "",
						status: false,
					},
					order: {
						message: "",
						status: false,
					},
					title: {
						message: "",
						status: false,
					},
					description: {
						message: "",
						status: false,
					},
					imageOne: {
						message: "",
						status: false,
					},
					imageTwo: {
						message: "",
						status: false,
					},
					imageThree: {
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
					title: {
						value: "",
						edited: false,
					},
					description: {
						value: "",
						edited: false,
					},
					imageOne: {
						value: "",
						edited: false,
					},
					imageTwo: {
						value: "",
						edited: false,
					},
					imageThree: {
						value: "",
						edited: false,
					},
					pathOne: {
						value: "",
						edited: false,
					},
					pathTwo: {
						value: "",
						edited: false,
					},
					pathThree: {
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

			currentImage: {
				errors: {
					file: {
						message: "",
						status: false,
					},
				},
				data: {
					file: {
						value: "",
						edited: false,
					},
				},
			},

			currentFile: {
				errors: {
					file: {
						message: "",
						status: false,
					},
				},
				data: {
					file: {
						value: "",
						edited: false,
					},
				},
			},

			/* Таблица */
			table: {
				// Настройки
				options: {
					create: true,
					delete: true,
					update: true,
					report: false,
				},

				// Колонки
				head: [
					{
						name: "id",
						title: "ID",
						type: "id",
						hide: true,
					},
					{
						name: "filename",
						title: "Файл",
						type: "string",
					},
					{
						name: "date",
						title: "Дата загрузки",
						type: "time",
						options: {
							column: {
								style: {
									width: "300px",
								},
							},
						},
					},
				],

				// Элементы
				body: [],
			},

			/* Список */
			infoBlocks: [],
		};
	},
	methods: {
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                   Загрузчик                       |*/
		/* |___________________________________________________|*/
		/* После скрытия загрузчика */
		loaderChildAfterLeave() {
			for (let key in this.loading.loader) {
				if (!this.loading.loader[key]) {
					this.loading.sections[key] = true;
				}
			}
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                 Модальное окно                    |*/
		/* |___________________________________________________|*/
		/* Открытие модального окна */
		openModal(name, title, look) {
			this[name].values.title = title;
			this[name].values.look = look;

			this.$refs[name].open();
		},

		/* Открытие модального окна для добавления */
		openModalСreate() {
			shared.clearObjectFull(this.currentInfoBlock);

			this.openModal("modal", "БЛОК ИНФОРМАЦИИ", "create");
		},

		/* Открытие модального окна для редактирования */
		openModalEdite(value) {
			shared.clearObjectFull(this.currentInfoBlock);
			shared.setData(value, this.currentInfoBlock);

			this.openModal("modal", "БЛОК ИНФОРМАЦИИ", "default");
		},

		/* Открытие модального окна для загрузки */
		openModalImageCreate(name) {
			shared.clearObjectFull(this.currentImage);
			this.currentImage.data.file.value = name;

			this.$refs.fileImage.clear();

			this.openModal("modalImage", "ЗАГРУЗКА ИЗОБРАЖЕНИЯ", "default");
		},

		/* Открытие модального окна для загрузки */
		openModalImageEdite(name) {
			shared.clearObjectFull(this.currentImage);
			this.currentImage.data.file.value = name;

			this.$refs.fileImage.clear();

			this.openModal("modalImage", "ЗАГРУЗКА ИЗОБРАЖЕНИЯ", "default");
		},

		/* Открытие модального окна для загрузки */
		openFilesCreate() {
			shared.clearObjectFull(this.currentFile);

			this.$refs.fileFile.clear();

			this.openModal("modalFiles", "ФАЙЛ", "create");
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |              ИНФОРМАЦИОННЫЕ БЛОКИ                 |*/
		/* |___________________________________________________|*/
		/* Обновление картинки */
		updateImage() {
			if (
				validate.checkInputsAll(this.currentImage, [
					{
						key: "file",
						type: "file",
						value: this.$refs.fileImage.files(),
						formats: ["jpg", "jpeg", "png", "webp"],
					},
				])
			)
				return;

			/* Загрузка файла */
			let formData = new FormData();
			formData.append("file", this.$refs.fileImage.files()[0]);
			formData.append("type", "abouts");
			formData.append("formats", ["png", "jpg", "jpeg", "webp"]);

			if (this.modalImage.values.look == "create") {
				this.disabled.image.add = true;
			} else if (this.modalImage.values.look == "default") {
				this.disabled.image.update = true;
			}

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

					switch (this.currentImage.data.file.value) {
						case "imageOne":
							this.currentInfoBlock.data.pathOne.value = response.data.result;
							this.currentInfoBlock.data.imageOne.value = files.basename(
								response.data.result
							);
							break;
						case "imageTwo":
							this.currentInfoBlock.data.pathTwo.value = response.data.result;
							this.currentInfoBlock.data.imageTwo.value = files.basename(
								response.data.result
							);
							break;
						case "imageThree":
							this.currentInfoBlock.data.pathThree.value = response.data.result;
							this.currentInfoBlock.data.imageThree.value = files.basename(
								response.data.result
							);
							break;
					}

					this.$refs.modalImage.close();
				})
				.catch((error) => {
					this.$store.commit("addDebugger", {
						title: "Ошибка.",
						body: error,
						type: "error",
					});
				})
				.finally(() => {
					if (this.modalImage.values.look == "create") {
						this.disabled.image.add = false;
					}

					if (this.modalImage.values.look == "default") {
						this.disabled.image.update = false;
					}
				});
		},

		/* Удаление */
		deleteInfoBlock() {
			try {
				let block = this.infoBlocks.find(
					(item) => item.id == this.currentInfoBlock.data.id.value
				);

				block.delete = !block.delete;

				this.$refs.modal.close();
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			}
		},

		/* Добавление */
		addInfoBlock() {
			try {
				this.infoBlocks.push({
					id: shared.getMaxId(this.infoBlocks) + 1,
					order: shared.getMaxOrder(this.infoBlocks) + 1,
					title: this.currentInfoBlock.data.title.value,
					description: this.currentInfoBlock.data.description.value,
					imageOne: this.currentInfoBlock.data.imageOne.value,
					imageTwo: this.currentInfoBlock.data.imageTwo.value,
					imageThree: this.currentInfoBlock.data.imageThree.value,
					pathOne: this.currentInfoBlock.data.pathOne.value,
					pathTwo: this.currentInfoBlock.data.pathTwo.value,
					pathThree: this.currentInfoBlock.data.pathThree.value,
					create: true,
					delete: false,
				});

				this.$refs.modal.close();
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			}
		},

		/* Обновление */
		updateInfoBlock() {
			try {
				let block = this.infoBlocks.find(
					(item) => item.id == this.currentInfoBlock.data.id.value
				);

				for (let key in this.currentInfoBlock.data) {
					block[key] = this.currentInfoBlock.data[key].value;
				}

				this.$refs.modal.close();
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			}
		},

		/* Удаление */
		removeInfoBlockImage(name) {
			this.currentInfoBlock.data[name].value = "";
		},

		/* Изменение порядка */
		changeInfoBlockOrder(type) {
			try {
				shared.changeOrder(this.infoBlocks, this.currentInfoBlock, type);
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка",
					body: error,
					type: "error",
				});
			}
		},

		/* Сохранение */
		saveInfoBlocks() {
			this.disabled.about.save = true;

			let formData = new FormData();
			formData.append("abouts", JSON.stringify(this.infoBlocks));

			api({
				method: "post",
				url: this.$store.getters.urlApi + `save-abouts-changes`,
				headers: {
					ContentType: "multipart/form-data",
				},
				data: formData,
			})
				.then((response) => {
					if (!response) return;

					shared.updateId(this.infoBlocks, response.data.result);
					shared.clearDeletes(this.infoBlocks);
					shared.clearFlags(this.infoBlocks);
					shared.updateOrders(this.infoBlocks);
				})
				.catch((error) => {
					this.$store.commit("addDebugger", {
						title: "Ошибка.",
						body: error,
						type: "error",
					});
				})
				.finally(() => {
					this.disabled.about.save = false;
				});
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                     ФАЙЛЫ                         |*/
		/* |___________________________________________________|*/
		/* Загрузка картинки */
		uploadFile() {
			if (
				validate.checkInputsAll(this.currentFile, [
					{
						key: "file",
						type: "file",
						value: this.$refs.fileFile.files(),
						formats: [
							"pdf",
							"doc",
							"docx",
							"xlsx",
							"xls",
							"ods",
							"jpg",
							"jpeg",
							"png",
							"webp",
						],
					},
				])
			)
				return;

			this.disabled.files.upload = true;

			/* Загрузка файла */
			let formData = new FormData();
			formData.append("file", this.$refs.fileFile.files()[0]);
			formData.append("type", "files");
			formData.append("formats", [
				"pdf",
				"doc",
				"docx",
				"xlsx",
				"xls",
				"ods",
				"jpg",
				"jpeg",
				"png",
				"webp",
			]);

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

					this.table.body.push({
						id: shared.getMaxId(this.table.body) + 1,
						filename: files.basename(response.data.result),
						path: response.data.result,
						date: "Не определено",
						delete: false,
						create: true,
					});

					this.$refs.modalFiles.close();
				})
				.catch((error) => {
					this.$store.commit("addDebugger", {
						title: "Ошибка.",
						body: error,
						type: "error",
					});
				})
				.finally(() => {
					this.disabled.files.upload = false;
				});
		},

		/* Сохранение изменений */
		saveInfoFiles() {
			let formData = new FormData();
			formData.append("infoFiles", JSON.stringify(this.table.body));

			this.disabled.files.save = true;

			api({
				method: "post",
				url: this.$store.getters.urlApi + `save-info-files-changes`,
				headers: {
					ContentType: "multipart/form-data",
				},
				data: formData,
			})
				.then((response) => {
					if (!response) return;

					shared.updateId(this.table.body, response.data.result);
					shared.clearDeletes(this.table.body);
					shared.clearFlags(this.table.body);
					shared.updateOrders(this.table.body);
				})
				.catch((error) => {
					this.$store.commit("addDebugger", {
						title: "Ошибка.",
						body: error,
						type: "error",
					});
				})
				.finally(() => {
					this.disabled.files.save = false;
				});
		},

		downloadFile(value) {
			// Перенаправляем пользователя на ссылку для скачивания файла
			window.location.href = value.path;
		},

		/* Пометка на удаление */
		markToDelete(value) {
			value.delete = !value.delete;
		},
	},
	mounted() {
		api({
			method: "get",
			url: this.$store.getters.urlApi + `get-abouts-all`,
		})
			.then((response) => {
				if (!response) return;

				this.infoBlocks = response.data.result;

				this.infoBlocks.forEach((item) => {
					item.create = false;
					item.delete = false;
				});

				sorted.sortNumberByKey("up", this.infoBlocks, "order");
			})
			.catch((error) => {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			})
			.finally(() => {
				this.loading.loader.infoBlocks = false;
			});

		api({
			method: "get",
			url: this.$store.getters.urlApi + `get-info-files-all`,
		})
			.then((response) => {
				if (!response) return;

				this.table.body = response.data.result;

				this.table.body.forEach((item) => {
					item.create = false;
					item.delete = false;
				});
			})
			.catch((error) => {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			})
			.finally(() => {
				this.loading.loader.infoFiles = false;
			});
	},
};
</script>

<style scoped>
.modal-images {
	display: flex;
	align-items: center;
	flex-wrap: wrap;
	gap: 20px;

	border: var(--default-border);
	border-radius: var(--default-border-radius);
	padding: var(--default-padding);

	background-color: var(--item-background-color);
}

.modal-images > .item {
	position: relative;
	flex: 1 0 200px;
	gap: 10px;

	border-radius: calc(var(--default-border-radius) / 1.5);
	min-width: 100px;
	min-height: 300px;

	background-color: white;
}

.modal-images > .item.default {
	box-sizing: border-box;
	width: 100px;
	height: 100px;
}

.modal-images > .item > .img {
	flex: 1 0 300px;
	border: var(--default-border);
	border-radius: calc(var(--default-border-radius) / 1.5);
	min-width: 100px;
	min-height: 300px;
	background-position: center center;
	background-repeat: no-repeat;
	background-size: cover;
}

.modal-images > .item > .buttons {
	position: absolute;
	display: flex;
	gap: 5px;
	top: 10px;
	right: 10px;
}

.modal-images > .item > .buttons.add {
	top: 10px;
	right: 10px;
}

.modal-images > .item > .buttons > .icon {
	cursor: pointer;
	display: flex;
	justify-content: center;
	align-items: center;
	padding: 10px;
	border-radius: 100px;

	width: 25px;
	height: 25px;
}

.modal-images > .item > .buttons > .icon.delete {
	background-color: var(--icon-delete-background-color);
}

.modal-images > .item > .buttons > .icon.delete:hover {
	background-color: var(--icon-delete-background-color-hover);
}

.modal-images > .item > .buttons > .icon.edit {
	background-color: var(--icon-edit-background-color);
}

.modal-images > .item > .buttons > .icon.edit:hover {
	background-color: var(--icon-edit-background-color-hover);
}

.modal-images > .item > .buttons > .icon.create {
	background-color: var(--icon-create-background-color);
}

.modal-images > .item > .buttons > .icon.create:hover {
	background-color: var(--icon-create-background-color-hover);
}

.textarea-show {
	animation: show-bottom-to-top-15 0.5s ease-in-out;
}
</style>
