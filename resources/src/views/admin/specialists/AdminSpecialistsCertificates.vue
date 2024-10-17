<template>
	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                  МОДАЛЬНОЕ ОКНО                   |-->
	<!--|___________________________________________________|-->
	<admin-modal ref="modal" @touchCloseModal="closeModal" :modal="modal">
		<template #title>
			<span v-if="modal.type == 'create'">СЕРТИФИКАТ (СОЗДАНИЕ)</span>
			<span v-if="modal.type == 'edit'">СЕРТИФИКАТ (РЕДАКТИРОВАНИЕ)</span>
		</template>
		<template #body>
			<!-- Название -->
			<container-input-once :type="modal.type == 'create' ? 'create' : 'edit'">
				<template #title>
					<span :class="{ create: modal.type == 'create' }">НАЗВАНИЕ*</span>
					<span
						:class="{ create: modal.type == 'create' }"
						v-if="currentCertificate.data.name.edited"
					>
						(ИЗМЕНЕНО)
					</span>
				</template>
				<template #input>
					<input
						type="text"
						placeholder="Название должности"
						v-model="currentCertificate.data.name.body"
						:class="{ error: currentCertificate.errors.name.status }"
						@input="currentCertificate.data.name.edited = true"
						@blur="checkModalInput('name', 'text')"
					/>
				</template>
				<template #error>
					<span class="error" v-if="currentCertificate.errors.name.status">
						{{ currentCertificate.errors.name.body }}
					</span>
				</template>
			</container-input-once>
			<!-- Организация -->
			<container-input-once :type="modal.type == 'create' ? 'create' : 'edit'">
				<template #title>
					<span :class="{ create: modal.type == 'create' }">ОРГАНИЗАЦИЯ*</span>
					<span
						:class="{ create: modal.type == 'create' }"
						v-if="currentCertificate.data.organization.edited"
					>
						(ИЗМЕНЕНО)
					</span>
				</template>
				<template #input>
					<input
						type="text"
						placeholder="Название организации"
						v-model="currentCertificate.data.organization.body"
						:class="{ error: currentCertificate.errors.organization.status }"
						@input="currentCertificate.data.organization.edited = true"
						@blur="checkModalInput('organization', 'text')"
					/>
				</template>
				<template #error>
					<span class="error" v-if="currentCertificate.errors.organization.status">
						{{ currentCertificate.errors.organization.body }}
					</span>
				</template>
			</container-input-once>
			<!-- Дата окончания обучения -->
			<container-input-once :type="modal.type == 'create' ? 'create' : 'edit'">
				<template #title>
					<span :class="{ create: modal.type == 'create' }">ОКОНЧАНИЕ ОБУЧЕНИЯ*</span>
					<span
						:class="{ create: modal.type == 'create' }"
						v-if="currentCertificate.data.endEducation.edited"
					>
						(ИЗМЕНЕНО)
					</span>
				</template>
				<template #input>
					<input
						type="date"
						placeholder="Название организации"
						v-model="currentCertificate.data.endEducation.body"
						:class="{ error: currentCertificate.errors.endEducation.status }"
						@input="currentCertificate.data.endEducation.edited = true"
						@blur="checkModalInput('endEducation', 'text')"
					/>
				</template>
				<template #error>
					<span class="error" v-if="currentCertificate.errors.endEducation.status">
						{{ currentCertificate.errors.endEducation.body }}
					</span>
				</template>
			</container-input-once>
		</template>
		<template #footer>
			<BlockButtons>
				<button-claim @click="addCertificate" v-if="modal.type == 'create'">
					Создать
				</button-claim>
				<ButtonDefault @click="updateCertificate" v-if="modal.type == 'edit'">
					Обновить
				</ButtonDefault>
			</BlockButtons>
		</template>
	</admin-modal>
	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                    СЕРТИФИКАТЫ                    |-->
	<!--|___________________________________________________|-->
	<info-bar>
		<template v-slot:title>Специалисты</template>
		<template v-slot:addreas>specialists</template>
	</info-bar>

	<block-once>
		<block-title>
			<template #title>Сертификаты</template>
			<template #buttons>
				<icon-save :width="28" :height="28" @click="saveCertificatesChanges" />
			</template>
		</block-title>

		<admin-specialists-table
			v-show="loading.table"
			:array="certificates"
			@useFilter="filterCertificates"
			@touchRemoveArrValue="removeCertificate"
			@touchEditArrValue="editCertificate"
		/>

		<loader-child
			:isLoading="loading.loader"
			:minHeight="200"
			@loaderChildAfterLeave="loaderChildAfterLeave"
		/>

		<block-buttons>
			<button-default @click="createCertificate"> Добавить </button-default>
		</block-buttons>
	</block-once>
</template>

<script>
import AdminModal from "../../../components/includes/admin/AdminModal.vue";

import InfoBar from "../../../components/ui/admin/InfoBar.vue";

import LoaderChild from "../../../components/includes/LoaderChild.vue";

