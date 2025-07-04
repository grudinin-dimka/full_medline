<template>
	<!-- Модальное окно: Контакты -->
	<Modal ref="modal" :settings="modal">
		<template #title>
			<template v-if="modal.values.look == 'default' && !currentContact.data.delete.value">
				<VueIcon
					:name="'arrow'"
					:fill="'var(--icon-multi-fill)'"
					:hover="'var(--icon-nulti-fill-hover)'"
					:width="'16px'"
					:height="'16px'"
					:rotate="-90"
					:cursor="'pointer'"
					@click="changeContactsOrder('down')"
				/>
				#{{ currentContact.data.order.value }}
				<VueIcon
					:name="'arrow'"
					:fill="'var(--icon-multi-fill)'"
					:hover="'var(--icon-nulti-fill-hover)'"
					:width="'16px'"
					:height="'16px'"
					:rotate="90"
					:cursor="'pointer'"
					@click="changeContactsOrder('up')"
				/>
			</template>

			<template v-else>
				{{ modal.values.title }}
			</template>
		</template>

		<template #body>
			<VueInput
				v-model="currentContact.data.name.value"
				:type="'textarea'"
				:placeholder="'Введите заголовок'"
				:error="currentContact.errors.name.status"
			>
				<template #label> ЗАГОЛОВОК </template>
				<template #error>
					{{ currentContact.errors.name.message }}
				</template>
			</VueInput>

			<VueInput
				v-model="currentContact.data.clinicId.value"
				:options="filteredClinics"
				:type="'select'"
				:error="currentContact.errors.clinicId.status"
			>
				<template #label> КЛИНИКА </template>
				<template #error>
					{{ currentContact.errors.clinicId.message }}
				</template>
			</VueInput>

			<ModalList
				:array="currentContact.data.phones.value"
				@touchCreate="openModalPhoneCreate"
				@touchEdit="openModalPhoneEdite"
				@touchDelete="deleteContactPhone"
			>
				<template #title>
					<VueIcon
						:name="'phone'"
						:fill="'var(--primary-color)'"
						:width="'20px'"
						:height="'16px'"
					/>
					ТЕЛЕФОНЫ
				</template>
			</ModalList>

			<ModalList
				:array="currentContact.data.mails.value"
				@touchCreate="openModalMailCreate"
				@touchEdit="openModalMailEdite"
				@touchDelete="deleteContactMail"
			>
				<template #title>
					<VueIcon
						:name="'mail'"
						:fill="'var(--primary-color)'"
						:width="'20px'"
						:height="'14px'"
					/>
					ПОЧТА
				</template>
			</ModalList>
		</template>
		<template #footer>
			<template v-if="modal.values.look == 'create'">
				<button-default @click="addContact">
					<VueIcon :name="'add'" :fill="'white'" :width="'23px'" :height="'23px'" />
					Добавить
				</button-default>
			</template>

			<template v-if="modal.values.look == 'default' && !currentContact.data.delete.value">
				<button-remove v-if="!currentContact.data.create.value" @click="deleteContact">
					<VueIcon :name="'delete'" :fill="'white'" :width="'24px'" :height="'22px'" />
					Удалить
				</button-remove>
				<ButtonDefault @click="updateContact">
					<VueIcon :name="'edit'" :fill="'white'" :width="'28px'" :height="'28px'" />
					Обновить
				</ButtonDefault>
			</template>

			<template v-if="modal.values.look == 'default' && currentContact.data.delete.value">
				<ButtonDefault @click="deleteContact">
					<VueIcon :name="'restore'" :fill="'white'" :width="'28px'" :height="'28px'" />
					Вернуть
				</ButtonDefault>
			</template>
		</template>
	</Modal>

	<!-- Модальное окно: Контакты -> Телефон -->
	<Modal ref="modalPhone" :settings="modalPhone">
		<template #title>
			{{ modalPhone.values.title }}
		</template>

		<template #body>
			<VueInput
				v-model="currentPhone.data.name.value"
				:type="'phone'"
				:placeholder="'+7(___)-___-__-__'"
				:error="currentPhone.errors.name.status"
			>
				<template #label> НОМЕР ТЕЛЕФОНА </template>
				<template #error>
					{{ currentPhone.errors.name.message }}
				</template>
			</VueInput>
		</template>

		<template #footer>
			<button-default v-if="modalPhone.values.look == 'default'" @click="updateContactPhone">
				<VueIcon :name="'edit'" :fill="'white'" :width="'28px'" :height="'28px'" />
				Обновить
			</button-default>
			<button-default v-if="modalPhone.values.look == 'create'" @click="addContactPhone">
				<VueIcon :name="'add'" :fill="'white'" :width="'23px'" :height="'23px'" />
				Добавить
			</button-default>
		</template>
	</Modal>

	<!-- Модальное окно: Контакты -> Почта -->
	<Modal ref="modalMail" :settings="modalMail">
		<template #title>
			{{ modalMail.values.title }}
		</template>

		<template #body>
			<VueInput
				v-model="currentMail.data.name.value"
				:type="'email'"
				:placeholder="'test@mail.ru'"
				:error="currentMail.errors.name.status"
			>
				<template #label> ПОЧТОВЫЙ АДРЕС </template>
				<template #error>
					{{ currentMail.errors.name.message }}
				</template>
			</VueInput>
		</template>
		<template #footer>
			<button-default v-if="modalMail.values.look == 'default'" @click="updateContactMail">
				<VueIcon :name="'edit'" :fill="'white'" :width="'28px'" :height="'28px'" />
				Обновить
			</button-default>

			<button-default v-if="modalMail.values.look == 'create'" @click="addContactMail">
				<VueIcon :name="'add'" :fill="'white'" :width="'23px'" :height="'23px'" />
				Добавить
			</button-default>
		</template>
	</Modal>

	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                     КОНТАКТЫ                      |-->
	<!--|___________________________________________________|-->
	<info-bar>
		<template v-slot:title>Контакты</template>
		<template v-slot:addreas>contacts</template>
	</info-bar>

	<block-once :minHeight="300">
		<template #title>КОНТАКТЫ</template>

		<template #options>
			<button-default
				@click.prevent="saveContact"
				:disabled="disabled.contacts.save"
				:look="'white'"
			>
				<VueIcon :name="'save'" :fill="'var(--primary-color)'" :width="'28px'" :height="'28px'" />
				Сохранить
			</button-default>
		</template>

		<template #body>
			<!-- Список элементов -->
			<template v-if="loading.sections.clinics">
				<AdminContactsList
					v-if="contacts.length > 0"
					:contacts="contacts"
					:clinics="clinics"
					@touchEditContact="openModalEdite"
				/>

				<Empty :minHeight="300" v-else />
			</template>

			<!-- Загрузка элементов -->
			<loader-child
				:isLoading="loading.loader.clinics"
				:minHeight="300"
				@loaderChildAfterLeave="loaderChildAfterLeave"
			/>
		</template>

		<template #buttons>
			<ButtonDefault @click="openModalСreate">
				<VueIcon :name="'add'" :fill="'white'" :width="'23px'" :height="'23px'" />
				Добавить
			</ButtonDefault>
		</template>
	</block-once>
