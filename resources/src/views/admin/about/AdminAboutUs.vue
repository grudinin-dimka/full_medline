<template>
	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                  МОДАЛЬНОЕ ОКНО                   |-->
	<!--|___________________________________________________|-->
	<admin-modal ref="modal" @touchCloseModal="closeModal" :modal="modal">
		<template
			#title
			v-if="
				!currentInfoBlock.data.delete.body &&
				!currentInfoBlock.data.create.body &&
				!modal.style.create
			"
		>
			<icon-arrow :width="16" :height="16" :rotate="-90" @click="" />
			#{{ currentInfoBlock.data.order.body }}
			<icon-arrow :width="16" :height="16" :rotate="90" @click="" />
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
							backgroundImage: `url(${currentInfoBlock.data.imageOne.body})`,
						}"
					></div>
					<div class="buttons">
						<div class="icon edit" @click="openModal('edit', 'subModal')">
							<svg
								xmlns="http://www.w3.org/2000/svg"
								height="24px"
								viewBox="0 -960 960 960"
								width="24px"
								fill="#e8eaed"
							>
								<path
									d="M200-200h57l391-391-57-57-391 391v57Zm-80 80v-170l528-527q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L290-120H120Zm640-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z"
								/>
							</svg>
						</div>
						<div class="icon delete" @click="removeInfoBlockImage('imageOne')">
							<svg
								xmlns="http://www.w3.org/2000/svg"
								height="24px"
								viewBox="0 -960 960 960"
								width="24px"
								fill="#e8eaed"
							>
								<path
									d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"
								/>
							</svg>
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
					<div class="buttons add">
						<div class="icon create" @click="openModal('edit', 'subModal')">
							<svg
								xmlns="http://www.w3.org/2000/svg"
								height="24px"
								viewBox="0 -960 960 960"
								width="24px"
								fill="#e8eaed"
							>
								<path d="M440-440H200v-80h240v-240h80v240h240v80H520v240h-80v-240Z" />
							</svg>
						</div>
					</div>
				</div>
				<div class="item" v-if="currentInfoBlock.data.imageTwo.body">
					<div
						class="img"
						:style="{
							backgroundImage: `url(${currentInfoBlock.data.imageTwo.body})`,
						}"
					></div>
					<div class="buttons">
						<div class="icon edit" @click="openModal('edit', 'subModal')">
							<svg
								xmlns="http://www.w3.org/2000/svg"
								height="24px"
								viewBox="0 -960 960 960"
								width="24px"
								fill="#e8eaed"
							>
								<path
									d="M200-200h57l391-391-57-57-391 391v57Zm-80 80v-170l528-527q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L290-120H120Zm640-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z"
								/>
							</svg>
						</div>
						<div class="icon delete" @click="removeInfoBlockImage('imageTwo')">
							<svg
								xmlns="http://www.w3.org/2000/svg"
								height="24px"
								viewBox="0 -960 960 960"
								width="24px"
								fill="#e8eaed"
							>
								<path
									d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"
								/>
							</svg>
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
					<div class="buttons add">
						<div class="icon create" @click="openModal('edit', 'subModal')">
							<svg
								xmlns="http://www.w3.org/2000/svg"
								height="24px"
								viewBox="0 -960 960 960"
								width="24px"
								fill="#e8eaed"
							>
								<path d="M440-440H200v-80h240v-240h80v240h240v80H520v240h-80v-240Z" />
							</svg>
						</div>
					</div>
				</div>
				<div class="item" v-if="currentInfoBlock.data.imageThree.body">
					<div
						class="img"
						:style="{
							backgroundImage: `url(${currentInfoBlock.data.imageThree.body})`,
						}"
					></div>
					<div class="buttons">
						<div class="icon edit" @click="openModal('edit', 'subModal')">
							<svg
								xmlns="http://www.w3.org/2000/svg"
								height="24px"
								viewBox="0 -960 960 960"
								width="24px"
								fill="#e8eaed"
							>
								<path
									d="M200-200h57l391-391-57-57-391 391v57Zm-80 80v-170l528-527q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L290-120H120Zm640-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z"
								/>
							</svg>
						</div>
						<div class="icon delete" @click="removeInfoBlockImage('imageThree')">
							<svg
								xmlns="http://www.w3.org/2000/svg"
								height="24px"
								viewBox="0 -960 960 960"
								width="24px"
								fill="#e8eaed"
							>
								<path
									d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"
								/>
							</svg>
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
					<div class="buttons add">
						<div class="icon create" @click="openModal('edit', 'subModal')">
							<svg
								xmlns="http://www.w3.org/2000/svg"
								height="24px"
								viewBox="0 -960 960 960"
								width="24px"
								fill="#e8eaed"
							>
								<path d="M440-440H200v-80h240v-240h80v240h240v80H520v240h-80v-240Z" />
							</svg>
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
				<button-claim @click="" v-if="modal.type == 'create'"> Создать </button-claim>
				<button-remove
					@click="deleteInfoBlock"
					v-if="modal.type == 'edit' && !currentInfoBlock.data.delete.body"
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
			<container-input-once :type="'edit'">
				<template #title>
					<span>НОВЫЙ ФАЙЛ</span>
					<span v-if="false"> (ИЗМЕНЕНО) </span>
				</template>
				<template #input>
					<input type="file" />
				</template>
				<template #error>
					<span class="error" v-if="false"> Ошибка </span>
				</template>
			</container-input-once>
		</template>
		<template #footer>
			<block-buttons>
				<button-default @click=""> Обновить </button-default>
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
				<icon-save :width="28" :height="28" @click="" />
			</template>
		</block-title>

		<template v-if="loading.sections.infoBlocks">
			<AdminAboutUsList :infoBlocks="infoBlocks" @touchEditBlock="editInfoBlock" />

			<block-buttons>
				<button-default @click="openModal('create')"> Добавить </button-default>
			</block-buttons>
		</template>

		<LoaderChild
			:isLoading="loading.loader.infoBlocks"
			:minHeight="300"
			@loaderChildAfterLeave="loaderChildAfterLeave"
		></LoaderChild>
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

