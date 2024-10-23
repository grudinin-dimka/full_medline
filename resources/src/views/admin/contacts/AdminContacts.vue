<template>
	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                  МОДАЛЬНОЕ ОКНО                   |-->
	<!--|___________________________________________________|-->
	<admin-modal ref="modal" @touchCloseModal="closeModal('modal')" :modal="modal">
		<template #title v-if="true">
			<icon-arrow :width="16" :height="16" :rotate="-90" @click="" />
			#test
			<icon-arrow :width="16" :height="16" :rotate="90" @click="" />
		</template>
		<template #title v-if="true">
			<span v-if="modal.type == 'create'">КОНТАКТ (СОЗДАНИЕ)</span>
			<span v-if="modal.type == 'edit'">КОНТАКТ (РЕДАКТИРОВАНИЕ)</span>
		</template>
		<template #body>
			<container-textarea-once :type="'edit'">
				<template #title>
					<span>ЗАГОЛОВОК</span>
					<span v-if="true"> (ИЗМЕНЕНО) </span>
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
			<div class="modal-phone">
				<div class="title">Номера:</div>
				<div class="item">
					<div class="content">+7(495)123-45-67</div>
					<div class="buttons">
						<div class="icon edit">
							<IconEdit :width="24" :height="24" :type="'edit'"></IconEdit>
						</div>
						<div class="icon delete">
							<IconRemove :width="24" :height="24" :type="'delete'"></IconRemove>
						</div>
					</div>
				</div>
			</div>
		</template>
		<template #footer>
			<BlockButtons>
				<button-claim v-if="false"> Создать </button-claim>
				<button-remove> Удалить </button-remove>
				<ButtonDefault> Обновить </ButtonDefault>
			</BlockButtons>
		</template>
	</admin-modal>

	<info-bar>
		<template v-slot:title>Контакты</template>
		<template v-slot:addreas>contacts</template>
	</info-bar>

	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                     КОНТАКТЫ                      |-->
	<!--|___________________________________________________|-->
	<block-once>
		<block-title>
			<template #title>КОНТАКТЫ</template>
			<template #buttons>
				<icon-save :width="28" :height="28" @click="" />
			</template>
		</block-title>

		<div class="contacts" v-if="loading.sections.clinics">
			<div class="item" @click="openModal('edit', 'modal')">
				<div class="head">
					<div>id: 1</div>
					<div>order: 1</div>
				</div>
				<div class="body">
					<div class="title">Колл центр</div>
					<div class="info">
						<div class="phone">
							<div>Номера:</div>
							<ul>
								<li>+7(495)123-45-67</li>
								<li>+7(495)123-45-67</li>
								<li>+7(495)123-45-67</li>
							</ul>
						</div>
						<div class="mail">
							<div>Почта:</div>
							<ul>
								<li>medline-address@mail.ru</li>
								<li>medline-address@mail.ru</li>
								<li>medline-address@mail.ru</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Загрузка слайдов -->
		<loader-child
			:isLoading="loading.loader.clinics"
			:minHeight="300"
			@loaderChildAfterLeave="loaderChildAfterLeave"
		/>

		<BlockButtons>
			<ButtonDefault @click="openSlide(null, 'create')"> Добавить </ButtonDefault>
		</BlockButtons>
	</block-once>
</template>

<script>
import AdminModal from "../../../components/includes/admin/AdminModal.vue";

import InfoBar from "../../../components/ui/admin/InfoBar.vue";

import LoaderChild from "../../../components/includes/LoaderChild.vue";
import Empty from "../../../components/includes/Empty.vue";

import BlockTitle from "../../../components/ui/admin/blocks/BlockTitle.vue";
import BlockOnce from "../../../components/ui/admin/blocks/BlockOnce.vue";
import BlockButtons from "../../../components/ui/admin/blocks/BlockButtons.vue";

import ContainerTextareaOnce from "../../../components/ui/admin/containers/textarea/ContainerTextareaOnce.vue";

import ButtonDefault from "../../../components/ui/admin/buttons/ButtonDefault.vue";
import ButtonRemove from "../../../components/ui/admin/buttons/ButtonRemove.vue";
import ButtonClaim from "../../../components/ui/admin/buttons/ButtonClaim.vue";