import ElementInputLabel from "../../../components/ui/admin/elements/ElementInputLabel.vue";
import BlockOnce from "../../../components/ui/admin/blocks/BlockOnce.vue";
import BlockTitle from "../../../components/ui/admin/blocks/BlockTitle.vue";
import BlockButtons from "../../../components/ui/admin/blocks/BlockButtons.vue";

import AdminSpecialistsTable from "./AdminSpecialistsTable.vue";

import ContainerInput from "../../../components/ui/admin/containers/ContainerInput.vue";
import ContainerInputOnce from "../../../components/ui/admin/containers/input/ContainerInputOnce.vue";
import ContainerInputTwo from "../../../components/ui/admin/containers/input/ContainerInputTwo.vue";
import ContainerInputThree from "../../../components/ui/admin/containers/input/ContainerInputThree.vue";
import ContainerTextareaOnce from "../../../components/ui/admin/containers/textarea/ContainerTextareaOnce.vue";

import ButtonDefault from "../../../components/ui/admin/buttons/ButtonDefault.vue";
import ButtonRemove from "../../../components/ui/admin/buttons/ButtonRemove.vue";
import ButtonClaim from "../../../components/ui/admin/buttons/ButtonClaim.vue";

import IconSave from "../../../components/icons/IconSave.vue";

import axios from "axios";

