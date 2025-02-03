<template>
	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                  МОДАЛЬНОЕ ОКНО                   |-->
	<!--|___________________________________________________|-->
	<admin-modal ref="modal" @touchCloseModal="closeModal" :modal="modal">
		<template #title>
			<span v-if="modal.type == 'create'" class="create">КЛИНИКА (СОЗДАНИЕ)</span>
			<span v-if="modal.type == 'edit'">КЛИНИКА (РЕДАКТИРОВАНИЕ)</span>
		</template>
		<template #body>
			<container-input>
				<!-- Название и другое -->
				<container-input-two
					:fieldset="true"
					:type="modal.type == 'create' ? 'create' : 'edit'"
				>
					<template #legend>
						<span>НАЗВАНИЕ И ДРУГОЕ</span>
					</template>
					<!-- НАЗВАНИЕ -->
					<template #title-one>
						НАЗВАНИЕ*<span v-if="currentClinic.data.name.edited">(ИЗМЕНЕНО)</span>
					</template>
					<template #input-one>
						<input
							type="text"
							placeholder="Введите название"
							autocomplete="off"
							:class="{ error: currentClinic.errors.name.status }"
							v-model="currentClinic.data.name.body"
							@input="currentClinic.data.name.edited = true"
							@blur="checkModalInput('name', 'text')"
						/>
					</template>
					<template #error-one>
						<span class="error" v-if="currentClinic.errors.name.status">
							{{ currentClinic.errors.name.body }}
						</span>
					</template>
					<!-- ДРУГОЕ -->
					<template #title-two>
						ДРУГОЕ <span v-if="currentClinic.data.other.edited">(ИЗМЕНЕНО)</span>
					</template>
					<template #input-two>
						<input
							type="text"
							placeholder="Введите другое"
							autocomplete="off"
							v-model="currentClinic.data.other.body"
							@input="currentClinic.data.other.edited = true"
						/>
					</template>
					<template #error-two>
						<span class="error" v-if="currentClinic.errors.other.status">
							{{ currentClinic.errors.other.body }}
						</span>
					</template>
				</container-input-two>

				<!-- Город и улица -->
				<container-input-two
					:fieldset="true"
					:type="modal.type == 'create' ? 'create' : 'default'"
				>
					<template #legend>
						<span>ГОРОД И УЛИЦА</span>
					</template>
					<!-- Город -->
					<template #title-one>
						ГОРОД* <span v-if="currentClinic.data.city.edited">(ИЗМЕНЕНО)</span>
					</template>
					<template #input-one>
						<input
							type="text"
							placeholder="Введите город"
							autocomplete="off"
							:class="{ error: currentClinic.errors.city.status }"
							v-model="currentClinic.data.city.body"
							@blur="checkModalInput('city', 'text')"
							@input="currentClinic.data.city.edited = true"
						/>
					</template>
					<template #error-one>
						<span class="error" v-if="currentClinic.errors.city.status">
							{{ currentClinic.errors.city.body }}
						</span>
					</template>
					<!-- УЛИЦА -->
					<template #title-two>
						УЛИЦА* <span v-if="currentClinic.data.street.edited">(ИЗМЕНЕНО)</span>
					</template>
					<template #input-two>
						<input
							type="text"
							placeholder="Введите улицу"
							autocomplete="off"
							:class="{ error: currentClinic.errors.street.status }"
							v-model="currentClinic.data.street.body"
							@blur="checkModalInput('street', 'text')"
							@input="currentClinic.data.street.edited = true"
						/>
					</template>
					<template #error-two>
						<span class="error" v-if="currentClinic.errors.street.status">
							{{ currentClinic.errors.street.body }}
						</span>
					</template>
				</container-input-two>

				<!-- Дом и индекс -->
				<container-input-two
					:fieldset="true"
					:type="modal.type == 'create' ? 'create' : 'default'"
				>
					<template #legend>
						<span>ДОМ И ИНДЕКС</span>
					</template>
					<!-- ДОМ -->
					<template #title-one>
						ДОМ* <span v-if="currentClinic.data.home.edited">(ИЗМЕНЕНО)</span>
					</template>
					<template #input-one>
						<input
							type="text"
							placeholder="Введите дом"
							autocomplete="off"
							:class="{ error: currentClinic.errors.home.status }"
							v-model="currentClinic.data.home.body"
							@blur="checkModalInput('home', 'text')"
							@input="currentClinic.data.home.edited = true"
						/>
					</template>
					<template #error-one>
						<span class="error" v-if="currentClinic.errors.home.status">
							{{ currentClinic.errors.home.body }}
						</span>
					</template>
					<!-- ИНДЕКС -->
					<template #title-two>
						ИНДЕКС* <span v-if="currentClinic.data.index.edited">(ИЗМЕНЕНО)</span>
					</template>
					<template #input-two>
						<input
							type="tel"
							v-mask="'######'"
							placeholder="______"
							autocomplete="off"
							:class="{ error: currentClinic.errors.index.status }"
							v-model="currentClinic.data.index.body"
							@blur="checkModalInput('index', 'number')"
							@input="currentClinic.data.index.edited = true"
						/>
					</template>
					<template #error-two>
						<span class="error" v-if="currentClinic.errors.index.status">
							{{ currentClinic.errors.index.body }}
						</span>
					</template>
				</container-input-two>

				<!-- Географические координаты -->
				<container-input-two
					:fieldset="true"
					:type="modal.type == 'create' ? 'create' : 'default'"
				>
					<template #legend>
						<span>ГЕОГРАФИЧЕСКИЕ КООРДИНАТЫ</span>
					</template>
					<!-- Ширина -->
					<template #title-one>
						ШИРИНА* <span v-if="currentClinic.data.geoWidth.edited">(ИЗМЕНЕНО)</span>
					</template>
					<template #input-one>
						<input
							type="tel"
							autocomplete="off"
							placeholder="00.000000"
							v-mask="'##.######'"
							:class="{ error: currentClinic.errors.geoWidth.status }"
							v-model="currentClinic.data.geoWidth.body"
							@blur="checkModalInput('geoWidth', 'number')"
							@input="currentClinic.data.geoWidth.edited = true"
						/>
					</template>
					<template #error-one>
						<span class="error" v-if="currentClinic.errors.geoWidth.status">
							{{ currentClinic.errors.geoWidth.body }}
						</span>
					</template>
					<!-- Долгота -->
					<template #title-two>
						ДОЛГОТА* <span v-if="currentClinic.data.geoLongitude.edited">(ИЗМЕНЕНО)</span>
					</template>
					<template #input-two>
						<input
							type="tel"
							autocomplete="off"
							placeholder="00.000000"
							v-mask="'##.######'"
							:class="{ error: currentClinic.errors.geoLongitude.status }"
							v-model="currentClinic.data.geoLongitude.body"
							@blur="checkModalInput('geoLongitude', 'number')"
							@input="currentClinic.data.geoLongitude.edited = true"
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
			<block-buttons>
				<button-claim v-if="modal.type == 'create'" @click="addClinic"> Создать </button-claim>
				<button-default v-if="modal.type == 'edit'" @click="updateClinic">
					Обновить
				</button-default>
			</block-buttons>
		</template>
	</admin-modal>

	<info-bar>
		<template v-slot:title>Специалисты</template>
		<template v-slot:addreas>specialists</template>
	</info-bar>

	<block-once :minHeight="200">
		<block-title>
			<template #title>КЛИНИКИ</template>
			<template #buttons>
				<icon-load :width="28" :height="28" v-if="disabled.clinics.save" />
				<icon-save :width="28" :height="28" @click="saveClinicsChanges" v-else />
			</template>
		</block-title>

		<div class="container-clinics" v-if="loading.table">
			<admin-specialists-table
				:array="getClinics"
				@useFilter="filterClinics"
				@touchEditArrValue="editClinic"
				@touchRemoveArrValue="removeClinic"
			/>

			<pagination
				v-if="clinics.length > paginationClinics.elements.range"
				:arrayLength="clinics.length"
				:settings="paginationClinics"
				@changePage="changePageClinics"
			/>
		</div>

		<loader-child
			:isLoading="loading.loader"
			:minHeight="200"
			@loaderChildAfterLeave="loaderChildAfterLeave"
		/>

		<block-buttons>
			<button-default @click="createClinic"> Добавить </button-default>
		</block-buttons>
	</block-once>
