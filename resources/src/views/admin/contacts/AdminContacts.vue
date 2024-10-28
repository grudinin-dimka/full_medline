<template>
	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                  МОДАЛЬНОЕ ОКНО                   |-->
	<!--|___________________________________________________|-->
	<admin-modal ref="modal" @touchCloseModal="closeModal('modal')" :modal="modal">
		<template
			#title
			v-if="
				modal.type == 'edit' ||
				currentContact.data.create.body !== true ||
				currentContact.data.delete.body !== true
			"
		>
			<span v-if="modal.type == 'create'">КОНТАКТ (СОЗДАНИЕ)</span>
			<span v-if="modal.type == 'edit'">КОНТАКТ (РЕДАКТИРОВАНИЕ)</span>
		</template>
		<template #title v-else>
			<icon-arrow :width="16" :height="16" :rotate="-90" @click="" />
			#test
			<icon-arrow :width="16" :height="16" :rotate="90" @click="" />
		</template>
		<template #body>
			<ContainerInput>
				<container-textarea-once
					:type="modal.type == 'create' ? 'create' : modal.style.delete ? 'delete' : 'edit'"
				>
					<template #title>
						<span>ЗАГОЛОВОК</span>
						<span v-if="currentContact.data.name.edited"> (ИЗМЕНЕНО) </span>
					</template>
					<template #textarea>
						<textarea
							rows="4"
							placeholder="Введите заголовок"
							autocomplete="off"
							:class="{ error: currentContact.errors.name.status }"
							v-model="currentContact.data.name.body"
							@input="currentContact.data.name.edited = true"
							@blur="checkModalInput('currentContact', 'name', 'text')"
						></textarea>
					</template>
					<template #error>
						<span class="error" v-if="currentContact.errors.name.status">
							{{ currentContact.errors.name.body }}
						</span>
					</template>
				</container-textarea-once>
				<ContainerSelectOnce
					:type="modal.type == 'create' ? 'create' : modal.style.delete ? 'delete' : 'edit'"
				>
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
					:type="modal.type == 'create' ? 'create' : modal.style.delete ? 'delete' : 'edit'"
					:array="currentContact.data.phones.body"
					@touchCreate="createContactPhone"
					@touchEdit="editContactPhone"
					@touchDelete="deleteContactPhone"
				>
					<template #title>☎ ТЕЛЕФОНЫ</template>
				</admin-modal-list>
				<admin-modal-list
					:type="modal.type == 'create' ? 'create' : modal.style.delete ? 'delete' : 'edit'"
					:array="currentContact.data.mails.body"
					@touchCreate="createContactMail"
					@touchEdit="editContactMail"
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
				<button-claim v-if="modal.type == 'create'" @click="addContact"> Создать </button-claim>
				<template v-else>
					<button-remove> Удалить </button-remove>
					<ButtonDefault @click="updateContact"> Обновить </ButtonDefault>
				</template>
			</BlockButtons>
		</template>
	</admin-modal>

	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|             МОДАЛЬНОЕ ОКНО (ТЕЛЕФОН)              |-->
	<!--|___________________________________________________|-->
	<admin-sub-modal
		ref="sub-modal-phone"
		@touchCloseModal="closeModal('subModalPhone')"
		:modal="subModalPhone"
	>
		<template #title>ТЕЛЕФОН</template>
		<template #body>
			<container-input-once
				:type="
					subModalPhone.type == 'create'
						? 'create'
						: subModalPhone.style.delete
						? 'delete'
						: 'edit'
				"
			>
				<template #title>
					<span>НОМЕР ТЕЛЕФОНА*</span>
					<span v-if="currentPhone.data.name.edited"> (ИЗМЕНЕНО) </span>
				</template>
				<template #input>
					<input
						type="tel"
						placeholder="+7(___)-___-__-__"
						v-mask="'+7(###)-###-##-##'"
						autocomplete="off"
						:class="{ error: currentPhone.errors.name.status }"
						v-model="currentPhone.data.name.body"
						@input="currentPhone.data.name.edited = true"
						@blur="checkModalInput('currentPhone', 'name', 'phone')"
					/>
				</template>
				<template #error>
					<span class="error" v-if="currentPhone.errors.name.status">
						{{ currentPhone.errors.name.body }}
					</span>
				</template>
			</container-input-once>
		</template>
		<template #footer>
			<block-buttons>
				<button-default v-if="subModalPhone.type == 'edit'" @click="updateContactPhone">
					Обновить
				</button-default>
				<button-claim v-if="subModalPhone.type == 'create'" @click="addContactPhone">
					Добавить
				</button-claim>
			</block-buttons>
		</template>
	</admin-sub-modal>

	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|             МОДАЛЬНОЕ ОКНО (ПОЧТА)                |-->
	<!--|___________________________________________________|-->
	<admin-sub-modal
		ref="sub-modal-mail"
		@touchCloseModal="closeModal('subModalMail')"
		:modal="subModalMail"
	>
		<template #title>ПОЧТА</template>
		<template #body>
			<container-input-once
				:type="
					subModalMail.type == 'create'
						? 'create'
						: subModalPhone.style.delete
						? 'delete'
						: 'edit'
				"
			>
				<template #title>
					<span>ЭЛЕКТРОННЫЙ АДРЕС*</span>
					<span v-if="currentMail.data.name.edited"> (ИЗМЕНЕНО) </span>
				</template>
				<template #input>
					<input
						type="mail"
						placeholder="Введите почту"
						autocomplete="off"
						required
						:class="{ error: currentMail.errors.name.status }"
						v-model="currentMail.data.name.body"
						@input="currentMail.data.name.edited = true"
						@blur="checkModalInput('currentMail', 'name', 'email')"
					/>
				</template>
				<template #error>
					<span class="error" v-if="currentMail.errors.name.status">
						{{ currentMail.errors.name.body }}
					</span>
				</template>
			</container-input-once>
		</template>
		<template #footer>
			<block-buttons>
				<button-default v-if="subModalMail.type == 'edit'" @click="updateContactMail">
					Обновить
				</button-default>
				<button-claim v-if="subModalMail.type == 'create'" @click="addContactMail">
					Добавить
				</button-claim>
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

		<!-- Список элементов -->
		<AdminContactsList
			v-if="loading.sections.clinics"
			:contacts="contacts"
			:clinics="clinics"
			@touchEditContact="editContact"
		/>

		<!-- Загрузка элементов -->
		<loader-child
			:isLoading="loading.loader.clinics"
			:minHeight="300"
			@loaderChildAfterLeave="loaderChildAfterLeave"
		/>

		<BlockButtons>
			<ButtonDefault @click="openModal('create')"> Добавить </ButtonDefault>
		</BlockButtons>
	</block-once>
