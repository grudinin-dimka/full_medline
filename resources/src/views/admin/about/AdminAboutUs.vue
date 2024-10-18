<template>
	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                  МОДАЛЬНОЕ ОКНО                   |-->
	<!--|___________________________________________________|-->
	<admin-modal ref="modal" @touchCloseModal="closeModal" :modal="modal">
		<template #title>
			<span v-if="modal.type == 'create'">ИНФОРМАЦИОННЫЙ БЛОК</span>
			<span v-if="modal.type == 'edit'">ИНФОРМАЦИОННЫЙ БЛОК (РЕДАКТИРОВАНИЕ)</span>
		</template>
		<template #body>
			<!-- STOP делал кнопки для картинок у модального окна -->
			<div class="modal-images">
				<div class="item" v-if="currentInfoBlock.data.images.one.body">
					<div
						class="img"
						:style="{
							backgroundImage: `url(${currentInfoBlock.data.images.one.body})`,
						}"
					></div>
					<div class="buttons">
						<div class="icon edit">
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
						<div class="icon delete">
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
					<div class="buttons">
						<div class="icon create">
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
				<div class="item" v-if="currentInfoBlock.data.images.two.body">
					<div
						class="img"
						:style="{
							backgroundImage: `url(${currentInfoBlock.data.images.two.body})`,
						}"
					></div>
					<div class="buttons">
						<div class="icon delete">
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
					<div class="buttons">
						<div class="icon create">
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
				<div class="item" v-if="currentInfoBlock.data.images.three.body">
					<div
						class="img"
						:style="{
							backgroundImage: `url(${currentInfoBlock.data.images.three.body})`,
						}"
					></div>
					<div class="buttons">
						<div class="icon delete">
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
					<div class="buttons">
						<div class="icon create">
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
			<container-textarea-once :type="'edit'">
				<template #title>
					<span>ОПИСАНИЕ</span>
					<span v-if="false"> (ИЗМЕНЕНО) </span>
				</template>
				<template #textarea>
					<textarea
						rows="8"
						placeholder="Введите заголовок"
						autocomplete="off"
						:class="{ error: false }"
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
				<ButtonDefault @click="" v-if="modal.type == 'edit'"> Обновить </ButtonDefault>
			</BlockButtons>
		</template>
	</admin-modal>

	<info-bar>
		<template v-slot:title>О нас</template>
		<template v-slot:addreas>about</template>
	</info-bar>
	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                    ЗАГОЛОВОК                      |-->
	<!--|___________________________________________________|-->
	<block-once>
		<block-title>
			<template #title>ЗАГОЛОВОК СТРАНИЦЫ</template>
			<template #buttons>
				<icon-save :width="28" :height="28" @click="" />
			</template>
		</block-title>

		<container-textarea-once :type="'edit'" v-if="loading.sections.title" class="textarea-show">
			<template #title>
				<span>ЗАГОЛОВОК</span>
				<span v-if="false"> (ИЗМЕНЕНО) </span>
			</template>
			<template #textarea>
				<textarea
					rows="4"
					placeholder="Введите заголовок"
					autocomplete="off"
					:class="{ error: false }"
				></textarea>
			</template>
			<template #error>
				<span class="error" v-if="false"> Ошибка </span>
			</template>
		</container-textarea-once>

		<LoaderChild
			:isLoading="loading.loader.title"
			:minHeight="150"
			@loaderChildAfterLeave="loaderChildAfterLeave"
		></LoaderChild>
	</block-once>
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
			<AdminAboutUsList :infoBlocks="infoBlocks" @touchEditBlock="openModal('edit')" />

			<block-buttons>
				<button-default @click=""> Добавить </button-default>
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

import InfoBar from "../../../components/ui/admin/InfoBar.vue";

import BlockOnce from "../../../components/ui/admin/blocks/BlockOnce.vue";
import BlockTwo from "../../../components/ui/admin/blocks/BlockTwo.vue";
import BlockTitle from "../../../components/ui/admin/blocks/BlockTitle.vue";
import BlockButtons from "../../../components/ui/admin/blocks/BlockButtons.vue";

import ButtonDefault from "../../../components/ui/admin/buttons/ButtonDefault.vue";
import ButtonClaim from "../../../components/ui/admin/buttons/ButtonClaim.vue";

import IconSave from "../../../components/icons/IconSave.vue";

import LoaderChild from "../../../components/includes/LoaderChild.vue";

import AdminAboutUsList from "./AdminAboutUsList.vue";

