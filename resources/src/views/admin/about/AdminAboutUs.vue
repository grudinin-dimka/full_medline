<template>
	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                  МОДАЛЬНОЕ ОКНО                   |-->
	<!--|___________________________________________________|-->
	<admin-modal ref="modal" @touchCloseModal="" :modal="modal">
		<template #title>
			<span v-if="modal.type == 'create'">СЕРТИФИКАТ (СОЗДАНИЕ)</span>
			<span v-if="modal.type == 'edit'">СЕРТИФИКАТ (РЕДАКТИРОВАНИЕ)</span>
		</template>
		<template #body> </template>
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

		<container-textarea-once :type="'edit'" v-if="loading.sections.title">
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

		<LoaderChild :isLoading="loading.loader.title" :minHeight="200"></LoaderChild>
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
			<AdminAboutUsList />

			<block-buttons>
				<button-default @click=""> Добавить </button-default>
			</block-buttons>
		</template>

		<LoaderChild :isLoading="loading.loader.infoBlocks" :minHeight="300"></LoaderChild>
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

import IconArrow from "../../../components/icons/IconArrow.vue";

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
		IconArrow,
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
			currentCertificate: {
				errors: {
					id: {
						body: "",
						status: false,
					},
					organization: {
						body: "",
						status: false,
					},
					endEducation: {
						body: "",
						status: false,
					},
					name: {
						body: "",
						status: false,
					},
				},
				data: {
					id: {
						body: "",
						edited: false,
					},
					organization: {
						body: "",
						edited: false,
					},
					endEducation: {
						body: "",
						edited: false,
					},
					name: {
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
		};
	},
	methods: {
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                   Загрузчик                       |*/
		/* |___________________________________________________|*/
		/* После скрытия загрузчика */
		loaderChildAfterLeave() {
			this.loading.table = true;
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
						this.clearModalErrors();

						this.modal.type = "create";
						this.modal.status = true;
						this.modal.style.create = true;
						this.modal.style.delete = false;
						this.clearModalData();
					}
					document.body.classList.add("modal-open");
					break;
				case "edit":
					{
						this.clearModalErrors();

						this.modal.type = "edit";
						if (this.currentCertificate.data.create.body) {
							this.modal.style.create = true;
						} else {
							this.modal.style.create = false;
						}
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
};
</script>

<style scoped></style>
