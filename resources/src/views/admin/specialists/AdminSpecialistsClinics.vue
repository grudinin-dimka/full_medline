<template>
	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                  МОДАЛЬНОЕ ОКНО                   |-->
	<!--|___________________________________________________|-->
	<admin-modal ref="modal" @touchCloseModal="" :modal="modal">
		<template #title> Клиника (редактирование) </template>
		<template #body>
			<container-input>
				<!-- Название и другое -->
				<container-input-two :fieldset="true">
					<template #legend>НАЗВАНИЕ И ДРУГОЕ</template>
					<!-- НАЗВАНИЕ -->
					<template #title-one> НАЗВАНИЕ* <span v-if="false">(Изменено)</span> </template>
					<template #input-one>
						<input
							type="text"
							placeholder="Введите название"
							autocomplete="off"
							v-model="currentClinic.data.name.body"
							@blur="checkModalInput('name', 'text')"
						/>
					</template>
					<template #error-one>
						<span class="error" v-if="currentClinic.errors.name.status">
							{{ currentClinic.errors.name.body }}
						</span>
					</template>
					<!-- ДРУГОЕ -->
					<template #title-two> ДРУГОЕ* <span v-if="false">(Изменено)</span> </template>
					<template #input-two>
						<input
							type="text"
							placeholder="Введите другое"
							autocomplete="off"
							v-model="currentClinic.data.other.body"
							@blur="checkModalInput('other', 'text')"
						/>
					</template>
					<template #error-two>
						<span class="error" v-if="currentClinic.errors.other.status">
							{{ currentClinic.errors.other.body }}
						</span>
					</template>
				</container-input-two>

				<!-- Город и улица -->
				<container-input-two :fieldset="true">
					<template #legend>ГОРОД И УЛИЦА</template>
					<!-- Город -->
					<template #title-one> ГОРОД* <span v-if="false">(Изменено)</span> </template>
					<template #input-one>
						<input
							type="text"
							placeholder="Введите город"
							autocomplete="off"
							v-model="currentClinic.data.city.body"
							@blur="checkModalInput('city', 'text')"
						/>
					</template>
					<template #error-one>
						<span class="error" v-if="currentClinic.errors.city.status">
							{{ currentClinic.errors.city.body }}
						</span>
					</template>
					<!-- УЛИЦА -->
					<template #title-two> УЛИЦА* <span v-if="false">(Изменено)</span> </template>
					<template #input-two>
						<input
							type="text"
							placeholder="Введите улицу"
							autocomplete="off"
							v-model="currentClinic.data.street.body"
							@blur="checkModalInput('street', 'text')"
						/>
					</template>
					<template #error-two>
						<span class="error" v-if="currentClinic.errors.street.status">
							{{ currentClinic.errors.street.body }}
						</span>
					</template>
				</container-input-two>

				<!-- Дом и индекс -->
				<container-input-two :fieldset="true">
					<template #legend>ДОМ И ИНДЕКС</template>
					<!-- ДОМ -->
					<template #title-one> ДОМ* <span v-if="false">(Изменено)</span> </template>
					<template #input-one>
						<input
							type="text"
							placeholder="Введите дом"
							autocomplete="off"
							v-model="currentClinic.data.home.body"
							@blur="checkModalInput('home', 'text')"
						/>
					</template>
					<template #error-one>
						<span class="error" v-if="currentClinic.errors.home.status">
							{{ currentClinic.errors.home.body }}
						</span>
					</template>
					<!-- ИНДЕКС -->
					<template #title-two> ИНДЕКС* <span v-if="false">(Изменено)</span> </template>
					<template #input-two>
						<input
							type="tel"
							v-mask="'######'"
							placeholder="______"
							autocomplete="off"
							v-model="currentClinic.data.index.body"
							@blur="checkModalInput('index', 'number')"
						/>
					</template>
					<template #error-two>
						<span class="error" v-if="currentClinic.errors.index.status">
							{{ currentClinic.errors.index.body }}
						</span>
					</template>
				</container-input-two>

				<!-- Географические координаты -->
				<container-input-two :fieldset="true">
					<template #legend>ГЕОГРАФИЧЕСКИЕ КООРДИНАТЫ</template>
					<!-- Ширина -->
					<template #title-one> ШИРИНА* <span v-if="false">(Изменено)</span> </template>
					<template #input-one>
						<input
							type="number"
							placeholder="Введите ширину"
							autocomplete="off"
							v-model="currentClinic.data.geoWidth.body"
							@blur="checkModalInput('geoWidth', 'number')"
						/>
					</template>
					<template #error-one>
						<span class="error" v-if="currentClinic.errors.geoWidth.status">
							{{ currentClinic.errors.geoWidth.body }}
						</span>
					</template>
					<!-- Долгота -->
					<template #title-two> ДОЛГОТА* <span v-if="false">(Изменено)</span> </template>
					<template #input-two>
						<input
							type="number"
							placeholder="Введите долготу"
							autocomplete="off"
							v-model="currentClinic.data.geoLongitude.body"
							@blur="checkModalInput('geoLongitude', 'number')"
						/>
					</template>
					<template #error-two>
						<span class="error" v-if="currentClinic.errors.geoLongitude.status">
							{{ currentClinic.errors.geoLongitude.body }}
						</span>
					</template>
				</container-input-two>
			</container-input>
		</template>
		<template #footer>
			<BlockButtons>
				<ButtonDefault v-if="false"> Создать </ButtonDefault>
				<ButtonDefault v-if="true"> Обновить </ButtonDefault>
			</BlockButtons>
		</template>
	</admin-modal>

	<info-bar>
		<template v-slot:title>Специалисты</template>
		<template v-slot:addreas>specialists</template>
	</info-bar>

	<block-once>
		<block-title>
			<template #title>Клиники</template>
			<template #buttons>
				<icon-save :width="28" :height="28" />
			</template>
		</block-title>

		<!-- Таблица -->
		<admin-specialists-table
			v-show="loading.table"
			:array="clinics"
			@useFilter="filterClinics"
			@touchEditArrValue="editClinic"
			@touchRemoveArrValue="removeClinic"
		/>

		<!-- Загрузчик -->
		<loader-child
			:isLoading="loading.loader"
			:minHeight="200"
			@loaderChildAfterLeave="loaderChildAfterLeave"
		/>

		<block-buttons>
			<button-default> Добавить </button-default>
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

