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

	<block-once :minHeight="100">
		<template #title>КЛИНИКИ</template>

		<template #options>
			<icon-load :width="28" :height="28" v-if="disabled.clinics.save" />
			<icon-save :width="28" :height="28" @click="saveClinicsChanges" v-else />
		</template>

		<template #body>
			<BaseTable
				v-if="loading.sections.clinics"
				:table="table"
				@create="console.log('create')"
				@edite="editClinic"
				@delete="removeClinic"
			/>

			<loader-child
				:isLoading="loading.loader.clinics"
				:minHeight="200"
				@loaderChildAfterLeave="loaderChildAfterLeave"
			/>
		</template>

		<template #buttons>
			<button-default @click="createClinic"> Добавить </button-default>
		</template>
	</block-once>
</template>

<script>
import AdminModal from "../../../components/includes/admin/AdminModal.vue";

import InfoBar from "../../../components/ui/admin/InfoBar.vue";
import BaseTable from "../../../components/modules/table/BaseTable.vue";

import LoaderChild from "../../../components/modules/LoaderChild.vue";

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
import validate from "../../../services/validate";

export default {
	components: {
		AdminModal,
		InfoBar,
		BaseTable,

		LoaderChild,
		ElementInputLabel,

		BlockOnce,
		BlockTitle,
		BlockButtons,

		ContainerInput,
		ContainerInputOnce,
		ContainerInputTwo,
		ContainerInputThree,

		ButtonDefault,
		ButtonRemove,
		ButtonClaim,

		IconSave,
		IconLoad,

		axios,
	},
	data() {
		return {
			/* Загрузчик */
			loading: {
				loader: {
					clinics: true,
				},
				sections: {
					clinics: false,
				},
			},

			/* Кнопки */
			disabled: {
				clinics: {
					save: false,
				},
			},

			/* Модальное окно */
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

			/* Текущая клиника */
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

			/* Таблица */
			table: {
				// Настройки
				options: {
					create: false,
					delete: true,
					update: true,
					report: false,
				},

				// Колонки
				head: [
					{ name: "id", text: "ID", columnType: "id" },
					{
						name: "name",
						text: "Название",
						columnType: "default",
						columnSize: "auto",
					},
					{
						name: "city",
						text: "Город",
						columnType: "default",
						columnSize: "auto",
					},
					{
						name: "street",
						text: "Улица",
						columnType: "default",
						columnSize: "auto",
					},
					{
						name: "home",
						text: "Дом",
						columnType: "default",
						columnSize: "auto",
					},
				],

				// Элементы
				body: [],
			},
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
						this.$store.commit("addDebugger", {
							title: "Ошибка.",
							body: "Низвестный тип открытия модального окна.",
							type: "error",
						});
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
		/* Основные действия                                    */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		/* Открытие специализации для редактирования */
		editClinic(clinic) {
			this.clearModalData();

			for (let key in this.currentClinic.data) {
				this.currentClinic.data[key].body = clinic[key];
			}

			this.openModal("edit");
		},
		/* Открытие специализации для создания */
		createClinic() {
			this.openModal("create");
		},
		/* _____________________________________________________*/
		/* Сохранение, обновление и удаление                    */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		/* Пометка на удаление */
		removeClinic(clinic) {
			clinic.delete = !clinic.delete;
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
				let clinicToUpdate = this.table.body.find((item) => {
					if (item.id === this.currentClinic.data.id.body) {
						return item;
					}
				});

				for (let key in this.currentClinic.data) {
					clinicToUpdate[key] = this.currentClinic.data[key].body;
				}

				this.closeModal();
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
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
				this.table.body.push({
					id: shared.getMaxId(this.table.body) + 1,
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
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			}
		},

		/* Сохранение изменений на сервере */
		saveClinicsChanges() {
			this.disabled.clinics.save = true;

			axios({
				method: "post",
				url: `${this.$store.getters.urlApi}` + `save-clinics-changes`,
				headers: {
					Accept: "application/json",
					Authorization: `Bearer ${localStorage.getItem("token")}`,
				},
				data: {
					clinics: this.table.body,
				},
			})
				.then((response) => {
					if (response.data.status) {
						try {
							shared.updateId(this.table.body, response.data.data);
							shared.clearDeletes(this.table.body);
							shared.clearFlags(this.table.body);

							this.$store.commit("addDebugger", {
								title: "Успешно!",
								body: response.data.message,
								type: "completed",
							});
						} catch (error) {
							this.$store.commit("addDebugger", {
								title: "Ошибка.",
								body: error,
								type: "error",
							});
						}
					} else {
						this.$store.commit("addDebugger", {
							title: "Ошибка.",
							body: response.data.message,
							type: "error",
						});
					}
				})
				.catch((error) => {
					this.$store.commit("addDebugger", {
						title: "Ошибка.",
						body: error,
						type: "error",
					});
				})
				.finally(() => {
					this.disabled.clinics.save = false;
				});
		},
	},
	mounted() {
		axios({
			method: "get",
			headers: {
				Accept: "application/json",
			},
			url: `${this.$store.getters.urlApi}` + `get-clinics-all`,
		})
			.then((response) => {
				if (response.data.status) {
					this.table.body = response.data.data;

					for (let key in this.table.body) {
						this.table.body[key].create = false;
						this.table.body[key].delete = false;
					}
				} else {
					this.$store.commit("addDebugger", {
						title: "Ошибка.",
						body: response.data.message,
						type: "error",
					});
				}
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

<style scoped>
.container-clinics {
	display: flex;
	flex-direction: column;
	gap: 10px;
}
</style>
