<template>
	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                  МОДАЛЬНОЕ ОКНО                   |-->
	<!--|___________________________________________________|-->
	<admin-modal ref="modal" @touchCloseModal="closeModal" :modal="modal">
		<template #title>
			<span v-if="modal.type == 'create'">МЕСТО РАБОТЫ (СОЗДАНИЕ)</span>
			<span v-if="modal.type == 'edit'">МЕСТО РАБОТЫ (РЕДАКТИРОВАНИЕ)</span>
		</template>
		<template #body>
			<!-- Название -->
			<ContainerInputOnce>
				<template #title>
					<span :class="{ create: modal.type == 'create' }">НАЗВАНИЕ*</span>
					<span
						:class="{ create: modal.type == 'create' }"
						v-if="currentWork.data.name.edited"
					>
						(ИЗМЕНЕНО)
					</span>
				</template>
				<template #input>
					<input
						type="text"
						placeholder="Название работы"
						v-model="currentWork.data.name.body"
						:class="{ error: currentWork.errors.name.status }"
						@input="currentWork.data.name.edited = true"
						@blur="checkModalInput('name', 'text')"
					/>
				</template>
				<template #error>
					<span class="error" v-if="currentWork.errors.name.status">
						{{ currentWork.errors.name.body }}
					</span>
				</template>
			</ContainerInputOnce>
			<!-- Организация -->
			<ContainerTextareaOnce>
				<template #title>
					<span :class="{ create: modal.type == 'create' }">ОРГАНИЗАЦИЯ*</span>
					<span
						:class="{ create: modal.type == 'create' }"
						v-if="currentWork.data.organization.edited"
					>
						(ИЗМЕНЕНО)
					</span>
				</template>
				<template #textarea>
					<textarea
						rows="4"
						placeholder="Название организации"
						v-model="currentWork.data.organization.body"
						:class="{ error: currentWork.errors.organization.status }"
						@input="currentWork.data.organization.edited = true"
						@blur="checkModalInput('organization', 'text')"
					></textarea>
				</template>
				<template #error>
					<span class="error" v-if="currentWork.errors.organization.status">
						{{ currentWork.errors.organization.body }}
					</span>
				</template>
			</ContainerTextareaOnce>
			<!-- Начало и конец -->
			<container-input-two :fieldset="true">
				<template #legend>
					<span :class="{ create: modal.type == 'create' }">НАЧАЛО И ОКОНЧАНИЕ РАБОТЫ</span>
				</template>
				<!-- Начало работы -->
				<template #title-one>
					<span :class="{ create: modal.type == 'create' }">ДАТА НАЧАЛА*</span>
					<span v-if="currentWork.data.startWork.edited"> (ИЗМЕНЕНО)</span>
				</template>
				<template #input-one>
					<input
						type="date"
						autocomplete="off"
						v-model="currentWork.data.startWork.body"
						:class="{ error: currentWork.errors.startWork.status }"
						@input="currentWork.data.startWork.edited = true"
						@blur="checkModalInput('startWork', 'text')"
					/>
				</template>
				<template #error-one>
					<span class="error" v-if="currentWork.errors.startWork.status">
						{{ currentWork.errors.startWork.body }}
					</span>
				</template>
				<!-- Конец работы -->
				<template #title-two>
					<span :class="{ create: modal.type == 'create' }">ДАТА ОКОНЧАНИЯ*</span>
					<span v-if="currentWork.data.endWork.edited"> (ИЗМЕНЕНО)</span>
				</template>
				<template #input-two>
					<input
						type="date"
						placeholder="Введите улицу"
						autocomplete="off"
						v-model="currentWork.data.endWork.body"
						:class="{ error: currentWork.errors.endWork.status }"
						@input="currentWork.data.endWork.edited = true"
						@blur="checkModalInput('endWork', 'text')"
					/>
				</template>
				<template #error-two>
					<span class="error" v-if="currentWork.errors.endWork.status">
						{{ currentWork.errors.endWork.body }}
					</span>
				</template>
			</container-input-two>
		</template>
		<template #footer>
			<BlockButtons>
				<button-claim @click="addWork" v-if="modal.type == 'create'"> Создать </button-claim>
				<ButtonDefault @click="updateWork" v-if="modal.type == 'edit'">
					Обновить
				</ButtonDefault>
			</BlockButtons>
		</template>
	</admin-modal>

	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                      РАБОТЫ                       |-->
	<!--|___________________________________________________|-->
	<info-bar>
		<template v-slot:title>Специалисты</template>
		<template v-slot:addreas>specialists</template>
	</info-bar>

	<block-once>
		<block-title>
			<template #title>Места работы</template>
			<template #buttons>
				<icon-save :width="28" :height="28" @click="saveWorksChanges" />
			</template>
		</block-title>

		<!-- Таблица -->
		<admin-specialists-table
			v-show="loading.table"
			:array="works"
			@useFilter="filterWorks"
			@touchEditArrValue="editWork"
			@touchRemoveArrValue="removeWork"
		/>

		<!-- Загрузчик -->
		<loader-child
			:isLoading="loading.loader"
			:minHeight="200"
			@loaderChildAfterLeave="loaderChildAfterLeave"
		/>

		<block-buttons>
			<button-default @click="createWork"> Добавить </button-default>
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
import ContainerSelectOnce from "../../../components/ui/admin/containers/select/ContainerSelectOnce.vue";
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
			currentWork: {
				errors: {
					id: {
						body: "",
						status: false,
					},
					startWork: {
						body: "",
						status: false,
					},
					endWork: {
						body: "",
						status: false,
					},
					organization: {
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
					startWork: {
						body: "",
						edited: false,
					},
					endWork: {
						body: "",
						edited: false,
					},
					organization: {
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
			works: [],
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
						if (this.currentWork.data.create.body) {
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
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                 Специализации                     |*/
		/* |___________________________________________________|*/
		/* _____________________________________________________*/
		/* 1. Фильтрация                                        */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		/* Фильтрация по столбцу */
		filterWorks(column, type) {
			// Объявляем объект Intl.Collator, который обеспечивает сравнение строк с учётом языка.
			const collator = new Intl.Collator("ru");

			switch (column) {
				case "id":
					if (type == "default") {
						this.works.sort((a, b) => {
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
						this.works.sort((a, b) => {
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
						this.works.sort((a, b) => {
							return collator.compare(a.name, b.name);
						});
					}

					if (type == "reverse") {
						this.works.reverse((a, b) => {
							return collator.compare(a.name, b.name);
						});
					}

					break;
				default:
					break;
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
					errorLog = this.checkInputText(this.currentWork.data[dataKey].body);
					break;
				case "number":
					errorLog = this.checkInputNumber(this.currentWork.data[dataKey].body);
					break;
				case "select":
					errorLog = this.checkSelect(this.currentWork.data[dataKey].body);
					break;
				default:
					break;
			}

			if (errorLog.status) {
				this.currentWork.errors[dataKey].body = errorLog.message;
				this.currentWork.errors[dataKey].status = true;

				return true;
			} else {
				this.currentWork.errors[dataKey].body = "";
				this.currentWork.errors[dataKey].status = false;

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
			for (let key in this.currentWork.data) {
				this.currentWork.data[key].body = "";
				this.currentWork.data[key].edited = false;
			}
		},
		/* Очистка ошибок */
		clearModalErrors() {
			for (let key in this.currentWork.errors) {
				this.currentWork.errors[key].body = "";
				this.currentWork.errors[key].status = false;
			}
		},
		/* _____________________________________________________*/
		/* 2. Основные действия                                 */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		/* Открытие специализации для редактирования */
		editWork(selectedWork) {
			this.clearModalData();

			for (let key in this.currentWork.data) {
				this.currentWork.data[key].body = selectedWork[key];
			}

			this.openModal("edit");
		},
		/* Открытие специализации для создания */
		createWork() {
			this.openModal("create");
		},
		/* _____________________________________________________*/
		/* 3. Сохранение, обновление и удаление                 */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		/* Добавление работы */
		addWork() {
			if (this.checkModalInputsAll(["startWork", "endWork", "organization", "name"])) return;

			try {
				// Поиск максимального id
				let maxId = 0;
				for (let key in this.works) {
					if (this.works[key].id > maxId) {
						maxId = this.works[key].id;
					}
				}

				this.works.push({
					id: maxId + 1,
					name: this.currentWork.data.name.body,
					organization: this.currentWork.data.organization.body,
					startWork: this.currentWork.data.startWork.body,
					endWork: this.currentWork.data.endWork.body,
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
		updateWork() {
			if (this.checkModalInputsAll(["startWork", "endWork", "organization", "name"])) return;

			try {
				let workToUpdate = this.works.filter((work) => {
					if (work.id === this.currentWork.data.id.body) {
						return work;
					}
				});

				for (let key in this.currentWork.data) {
					workToUpdate[0][key] = this.currentWork.data[key].body;
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
		removeWork(id) {
			let workToDelete = this.works.filter((work) => {
				if (work.id === id) {
					return work;
				}
			});

			workToDelete[0].delete = !workToDelete[0].delete;
		},
		/* Сохранение изменений на сервере */
		saveWorksChanges() {
			let newArray = [];

			for (let key in this.works) {
				newArray.push(Object.assign({}, this.works[key]));
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
				url: `${this.$store.state.axios.urlApi}` + `save-works-changes`,
				headers: {
					Accept: "application/json",
					Authorization: `Bearer ${localStorage.getItem("token")}`,
				},
				data: {
					works: newArray,
				},
			})
				.then((response) => {
					try {
						// Обновление id добавленных элементов на данные из бд
						for (let key in response.data) {
							let work = this.works.filter((work) => {
								if (work.id === response.data[key].old) {
									return work;
								}
							});
							work[0].id = response.data[key].new;
						}

						// Получения нового массива, с данными помеченными на удаление
						let res = this.works.filter((work) => {
							if (work.delete == true) {
								return Object.assign({}, work);
							}
						});

						// Повторять, пока не будут удалены все элементы, помеченные на удаление
						while (res.length > 0) {
							/* Получение индекса элемента, помеченного на удаление из массива специалистов */
							this.works.splice(this.works.indexOf(res[0]), 1);
							/* Обновление списка с элементами, помеченными на удаление */
							res = this.works.filter((work) => {
								if (work.delete == true) {
									return Object.assign({}, work);
								}
							});
						}

						// Сброс флагов добавления и удаления
						for (let key in this.works) {
							this.works[key].create = false;
							this.works[key].delete = false;
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
			url: `${this.$store.state.axios.urlApi}` + `get-works-all`,
		})
			.then((response) => {
				this.works = response.data;

				for (let key in this.works) {
					this.works[key].create = false;
					this.works[key].delete = false;
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
