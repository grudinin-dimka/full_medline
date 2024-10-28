<template>
	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                  МОДАЛЬНОЕ ОКНО                   |-->
	<!--|___________________________________________________|-->
	<admin-modal ref="modal" @touchCloseModal="closeModal" :modal="modal">
		<template #title v-if="!currentInfoBlock.data.delete.body && !modal.style.create">
			<icon-arrow :width="16" :height="16" :rotate="-90" @click="changeInfoBlockOrder('down')" />
			#{{ currentInfoBlock.data.order.body }}
			<icon-arrow :width="16" :height="16" :rotate="90" @click="changeInfoBlockOrder('up')" />
		</template>
		<template #title v-else>
			<span v-if="modal.type == 'create'">БЛОК (СОЗДАНИЕ)</span>
			<span v-if="modal.type == 'edit'">БЛОК (РЕДАКТИРОВАНИЕ)</span>
		</template>
		<template #body>
			<div class="modal-images">
				<div class="item" v-if="currentInfoBlock.data.imageOne.body">
					<div
						class="img"
						:style="{
							backgroundImage: `url(${currentInfoBlock.data.pathOne.body})`,
						}"
					></div>
					<div class="buttons" v-if="!currentInfoBlock.data.delete.body">
						<div class="icon edit" @click="editImage('imageOne')">
							<IconEdit :width="24" :height="24" :type="'edit'"></IconEdit>
						</div>
						<div class="icon delete" @click="removeInfoBlockImage('imageOne')">
							<IconRemove :width="24" :height="24" :type="'delete'"></IconRemove>
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
					<div class="buttons add" v-if="!currentInfoBlock.data.delete.body">
						<div class="icon create" @click="createImage('imageOne')">
							<IconCreate :width="24" :height="24" :type="'create'"></IconCreate>
						</div>
					</div>
				</div>
				<div class="item" v-if="currentInfoBlock.data.imageTwo.body">
					<div
						class="img"
						:style="{
							backgroundImage: `url(${currentInfoBlock.data.pathTwo.body})`,
						}"
					></div>
					<div class="buttons" v-if="!currentInfoBlock.data.delete.body">
						<div class="icon edit" @click="editImage('imageTwo')">
							<IconEdit :width="24" :height="24" :type="'edit'"></IconEdit>
						</div>
						<div class="icon delete" @click="removeInfoBlockImage('imageTwo')">
							<IconRemove :width="24" :height="24" :type="'delete'"></IconRemove>
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
					<div class="buttons add" v-if="!currentInfoBlock.data.delete.body">
						<div class="icon create" @click="createImage('imageTwo')">
							<IconCreate :width="24" :height="24" :type="'create'"></IconCreate>
						</div>
					</div>
				</div>
				<div class="item" v-if="currentInfoBlock.data.imageThree.body">
					<div
						class="img"
						:style="{
							backgroundImage: `url(${currentInfoBlock.data.pathThree.body})`,
						}"
					></div>
					<div class="buttons" v-if="!currentInfoBlock.data.delete.body">
						<div class="icon edit" @click="editImage('imageThree')">
							<IconEdit :width="24" :height="24" :type="'edit'"></IconEdit>
						</div>
						<div class="icon delete" @click="removeInfoBlockImage('imageThree')">
							<IconRemove :width="24" :height="24" :type="'delete'"></IconRemove>
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
					<div class="buttons add" v-if="!currentInfoBlock.data.delete.body">
						<div class="icon create" @click="createImage('imageThree')">
							<IconCreate :width="24" :height="24" :type="'create'"></IconCreate>
						</div>
					</div>
				</div>
			</div>
			<container-textarea-once
				:type="modal.type == 'create' ? 'create' : modal.style.delete ? 'delete' : 'edit'"
			>
				<template #title>
					<span>ЗАГОЛОВОК</span>
					<span v-if="currentInfoBlock.data.title.edited"> (ИЗМЕНЕНО) </span>
				</template>
				<template #textarea>
					<textarea
						rows="4"
						placeholder="Введите заголовок"
						autocomplete="off"
						:class="{ error: false }"
						v-model="currentInfoBlock.data.title.body"
						@input="currentInfoBlock.data.title.edited = true"
					></textarea>
				</template>
				<template #error>
					<span class="error" v-if="false"> Ошибка </span>
				</template>
			</container-textarea-once>
			<container-textarea-once
				:type="modal.type == 'create' ? 'create' : modal.style.delete ? 'delete' : 'edit'"
			>
				<template #title>
					<span>ОПИСАНИЕ</span>
					<span v-if="currentInfoBlock.data.description.edited"> (ИЗМЕНЕНО) </span>
				</template>
				<template #textarea>
					<textarea
						rows="8"
						placeholder="Введите заголовок"
						autocomplete="off"
						:class="{ error: false }"
						v-model="currentInfoBlock.data.description.body"
						@input="currentInfoBlock.data.description.edited = true"
					></textarea>
				</template>
				<template #error>
					<span class="error" v-if="false"> Ошибка </span>
				</template>
			</container-textarea-once>
		</template>
		<template #footer>
			<BlockButtons>
				<button-claim @click="addInfoBlock" v-if="modal.type == 'create'">
					Создать
				</button-claim>
				<button-remove
					@click="deleteInfoBlock"
					v-if="
						modal.type == 'edit' &&
						!currentInfoBlock.data.delete.body &&
						!currentInfoBlock.data.create.body
					"
				>
					Удалить
				</button-remove>
				<ButtonDefault
					@click="updateInfoBlock"
					v-if="modal.type == 'edit' && !currentInfoBlock.data.delete.body"
				>
					Обновить
				</ButtonDefault>
				<ButtonDefault @click="deleteInfoBlock" v-if="currentInfoBlock.data.delete.body">
					Восстановить
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
			<container-input-once
				:type="subModal.type == 'create' ? 'create' : subModal.style.delete ? 'delete' : 'edit'"
			>
				<template #title>
					<span>НОВЫЙ ФАЙЛ*</span>
					<span v-if="currentImage.data.edited"> (ИЗМЕНЕНО) </span>
				</template>
				<template #input>
					<input
						type="file"
						autocomplete="off"
						ref="fileUpload"
						:class="{ error: currentImage.errors.status }"
						@input="currentImage.data.edited = true"
					/>
				</template>
				<template #error>
					<span class="error" v-if="currentImage.errors.status">
						{{ currentImage.errors.body }}
					</span>
				</template>
			</container-input-once>
		</template>
		<template #footer>
			<block-buttons>
				<button-default v-if="subModal.type == 'edit'" @click="updateImage">
					Обновить
				</button-default>
				<button-claim v-if="subModal.type == 'create'" @click="updateImage">
					Добавить
				</button-claim>
			</block-buttons>
		</template>
	</admin-sub-modal>

	<info-bar>
		<template v-slot:title>О нас</template>
		<template v-slot:addreas>about</template>
	</info-bar>
	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|               ИНФОРМАЦИОННЫЕ БЛОКИ                |-->
	<!--|___________________________________________________|-->
	<block-once>
		<block-title>
			<template #title>ИНФОРМАЦИОННЫЕ БЛОКИ</template>
			<template #buttons>
				<icon-save :width="28" :height="28" @click="saveInfoBlocks" />
			</template>
		</block-title>

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

		<block-buttons>
			<button-default @click="openModal('create')"> Добавить </button-default>
		</block-buttons>
	</block-once>