</template>

<script>
import AdminModal from "../../../components/includes/admin/AdminModal.vue";

import InfoBar from "../../../components/ui/admin/InfoBar.vue";
import Pagination from "../../../components/ui/admin/pagination/Pagination.vue";

import LoaderChild from "../../../components/includes/LoaderChild.vue";

import ElementInputLabel from "../../../components/ui/admin/elements/ElementInputLabel.vue";
import BlockOnce from "../../../components/ui/admin/blocks/BlockOnce.vue";
import BlockTitle from "../../../components/ui/admin/blocks/BlockTitle.vue";
import BlockButtons from "../../../components/ui/admin/blocks/BlockButtons.vue";

import ContainerInput from "../../../components/ui/admin/containers/ContainerInput.vue";
import ContainerInputOnce from "../../../components/ui/admin/containers/input/ContainerInputOnce.vue";
import ContainerInputTwo from "../../../components/ui/admin/containers/input/ContainerInputTwo.vue";
import ContainerInputThree from "../../../components/ui/admin/containers/input/ContainerInputThree.vue";

import AdminSpecialistsTable from "./AdminSpecialistsTable.vue";

import ButtonDefault from "../../../components/ui/admin/buttons/ButtonDefault.vue";
import ButtonRemove from "../../../components/ui/admin/buttons/ButtonRemove.vue";
import ButtonClaim from "../../../components/ui/admin/buttons/ButtonClaim.vue";

