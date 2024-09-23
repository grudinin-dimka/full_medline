<template>
	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                  МОДАЛЬНОЕ ОКНО                   |-->
	<!--|___________________________________________________|-->
	<admin-modal ref="modal" @touchCloseModal="closeModal" :modal="modal">
		<template #title>
			<span class="create" v-if="modal.type == 'create'">
				СПЕЦИАЛИЗАЦИЯ (СОЗДАНИЕ)
			</span>
			<span v-if="modal.type == 'edit'">СПЕЦИАЛИЗАЦИЯ (РЕДАКТИРОВАНИЕ)</span>
		</template>
		<template #body>
			<ContainerInputOnce>
				<template #title>
					<span :class="{ create: modal.type == 'create' }">НАЗВАНИЕ*</span>
					<span v-if="currentSpecialization.data.name.edited">(ИЗМЕНЕНО)</span>
				</template>
				<template #input>
					<input
						type="text"
						ref="inputName"
						placeholder="Название специализации"
						v-model="currentSpecialization.data.name.body"
						:class="{
							create: modal.style.create,
							error: currentSpecialization.errors.name.status,
						}"
						@input="currentSpecialization.data.name.edited = true"
						@blur="checkModalInput('name', 'text')"
					/>
				</template>
				<template #error>
					<span class="error" v-if="currentSpecialization.errors.name.status">
						{{ this.currentSpecialization.errors.name.body }}
					</span>
				</template>
			</ContainerInputOnce>
		</template>
		<template #footer>
			<BlockButtons>
				<ButtonDefault @click="addSpecialization" v-if="modal.type == 'create'">
					Создать
				</ButtonDefault>
				<ButtonDefault @click="updateSpecialization" v-if="modal.type == 'edit'">
					Обновить
				</ButtonDefault>
			</BlockButtons>
		</template>
	</admin-modal>

	<info-bar>
		<template v-slot:title>Специалисты</template>
		<template v-slot:addreas>specialists</template>
	</info-bar>

	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                   СПЕЦИАЛИЗАЦИИ                   |-->
	<!--|___________________________________________________|-->
	<block-once>
		<block-title>
			<template #title>Специализации</template>
			<template #buttons>
				<icon-save :width="28" :height="28" @click="saveSpecializationsChanges" />
			</template>
		</block-title>

		<admin-specialists-table
			v-show="loading.table"
			:array="specializations"
			@useFilter="filterSpecializations"
			@touchEditArrValue="editSpecialization"
			@touchRemoveArrValue="removeSpecialization"
		/>

		<loader-child
			:isLoading="loading.loader"
			:minHeight="200"
			@loaderChildAfterLeave="loaderChildAfterLeave"
		/>

		<block-buttons>
			<button-default @click="createSpecialization"> Добавить </button-default>
		</block-buttons>
	</block-once>
</template>

<script>
import AdminModal from "../../../components/includes/admin/AdminModal.vue";

import InfoBar from "../../../components/ui/admin/InfoBar.vue";

import LoaderChild from "../../../components/includes/LoaderChild.vue";

import ElementInputLabel from "../../../components/ui/admin/ElementInputLabel.vue";
import BlockOnce from "../../../components/ui/admin/BlockOnce.vue";
import BlockTitle from "../../../components/ui/admin/BlockTitle.vue";
import BlockButtons from "../../../components/ui/admin/BlockButtons.vue";

import ContainerInputOnce from "../../../components/ui/admin/containers/input/ContainerInputOnce.vue";

import AdminSpecialistsTable from "./AdminSpecialistsTable.vue";

import ButtonDefault from "../../../components/ui/admin/ButtonDefault.vue";
import ButtonRemove from "../../../components/ui/admin/ButtonRemove.vue";

import IconSave from "../../../components/icons/IconSave.vue";

import axios from "axios";