</template>

<script>
import AdminModal from "../../../components/includes/admin/AdminModal.vue";
import AdminSubModal from "../../../components/includes/admin/AdminSubModal.vue";

import InfoBar from "../../../components/ui/admin/InfoBar.vue";

import BlockOnce from "../../../components/ui/admin/blocks/BlockOnce.vue";
import BlockTwo from "../../../components/ui/admin/blocks/BlockTwo.vue";
import BlockTitle from "../../../components/ui/admin/blocks/BlockTitle.vue";
import BlockButtons from "../../../components/ui/admin/blocks/BlockButtons.vue";

import ButtonDefault from "../../../components/ui/admin/buttons/ButtonDefault.vue";
import ButtonRemove from "../../../components/ui/admin/buttons/ButtonRemove.vue";
import ButtonClaim from "../../../components/ui/admin/buttons/ButtonClaim.vue";

import IconArrow from "../../../components/icons/IconArrow.vue";
import IconSave from "../../../components/icons/IconSave.vue";
import IconHide from "../../../components/icons/IconHide.vue";
import IconVisible from "../../../components/icons/IconVisible.vue";
import IconEdit from "../../../components/icons/IconEdit.vue";
import IconRemove from "../../../components/icons/IconRemove.vue";
import IconCreate from "../../../components/icons/IconCreate.vue";

