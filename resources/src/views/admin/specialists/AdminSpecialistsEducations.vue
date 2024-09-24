<template>
	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                  МОДАЛЬНОЕ ОКНО                   |-->
	<!--|___________________________________________________|-->
	<admin-modal ref="modal" @touchCloseModal="closeModal" :modal="modal">
		<template #title>
			<span v-if="modal.type == 'create'">ОБРАЗОВАНИЕ (СОЗДАНИЕ)</span>
			<span v-if="modal.type == 'edit'">ОБРАЗОВАНИЕ (РЕДАКТИРОВАНИЕ)</span>
		</template>
		<template #body>
			<container-input>
				<!-- Название -->
				<container-input-once>
					<template #title>
						<span :class="{ create: modal.type == 'create' }">НАЗВАНИЕ*</span>
						<span
							:class="{ create: modal.type == 'create' }"
							v-if="currentEducation.data.name.edited"
						>
							(ИЗМЕНЕНО)
						</span>
					</template>
					<template #input>
						<input
							type="text"
							placeholder="Введите название"
							autocomplete="off"
							:class="{ error: currentEducation.errors.name.status }"
							v-model="currentEducation.data.name.body"
							@blur="checkModalInput('name', 'text')"
							@input="currentEducation.data.name.edited = true"
						/>
					</template>
					<template #error>
						<span class="error" v-if="currentEducation.errors.name.status">
							{{ currentEducation.errors.name.body }}
						</span>
					</template>
				</container-input-once>
				<!-- Организация -->
				<container-textarea-once>
					<template #title>
						<span :class="{ create: modal.type == 'create' }">ОРГАНИЗАЦИЯ*</span>
						<span
							:class="{ create: modal.type == 'create' }"
							v-if="currentEducation.data.organization.edited"
						>
							(ИЗМЕНЕНО)
						</span>
					</template>
					<template #textarea>
						<textarea
							rows="4"
							placeholder="Введите организацию"
							autocomplete="off"
							:class="{ error: currentEducation.errors.organization.status }"
							v-model="currentEducation.data.organization.body"
							@blur="checkModalInput('organization', 'text')"
							@input="currentEducation.data.organization.edited = true"
						></textarea>
					</template>
					<template #error>
						<span class="error" v-if="currentEducation.errors.organization.status">
							{{ currentEducation.errors.organization.body }}
						</span>
					</template>
				</container-textarea-once>
				<!-- Дата получения образования -->
				<container-input-once>
					<template #title>
						<span :class="{ create: modal.type == 'create' }">ДАТА ПОЛУЧЕНИЯ*</span>
						<span
							:class="{ create: modal.type == 'create' }"
							v-if="currentEducation.data.date.edited"
						>
							(ИЗМЕНЕНО)</span
						>
					</template>
					<template #input>
						<input
							type="date"
							placeholder="Введите организацию"
							autocomplete="off"
							:class="{ error: currentEducation.errors.date.status }"
							v-model="currentEducation.data.date.body"
							@blur="checkModalInput('date', 'text')"
							@input="currentEducation.data.date.edited = true"
						/>
					</template>
					<template #error>
						<span class="error" v-if="currentEducation.errors.date.status">
							{{ currentEducation.errors.date.body }}
						</span>
					</template>
				</container-input-once>
				<!-- Выбор специализации -->
				<container-select-once>
					<template #title>
						<span :class="{ create: modal.type == 'create' }">СПЕЦИАЛИЗАЦИЯ*</span>
						<span
							:class="{ create: modal.type == 'create' }"
							v-if="currentEducation.data.id_specialization.edited"
						>
							(ИЗМЕНЕНО)</span
						>
					</template>
					<template #select>
						<select
							v-model="currentEducation.data.id_specialization.body"
							:class="{ error: currentEducation.errors.id_specialization.status }"
							@blur="checkModalInput('id_specialization', 'select')"
						>
							<option value="">Ничего не выбрано...</option>
							<option :value="specialization.id" v-for="specialization in specializations">
								{{ specialization.name }}
							</option>
						</select>
					</template>
					<template #error>
						<span class="error" v-if="currentEducation.errors.id_specialization.status">
							{{ currentEducation.errors.id_specialization.body }}
						</span>
					</template>
				</container-select-once>
			</container-input>
		</template>
		<template #footer>
			<BlockButtons>
				<ButtonDefault v-if="modal.type == 'create'" @click="addEducation">
					Создать
				</ButtonDefault>
				<ButtonDefault v-if="modal.type == 'edit'" @click="updateEducation">
					Обновить
				</ButtonDefault>
			</BlockButtons>
		</template>
	</admin-modal>

	<info-bar>
		<template v-slot:title>Специалисты</template>
		<template v-slot:addreas>specialists</template>
	</info-bar>

	<block-once>
		<block-title>
			<template #title>Образование</template>
			<template #buttons>
				<icon-save :width="28" :height="28" @click="saveEducationsChanges" />
			</template>
		</block-title>

		<!-- Таблица -->
		<admin-specialists-table
			v-show="loading.table"
			:array="educations"
			@useFilter="filterEducations"
			@touchEditArrValue="editEducation"
			@touchRemoveArrValue="removeEducation"
		/>

		<!-- Загрузчик -->
		<loader-child
			:isLoading="loading.loader"
			:minHeight="200"
			@loaderChildAfterLeave="loaderChildAfterLeave"
		/>

		<block-buttons>
			<button-default @click="createEducation"> Добавить </button-default>
		</block-buttons>
	</block-once>