import ContainerInput from "../../../components/ui/admin/ContainerInput.vue";
import ContainerInputOnce from "../../../components/ui/admin/ContainerInputOnce.vue";
import ContainerInputTwo from "../../../components/ui/admin/ContainerInputTwo.vue";
import ContainerInputThree from "../../../components/ui/admin/ContainerInputThree.vue";

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
		BlockOnce,
		BlockTitle,
		BlockButtons,
		ContainerInput,
		ContainerInputOnce,
		ContainerInputTwo,
		ContainerInputThree,
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
			currentClinic: {
				errors: {
					id: {
						body: "",
						status: false,
					},
					name: {
						body: "",
						status: false,
					},
					other: {
						body: "",
						status: false,
					},
					city: {
						body: "",
						status: false,
					},
					street: {
						body: "",
						status: false,
					},
					home: {
						body: "",
						status: false,
					},
					index: {
						body: "",
						status: false,
					},
					geoWidth: {
						body: "",
						status: false,
					},
					geoLongitude: {
						body: "",
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
						body: "",
						edited: false,
					},
					name: {
						body: "",
						edited: false,
					},
					other: {
						body: "",
						edited: false,
					},
					city: {
						body: "",
						edited: false,
					},
					street: {
						body: "",
						edited: false,
					},
					home: {
						body: "",
						edited: false,
					},
					index: {
						body: "",
						edited: false,
					},
					geoWidth: {
						body: "",
						edited: false,
					},
					geoLongitude: {
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
			clinics: [],
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
						this.modal.type = "create";
						this.modal.status = true;
						this.modal.style.create = true;
						this.modal.style.delete = false;
						// this.clearModalData();
					}
					document.body.classList.toggle("modal-open");
					break;
				case "edit":
					{
						this.modal.type = "edit";
						// if (this.currentSpecialization.data.create.body) {
						// 	this.modal.style.create = true;
						// } else {
						// 	this.modal.style.create = false;
						// }
						this.modal.status = true;
						this.modal.style.delete = false;
					}
					document.body.classList.toggle("modal-open");
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
			document.body.classList.toggle("modal-open");
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

			/* Проверка на соответствие типу string */
			if (typeof value !== "number") {
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
					errorLog = this.checkInputText(this.currentClinic.data[dataKey].body);
					break;
				case "number":
					errorLog = this.checkInputNumber(this.currentClinic.data[dataKey].body);
					break;
				default:
					break;
			}

			if (errorLog.status) {
				this.currentClinic.errors[dataKey].body = errorLog.message;
				this.currentClinic.errors[dataKey].status = true;

				return true;
			} else {
				this.currentClinic.errors[dataKey].body = "";
				this.currentClinic.errors[dataKey].status = false;

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
			for (let key in this.currentClinic.data) {
				this.currentClinic.data[key].body = "";
				this.currentClinic.data[key].edited = false;
			}
		},
		/* Очистка ошибок */
		clearModalErrors() {
			for (let key in this.currentClinic.errors) {
				this.currentClinic.errors[key].body = "";
				this.currentClinic.errors[key].status = false;
			}
		},
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                    КЛИНИКИ                        |*/
		/* |___________________________________________________|*/
		/* _____________________________________________________*/
		/* 1. Фильтрация                                        */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		filterClinics(column, type) {
			switch (column) {
				case "id":
					if (type == "default") {
						this.clinics.sort((a, b) => {
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
						this.clinics.sort((a, b) => {
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
						this.clinics.sort((a, b) => {
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
						this.clinics.sort((a, b) => {
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
		editClinic(selectedClinic) {
			this.clearModalData();

			for (let key in this.currentClinic.data) {
				this.currentClinic.data[key].body = selectedClinic[key];
			}

			this.openModal("edit");
		},
		/* Открытие специализации для создания */
		createClinic() {
			this.openModal("create");
		},
		/* _____________________________________________________*/
		/* 3. Сохранение, обновление и удаление                 */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		/* Пометка на удаление */
		removeClinic(id) {
			let clinicToDelete = this.clinics.filter((clinic) => {
				if (clinic.id === id) {
					return clinic;
				}
			});

			clinicToDelete[0].delete = !clinicToDelete[0].delete;
		},
	},
	mounted() {
		axios({
			method: "post",
			headers: {
				Accept: "application/json",
			},
			url: `${this.$store.state.axios.urlApi}` + `get-clinics-all`,
		})
			.then((response) => {
				this.clinics = response.data;

				for (let key in this.clinics) {
					this.clinics[key].create = false;
					this.clinics[key].delete = false;
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