</template>

<script>
import Modal from "../../../components/modules/modal/VueModal.vue";
import ModalList from "../../../components/modules/modal/VueModalList.vue";
import VueInput from "../../../components/modules/input/VueInput.vue";

import AdminContactsList from "./AdminContactsList.vue";

import InfoBar from "../../../components/ui/admin/InfoBar.vue";

import LoaderChild from "../../../components/modules/LoaderChild.vue";
import Empty from "../../../components/modules/Empty.vue";

import BlockOnce from "../../../components/ui/admin/blocks/BlockOnce.vue";

import ButtonDefault from "../../../components/ui/admin/buttons/ButtonDefault.vue";
import ButtonRemove from "../../../components/ui/admin/buttons/ButtonRemove.vue";
import ButtonClaim from "../../../components/ui/admin/buttons/ButtonClaim.vue";

import VueIcon from "../../../components/modules/icon/VueIcon.vue";

import api from "../../../services/api";
import shared from "../../../services/shared.js";
import validate from "../../../services/validate.js";
import sorted from "../../../services/sorted.js";

export default {
	components: {
		Modal,
		ModalList,
		VueInput,

		AdminContactsList,

		InfoBar,
		LoaderChild,
		Empty,

		BlockOnce,

		ButtonDefault,
		ButtonRemove,
		ButtonClaim,

		VueIcon,
	},
	data() {
		return {
			/* Кнопки */
			disabled: {
				contacts: {
					save: false,
				},
			},

			/* Загрузчик */
			loading: {
				loader: {
					clinics: true,
				},
				sections: {
					clinics: false,
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

			modalPhone: {
				thin: true,
				clamped: false,
				values: {
					title: "",
					look: "default",
				},
			},

			modalMail: {
				thin: true,
				clamped: false,
				values: {
					title: "",
					look: "default",
				},
			},

			/* Форма */
			currentContact: {
				errors: {
					id: {
						message: null,
						status: false,
					},
					order: {
						message: null,
						status: false,
					},
					name: {
						message: null,
						status: false,
					},
					clinicId: {
						message: null,
						status: false,
					},
					mails: {
						message: null,
						status: false,
					},
					phones: {
						message: null,
						status: false,
					},
					create: {
						message: null,
						status: false,
					},
					delete: {
						message: null,
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
					name: {
						value: "",
						edited: false,
					},
					clinicId: {
						value: "",
						edited: false,
					},
					mails: {
						value: [],
						edited: false,
					},
					phones: {
						value: [],
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

			currentPhone: {
				errors: {
					id: {
						message: "",
						status: false,
					},
					name: {
						message: "",
						status: false,
					},
				},
				data: {
					id: {
						value: null,
						edited: false,
					},
					name: {
						value: null,
						edited: false,
					},
				},
			},

			currentMail: {
				errors: {
					id: {
						message: "",
						status: false,
					},
					name: {
						message: "",
						status: false,
					},
				},
				data: {
					id: {
						value: null,
						edited: false,
					},
					name: {
						value: null,
						edited: false,
					},
				},
			},

			/* Данные */
			contacts: [],
			clinics: [
				{
					id: 0,
					name: "Нулевой элемент",
				},
			],
		};
	},
	computed: {
		filteredClinics() {
			let array = this.clinics.map((value, index) => {
				return {
					default: false,
					disabled: false,
					value: value.id,
					label: value.name,
				};
			});

			array.push({
				default: true,
				disabled: false,
				value: "",
				label: "Отсутствует",
			});

			sorted.sortStringByKey("down", array, "default");

			return array;
		},
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
		/* Открытие модального окна */
		openModal(name, title, look) {
			this[name].values.title = title;
			this[name].values.look = look;

			this.$refs[name].open();
		},

		/* Открытие модального окна для добавления */
		openModalСreate() {
			shared.clearObjectFull(this.currentContact);

			this.openModal("modal", "КОНТАКТ", "create");
		},

		/* Открытие модального окна для редактирования */
		openModalEdite(value) {
			shared.clearObjectFull(this.currentContact);
			shared.setData(value, this.currentContact);

			this.openModal("modal", "КОНТАКТ", "default");
		},

		/* Телефон */
		openModalPhoneCreate() {
			shared.clearObjectFull(this.currentPhone);

			this.openModal("modalPhone", "ТЕЛЕФОН", "create");
		},

		openModalPhoneEdite(value) {
			shared.clearObjectFull(this.currentPhone);
			shared.setData(value, this.currentPhone);

			this.openModal("modalPhone", "ТЕЛЕФОН", "default");
		},

		/* Почта */
		openModalMailCreate() {
			shared.clearObjectFull(this.currentMail);

			this.openModal("modalMail", "ПОЧТА", "create");
		},

		openModalMailEdite(value) {
			shared.clearObjectFull(this.currentMail);
			shared.setData(value, this.currentMail);

			this.openModal("modalMail", "ПОЧТА", "default");
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                    КОНТАКТЫ                       |*/
		/* |___________________________________________________|*/
		/* Добавление */
		addContact() {
			try {
				if (
					validate.checkInputsAll(this.currentContact, [
						{
							key: "name",
							type: "text",
						},
					])
				)
					return;

				this.contacts.push({
					id: shared.getMaxId(this.contacts) + 1,
					order: shared.getMaxOrder(this.contacts) + 1,
					name: this.currentContact.data.name.value,
					clinicId: this.currentContact.data.clinicId.value,
					phones: [...this.currentContact.data.phones.value],
					mails: [...this.currentContact.data.mails.value],
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
		updateContact() {
			if (
				validate.checkInputsAll(this.currentContact, [
					{
						key: "name",
						type: "text",
					},
				])
			)
				return;

			let contact = this.contacts.find(
				(contact) => contact.id === this.currentContact.data.id.value
			);

			for (let key in this.currentContact.data) {
				contact[key] = this.currentContact.data[key].value;
			}

			this.$refs.modal.close();
		},

		/* Удаление */
		deleteContact() {
			try {
				let contact = this.contacts.find(
					(item) => item.id == this.currentContact.data.id.value
				);

				contact.delete = !contact.delete;

				this.$refs.modal.close();
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			}
		},

		/* Сохранение */
		saveContact() {
			this.disabled.contacts.save = true;

			let formData = new FormData();
			formData.append("contacts", JSON.stringify(this.contacts));

			api({
				method: "post",
				url: this.$store.getters.urlApi + `save-contacts-changes`,
				headers: {
					ContentType: "multipart/form-data",
				},
				data: formData,
			})
				.then((response) => {
					if (!response) return;

					shared.updateId(this.contacts, response.data.result);
					shared.clearDeletes(this.contacts);
					shared.clearFlags(this.contacts);
					shared.updateOrders(this.contacts);
				})
				.catch((error) => {
					this.$store.commit("addDebugger", {
						title: "Ошибка.",
						body: error,
						type: "error",
					});
				})
				.finally(() => {
					this.disabled.contacts.save = false;
				});
		},

		/* Изменение порядка */
		changeContactsOrder(type) {
			try {
				shared.changeOrder(this.contacts, this.currentContact, type);
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка",
					body: error,
					type: "error",
				});
			}
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                     ТЕЛЕФОН                       |*/
		/* |___________________________________________________|*/
		/* Добавление */
		addContactPhone() {
			// Поиск максимального id
			try {
				if (
					validate.checkInputsAll(this.currentPhone, [
						{
							key: "name",
							type: "phone",
						},
					])
				)
					return;

				this.currentContact.data.phones.value.push({
					id: shared.getMaxId(this.currentContact.data.phones.value) + 1,
					name: this.currentPhone.data.name.value,
				});

				this.$refs.modalPhone.close();
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			}
		},

		/* Обновление */
		updateContactPhone() {
			try {
				if (
					validate.checkInputsAll(this.currentPhone, [
						{
							key: "name",
							type: "phone",
						},
					])
				)
					return;

				let phone = this.currentContact.data.phones.value.find((item) => {
					return item.id == this.currentPhone.data.id.value;
				});

				for (let key in this.currentPhone.data) {
					phone[key] = this.currentPhone.data[key].body;
				}

				this.$refs.modalPhone.close();
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			}
		},

		/* Удаление */
		deleteContactPhone(selectedPhone) {
			try {
				this.currentContact.data.phones.value = this.currentContact.data.phones.value.filter(
					(phone) => {
						if (selectedPhone.id !== phone.id) {
							return phone;
						}
					}
				);
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			}
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                      ПОЧТА                        |*/
		/* |___________________________________________________|*/
		/* Добавление */
		addContactMail() {
			// Поиск максимального id
			try {
				if (
					validate.checkInputsAll(this.currentMail, [
						{
							key: "name",
							type: "email",
						},
					])
				)
					return;

				this.currentContact.data.mails.value.push({
					id: shared.getMaxId(this.currentContact.data.mails.value) + 1,
					name: this.currentMail.data.name.value,
				});

				this.$refs.modalMail.close();
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			}
		},

		/* Обновление */
		updateContactMail() {
			try {
				if (
					validate.checkInputsAll(this.currentMail, [
						{
							key: "name",
							type: "email",
						},
					])
				)
					return;

				let mail = this.currentContact.data.mails.value.find((item) => {
					return item.id == this.currentMail.data.id.value;
				});

				mail.name = this.currentMail.data.name.value;

				this.$refs.modalMail.close();
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			}
		},

		/* Удаление */
		deleteContactMail(selectedMail) {
			this.currentContact.data.mails.value = this.currentContact.data.mails.value.filter(
				(mail) => {
					if (selectedMail.id !== mail.id) {
						return mail;
					}
				}
			);
		},
	},
	mounted() {
		api({
			method: "get",
			url: `${this.$store.getters.urlApi}` + `get-contacts-all`,
		})
			.then((response) => {
				if (!response) return;

				this.contacts = response.data.result.contacts;

				this.contacts.forEach((contact) => {
					contact.create = false;
					contact.delete = false;
				});
				sorted.sortByOrder("up", this.contacts);

				this.clinics = response.data.result.clinics;
			})
			.catch((error) => {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			})
			.finally(() => {
				this.loading.loader.clinics = false;
			});
	},
};
</script>

<style scoped></style>
