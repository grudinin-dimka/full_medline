<template>
	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                  МОДАЛЬНОЕ ОКНО                   |-->
	<!--|___________________________________________________|-->
	<admin-modal ref="modal" @touchCloseModal="closeModal" :modal="modal">
		<template
			#title
			v-if="modal.type == 'edit' && !currentInfoBlock.data.delete.value && !modal.style.create"
		>
			<icon-arrow :width="16" :height="16" :rotate="-90" @click="changeInfoBlockOrder('down')" />
			#{{ currentInfoBlock.data.order.value }}
			<icon-arrow :width="16" :height="16" :rotate="90" @click="changeInfoBlockOrder('up')" />
		</template>

		<template #title v-else>
			{{ modal.title }}
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
						<div class="icon edit" @click="editImage('imageOne')">
							<Icon
								:name="'edit'"
								:fill="'var(--icon-edit-fill)'"
								:width="'24px'"
								:height="'24px'"
							/>
						</div>
						<div class="icon delete" @click="removeInfoBlockImage('imageOne')">
							<Icon
								:name="'delete'"
								:fill="'var(--icon-delete-fill)'"
								:width="'24px'"
								:height="'22px'"
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
						<div class="icon create" @click="createImage('imageOne')">
							<Icon
								:name="'add'"
								:fill="'var(--icon-create-fill)'"
								:width="'19px'"
								:height="'19px'"
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
						<div class="icon edit" @click="editImage('imageTwo')">
							<Icon
								:name="'edit'"
								:fill="'var(--icon-edit-fill)'"
								:width="'24px'"
								:height="'24px'"
							/>
						</div>
						<div class="icon delete" @click="removeInfoBlockImage('imageTwo')">
							<Icon
								:name="'delete'"
								:fill="'var(--icon-delete-fill)'"
								:width="'24px'"
								:height="'22px'"
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
						<div class="icon create" @click="createImage('imageTwo')">
							<Icon
								:name="'add'"
								:fill="'var(--icon-create-fill)'"
								:width="'19px'"
								:height="'19px'"
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
						<div class="icon edit" @click="editImage('imageThree')">
							<Icon
								:name="'edit'"
								:fill="'var(--icon-edit-fill)'"
								:width="'24px'"
								:height="'24px'"
							/>
						</div>
						<div class="icon delete" @click="removeInfoBlockImage('imageThree')">
							<Icon
								:name="'delete'"
								:fill="'var(--icon-delete-fill)'"
								:width="'24px'"
								:height="'22px'"
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
						<div class="icon create" @click="createImage('imageThree')">
							<Icon
								:name="'add'"
								:fill="'var(--icon-create-fill)'"
								:width="'19px'"
								:height="'19px'"
							/>
						</div>
					</div>
				</div>
			</div>
			<ContainerInput>
				<Tiptap
					ref="tiptapTitle"
					v-model="currentInfoBlock.data.title.value"
					:editable="true"
					:limit="500"
					:minHeight="100"
					:options="['format', 'align']"
					:placeholder="'Заголовок'"
				/>

				<Tiptap
					ref="tiptapDescription"
					v-model="currentInfoBlock.data.description.value"
					:editable="true"
					:limit="10_000"
					:minHeight="200"
					:options="['format', 'align', 'list', 'link']"
					:placeholder="'Заголовок'"
				/>
			</ContainerInput>
		</template>

		<template #footer>
			<BlockButtons>
				<ButtonDefault @click="addInfoBlock" v-if="modal.type == 'create'">
					<Icon :name="'add'" :fill="'white'" :width="'23px'" :height="'23px'" />
					Добавить
				</ButtonDefault>
				<button-remove
					@click="deleteInfoBlock"
					v-if="
						modal.type == 'edit' &&
						!currentInfoBlock.data.delete.value &&
						!currentInfoBlock.data.create.value
					"
				>
					<Icon :name="'delete'" :fill="'white'" :width="'24px'" :height="'22px'" />
					Удалить
				</button-remove>
				<ButtonDefault
					@click="updateInfoBlock"
					v-if="modal.type == 'edit' && !currentInfoBlock.data.delete.value"
				>
					<Icon :name="'edit'" :fill="'white'" :width="'28px'" :height="'28px'" />
					Обновить
				</ButtonDefault>
				<ButtonDefault @click="deleteInfoBlock" v-if="currentInfoBlock.data.delete.value">
					<Icon :name="'restore'" :fill="'white'" :width="'28px'" :height="'28px'" />
					Вернуть
				</ButtonDefault>
			</BlockButtons>
		</template>
	</admin-modal>

	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|            МОДАЛЬНОЕ ОКНО (ДОЧЕРНЕЕ)              |-->
	<!--|___________________________________________________|-->
	<admin-sub-modal ref="sub-modal" @touchCloseModal="closeModal('subModal')" :modal="subModal">
		<template #title>ЗАГРУЗКА ИЗОБРАЖЕНИЯ</template>
		<template #body>
			<container-input-once>
				<template #input>
					<input
						type="file"
						autocomplete="off"
						ref="fileUpload"
						:class="{ error: currentImage.errors.file.status }"
						@input="currentImage.data.file.edited = true"
					/>
				</template>
				<template #error>
					<span class="error" v-if="currentImage.errors.file.status">
						{{ currentImage.errors.file.message }}
					</span>
				</template>
			</container-input-once>
		</template>
		<template #footer>
			<block-buttons>
				<button-default
					v-if="subModal.type == 'edit'"
					@click="updateImage"
					:disabled="disabled.image.update"
				>
					<Icon :name="'edit'" :fill="'white'" :width="'28px'" :height="'28px'" />
					Обновить
				</button-default>
				<button-default
					v-if="subModal.type == 'create'"
					@click="updateImage"
					:disabled="disabled.image.add"
				>
					<Icon :name="'add'" :fill="'white'" :width="'23px'" :height="'23px'" />
					Добавить
				</button-default>
			</block-buttons>
		</template>
	</admin-sub-modal>

	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|               ИНФОРМАЦИОННЫЕ БЛОКИ                |-->
	<!--|___________________________________________________|-->
	<info-bar>
		<template v-slot:title>О нас</template>
		<template v-slot:addreas>about</template>
	</info-bar>

	<block-once :minHeight="300">
		<template #title>ИНФОРМАЦИОННЫЕ БЛОКИ</template>

		<template #options>
			<button-default
				@click.prevent="saveInfoBlocks"
				:disabled="disabled.about.save"
				:look="'white'"
			>
				<Icon :name="'save'" :fill="'white'" :width="'28px'" :height="'28px'" />
				Сохранить
			</button-default>
		</template>

		<template #body>
			<template v-if="loading.sections.infoBlocks">
				<AdminAboutUsList
					:infoBlocks="infoBlocks"
					@touchEditBlock="editInfoBlock"
					v-if="infoBlocks.length > 0"
				/>

				<!-- Элемент пустой страницы -->
				<empty :minHeight="300" v-if="infoBlocks.length == 0" />
			</template>

			<LoaderChild
				:isLoading="loading.loader.infoBlocks"
				:minHeight="300"
				@loaderChildAfterLeave="loaderChildAfterLeave"
			></LoaderChild>
		</template>

		<template #buttons>
			<button-default @click="openModal('create')">
				<Icon :name="'add'" :fill="'white'" :width="'23px'" :height="'23px'" />
				Добавить
			</button-default>
		</template>
	</block-once>
