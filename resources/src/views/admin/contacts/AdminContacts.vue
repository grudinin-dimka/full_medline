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
			<ContainerInput>
				<container-textarea-once :type="'edit'">
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
							v-model="currentContact.data.name.body"
						></textarea>
					</template>
					<template #error>
						<span class="error" v-if="false"> Ошибка </span>
					</template>
				</container-textarea-once>
				<ContainerSelectOnce type="edit">
					<template #title>
						<span>КЛИНИКА</span>
					</template>
					<template #select>
						<select v-model="currentContact.data.clinicId.body">
							<option value="null" selected>Ничего не выбрано</option>
							<option v-for="clinic in clinics" :key="clinic.id" :value="clinic.id">
								{{ clinic.name }}
							</option>
						</select>
					</template>
				</ContainerSelectOnce>
				<admin-modal-list
					:array="currentContact.data.phones.body"
					@touchCreate="console.log('create')"
					@touchEdit="console.log('edit')"
					@touchDelete="deleteContactPhone"
				>
					<template #title>☎ ТЕЛЕФОНЫ</template>
				</admin-modal-list>
				<admin-modal-list
					:array="currentContact.data.mails.body"
					@touchCreate="console.log('create')"
					@touchEdit="console.log('edit')"
					@touchDelete="deleteContactMail"
				>
					<template #title>
						<span style="font-weight: bold">🖂</span>
						ПОЧТА
					</template>
				</admin-modal-list>
			</ContainerInput>
		</template>
		<template #footer>
			<BlockButtons>
				<button-claim v-if="false"> Создать </button-claim>
				<button-remove> Удалить </button-remove>
				<ButtonDefault @click="updateContact"> Обновить </ButtonDefault>
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
					<span>НОВЫЙ ФАЙЛ*</span>
					<span v-if="true"> (ИЗМЕНЕНО) </span>
				</template>
				<template #input>
					<input
						type="tel"
						mask="+7 (999) 999-99-99"
						autocomplete="off"
						:class="{ error: false }"
					/>
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
			<div class="item" @click="editContact(contact)" v-for="contact in contacts">
				<div class="head">
					<div>id: {{ contact.id }}</div>
					<div>order: {{ contact.order }}</div>
				</div>
				<div class="body">
					<div class="title">{{ contact.name }}</div>
					<div>
						Клиника:
						<span :class="{ empty: contact.clinicId == 'null' || contact.clinicId == null }">
							{{
								clinics.find((item) => item.id == contact.clinicId)?.name ?? "Отсутствует"
							}}
						</span>
					</div>
					<div class="info">
						<div class="phone">
							<div>Номера:</div>
							<ul>
								<li v-if="contact.phones.length > 0" v-for="phone in contact.phones">
									{{ phone.name }}
								</li>
								<li v-else>
									<span class="empty"> Отсутствует </span>
								</li>
							</ul>
						</div>
						<div class="mail">
							<div>Почта:</div>
							<ul>
								<li v-if="contact.mails.length > 0" v-for="mail in contact.mails">
									{{ mail.name }}
								</li>
								<li v-else>
									<span class="empty"> Отсутствует </span>
								</li>
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
import AdminSubModal from "../../../components/includes/admin/AdminSubModal.vue";
import AdminModalList from "../../../components/includes/admin/AdminModalList.vue";

import InfoBar from "../../../components/ui/admin/InfoBar.vue";

import LoaderChild from "../../../components/includes/LoaderChild.vue";
import Empty from "../../../components/includes/Empty.vue";

import BlockTitle from "../../../components/ui/admin/blocks/BlockTitle.vue";
import BlockOnce from "../../../components/ui/admin/blocks/BlockOnce.vue";
import BlockButtons from "../../../components/ui/admin/blocks/BlockButtons.vue";

