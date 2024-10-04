<template>
	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                  МОДАЛЬНОЕ ОКНО                   |-->
	<!--|___________________________________________________|-->
	<admin-modal ref="modal" @touchCloseModal="closeModal" :modal="modal">
		<template #title>
			<span v-if="modal.type == 'create'" class="create">КЛИНИКА (СОЗДАНИЕ)*</span>
			<span v-if="modal.type == 'edit'">КЛИНИКА (РЕДАКТИРОВАНИЕ)</span>
		</template>
		<template #body>
			<container-input>
				<!-- Название и другое -->
				<container-input-two
					:fieldset="true"
					:type="modal.type == 'create' ? 'create' : 'default'"
				>
					<template #legend>
						<span :class="{ create: modal.type == 'create' }">НАЗВАНИЕ И ДРУГОЕ</span>
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
						<span :class="{ create: modal.type == 'create' }">ГОРОД И УЛИЦА</span>
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
						<span :class="{ create: modal.type == 'create' }">ДОМ И ИНДЕКС</span>
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
						<span :class="{ create: modal.type == 'create' }">ГЕОГРАФИЧЕСКИЕ КООРДИНАТЫ</span>
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

	<block-once>
		<block-title>
			<template #title>Клиники</template>
			<template #buttons>
				<icon-save :width="28" :height="28" @click="saveClinicsChanges" />
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
			<button-default @click="createClinic"> Добавить </button-default>
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

import ContainerInput from "../../../components/ui/admin/containers/ContainerInput.vue";
import ContainerInputOnce from "../../../components/ui/admin/containers/input/ContainerInputOnce.vue";
import ContainerInputTwo from "../../../components/ui/admin/containers/input/ContainerInputTwo.vue";
import ContainerInputThree from "../../../components/ui/admin/containers/input/ContainerInputThree.vue";

import AdminSpecialistsTable from "./AdminSpecialistsTable.vue";

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
					if (this.currentClinic.data.create.body) {
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
		// Проверка введенного текстового значения
		checkInputNumber(value) {
			/* Проверка на пустую строку */
			if (value === "" || value === null) {
				return {
					status: true,
					message: "Поле не может быть пустым.",
				};
			}

			/* Проверка на соответствие типу Number */
			if (Number.isNaN(Number(value))) {
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
			// Объявляем объект Intl.Collator, который обеспечивает сравнение строк с учётом языка.
			const collator = new Intl.Collator("ru");

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
							return collator.compare(a.name, b.name);
						});
					}

					if (type == "reverse") {
						this.clinics.reverse((a, b) => {
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
				// Поиск максимального id
				let maxId = 0;
				for (let key in this.clinics) {
					if (this.clinics[key].id > maxId) {
						maxId = this.clinics[key].id;
					}
				}

				this.clinics.push({
					id: maxId + 1,
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
						// Обновление id добавленных элементов на данные из бд
						for (let key in response.data) {
							let clinic = this.clinics.filter((item) => {
								if (item.id === response.data[key].old) {
									return item;
								}
							});
							clinic[0].id = response.data[key].new;
						}

						// Получения нового массива клиник, помеченных на удаление
						let res = this.clinics.filter((item) => {
							if (item.delete == true) {
								return Object.assign({}, item);
							}
						});

						// Повторять, пока не будут удалены все элементы, помеченные на удаление
						while (res.length > 0) {
							/* Получение индекса элемента, помеченного на удаление из массива специалистов */
							this.clinics.splice(this.clinics.indexOf(res[0]), 1);
							/* Обновление списка с элементами, помеченными на удаление */
							res = this.clinics.filter((item) => {
								if (item.delete == true) {
									return Object.assign({}, item);
								}
							});
						}

						// Сброс флагов добавления и удаления
						for (let key in this.clinics) {
							this.clinics[key].create = false;
							this.clinics[key].delete = false;
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
				console.log(error);
			});
	},
};
</script>

<style scoped></style>