import LoaderChild from "../../../components/includes/LoaderChild.vue";
import Empty from "../../../components/includes/Empty.vue";

import AdminAboutUsList from "./AdminAboutUsList.vue";

import ContainerInputOnce from "../../../components/ui/admin/containers/input/ContainerInputOnce.vue";
import ContainerTextareaOnce from "../../../components/ui/admin/containers/textarea/ContainerTextareaOnce.vue";

import axios from "axios";
import shared from "../../../services/shared";

export default {
	components: {
		AdminModal,
		AdminSubModal,
		InfoBar,
		BlockOnce,
		BlockTwo,
		BlockTitle,
		BlockButtons,
		ButtonDefault,
		ButtonRemove,
		ButtonClaim,
		IconArrow,
		IconSave,
		IconHide,
		IconVisible,
		IconEdit,
		IconRemove,
		IconCreate,
		LoaderChild,
		Empty,
		AdminAboutUsList,
		ContainerInputOnce,
		ContainerTextareaOnce,
		axios,
	},
	data() {
		return {
			modal: {
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
			currentInfoBlock: {
				errors: {
					id: {
						body: "",
						status: false,
					},
					order: {
						body: "",
						status: false,
					},
					title: {
						body: "",
						status: false,
					},
					description: {
						body: "",
						status: false,
					},
					imageOne: {
						body: "",
						status: false,
					},
					imageTwo: {
						body: "",
						status: false,
					},
					imageThree: {
						body: "",
						status: false,
					},
				},
				data: {
					id: {
						body: "",
						edited: false,
					},
					order: {
						body: "",
						edited: false,
					},
					title: {
						body: "",
						edited: false,
					},
					description: {
						body: "",
						edited: false,
					},
					imageOne: {
						body: "",
						edited: false,
					},
					imageTwo: {
						body: "",
						edited: false,
					},
					imageThree: {
						body: "",
						edited: false,
					},
					pathOne: {
						body: "",
						edited: false,
					},
					pathTwo: {
						body: "",
						edited: false,
					},
					pathThree: {
						body: "",
						edited: false,
					},
					create: {
						body: false,
						edited: false,
					},
					delete: {
						body: false,
						edited: false,
					},
				},
			},
			currentImage: {
				errors: {
					body: "",
					status: false,
				},
				data: {
					body: "",
					edited: false,
				},
			},
			infoBlocks: [],
		};
	},
	computed: {
		getSortedInfoBlocks() {
			return this.infoBlocks.sort((a, b) => a.order - b.order);
		},
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
						this[name].style.create = true;
						this[name].style.delete = false;
						if (name === "modal") {
							this.clearModalData("currentInfoBlock");
						}
					}
					document.body.classList.add("modal-open");
					break;
				case "edit":
					{
						this[name].type = "edit";
						this[name].status = true;
						this[name].style.delete = false;
						this[name].style.create = false;

						if (this.currentInfoBlock.data.delete.body) {
							this[name].style.delete = true;
						} else {
							this[name].style.delete = false;
						}
					}
					document.body.classList.add("modal-open");
					break;
				default:
					{
						let debbugStory = {
							title: "Ошибка.",
							body: "Низвестный тип открытия модального окна.",
							type: "Error",
						};
						this.$store.commit("debuggerState", debbugStory);
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
		/* Очистка содержимого модального окна */
		clearModalData(name = "currentInfoBlock") {
			for (let key in this[name].data) {
				this[name].data[key].body = "";
			}
		},
		/* Очистка содержимого модального окна */
		clearModalEdited(name = "currentInfoBlock") {
			for (let key in this[name].data) {
				this[name].data[key].edited = false;
			}
		},
		/* Очистка ошибок */
		clearModalErrors(name = "currentInfoBlock") {
			for (let key in this[name].errors) {
				this[name].errors[key].body = "";
				this[name].errors[key].status = false;
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
			/* Проверка на загруженный файл */
			if (!this.$refs.fileUpload.files[0]) {
				this.currentImage.errors.status = true;
				this.currentImage.errors.body = "Поле не может быть пустым.";
				return;
			}
			/* Проверка на тип загруженного файла */
			if (
				this.$refs.fileUpload.files[0].type !== "image/png" &&
				this.$refs.fileUpload.files[0].type !== "image/jpeg"
			) {
				this.currentImage.errors.status = true;
				this.currentImage.errors.body = "Разрешенный формат файла: png, jpg.";
				return;
			}
			/* Проверка на размер загруженного файла */
			let fileSize = this.$refs.fileUpload.files[0].size / 1024 / 1024;
			if (fileSize > 10) {
				this.currentImage.errors.status = true;
				this.currentImage.errors.body = "Размер файла более 10 МБ.";
				return;
			}

			this.currentImage.errors.status = false;

			/* Загрузка файла */
			let formData = new FormData();
			formData.append("image", this.$refs.fileUpload.files[0]);
			formData.append("type", "abouts");
			formData.append("formats", ["png", "jpg", "jpeg"]);

			axios({
				method: "post",
				url: `${this.$store.state.axios.urlApi}` + `upload-file`,
				headers: {
					"Content-Type": "multipart/form-data",
					Authorization: `Bearer ${localStorage.getItem("token")}`,
				},
				data: formData,
			})
				.then((response) => {
					if (response.data.status) {
						try {
							switch (this.currentImage.data.body) {
								case "imageOne":
									this.currentInfoBlock.data.pathOne.body = response.data.data;
									this.currentInfoBlock.data.imageOne.body = response.data.data.replace(
										"/storage/abouts/",
										""
									);
									break;
								case "imageTwo":
									this.currentInfoBlock.data.pathTwo.body = response.data.data;
									this.currentInfoBlock.data.imageTwo.body = response.data.data.replace(
										"/storage/abouts/",
										""
									);
									break;
								case "imageThree":
									this.currentInfoBlock.data.pathThree.body = response.data.data;
									this.currentInfoBlock.data.imageThree.body = response.data.data.replace(
										"/storage/abouts/",
										""
									);
									break;
							}
							this.closeModal("subModal");
						} catch (error) {
							let debbugStory = {
								title: "Ошибка.",
								body: "Не удалось обновить данные после загрузки изображения.",
								type: "Error",
							};
							this.$store.commit("debuggerState", debbugStory);
						}
					} else {
						let debbugStory = {
							title: "Ошибка.",
							body: response.data.message,
							type: "Error",
						};
						this.$store.commit("debuggerState", debbugStory);
					}
				})
				.catch((error) => {
					console.log(error);
				});
		},
		/* Очистка */
		clearDataImage(name) {
			this.currentImage.data.body = name;
			this.currentImage.data.edited = false;
			this.currentImage.errors.status = false;
			this.$refs.fileUpload.value = "";
		},
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |              ИНФОРМАЦИОННЫЕ БЛОКИ                 |*/
		/* |___________________________________________________|*/
		/* _____________________________________________________*/
		/* 1. Основные действия                                 */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		/* Открытие */
		editInfoBlock(selectedBlock) {
			this.modal.title = "БЛОК (РЕДАКТИРОВАНИЕ)";

			for (let key in this.currentInfoBlock.data) {
				this.currentInfoBlock.data[key].body = selectedBlock[key];
			}

			this.clearModalEdited("currentInfoBlock");
			this.clearModalErrors("currentInfoBlock");

			this.openModal("edit", "modal");
		},
		/* Удаление */
		deleteInfoBlock() {
			try {
				let block = this.infoBlocks.find(
					(item) => item.id == this.currentInfoBlock.data.id.body
				);

				block.delete = !block.delete;

				this.closeModal("modal");
			} catch (error) {
				let debbugStory = {
					title: "Ошибка.",
					body: "Не удалось пометить блок на удаление.",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
			}
		},
		/* Добавление */
		addInfoBlock() {
			try {
				this.infoBlocks.push({
					id: shared.getMaxId(this.infoBlocks) + 1,
					order: shared.getMaxOrder(this.infoBlocks) + 1,
					title: this.currentInfoBlock.data.title.body,
					description: this.currentInfoBlock.data.description.body,
					imageOne: this.currentInfoBlock.data.imageOne.body,
					imageTwo: this.currentInfoBlock.data.imageTwo.body,
					imageThree: this.currentInfoBlock.data.imageThree.body,
					pathOne: this.currentInfoBlock.data.pathOne.body,
					pathTwo: this.currentInfoBlock.data.pathTwo.body,
					pathThree: this.currentInfoBlock.data.pathThree.body,
					create: true,
					delete: false,
				});

				this.closeModal("modal");
			} catch (error) {
				let debbugStory = {
					title: "Ошибка.",
					body: "Не удалось добавить новый блок.",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
			}
		},
		/* Обновление */
		updateInfoBlock() {
			let block = this.infoBlocks.find((item) => item.id == this.currentInfoBlock.data.id.body);

			for (let key in this.currentInfoBlock.data) {
				block[key] = this.currentInfoBlock.data[key].body;
			}

			this.closeModal("modal");
		},
		/* Удаление */
		removeInfoBlockImage(name) {
			this.currentInfoBlock.data[name].body = "";
		},
		/* Изменение порядка */
		changeInfoBlockOrder(type) {
			if (this.infoBlocks.length <= 1) {
				return;
			}

			// Является ли текущий элемент первым
			let firstBlockStatus = this.currentInfoBlock.data.order.body == 1;
			// Предидущей элемент
			let blockPrevious = null;
			if (firstBlockStatus) {
				blockPrevious = this.infoBlocks.find((block) => block.order === this.infoBlocks.length);
			} else {
				blockPrevious = this.infoBlocks.find(
					(block) => block.order === this.currentInfoBlock.data.order.body - 1
				);
			}

			// Текущий элемент
			let blockCurrent = this.infoBlocks.find(
				(block) => block.order === this.currentInfoBlock.data.order.body
			);

			// Является ли текущий элемент последним
			let lastBlockStatus = this.currentInfoBlock.data.order.body == this.infoBlocks.length;

			// Следующий элемент
			let blockNext = null;
			if (lastBlockStatus) {
				blockNext = this.infoBlocks.find((block) => block.order === 1);
			} else {
				blockNext = this.infoBlocks.find(
					(block) => block.order === this.currentInfoBlock.data.order.body + 1
				);
			}

			// Изменение порядка
			switch (type) {
				case "up":
					{
						if (lastBlockStatus) {
							this.currentInfoBlock.data.order.body = 1;
							blockCurrent.order = 1;
							blockNext.order = this.infoBlocks.length;
						} else {
							this.currentInfoBlock.data.order.body++;
							blockCurrent.order++;
							blockNext.order--;
						}
						this.sortInfoBlocks("order");
					}
					break;
				case "down":
					{
						if (firstBlockStatus) {
							this.currentInfoBlock.data.order.body = this.infoBlocks.length;
							blockCurrent.order = this.infoBlocks.length;
							blockPrevious.order = 1;
						} else {
							this.currentInfoBlock.data.order.body--;
							blockCurrent.order--;
							blockPrevious.order++;
						}
						this.sortInfoBlocks("order");
					}
					break;
			}
		},
		/* Соритровка */
		sortInfoBlocks(type) {
			switch (type) {
				case "id":
					this.infoBlocks.sort((a, b) => a.id - b.id);
					break;
				case "order":
					this.infoBlocks.sort((a, b) => a.order - b.order);
					break;
				default:
					break;
			}
		},
		/* Сохранение */
		saveInfoBlocks() {
			let formData = new FormData();
			formData.append("abouts", JSON.stringify(this.infoBlocks));

			axios({
				method: "post",
				url: `${this.$store.state.axios.urlApi}` + `save-abouts-changes`,
				headers: {
					"Content-Type": "multipart/form-data",
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

							let debbugStory = {
								title: "Успешно!",
								body: response.data.message,
								type: "Completed",
							};
							this.$store.commit("debuggerState", debbugStory);
						} catch (error) {
							let debbugStory = {
								title: "Ошибка.",
								body: "Не удалось обновить данные после загрузки изображения.",
								type: "Error",
							};
							this.$store.commit("debuggerState", debbugStory);
						}
					} else {
						let debbugStory = {
							title: "Ошибка.",
							body: response.data.message,
							type: "Error",
						};
						this.$store.commit("debuggerState", debbugStory);
					}
				})
				.catch((error) => {
					let debbugStory = {
						title: "Ошибка.",
						body: "Не удалось сохранить данные.",
						type: "Error",
					};
					this.$store.commit("debuggerState", debbugStory);
				});
		},
	},
	mounted() {
		axios({
			method: "post",
			headers: {
				Accept: "application/json",
			},
			url: `${this.$store.state.axios.urlApi}` + `get-abouts-all`,
		})
			.then((response) => {
				if (response.data.status) {
					this.infoBlocks = response.data.data;

					this.infoBlocks.forEach((item) => {
						item.create = false;
						item.delete = false;
					});

					this.infoBlocks.sort((a, b) => a.order - b.order);
				} else {
					let debbugStory = {
						title: "Ошибка.",
						body: response.data.message,
						type: "Error",
					};
					this.$store.commit("debuggerState", debbugStory);
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
	gap: 10px;
}

.modal-images > .item {
	position: relative;
	flex: 1 0 300px;
	gap: 10px;

	border-radius: 10px;
	min-width: 100px;
	min-height: 300px;
}

.modal-images > .item.default {
	box-sizing: border-box;
	width: 100px;
	height: 100px;
	border: 6px dotted #e4e4e4;
}

.modal-images > .item > .img {
	flex: 1 0 300px;
	border-radius: 10px;
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
	top: 5px;
	right: 5px;
}

.modal-images > .item > .buttons > .icon {
	cursor: pointer;
	padding: 10px;
	border-radius: 100px;

	width: 25px;
	height: 25px;
}

.modal-images > .item > .buttons > .icon.delete {
	background-color: rgb(255, 237, 237);
}

.modal-images > .item > .buttons > .icon.delete:hover {
	background-color: rgb(245, 227, 227);
}

.modal-images > .item > .buttons > .icon.edit {
	background-color: rgb(234, 253, 255);
}

.modal-images > .item > .buttons > .icon.edit:hover {
	background-color: rgb(224, 243, 245);
}

.modal-images > .item > .buttons > .icon.create {
	background-color: rgb(234, 255, 236);
}

.modal-images > .item > .buttons > .icon.create:hover {
	background-color: rgb(224, 245, 226);
}

.textarea-show {
	animation: show-bottom-to-top-15 0.5s ease-in-out;
}
</style>