</template>

<script>
import AdminModal from "../../../components/includes/admin/AdminModal.vue";

import InfoBar from "../../../components/ui/admin/InfoBar.vue";

import LoaderChild from "../../../components/includes/LoaderChild.vue";

import ElementInputLabel from "../../../components/ui/admin/elements/ElementInputLabel.vue";
import BlockOnce from "../../../components/ui/admin/BlockOnce.vue";
import BlockTitle from "../../../components/ui/admin/BlockTitle.vue";
import BlockButtons from "../../../components/ui/admin/BlockButtons.vue";

import ContainerInput from "../../../components/ui/admin/containers/ContainerInput.vue";
import ContainerInputOnce from "../../../components/ui/admin/containers/input/ContainerInputOnce.vue";
import ContainerInputTwo from "../../../components/ui/admin/containers/input/ContainerInputTwo.vue";
import ContainerInputThree from "../../../components/ui/admin/containers/input/ContainerInputThree.vue";
import ContainerSelectOnce from "../../../components/ui/admin/containers/select/ContainerSelectOnce.vue";
import ContainerTextareaOnce from "../../../components/ui/admin/containers/textarea/ContainerTextareaOnce.vue";

import AdminSpecialistsTable from "./AdminSpecialistsTable.vue";

import ButtonDefault from "../../../components/ui/admin/buttons/ButtonDefault.vue";
import ButtonRemove from "../../../components/ui/admin/buttons/ButtonRemove.vue";

import IconSave from "../../../components/icons/IconSave.vue";

import axios from "axios";