export default {
	components: {
		AdminModal,
		InfoBar,
		LoaderChild,
		ElementInputLabel,
		AdminSpecialistsTable,
		ContainerInput,
		ContainerInputOnce,
		ContainerInputTwo,
		ContainerInputThree,
		ContainerTextareaOnce,
		BlockOnce,
		BlockTitle,
		BlockButtons,
		ButtonDefault,
		ButtonRemove,
		ButtonClaim,
		IconSave,
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
						hide: false,
						close: true,
					},
					images: false,
					body: true,
					footer: true,
				},
			},
			loading: {
				loader: true,
				table: false,
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
			certificates: [],
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
		/* _____________________________________________________*/
		/* 2. Работа с полями ввода модального окна             */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		// Проверка введенного текстового значения
		checkInputText(value) {
			/* Проверка на пустую строку */
			if (value == "" || value == null) {
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
		// Проверка введенного текстового значения
		checkInputNumber(value) {
			/* Проверка на пустую строку */
			if (value == "" || value == null) {
				return {
					status: true,
					message: "Поле не может быть пустым.",
				};
			}

			/* Проверка на соответствие типу Number */
			if (!Number(value)) {
				console.log(value, typeof value);
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
		// Проверка поля имени
		checkModalInput(dataKey, inputType) {
			let errorLog = {};
			switch (inputType) {
				case "text":
					errorLog = this.checkInputText(this.currentCertificate.data[dataKey].body);
					break;
				case "number":
					errorLog = this.checkInputNumber(this.currentCertificate.data[dataKey].body);
					break;
				default:
					break;
			}

			if (errorLog.status) {
				this.currentCertificate.errors[dataKey].body = errorLog.message;
				this.currentCertificate.errors[dataKey].status = true;

				return true;
			} else {
				this.currentCertificate.errors[dataKey].body = "";
				this.currentCertificate.errors[dataKey].status = false;

				return false;
			}
		},
		// Проверка всех полей ввода модального окна
		checkModalInputsAll(inputKeys) {
			let errorCount = 0;
			for (let i = 0; i < inputKeys.length; i++) {
				switch (inputKeys[i]) {
					// Для индекса
					case "id_specialization":
						if (this.checkModalInput(inputKeys[i], "select")) {
							errorCount++;
						}
						break;
					// Для всех остальных полей
					default:
						if (this.checkModalInput(inputKeys[i], "text")) {
							errorCount++;
						}
						break;
				}
			}

			if (errorCount > 0) {
				return true;
			} else {
				return false;
			}
		},
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                  Сертификаты                      |*/
		/* |___________________________________________________|*/
		/* _____________________________________________________*/
		/* 1. Фильтрация                                        */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		/* Фильтрация по столбцу */
		filterCertificates(column, type) {
			// Объявляем объект Intl.Collator, который обеспечивает сравнение строк с учётом языка.
			const collator = new Intl.Collator("ru");

			switch (column) {
				case "id":
					if (type == "default") {
						this.certificates.sort((a, b) => {
							if (a.id > b.id) {
								return 1;
							}
							if (a.id < b.id) {
								return -1;
							}
							// a должно быть равным b
							return 0;
						});
					}

					if (type == "reverse") {
						this.certificates.sort((a, b) => {
							if (a.id < b.id) {
								return 1;
							}
							if (a.id > b.id) {
								return -1;
							}
							// a должно быть равным b
							return 0;
						});
					}
					break;
				case "name":
					if (type == "default") {
						this.certificates.sort((a, b) => {
							return collator.compare(a.name, b.name);
						});
					}

					if (type == "reverse") {
						this.certificates.reverse((a, b) => {
							return collator.compare(a.name, b.name);
						});
					}

					break;
				default:
					break;
			}
		},
		/* _____________________________________________________*/
		/* 2. Основные действия                                 */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		/* Открытие специализации для редактирования */
		editCertificate(selectedCertificate) {
			this.clearModalData();

			for (let key in this.currentCertificate.data) {
				this.currentCertificate.data[key].body = selectedCertificate[key];
			}

			this.openModal("edit");
		},
		/* Открытие специализации для создания */
		createCertificate() {
			this.openModal("create");
		},
		/* _____________________________________________________*/
		/* 3. Сохранение, обновление и удаление                 */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		/* Добавление работы */
		addCertificate() {
			if (this.checkModalInputsAll(["name", "organization", "endEducation"])) return;

			try {
				// Поиск максимального id
				let maxId = 0;
				for (let key in this.certificates) {
					if (this.certificates[key].id > maxId) {
						maxId = this.certificates[key].id;
					}
				}

				this.certificates.push({
					id: maxId + 1,
					name: this.currentCertificate.data.name.body,
					organization: this.currentCertificate.data.organization.body,
					endEducation: this.currentCertificate.data.endEducation.body,
					create: true,
					delete: false,
				});

				this.closeModal();
			} catch (error) {
				let debbugStory = {
					title: "Ошибка.",
					body: "При добавлении что-то пошло не так.",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
			}
		},
		/* Обновление данных */
		updateCertificate() {
			if (this.checkModalInputsAll(["name", "organization", "endEducation"])) return;

			try {
				let сertificateToUpdate = this.certificates.filter((сertificate) => {
					if (сertificate.id === this.currentCertificate.data.id.body) {
						return сertificate;
					}
				});

				for (let key in this.currentCertificate.data) {
					сertificateToUpdate[0][key] = this.currentCertificate.data[key].body;
				}

				this.closeModal();
			} catch (error) {
				let debbugStory = {
					title: "Ошибка.",
					body: "При обновлении что-то пошло не так.",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
			}
		},
		/* Пометка на удаление */
		removeCertificate(id) {
			let certificateToDelete = this.certificates.filter((certificate) => {
				if (certificate.id === id) {
					return certificate;
				}
			});

			certificateToDelete[0].delete = !certificateToDelete[0].delete;
		},
		saveCertificatesChanges() {
			let newArray = [];

			for (let key in this.certificates) {
				newArray.push(Object.assign({}, this.certificates[key]));
			}

			newArray.sort((a, b) => {
				if (a.id > b.id) {
					return 1;
				} else if (a.id < b.id) {
					return -1;
				} else {
					return 0;
				}
			});

			axios({
				method: "post",
				url: `${this.$store.state.axios.urlApi}` + `save-certificates-changes`,
				headers: {
					Accept: "application/json",
					Authorization: `Bearer ${localStorage.getItem("token")}`,
				},
				data: {
					certificates: newArray,
				},
			})
				.then((response) => {
					try {
						// Обновление id добавленных элементов на данные из бд
						for (let key in response.data) {
							let сertificate = this.certificates.filter((item) => {
								if (item.id === response.data[key].old) {
									return item;
								}
							});
							сertificate[0].id = response.data[key].new;
						}

						// Получения нового массива, с данными помеченными на удаление
						let res = this.certificates.filter((item) => {
							if (item.delete == true) {
								return Object.assign({}, item);
							}
						});

						// Повторять, пока не будут удалены все элементы, помеченные на удаление
						while (res.length > 0) {
							/* Получение индекса элемента, помеченного на удаление из массива специалистов */
							this.certificates.splice(this.certificates.indexOf(res[0]), 1);
							/* Обновление списка с элементами, помеченными на удаление */
							res = this.certificates.filter((item) => {
								if (item.delete == true) {
									return Object.assign({}, item);
								}
							});
						}

						// Сброс флагов добавления и удаления
						for (let key in this.certificates) {
							this.certificates[key].create = false;
							this.certificates[key].delete = false;
						}

						let debbugStory = {
							title: "Успешно!",
							body: "Данные сохранились.",
							type: "Completed",
						};
						this.$store.commit("debuggerState", debbugStory);
					} catch (error) {
						let debbugStory = {
							title: "Ошибка.",
							body: "После сохранения что-то пошло не так.",
							type: "Error",
						};
						this.$store.commit("debuggerState", debbugStory);
					}
				})
				.catch((error) => {
					let debbugStory = {
						title: "Ошибка.",
						body: "Данные почему-то не сохранились.",
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
			url: `${this.$store.state.axios.urlApi}` + `get-certificates-all`,
		})
			.then((response) => {
				this.certificates = response.data;

				for (let key in this.certificates) {
					this.certificates[key].create = false;
					this.certificates[key].delete = false;
				}

				this.loading.loader = false;
			})
			.catch((error) => {
				console.log(error);
			});
	},
};
</script>

<style scoped></style>