import IconSave from "../../../components/icons/IconSave.vue";
import IconLoad from "../../../components/icons/IconLoad.vue";

import axios from "axios";
import shared from "../../../services/shared";
import sorted from "../../../services/sorted";
import validate from "../../../services/validate";

export default {
	components: {
		AdminModal,
		InfoBar,
		Pagination,
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
		ButtonClaim,
		IconSave,
		IconLoad,
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
			disabled: {
				clinics: {
					save: false,
				},
			},
			paginationClinics: {
				pages: {
					current: 1,
					range: 5,
				},
				elements: {
					range: 10,
				},
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
	computed: {
		getClinics() {
			return [...this.clinics].splice(
				(this.paginationClinics.pages.current - 1) * this.paginationClinics.elements.range,
				this.paginationClinics.elements.range
			);
		},
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
		/* |                    ПАГИНАЦИЯ                      |*/
		/* |___________________________________________________|*/
		/* _____________________________________________________*/
		/* 1. Основные действия                                 */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		/* Изменение текущей страницы */
		changePageClinics(pageNumber) {
			// Проверка на превышение количества страниц
			if (pageNumber > Math.ceil(this.clinics.length / this.paginationClinics.elements.range)) {
				return;
			} else if (pageNumber < 1) {
				return;
			}

			this.paginationClinics.pages.current = pageNumber;
		},
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                 Модальное окно                    |*/
		/* |___________________________________________________|*/
		/* _____________________________________________________*/
		/* 1. Основные действия                                 */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		/* Открытие */
		openModal(type) {
			this.clearModalErrors();

			switch (type) {
				case "create":
					this.modal.type = "create";
					this.modal.status = true;
					this.modal.style.create = true;
					this.modal.style.delete = false;

					this.clearModalData();

					document.body.classList.add("modal-open");
					break;
				case "edit":
					this.modal.type = "edit";
					this.modal.status = true;
					this.modal.style.create = false;
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
		// Проверка поля ввода
		checkModalInput(dataKey, inputType) {
			let errorLog = {};
			switch (inputType) {
				case "text":
					errorLog = validate.checkInputText(this.currentClinic.data[dataKey].body);
					break;
				case "number":
					errorLog = validate.checkInputNumber(this.currentClinic.data[dataKey].body);
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
					// Для индекса
					case "index":
						if (this.checkModalInput(inputKeys[i], "number")) {
							errorCount++;
						}
						break;
					// Для числовых полей
					case "geoWidth":
						if (this.checkModalInput(inputKeys[i], "number")) {
							errorCount++;
						}
						break;
					// Для числовых полей
					case "geoLongitude":
						if (this.checkModalInput(inputKeys[i], "number")) {
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
						sorted.sortById("up", this.clinics);
					}

					if (type == "reverse") {
						sorted.sortById("down", this.clinics);
					}
					break;
				case "name":
					if (type == "default") {
						sorted.sortByName("up", this.clinics);
					}

					if (type == "reverse") {
						sorted.sortByName("down", this.clinics);
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
		/* Обновление элемента */
		updateClinic() {
			if (
				this.checkModalInputsAll([
					"name",
					"city",
					"street",
					"home",
					"index",
					"geoWidth",
					"geoLongitude",
				])
			)
				return;

			try {
				let clinicToUpdate = this.clinics.filter((item) => {
					if (item.id === this.currentClinic.data.id.body) {
						return item;
					}
				});

				for (let key in this.currentClinic.data) {
					clinicToUpdate[0][key] = this.currentClinic.data[key].body;
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
		/* Добавление элемента в массив */
		addClinic() {
			if (
				this.checkModalInputsAll([
					"name",
					"city",
					"street",
					"home",
					"index",
					"geoWidth",
					"geoLongitude",
				])
			)
				return;

			try {
				this.clinics.push({
					id: shared.getMaxId(this.clinics) + 1,
					name: this.currentClinic.data.name.body,
					city: this.currentClinic.data.city.body,
					street: this.currentClinic.data.street.body,
					home: this.currentClinic.data.home.body,
					index: this.currentClinic.data.index.body,
					geoWidth: this.currentClinic.data.geoWidth.body,
					geoLongitude: this.currentClinic.data.geoLongitude.body,
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
		/* Сохранение изменений на сервере */
		saveClinicsChanges() {
			let newArray = [];

			for (let key in this.clinics) {
				newArray.push(Object.assign({}, this.clinics[key]));
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

			this.disabled.clinics.save = true;

			axios({
				method: "post",
				url: `${this.$store.state.axios.urlApi}` + `save-clinics-changes`,
				headers: {
					Accept: "application/json",
					Authorization: `Bearer ${localStorage.getItem("token")}`,
				},
				data: {
					clinics: newArray,
				},
			})
				.then((response) => {
					try {
						shared.updateId(this.clinics, response.data);
						shared.clearDeletes(this.clinics);
						shared.clearFlags(this.clinics);

						this.disabled.clinics.save = false;

						let debbugStory = {
							title: "Успешно!",
							body: "Данные сохранились.",
							type: "Completed",
						};
						this.$store.commit("debuggerState", debbugStory);
					} catch (error) {
						this.disabled.clinics.save = false;

						let debbugStory = {
							title: "Ошибка.",
							body: "После сохранения что-то пошло не так.",
							type: "Error",
						};
						this.$store.commit("debuggerState", debbugStory);
					}
				})
				.catch((error) => {
					this.disabled.clinics.save = false;

					let debbugStory = {
						title: "Ошибка.",
						body: "Данные почему-то не сохранились...",
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
				let debbugStory = {
					title: "Ошибка.",
					body: "При загрузке что-то пошло не так.",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
			})
			.finally(() => {
				sorted.sortByName("up", this.clinics);
			});
	},
};
</script>

<style scoped>
.container-clinics {
	display: flex;
	flex-direction: column;
	gap: 10px;
}
</style>
