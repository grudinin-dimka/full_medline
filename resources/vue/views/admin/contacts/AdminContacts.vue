<template>
	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                  МОДАЛЬНОЕ ОКНО                   |-->
	<!--|___________________________________________________|-->
	<admin-modal ref="modal" @touchCloseModal="closeModal('modal')" :modal="modal">
		<template #title v-if="!currentContact.data.delete.body && !modal.style.create">
			<icon-arrow :width="16" :height="16" :rotate="-90" @click="changeContactsOrder('down')" />
			#{{ currentContact.data.order.body }}
			<icon-arrow :width="16" :height="16" :rotate="90" @click="changeContactsOrder('up')" />
		</template>
		<template #title v-else>
			<span v-if="modal.type == 'create'">КОНТАКТ (СОЗДАНИЕ)</span>
			<span v-if="modal.type == 'edit'">КОНТАКТ (РЕДАКТИРОВАНИЕ)</span>
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
				<ContainerInputOnce
					:type="modal.type == 'create' ? 'create' : modal.style.delete ? 'delete' : 'edit'"
				>
					<template #title>
						<span>КЛИНИКА</span>
					</template>
					<template #input>
						<select v-model="currentContact.data.clinicId.body">
							<option value="null" selected>Ничего не выбрано</option>
							<option v-for="clinic in clinics" :key="clinic.id" :value="clinic.id">
								{{ clinic.name }}
							</option>
						</select>
					</template>
				</ContainerInputOnce>
				<admin-modal-list
					:type="modal.type == 'create' ? 'create' : modal.style.delete ? 'delete' : 'edit'"
					:array="currentContact.data.phones.body"
					@touchCreate="createContactPhone"
					@touchEdit="editContactPhone"
					@touchDelete="deleteContactPhone"
				>
					<template #title>
						<IconContactPhone
							:width="16"
							:height="14"
							:type="modal.type == 'create' ? 'create' : 'edit'"
						/>
						ТЕЛЕФОНЫ
					</template>
				</admin-modal-list>
				<admin-modal-list
					:type="modal.type == 'create' ? 'create' : modal.style.delete ? 'delete' : 'edit'"
					:array="currentContact.data.mails.body"
					@touchCreate="createContactMail"
					@touchEdit="editContactMail"
					@touchDelete="deleteContactMail"
				>
					<template #title>
						<IconContactMail
							:width="20"
							:height="14"
							:type="modal.type == 'create' ? 'create' : 'edit'"
						/>
						ПОЧТА
					</template>
				</admin-modal-list>
			</ContainerInput>
		</template>
		<template #footer>
			<BlockButtons>
				<button-claim v-if="modal.type == 'create'" @click="addContact"> Создать </button-claim>
				<template v-if="modal.type == 'edit' && !currentContact.data.delete.body">
					<button-remove @click="deleteContact"> Удалить </button-remove>
					<ButtonDefault @click="updateContact"> Обновить </ButtonDefault>
				</template>
				<ButtonDefault
					v-if="modal.type == 'edit' && currentContact.data.delete.body"
					@click="deleteContact"
				>
					Восстановить
				</ButtonDefault>
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
					Создать
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
					Создать
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
	<block-once :minHeight="300">
		<block-title>
			<template #title>КОНТАКТЫ</template>
			<template #buttons>
				<icon-load :width="28" :height="28" v-if="disabled.contacts.save" />
				<icon-save :width="28" :height="28" @click="saveContact" v-else />
			</template>
		</block-title>

		<!-- Список элементов -->
		<template v-if="loading.sections.clinics">
			<AdminContactsList
				v-if="contacts.length > 0"
				:contacts="contacts"
				:clinics="clinics"
				@touchEditContact="editContact"
			/>

			<Empty :minHeight="300" v-else />
		</template>

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
import IconEdit from "../../../components/icons/IconEdit.vue";
import IconRemove from "../../../components/icons/IconRemove.vue";
import IconCreate from "../../../components/icons/IconCreate.vue";
import IconSave from "../../../components/icons/IconSave.vue";
import IconLoad from "../../../components/icons/IconLoad.vue";
import IconContactHome from "../../../components/icons/contacts/IconContactHome.vue";
import IconContactMail from "../../../components/icons/contacts/IconContactMail.vue";
import IconContactPhone from "../../../components/icons/contacts/IconContactPhone.vue";