</template>

<script>
import AdminModal from "../../../components/includes/admin/AdminModal.vue";
import AdminSubModal from "../../../components/includes/admin/AdminSubModal.vue";

import InfoBar from "../../../components/ui/admin/InfoBar.vue";
import Tiptap from "../../../components/modules/Tiptap.vue";

import BlockOnce from "../../../components/ui/admin/blocks/BlockOnce.vue";
import BlockTwo from "../../../components/ui/admin/blocks/BlockTwo.vue";
import BlockButtons from "../../../components/ui/admin/blocks/BlockButtons.vue";

import ButtonDefault from "../../../components/ui/admin/buttons/ButtonDefault.vue";
import ButtonRemove from "../../../components/ui/admin/buttons/ButtonRemove.vue";
import ButtonClaim from "../../../components/ui/admin/buttons/ButtonClaim.vue";

import Icon from "../../../components/modules/icon/Icon.vue";
import IconArrow from "../../../components/icons/IconArrow.vue";
import IconHide from "../../../components/icons/IconHide.vue";
import IconVisible from "../../../components/icons/IconVisible.vue";

import LoaderChild from "../../../components/modules/LoaderChild.vue";
import Empty from "../../../components/modules/Empty.vue";

import AdminAboutUsList from "./AdminAboutUsList.vue";