import IconArrow from "../../../components/icons/IconArrow.vue";
import IconHide from "../../../components/icons/IconHide.vue";
import IconVisible from "../../../components/icons/IconVisible.vue";
import IconSave from "../../../components/icons/IconSave.vue";
import IconEdit from "../../../components/icons/IconEdit.vue";
import IconRemove from "../../../components/icons/IconRemove.vue";
import IconCreate from "../../../components/icons/IconCreate.vue";

export default {
	components: {
		AdminModal,
		InfoBar,
		LoaderChild,
		Empty,
		BlockTitle,
		BlockOnce,
		BlockButtons,
		ContainerTextareaOnce,
		ButtonDefault,
		ButtonRemove,
		ButtonClaim,
		IconArrow,
		IconHide,
		IconVisible,
		IconSave,
		IconEdit,
		IconRemove,
		IconCreate,
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
			loading: {
				loader: {
					clinics: true,
				},
				sections: {
					clinics: false,
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
			this.loading.sections.clinics = true;
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
						this[name].style.create = false;

						if (false) {
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
	},
	mounted() {
		this.loading.loader.clinics = false;
	},
};
</script>

<style scoped>
.modal-phone > .item {
	display: flex;
	justify-content: space-between;
	align-items: center;

	border: 2px solid var(--input-border-color-inactive);
	border-radius: 10px;
   padding: 10px;
}

.modal-phone > .item > .buttons {
	display: flex;
	gap: 10px;
}

.modal-phone > .item > .buttons > .icon {
	cursor: pointer;
	padding: 10px;
	border-radius: 100px;

	width: 25px;
	height: 25px;
}

.modal-phone > .item > .buttons > .icon.edit {
	background-color: rgb(234, 253, 255);
}

.modal-phone > .item > .buttons > .icon.edit:hover {
	background-color: rgb(224, 243, 245);
}

.modal-phone > .item > .buttons > .icon.create {
	background-color: rgb(234, 255, 236);
}

.modal-phone > .item > .buttons > .icon.create:hover {
	background-color: rgb(224, 245, 226);
}

.modal-phone > .item > .buttons > .icon.delete {
	background-color: rgb(255, 237, 237);
}

.modal-phone > .item > .buttons > .icon.delete:hover {
	background-color: rgb(245, 227, 227);
}

.contacts {
	display: grid;
	grid-template-columns: repeat(3, 1fr);
	gap: 20px;

	animation: show-bottom-to-top-15 0.5s ease-in-out;
}

.contacts > .item {
	border: 2px solid var(--input-border-color-inactive);
	border-radius: 10px;
}

.contacts > .item {
	cursor: pointer;
	display: flex;
	flex-direction: column;
	gap: 10px;

	border: 2px solid var(--input-border-color-inactive);
	border-radius: 10px;
	padding: 10px;

	font-size: 16px;

	transition: all 0.2s;
}

.contacts > .item:hover {
	border: 2px solid var(--input-border-color-active);
	background-color: #f2feff;
}

.contacts > .item:hover > .head > div {
	border: 2px solid var(--input-border-color-active);
	background-color: #f2feff;
}

.contacts > .item > .head {
	display: flex;
	gap: 10px;
}

.contacts > .item > .head > div {
	padding: 5px 10px;
	border-radius: 7.5px;
	border: 2px solid var(--input-border-color-inactive);

	transition: all 0.2s;
}

.contacts > .item > .body {
	display: flex;
	flex-direction: column;
	gap: 10px;
}

.contacts > .item > .body > .title {
	font-size: 22px;
	color: var(--primary-color);
}

.contacts > .item > .body > .info {
	display: grid;
	grid-template-columns: repeat(2, minmax(200px, 1fr));
	gap: 10px;
}

.contacts > .item > .body > .info > :is(.mail, .phone) > ul {
	padding: 0px;
	margin: 10px 10px;
	inline-size: 80%;
}

.contacts > .item > .body > .info > :is(.mail, .phone) > ul > li {
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;

	margin-top: 10px;
}

.contacts > .item > .body > .info > .mail > ul > li::before {
	content: "🖂";
	padding-right: 10px;
	font-weight: bold;
}

.contacts > .item > .body > .info > .phone > ul > li::before {
	content: "☎ ";
	padding-right: 10px;
	font-weight: bold;
}

.contacts > .item > .body > .phone > ul {
	padding: 0px 30px;
	margin: 5px;
}

.contacts > .item > .body > .phone > ul > li {
	padding: 10px;
	margin: 0px;
}
</style>