export default {
	components: {
		AdminModal,
		InfoBar,
		LoaderChild,
		ElementInputLabel,
		BlockOnce,
		BlockTitle,
		BlockButtons,
		ContainerInput,
		ContainerInputOnce,
		ContainerInputTwo,
		ContainerInputThree,
		ContainerSelectOnce,
		ContainerTextareaOnce,
		AdminSpecialistsTable,
		ButtonDefault,
		ButtonRemove,
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
			currentEducation: {
				errors: {
					id: {
						body: null,
						status: false,
					},
					name: {
						body: null,
						status: false,
					},
					organization: {
						body: null,
						status: false,
					},
					date: {
						body: null,
						status: false,
					},
					id_specialization: {
						body: null,
						status: false,
					},
					create: {
						body: false,
						status: false,
					},
					delete: {
						body: false,
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
					organization: {
						body: null,
						edited: false,
					},
					date: {
						body: null,
						edited: false,
					},
					id_specialization: {
						body: null,
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
			educations: [],
			specializations: [
				{
					id: 1,
					name: "Терапевт",
				},
				{
					id: 2,
					name: "Хирург",
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
					this.clearModalErrors();

					this.modal.type = "create";
					this.modal.status = true;
					this.modal.style.create = true;
					this.modal.style.delete = false;
					this.clearModalData();

					document.body.classList.add("modal-open");
					break;
				case "edit":
					this.clearModalErrors();

					this.modal.type = "edit";
					if (this.currentEducation.data.create.body) {
						this.modal.style.create = true;
					} else {
						this.modal.style.create = false;
					}
					this.modal.status = true;
					this.modal.style.delete = false;

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
		checkSelect(value) {
			/* Проверка на пустую строку */
			if (value == "" || value == "null") {
				return {
					status: true,
					message: "Поле не может быть пустым.",
				};
			}

			/* Проверка на наличие в массиве со специализациями */
			let valueInSpecializations = this.specializations.find((element, index, array) => {
				if (element.id == value) {
					return true;
				}
			});

			if (valueInSpecializations) {
				return {
					status: false,
					message: "Ошибок нет.",
				};
			} else {
				return {
					status: true,
					message: "Такого значения нет в специализациях.",
				};
			}
		},
		// Проверка поля имени
		checkModalInput(dataKey, inputType) {
			let errorLog = {};
			switch (inputType) {
				case "text":
					errorLog = this.checkInputText(this.currentEducation.data[dataKey].body);
					break;
				case "number":
					errorLog = this.checkInputNumber(this.currentEducation.data[dataKey].body);
					break;
				case "select":
					errorLog = this.checkSelect(this.currentEducation.data[dataKey].body);
					break;
				default:
					break;
			}

			if (errorLog.status) {
				this.currentEducation.errors[dataKey].body = errorLog.message;
				this.currentEducation.errors[dataKey].status = true;

				return true;
			} else {
				this.currentEducation.errors[dataKey].body = "";
				this.currentEducation.errors[dataKey].status = false;

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
		/* Очистка содержимого модального окна */
		clearModalData() {
			for (let key in this.currentEducation.data) {
				this.currentEducation.data[key].body = "";
				this.currentEducation.data[key].edited = false;
			}
		},
		/* Очистка ошибок */
		clearModalErrors() {
			for (let key in this.currentEducation.errors) {
				this.currentEducation.errors[key].body = "";
				this.currentEducation.errors[key].status = false;
			}
		},
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                   ОБРАЗОВАНИЕ                     |*/
		/* |___________________________________________________|*/
		/* _____________________________________________________*/
		/* 1. Фильтрация                                        */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		filterEducations(column, type) {
			switch (column) {
				case "id":
					if (type == "default") {
						this.educations.sort((a, b) => {
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
						this.educations.sort((a, b) => {
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
						this.educations.sort((a, b) => {
							let aName = a.name.toLowerCase();
							let bName = b.name.toLowerCase();

							let aNameFirstLetter = aName[0].charCodeAt(0);
							let bNameFirstLetter = bName[0].charCodeAt(0);

							if (aNameFirstLetter > bNameFirstLetter) {
								return 1;
							}
							if (aNameFirstLetter < bNameFirstLetter) {
								return -1;
							}
							// a должно быть равным b
							return 0;
						});
					}

					if (type == "reverse") {
						this.educations.sort((a, b) => {
							let aName = a.name.toLowerCase();
							let bName = b.name.toLowerCase();

							let aNameFirstLetter = aName[0].charCodeAt(0);
							let bNameFirstLetter = bName[0].charCodeAt(0);

							if (aNameFirstLetter < bNameFirstLetter) {
								return 1;
							}
							if (aNameFirstLetter > bNameFirstLetter) {
								return -1;
							}
							// a должно быть равным b
							return 0;
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
		editEducation(selectedEducation) {
			this.clearModalData();

			for (let key in this.currentEducation.data) {
				this.currentEducation.data[key].body = selectedEducation[key];
			}

			this.openModal("edit");
		},
		/* Открытие специализации для создания */
		createEducation() {
			this.openModal("create");
		},
		/* _____________________________________________________*/
		/* 3. Сохранение, обновление и удаление                 */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		/* Добавление образования */
		addEducation() {
			if (this.checkModalInputsAll(["name", "organization", "date", "id_specialization"]))
				return;

			try {
				// Поиск максимального id
				let maxId = 0;
				for (let key in this.educations) {
					if (this.educations[key].id > maxId) {
						maxId = this.educations[key].id;
					}
				}

				this.educations.push({
					id: maxId + 1,
					name: this.currentEducation.data.name.body,
					organization: this.currentEducation.data.organization.body,
					date: this.currentEducation.data.date.body,
					id_specialization: this.currentEducation.data.id_specialization.body,
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
		/* Пометка на удаление */
		removeEducation(id) {
			let educationToDelete = this.educations.filter((education) => {
				if (education.id === id) {
					return education;
				}
			});

			educationToDelete[0].delete = !educationToDelete[0].delete;
		},
		updateEducation() {
			if (this.checkModalInputsAll(["name", "organization", "date", "id_specialization"]))
				return;

			try {
				let educationToUpdate = this.educations.filter((education) => {
					if (education.id === this.currentEducation.data.id.body) {
						return education;
					}
				});

				for (let key in this.currentEducation.data) {
					educationToUpdate[0][key] = this.currentEducation.data[key].body;
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
		/* Сохранение изменений на сервере */
		saveEducationsChanges() {
			let newArray = [];

			for (let key in this.educations) {
				newArray.push(Object.assign({}, this.educations[key]));
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
				url: `${this.$store.state.axios.urlApi}` + `save-educations-changes`,
				headers: {
					Accept: "application/json",
					Authorization: `Bearer ${localStorage.getItem("token")}`,
				},
				data: {
					educations: newArray,
				},
			})
				.then((response) => {
					// Обновление id добавленных элементов на данные из бд
					for (let key in response.data) {
						let education = this.educations.filter((education) => {
							if (education.id === response.data[key].old) {
								return education;
							}
						});
						education[0].id = response.data[key].new;
					}

					// Получения нового массива специалистов, помеченных на удаление
					let res = this.educations.filter((education) => {
						if (education.delete == true) {
							return Object.assign({}, education);
						}
					});

					// Повторять, пока не будут удалены все элементы, помеченные на удаление
					while (res.length > 0) {
						/* Получение индекса элемента, помеченного на удаление из массива специалистов */
						this.educations.splice(this.educations.indexOf(res[0]), 1);
						/* Обновление списка с элементами, помеченными на удаление */
						res = this.educations.filter((education) => {
							if (education.delete == true) {
								return Object.assign({}, education);
							}
						});
					}

					// Сброс флагов добавления и удаления
					for (let key in this.educations) {
						this.educations[key].create = false;
						this.educations[key].delete = false;
					}

					let debbugStory = {
						title: "Успешно!",
						body: "Данные о специализациях сохранились.",
						type: "Completed",
					};
					this.$store.commit("debuggerState", debbugStory);
				})
				.catch((error) => {
					let debbugStory = {
						title: "Ошибка.",
						body: "Данные о специализациях почему-то не сохранились.",
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
			url: `${this.$store.state.axios.urlApi}` + `get-educations-all`,
		})
			.then((response) => {
				this.educations = response.data.educations;
				this.specializations = response.data.spezializations;
				this.loading.loader = false;

				for (let key in this.educations) {
					this.educations[key].create = false;
					this.educations[key].delete = false;
				}
			})
			.catch((error) => {
				console.log(error);
			});
	},
};
</script>

<style scoped></style>