import ContainerInput from "../../../components/ui/admin/containers/ContainerInput.vue";
import ContainerInputOnce from "../../../components/ui/admin/containers/input/ContainerInputOnce.vue";
import ContainerSelectOnce from "../../../components/ui/admin/containers/select/ContainerSelectOnce.vue";
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

import axios from "axios";

export default {
	components: {
		AdminModal,
		AdminSubModal,
		AdminModalList,
		InfoBar,
		LoaderChild,
		Empty,
		BlockTitle,
		BlockOnce,
		BlockButtons,
		ContainerInput,
		ContainerInputOnce,
		ContainerSelectOnce,
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
		axios,
	},
	data() {
		return {
			loading: {
				loader: {
					clinics: true,
				},
				sections: {
					clinics: false,
				},
			},
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
			currentContact: {
				errors: {
					id: {
						body: null,
						status: false,
					},
					name: {
						body: null,
						status: false,
					},
					clinicId: {
						body: null,
						status: false,
					},
					mails: {
						body: null,
						status: false,
					},
					phones: {
						body: null,
						status: false,
					},
					create: {
						body: null,
						status: false,
					},
					delete: {
						body: null,
						status: false,
					},
				},
				data: {
					id: {
						body: null,
						edited: false,
					},
					name: {
						body: null,
						edited: false,
					},
					clinicId: {
						body: null,
						edited: false,
					},
					mails: {
						body: [],
						edited: false,
					},
					phones: {
						body: [],
						edited: false,
					},
					create: {
						body: null,
						edited: false,
					},
					delete: {
						body: null,
						edited: false,
					},
				},
				currentPhone: {
					errors: {
						id: {
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
							body: null,
							edited: false,
						},
						name: {
							body: null,
							edited: false,
						},
					},
				},
			},
			contacts: [],
			clinics: [
				{
					id: 0,
					name: "Нулевой элемент",
				},
			],
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
		/* |               SUB-МОДАЛЬНОЕ ОКНО                  |*/
		/* |___________________________________________________|*/
		/* _____________________________________________________*/
		/* 1. Основные действия                                 */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		/* Изменение картинки */
		editContactMail(selectedMail) {
			this.openModal("edit", "subModal");
		},
		editContactPhone() {
			this.closeModal("subModal");
		},
		/* Удаление телефона */
		deleteContactPhone(selectedPhone) {
			this.currentContact.data.phones.body = this.currentContact.data.phones.body.filter(
				(phone) => {
					if (selectedPhone.id !== phone.id) {
						return phone;
					}
				}
			);
		},
		/* Удаление почты */
		deleteContactMail(selectedMail) {
			this.currentContact.data.mails.body = this.currentContact.data.mails.body.filter(
				(mail) => {
					if (selectedMail.id !== mail.id) {
						return mail;
					}
				}
			);
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
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                    КОНТАКТЫ                       |*/
		/* |___________________________________________________|*/
		/* Изменение */
		editContact(selectedContact) {
			for (let key in this.currentContact.data) {
				this.currentContact.data[key].body = selectedContact[key];
			}

			this.openModal("edit", "modal");
		},
		/* Обновление */
		updateContact() {
			let contact = this.contacts.find(
				(contact) => contact.id === this.currentContact.data.id.body
			);

			for (let key in this.currentContact.data) {
				contact[key] = this.currentContact.data[key].body;
			}

			this.closeModal("modal");
		},
	},
	mounted() {
		this.loading.loader.clinics = false;

		axios({
			method: "post",
			url: `${this.$store.state.axios.urlApi}` + `get-contacts-all`,
		})
			.then((response) => {
				if (response.data.status) {
					this.contacts = response.data.data.contacts;
					this.contacts.forEach((contact) => {
						contact.create = false;
						contact.delete = false;
					});

					this.clinics = response.data.data.clinics;
				} else {
				}
			})
			.catch((error) => {})
			.finally(() => {});
	},
};
</script>

<style scoped>
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
	content: "☎";
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

span.empty {
	color: #c7c7c7;
}
</style>