import ContainerInput from "../../../components/ui/admin/containers/ContainerInput.vue";
import ContainerInputOnce from "../../../components/ui/admin/containers/input/ContainerInputOnce.vue";
import ContainerTextareaOnce from "../../../components/ui/admin/containers/textarea/ContainerTextareaOnce.vue";

import axios from "axios";
import shared from "../../../services/shared";
import files from "../../../services/files";
import sorted from "../../../services/sorted";
import validate from "../../../services/validate";

export default {
	components: {
		AdminModal,
		AdminSubModal,
		InfoBar,
		Tiptap,
		LoaderChild,

		BlockOnce,
		BlockTwo,
		BlockButtons,

		ButtonDefault,
		ButtonRemove,
		ButtonClaim,

		Icon,
		IconArrow,
		IconHide,
		IconVisible,

		Empty,
		AdminAboutUsList,
		ContainerInput,
		ContainerInputOnce,
		ContainerTextareaOnce,

		axios,
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
			},

			/* Загрузчик */
			loading: {
				loader: {
					title: true,
					infoBlocks: true,
				},
				sections: {
					title: false,
					infoBlocks: false,
				},
			},

			/* Модальное окно */
			modal: {
				title: "БЛОК",
				status: false,
				type: null,
				style: {
					create: false,
					delete: false,
				},
				modules: {
					title: true,
					buttons: {
						hide: true,
						close: true,
					},
					images: false,
					body: true,
					footer: true,
				},
			},

			subModal: {
				title: "",
				status: false,
				type: null,
				style: {
					create: false,
					delete: false,
				},
				modules: {
					title: true,
					buttons: {
						hide: false,
						close: true,
					},
					images: false,
					body: true,
					footer: true,
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
			this.loading.sections.title = true;
			this.loading.sections.infoBlocks = true;
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                 Модальное окно                    |*/
		/* |___________________________________________________|*/
		/* _____________________________________________________*/
		/* 1. Основные действия                                 */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		/* Открытие */
		openModal(type = "edit", name = "modal") {
			switch (type) {
				case "create":
					{
						this[name].type = "create";
						this[name].status = true;

						if (name === "modal") {
							shared.clearObjectSelective(this.currentInfoBlock, "data", [
								"value",
								"edited",
							]);
						}
					}
					document.body.classList.add("modal-open");
					break;
				case "edit":
					{
						this[name].type = "edit";
						this[name].status = true;
					}
					document.body.classList.add("modal-open");
					break;
				default:
					{
						this.$store.commit("addDebugger", {
							title: "Ошибка.",
							body: "Низвестный тип открытия модального окна.",
							type: "error",
						});
					}
					break;
			}
		},

		/* Закрытие */
		closeModal(name = "modal") {
			this[name].status = false;
			if (name == "modal") {
				document.body.classList.remove("modal-open");
			}
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |               SUB-МОДАЛЬНОЕ ОКНО                  |*/
		/* |___________________________________________________|*/
		/* _____________________________________________________*/
		/* 1. Основные действия                                 */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		/* Изменение картинки */
		editImage(name) {
			this.clearDataImage(name);

			this.openModal("edit", "subModal");
		},

		/* Изменение картинки */
		createImage(name) {
			this.clearDataImage(name);

			this.openModal("create", "subModal");
		},
		updateImage() {
			if (
				validate.checkInputsAll(this.currentImage, [
					{
						key: "file",
						type: "file",
						value: this.$refs.fileUpload,
						formats: ["jpg", "jpeg", "png", "webp"],
					},
				])
			)
				return;

			/* Загрузка файла */
			let formData = new FormData();
			formData.append("file", this.$refs.fileUpload.files[0]);
			formData.append("type", "abouts");
			formData.append("formats", ["png", "jpg", "jpeg", "webp"]);

			if (this.subModal.type == "create") {
				this.disabled.image.add = true;
			} else if (this.subModal.type == "edit") {
				this.disabled.image.update = true;
			}

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
						try {
							switch (this.currentImage.data.file.value) {
								case "imageOne":
									this.currentInfoBlock.data.pathOne.value = response.data.data;
									this.currentInfoBlock.data.imageOne.value = files.basename(
										response.data.data
									);
									break;
								case "imageTwo":
									this.currentInfoBlock.data.pathTwo.value = response.data.data;
									this.currentInfoBlock.data.imageTwo.value = files.basename(
										response.data.data
									);
									break;
								case "imageThree":
									this.currentInfoBlock.data.pathThree.value = response.data.data;
									this.currentInfoBlock.data.imageThree.value = files.basename(
										response.data.data
									);
									break;
							}

							this.closeModal("subModal");
						} catch (error) {
							this.$store.commit("addDebugger", {
								title: "Ошибка.",
								body: error,
								type: "error",
							});
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
						body: response.data.message,
						type: "error",
					});
				})
				.finally(() => {
					if (this.subModal.type == "create") {
						this.disabled.image.add = false;
					} else if (this.subModal.type == "edit") {
						this.disabled.image.update = false;
					}
				});
		},

		/* Очистка */
		clearDataImage(name) {
			this.currentImage.data.file.value = name;
			this.currentImage.data.file.edited = false;
			this.currentImage.errors.file.status = false;

			this.$refs.fileUpload.value = "";
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |              ИНФОРМАЦИОННЫЕ БЛОКИ                 |*/
		/* |___________________________________________________|*/
		/* _____________________________________________________*/
		/* 1. Основные действия                                 */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		/* Открытие */
		editInfoBlock(block) {
			this.modal.title = "БЛОК (РЕДАКТИРОВАНИЕ)";

			shared.clearObjectFull(this.currentInfoBlock);
			shared.setData(block, this.currentInfoBlock);

			this.openModal("edit", "modal");
		},

		/* Удаление */
		deleteInfoBlock() {
			try {
				let block = this.infoBlocks.find(
					(item) => item.id == this.currentInfoBlock.data.id.value
				);

				block.delete = !block.delete;

				this.closeModal("modal");
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

				this.closeModal("modal");
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: response.data.message,
					type: "error",
				});
			}
		},

		/* Обновление */
		updateInfoBlock() {
			let block = this.infoBlocks.find((item) => item.id == this.currentInfoBlock.data.id.value);

			for (let key in this.currentInfoBlock.data) {
				block[key] = this.currentInfoBlock.data[key].value;
			}

			this.closeModal("modal");
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

			axios({
				method: "post",
				url: `${this.$store.getters.urlApi}` + `save-abouts-changes`,
				headers: {
					ContentType: "multipart/form-data",
					Authorization: `Bearer ${localStorage.getItem("token")}`,
				},
				data: formData,
			})
				.then((response) => {
					if (response.data.status) {
						try {
							shared.updateId(this.infoBlocks, response.data.data);
							shared.clearDeletes(this.infoBlocks);
							shared.clearFlags(this.infoBlocks);
							shared.updateOrders(this.infoBlocks);

							this.$store.commit("addDebugger", {
								title: "Успешно!",
								body: response.data.message,
								type: "completed",
							});
						} catch (error) {
							this.$store.commit("addDebugger", {
								title: "Ошибка.",
								body: response.data.message,
								type: "error",
							});
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
					this.disabled.about.save = false;
				});
		},
	},
	mounted() {
		axios({
			method: "get",
			headers: {
				Accept: "application/json",
			},
			url: `${this.$store.getters.urlApi}` + `get-abouts-all`,
		})
			.then((response) => {
				if (response.data.status) {
					this.infoBlocks = response.data.data;

					this.infoBlocks.forEach((item) => {
						item.create = false;
						item.delete = false;
					});

					sorted.sortByOrder("up", this.infoBlocks);
				} else {
					this.$store.commit("addDebugger", {
						title: "Ошибка.",
						body: response.data.message,
						type: "error",
					});
				}
			})
			.catch((error) => {
				console.log(error);
			})
			.finally(() => {
				this.loading.loader.infoBlocks = false;
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