export default {
	components: {
		AdminModal,
		InfoBar,
		LoaderChild,
		ElementInputLabel,
		ContainerInputOnce,
		AdminSpecialistsTable,
		BlockOnce,
		BlockTitle,
		BlockButtons,
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
			currentSpecialization: {
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
			specializations: [],
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
						this.modal.type = "edit";
						if (this.currentSpecialization.data.create.body) {
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
		/* _____________________________________________________*/
		/* 2. Работа с полями ввода модального окна             */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		// Проверка введенного значения
		checkInputText(value) {
			/* Проверка на пустую строку */
			if (value == "" || value == null) {
				return {
					status: true,
					message: "Поле не может быть пустым.",
				};
			}

			/* Проверка на соответствие типу string */
			if (typeof value !== "string") {
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
					errorLog = this.checkInputText(this.currentSpecialization.data[dataKey].body);
					break;
				default:
					break;
			}

			if (errorLog.status) {
				this.currentSpecialization.errors[dataKey].body = errorLog.message;
				this.currentSpecialization.errors[dataKey].status = true;

				return true;
			} else {
				this.currentSpecialization.errors[dataKey].body = "";
				this.currentSpecialization.errors[dataKey].status = false;

				return false;
			}
		},
		// Проверка всех полей ввода модального окна
		checkModalInputsAll(inputKeys) {
			let errorCount = 0;
			for (let i = 0; i < inputKeys.length; i++) {
				switch (inputKeys[i]) {
					// Для поля файл
					case "file":
						console.log("Функция в разработке");
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
			for (let key in this.currentSpecialization.data) {
				this.currentSpecialization.data[key].body = "";
				this.currentSpecialization.data[key].edited = false;
			}
		},
		/* Очистка ошибок модального окна */
		clearModalErrors() {
			for (let key in this.currentSpecialization.errors) {
				this.currentSpecialization.errors[key].body = "";
				this.currentSpecialization.errors[key].status = false;
			}
		},
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                 Специализации                     |*/
		/* |___________________________________________________|*/
		/* _____________________________________________________*/
		/* 1. Фильтрация                                        */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		/* Фильтрация по столбцу */
		filterSpecializations(column, type) {
			switch (column) {
				case "id":
					if (type == "default") {
						this.specializations.sort((a, b) => {
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
						this.specializations.sort((a, b) => {
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
						this.specializations.sort((a, b) => {
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
						this.specializations.sort((a, b) => {
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
		editSpecialization(selectedSpecialization) {
			this.clearModalData();

			for (let key in this.currentSpecialization.data) {
				this.currentSpecialization.data[key].body = selectedSpecialization[key];
			}

			this.openModal("edit");
		},
		/* Открытие специализации для создания */
		createSpecialization() {
			this.openModal("create");
		},
		/* _____________________________________________________*/
		/* 3. Сохранение, обновление и удаление                 */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		/* Добавление специализации */
		addSpecialization() {
			if (this.checkModalInputsAll(["name"])) return;
			try {
				// Поиск максимального id
				let maxId = 0;
				for (let key in this.specializations) {
					if (this.specializations[key].id > maxId) {
						maxId = this.specializations[key].id;
					}
				}

				this.specializations.push({
					id: maxId + 1,
					name: this.currentSpecialization.data.name.body,
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
		removeSpecialization(id) {
			let specializationToDelete = this.specializations.filter((specialization) => {
				if (specialization.id === id) {
					return specialization;
				}
			});

			specializationToDelete[0].delete = !specializationToDelete[0].delete;
		},
		/* Обновление содержимого */
		updateSpecialization() {
			if (this.checkModalInputsAll(["name"])) return;
			try {
				let specializationToUpdate = this.specializations.filter((specialization) => {
					if (specialization.id === this.currentSpecialization.data.id.body) {
						return specialization;
					}
				});

				specializationToUpdate[0].name = this.currentSpecialization.data.name.body;
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
		saveSpecializationsChanges() {
			let newArray = [];

			for (let key in this.specializations) {
				newArray.push(Object.assign({}, this.specializations[key]));
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
				url: `${this.$store.state.axios.urlApi}` + `save-specializations-changes`,
				headers: {
					Accept: "application/json",
					Authorization: `Bearer ${localStorage.getItem("token")}`,
				},
				data: {
					specializations: newArray,
				},
			})
				.then((response) => {
					try {
						// Обновление id добавленных элементов на данные из бд
						for (let key in response.data) {
							let specialization = this.specializations.filter((specialization) => {
								if (specialization.id === response.data[key].old) {
									return specialization;
								}
							});
							specialization[0].id = response.data[key].new;
						}

						// Получения нового массива специалистов, помеченных на удаление
						let res = this.specializations.filter((specialization) => {
							if (specialization.delete == true) {
								return Object.assign({}, specialization);
							}
						});

						// Повторять, пока не будут удалены все элементы, помеченные на удаление
						while (res.length > 0) {
							/* Получение индекса элемента, помеченного на удаление из массива специалистов */
							this.specializations.splice(this.specializations.indexOf(res[0]), 1);
							/* Обновление списка с элементами, помеченными на удаление */
							res = this.specializations.filter((specialization) => {
								if (specialization.delete == true) {
									return Object.assign({}, specialization);
								}
							});
						}

						// Сброс флагов добавления и удаления
						for (let key in this.specializations) {
							this.specializations[key].create = false;
							this.specializations[key].delete = false;
						}

						let debbugStory = {
							title: "Успешно!",
							body: "Данные о специализациях сохранились.",
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
			url: `${this.$store.state.axios.urlApi}` + `get-specializations-all`,
		})
			.then((response) => {
				this.specializations = response.data;

				for (let key in this.specializations) {
					this.specializations[key].create = false;
					this.specializations[key].delete = false;
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