import ContainerInputOnce from "../../../components/ui/admin/containers/input/ContainerInputOnce.vue";
import ContainerTextareaOnce from "../../../components/ui/admin/containers/textarea/ContainerTextareaOnce.vue";

export default {
	components: {
		AdminModal,
		InfoBar,
		BlockOnce,
		BlockTwo,
		BlockTitle,
		BlockButtons,
		ButtonDefault,
		ButtonClaim,
		IconSave,
		LoaderChild,
		AdminAboutUsList,
		ContainerInputOnce,
		ContainerTextareaOnce,
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
					images: {
						one: {
							body: "",
							status: false,
						},
						two: {
							body: "",
							status: false,
						},
						three: {
							body: "",
							status: false,
						},
					},
					description: {
						body: "",
						status: false,
					},
				},
				data: {
					id: {
						body: "",
						edited: false,
					},
					images: {
						one: {
							body: "/storage/about/1.jpg",
							status: false,
						},
						two: {
							body: "",
							status: false,
						},
						three: {
							body: "",
							status: false,
						},
					},
					description: {
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
			infoBlocks: [
				{
					id: 1,
					description: `Lorem, ipsum dolor sit amet consectetur adipisicing elit. Minima dicta explicabo nulla tempora eveniet dolore dolorem id earum suscipit, dolorum quidem iure provident autem nam, animi sapiente dignissimos sed est, asperiores beatae praesentium deserunt doloribus natus sit. Possimus, accusamus natus, dolorem ea nihil error, est quod consectetur veritatis vitae optio.`,
					images: [],
				},
				{
					id: 1,
					description: `Lorem, ipsum dolor sit amet consectetur adipisicing elit. Minima dicta explicabo nulla tempora eveniet dolore dolorem id earum suscipit, dolorum quidem iure provident autem nam, animi sapiente dignissimos sed est, asperiores beatae praesentium deserunt doloribus natus sit. Possimus, accusamus natus, dolorem ea nihil error, est quod consectetur veritatis vitae optio.`,
					images: [
						{
							id: 1,
							path: "/storage/about/1.jpg",
						},
					],
				},
				{
					id: 2,
					description: `Lorem, ipsum dolor sit amet consectetur adipisicing elit. Minima dicta explicabo nulla tempora eveniet dolore dolorem id earum suscipit, dolorum quidem iure provident autem nam, animi sapiente dignissimos sed est, asperiores beatae praesentium deserunt doloribus natus sit. Possimus, accusamus natus, dolorem ea nihil error, est quod consectetur veritatis vitae optio. Possimus, accusamus natus, dolorem ea nihil error, est quod consectetur veritatis vitae optio. Possimus, accusamus natus, dolorem ea nihil error, est quod consectetur veritatis vitae optio. Possimus, accusamus natus, dolorem ea nihil error, est quod consectetur veritatis vitae optio.`,
					images: [
						{
							id: 3,
							path: "/storage/about/2.jpg",
						},
						{
							id: 4,
							path: "/storage/about/3.jpg",
						},
					],
				},
				{
					id: 3,
					description: "Описание блока",
					images: [
						{
							id: 5,
							path: "/storage/about/4.jpg",
						},
						{
							id: 6,
							path: "/storage/about/5.jpg",
						},
						{
							id: 7,
							path: "/storage/about/6.jpg",
						},
					],
				},
			],
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
		openModal(type) {
			switch (type) {
				case "create":
					{
						// this.clearModalErrors();

						this.modal.type = "create";
						this.modal.status = true;
						this.modal.style.create = true;
						this.modal.style.delete = false;
						// this.clearModalData();
					}
					document.body.classList.add("modal-open");
					break;
				case "edit":
					{
						// this.clearModalErrors();

						this.modal.type = "edit";
						// if (this.currentCertificate.data.create.body) {
						// 	this.modal.style.create = true;
						// } else {
						this.modal.style.create = false;
						// }
						this.modal.status = true;
						this.modal.style.delete = false;
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
		closeModal() {
			this.modal.status = false;
			document.body.classList.remove("modal-open");
		},
		/* Очистка содержимого модального окна */
		clearModalData() {
			for (let key in this.currentCertificate.data) {
				this.currentCertificate.data[key].body = "";
				this.currentCertificate.data[key].edited = false;
			}
		},
		/* Очистка ошибок */
		clearModalErrors() {
			for (let key in this.currentCertificate.errors) {
				this.currentCertificate.errors[key].body = "";
				this.currentCertificate.errors[key].status = false;
			}
		},
	},
	mounted() {
		this.loading.loader.title = false;
		this.loading.loader.infoBlocks = false;
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