</template>

<script>
import AdminModal from "../../../components/includes/admin/AdminModal.vue";
import AdminSubModal from "../../../components/includes/admin/AdminSubModal.vue";
import AdminModalList from "../../../components/includes/admin/AdminModalList.vue";

import AdminContactsList from "./AdminContactsList.vue";

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
import shared from "../../../services/shared.js";

export default {
	components: {
		AdminModal,
		AdminSubModal,
		AdminModalList,
		AdminContactsList,
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
			validator: {
				email: null,
			},
			form: {
				email: null,
			},
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
			subModalPhone: {
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
			subModalMail: {
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
			currentMail: {
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
							this.clearModalData("currentContact");
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
		/* _____________________________________________________*/
		/* 2. Работа с полями ввода модального окна             */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		/* Проверка введенного значения почты */
		checkInputText(value) {
			/* Проверка на пустую строку */
			if (value === "" || value === null) {
				return {
					status: true,
					message: "Поле не может быть пустым.",
				};
			}

			/* Проверка на соответствие типу string */
			if (typeof value != "string") {
				return {
					status: true,
					message: "Тип данных не совпадает.",
				};
			}

			return {
				status: false,
				message: "Ошибок нет.",
			};
		},
		/* Проверка введенного текстового значения */
		checkInputEmail(value) {
			/* Проверка на пустую строку */
			if (value === "" || value === null) {
				return {
					status: true,
					message: "Поле не может быть пустым.",
				};
			}

			/* Проверка на соответствие типу string */
			if (typeof value != "string") {
				return {
					status: true,
					message: "Тип данных не совпадает.",
				};
			}

			/* Проверка на правильность почты */
			let mailRegexp =
				/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

			if (!mailRegexp.test(value)) {
				return {
					status: true,
					message: "Почта не прошла проверку.",
				};
			}

			return {
				status: false,
				message: "Ошибок нет.",
			};
		},
		/* Проверка введенного текстового значения */
		checkInputPhone(value) {
			/* Проверка на пустую строку */
			if (value === "" || value === null) {
				return {
					status: true,
					message: "Поле не может быть пустым.",
				};
			}

			/* Проверка на соответствие типу string */
			if (typeof value != "string") {
				return {
					status: true,
					message: "Тип данных не совпадает.",
				};
			}

			/* Проверка на правильность номера телефона */
			let phoneRegexp = /^\+7\(\d{3}\)-\d{3}-\d{2}-\d{2}$/;

			if (!phoneRegexp.test(value)) {
				return {
					status: true,
					message: "Телефон не прошел проверку.",
				};
			}

			return {
				status: false,
				message: "Ошибок нет.",
			};
		},
		// Проверка поля имени
		checkModalInput(currentName, dataKey, inputType) {
			let errorLog = {};
			switch (inputType) {
				case "text":
					errorLog = this.checkInputText(this[currentName].data[dataKey].body);
					break;
				case "email":
					errorLog = this.checkInputEmail(this[currentName].data[dataKey].body);
					break;
				case "phone":
					errorLog = this.checkInputPhone(this[currentName].data[dataKey].body);
					break;
				default:
					break;
			}

			if (errorLog.status) {
				this[currentName].errors[dataKey].body = errorLog.message;
				this[currentName].errors[dataKey].status = true;

				return true;
			} else {
				this[currentName].errors[dataKey].body = "";
				this[currentName].errors[dataKey].status = false;

				return false;
			}
		},
		/* Очистка содержимого модального окна */
		clearModalData(name = "currentInfoBlock") {
			for (let key in this[name].data) {
				if (key === "phones" || key === "mails") {
					this[name].data[key].body = [];
					continue;
				}

				this[name].data[key].body = null;
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
				this[name].errors[key].body = null;
				this[name].errors[key].status = false;
			}
		},
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                    КОНТАКТЫ                       |*/
		/* |___________________________________________________|*/
		/* Добавление */
		addContact() {
			try {
				if (this.checkModalInput("currentContact", "name", "text")) return;

				this.contacts.push({
					id: shared.getMaxId(this.contacts) + 1,
					order: shared.getMaxOrder(this.contacts) + 1,
					name: this.currentContact.data.name.body,
					clinicId: this.currentContact.data.clinicId.body,
					phones: [...this.currentContact.data.phones.body],
					mails: [...this.currentContact.data.mails.body],
					create: true,
					delete: false,
				});

				this.closeModal("modal");
			} catch (error) {
				let debbugStory = {
					title: "Ошибка.",
					body: "Не удалось добавить контакт.",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
			}
		},
		/* Изменение */
		editContact(selectedContact) {
			for (let key in this.currentContact.data) {
				if (key == "phones" || key == "mails") {
					this.currentContact.data[key].body = [...selectedContact[key]];
				}

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
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                     ТЕЛЕФОН                       |*/
		/* |___________________________________________________|*/
		/* Создание */
		createContactPhone() {
			this.clearModalErrors("currentPhone");
			this.clearModalEdited("currentPhone");
			this.clearModalData("currentPhone");

			this.openModal("create", "subModalPhone");
		},
		/* Добавление */
		addContactPhone() {
			// Поиск максимального id
			try {
				if (this.checkModalInput("currentPhone", "name", "phone")) return;

				let maxId = this.getMaxIdFromArray(this.currentContact.data.phones.body);

				this.currentContact.data.phones.body.push({
					id: maxId + 1,
					name: this.currentPhone.data.name.body,
				});

				this.closeModal("subModalPhone");
			} catch (error) {
				let debbugStory = {
					title: "Ошибка.",
					body: "Не удалось добавить телефон.",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
			}
		},
		/* Изменение */
		editContactPhone(selectedPhone) {
			this.clearModalErrors("currentPhone");
			this.clearModalEdited("currentPhone");

			for (let key in this.currentPhone.data) {
				this.currentPhone.data[key].body = selectedPhone[key];
			}

			this.openModal("edit", "subModalPhone");
		},
		/* Обновление */
		updateContactPhone() {
			try {
				if (this.checkModalInput("currentPhone", "name", "phone")) return;

				let phone = this.currentContact.data.phones.body.find((item) => {
					return item.id == this.currentPhone.data.id.body;
				});

				for (let key in this.currentPhone.data) {
					phone[key] = this.currentPhone.data[key].body;
				}

				this.closeModal("subModalPhone");
			} catch (error) {
				let debbugStory = {
					title: "Ошибка.",
					body: "Не удалось обновить телефон.",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
			}
		},
		/* Удаление */
		deleteContactPhone(selectedPhone) {
			try {
				this.currentContact.data.phones.body = this.currentContact.data.phones.body.filter(
					(phone) => {
						if (selectedPhone.id !== phone.id) {
							return phone;
						}
					}
				);
			} catch (error) {
				let debbugStory = {
					title: "Ошибка.",
					body: "Не удалось обновить телефон.",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
			}
		},
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                      ПОЧТА                        |*/
		/* |___________________________________________________|*/
		/* _____________________________________________________*/
		/* 1. Основные действия                                 */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		/* Создание */
		createContactMail() {
			this.clearModalErrors("currentMail");
			this.clearModalEdited("currentMail");
			this.clearModalData("currentMail");

			this.openModal("create", "subModalMail");
		},
		/* Добавление */
		addContactMail() {
			// Поиск максимального id
			try {
				if (this.checkModalInput("currentMail", "name", "email")) return;

				let maxId = this.getMaxIdFromArray(this.currentContact.data.mails.body);

				this.currentContact.data.mails.body.push({
					id: maxId + 1,
					name: this.currentMail.data.name.body,
				});

				this.closeModal("subModalMail");
			} catch (error) {
				let debbugStory = {
					title: "Ошибка.",
					body: "Не удалось добавить почту.",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
			}
		},
		/* Изменение */
		editContactMail(selectedMail) {
			this.clearModalErrors("currentMail");
			this.clearModalEdited("currentMail");

			for (let key in this.currentMail.data) {
				this.currentMail.data[key].body = selectedMail[key];
			}

			this.openModal("edit", "subModalMail");
		},
		updateContactMail() {
			try {
				if (this.checkModalInput("currentMail", "name", "email")) return;

				let mail = this.currentContact.data.mails.body.find((item) => {
					return item.id == this.currentMail.data.id.body;
				});

				for (let key in this.currentMail.data) {
					mail[key] = this.currentMail.data[key].body;
				}

				this.closeModal("subModalMail");
			} catch (error) {
				let debbugStory = {
					title: "Ошибка.",
					body: "Не удалось обновить почту.",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
			}
		},
		/* Удаление */
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
		/* |                   ВАЛИДАЦИЯ                       |*/
		/* |___________________________________________________|*/
		/* Почта */
		validateMail(mail) {
			let mailRegexp =
				/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

			this.validator.email = mailRegexp.test(mail);
		},
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                  ОБЩИЕ МЕТОДЫ                     |*/
		/* |___________________________________________________|*/
		/* Поиск максимального id */
		getMaxIdFromArray(array) {
			try {
				// Поиск максимального id
				let maxId = 0;

				array.forEach((item) => {
					if (item.id > maxId) {
						maxId = item.id;
					}
				});

				return Number(maxId);
			} catch (error) {
				let debbugStory = {
					title: "Ошибка.",
					body: "Не удалось получить максимальный id.",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
			}
		},
		/* Поиск максимального order */
		getMaxOrderFromArray(array) {
			try {
				// Поиск максимального order
				let maxOrder = 0;

				array.forEach((item) => {
					if (item.order > maxOrder) {
						maxOrder = item.order;
					}
				});

				return Number(maxOrder);
			} catch (error) {
				let debbugStory = {
					title: "Ошибка.",
					body: "Не удалось получить максимальный order.",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
			}
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