import LoaderChild from "../../../components/includes/LoaderChild.vue";

import AdminAboutUsList from "./AdminAboutUsList.vue";

import ContainerInputOnce from "../../../components/ui/admin/containers/input/ContainerInputOnce.vue";
import ContainerTextareaOnce from "../../../components/ui/admin/containers/textarea/ContainerTextareaOnce.vue";

import axios from "axios";

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
		LoaderChild,
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
						this.clearModalData("currentInfoBlock");
					}
					document.body.classList.add("modal-open");
					break;
				case "edit":
					{
						this[name].type = "edit";
						this[name].status = true;
						this[name].style.delete = false;
						if (this.currentInfoBlock.data.create.body) {
							this[name].style.create = true;
						} else {
							this[name].style.create = false;
						}

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
			let block = this.infoBlocks.find((item) => item.id == this.currentInfoBlock.data.id.body);

			if (block.delete) {
				block.delete = false;
			} else {
				block.delete = true;
			}

			this.closeModal("modal");
		},
		/* Обновление */
		updateInfoBlock() {
			let block = this.infoBlocks.find((item) => item.id == this.currentInfoBlock.data.id.body);

			for (let key in this.currentInfoBlock.data) {
				block[key] = this.currentInfoBlock.data[key].body;
			}

			this.closeModal("modal");
		},
		removeInfoBlockImage(name) {
			this.currentInfoBlock.data[name].body = "";
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

.modal-images > .item > .buttons > .icon.delete > svg {
	fill: #d74949;
}

.modal-images > .item > .buttons > .icon.edit {
	background-color: rgb(234, 253, 255);
}

.modal-images > .item > .buttons > .icon.edit:hover {
	background-color: rgb(224, 243, 245);
}

.modal-images > .item > .buttons > .icon.edit > svg {
	fill: var(--primary-color);
}

.modal-images > .item > .buttons > .icon.create {
	background-color: rgb(234, 255, 236);
}

.modal-images > .item > .buttons > .icon.create:hover {
	background-color: rgb(224, 245, 226);
}

.modal-images > .item > .buttons > .icon.create > svg {
	fill: rgb(62, 208, 76);
}

.textarea-show {
	animation: show-bottom-to-top-15 0.5s ease-in-out;
}
</style>