import axios from "axios";
import shared from "../../../services/shared.js";
import validate from "../../../services/validate.js";
import sorted from "../../../services/sorted.js";

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
		IconLoad,
		IconContactHome,
		IconContactMail,
		IconContactPhone,
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
			disabled: {
				contacts: {
					save: false,
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
					order: {
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
					order: {
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
			if (name === "modal") {
				this.clearModalErrors("currentContact");
				this.clearModalEdited("currentContact");
			}

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

						if (name === "modal" && this.currentContact.data.delete.body) {
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
		// Проверка поля ввода
		checkModalInput(currentName, dataKey, inputType) {
			let errorLog = {};
			switch (inputType) {
				case "text":
					errorLog = validate.checkInputText(this[currentName].data[dataKey].body);
					break;
				case "email":
					errorLog = validate.checkInputEmail(this[currentName].data[dataKey].body);
					break;
				case "phone":
					errorLog = validate.checkInputPhone(this[currentName].data[dataKey].body);
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
				if (key === "phones" || key === "mails") {
					this.currentContact.data[key].body = Array.from(selectedContact[key]);
					continue;
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
		/* Удаление */
		deleteContact() {
			try {
				let contact = this.contacts.find((item) => item.id == this.currentContact.data.id.body);

				contact.delete = !contact.delete;

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
		/* Сохранение */
		saveContact() {
			this.disabled.contacts.save = true;

			let formData = new FormData();
			formData.append("contacts", JSON.stringify(this.contacts));

			axios({
				method: "post",
				url: `${this.$store.state.axios.urlApi}` + `save-contacts-changes`,
				headers: {
					ContentType: "multipart/form-data",
					Authorization: `Bearer ${localStorage.getItem("token")}`,
				},
				data: formData,
			})
				.then((response) => {
					if (response.data.status) {
						try {
							this.disabled.contacts.save = false;

							shared.updateId(this.contacts, response.data.data);
							shared.clearDeletes(this.contacts);
							shared.clearFlags(this.contacts);
							shared.updateOrders(this.contacts);

							let debbugStory = {
								title: "Успешно!",
								body: response.data.message,
								type: "Completed",
							};
							this.$store.commit("debuggerState", debbugStory);
						} catch (error) {
							this.disabled.contacts.save = false;

							let debbugStory = {
								title: "Ошибка.",
								body: "Не удалось обновить данные после загрузки изображения.",
								type: "Error",
							};
							this.$store.commit("debuggerState", debbugStory);
						}
					} else {
						this.disabled.contacts.save = false;

						let debbugStory = {
							title: "Ошибка.",
							body: response.data.message,
							type: "Error",
						};
						this.$store.commit("debuggerState", debbugStory);
					}
				})
				.catch((error) => {
					this.disabled.contacts.save = false;

					let debbugStory = {
						title: "Ошибка.",
						body: "Не удалось сохранить данные.",
						type: "Error",
					};
					this.$store.commit("debuggerState", debbugStory);
				});
		},
		/* Изменение порядка */
		changeContactsOrder(type) {
			if (this.contacts.length <= 1) {
				return;
			}

			// Является ли текущий элемент первым
			let firstElementStatus = this.currentContact.data.order.body == 1;
			// Предидущей элемент
			let elementPrevious = null;
			if (firstElementStatus) {
				elementPrevious = this.contacts.find((item) => item.order === this.contacts.length);
			} else {
				elementPrevious = this.contacts.find(
					(item) => item.order === this.currentContact.data.order.body - 1
				);
			}

			// Текущий элемент
			let elementCurrent = this.contacts.find(
				(item) => item.order === this.currentContact.data.order.body
			);

			// Является ли текущий элемент последним
			let lastElementStatus = this.currentContact.data.order.body == this.contacts.length;

			// Следующий элемент
			let elementNext = null;
			if (lastElementStatus) {
				elementNext = this.contacts.find((item) => item.order === 1);
			} else {
				elementNext = this.contacts.find(
					(item) => item.order === this.currentContact.data.order.body + 1
				);
			}

			// Изменение порядка
			switch (type) {
				case "up":
					{
						if (lastElementStatus) {
							this.currentContact.data.order.body = 1;
							elementCurrent.order = 1;
							elementNext.order = this.contacts.length;
						} else {
							this.currentContact.data.order.body++;
							elementCurrent.order++;
							elementNext.order--;
						}

						sorted.sortByOrder("up", this.contacts);
					}
					break;
				case "down":
					{
						if (firstElementStatus) {
							this.currentContact.data.order.body = this.contacts.length;
							elementCurrent.order = this.contacts.length;
							elementPrevious.order = 1;
						} else {
							this.currentContact.data.order.body--;
							elementCurrent.order--;
							elementPrevious.order++;
						}

						sorted.sortByOrder("up", this.contacts);
					}
					break;
			}
		},
		/* Соритровка */
		sortContacts(type) {
			switch (type) {
				case "id":
					this.contacts.sort((a, b) => a.id - b.id);
					break;
				case "order":
					this.contacts.sort((a, b) => a.order - b.order);
					break;
				default:
					break;
			}
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

				this.currentContact.data.phones.body.push({
					id: shared.getMaxId(this.currentContact.data.phones.body) + 1,
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

				this.currentContact.data.mails.body.push({
					id: shared.getMaxId(this.currentContact.data.mails.body) + 1,
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
					sorted.sortByOrder("up", this.contacts);

					this.clinics = response.data.data.clinics;
				} else {
					let debbugStory = {
						title: "Ошибка.",
						body: "Не удалось получить данные.",
						type: "Error",
					};
					this.$store.commit("debuggerState", debbugStory);
				}
			})
			.catch((error) => {})
			.finally(() => {});
	},
};
</script>

<style scoped></style>
