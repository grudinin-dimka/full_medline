<template>
	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                  МОДАЛЬНОЕ ОКНО                   |-->
	<!--|___________________________________________________|-->
	<admin-modal ref="modal" @touchCloseModal="closeModal" :modal="modal">
		<template #title>
			{{ modal.title }}
		</template>
		<template #body>
			<container-input-once>
				<template #title>
					<span>НАЗВАНИЕ*</span>
					<span v-if="currentSpecialization.data.name.edited"> (ИЗМЕНЕНО) </span>
				</template>
				<template #input>
					<input
						type="text"
						ref="inputName"
						placeholder="Название специализации"
						v-model="currentSpecialization.data.name.body"
						:class="{
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
			</container-input-once>
		</template>
		<template #footer>
			<block-buttons>
				<button-default @click="addSpecialization" v-if="modal.type == 'create'">
					Создать
				</button-default>
				<button-default @click="updateSpecialization" v-if="modal.type == 'edit'">
					Обновить
				</button-default>
			</block-buttons>
		</template>
	</admin-modal>

	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                   СПЕЦИАЛИЗАЦИИ                   |-->
	<!--|___________________________________________________|-->
	<info-bar>
		<template v-slot:title>Специалисты</template>
		<template v-slot:addreas>specialists</template>
	</info-bar>

	<block-once :minHeight="100">
		<template #title>СПЕЦИАЛИЗАЦИИ</template>

		<template #options>
			<icon-load :width="28" :height="28" v-if="disabled.specializations.save" />
			<icon-save :width="28" :height="28" @click="saveSpecializationsChanges" v-else />
		</template>

		<template #body>
			<BaseTable
				v-if="loading.sections.specializations"
				:table="table"
				@create="createSpecialization"
				@edite="editSpecialization"
				@delete="removeSpecialization"
			/>

			<loader-child
				:isLoading="loading.loader.specializations"
				:minHeight="200"
				@loaderChildAfterLeave="loaderChildAfterLeave"
			/>
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
import BlockButtons from "../../../components/ui/admin/blocks/BlockButtons.vue";

import ContainerInputOnce from "../../../components/ui/admin/containers/input/ContainerInputOnce.vue";

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
		BaseTable,
		LoaderChild,

		ElementInputLabel,
		ContainerInputOnce,

		BlockOnce,
		BlockButtons,

		ButtonDefault,
		ButtonClaim,
		ButtonRemove,

		IconSave,
		IconLoad,
		axios,
	},
	data() {
		return {
			/* Модальное окно */
			modal: {
				title: "СПЕЦИАЛИЗАЦИЯ",
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
				loader: {
					specializations: true,
				},
				sections: {
					specializations: false,
				},
			},

			/* Кнопки */
			disabled: {
				specializations: {
					save: false,
				},
			},

			/* Форма */
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

			/* Таблица */
			table: {
				// Настройки
				options: {
					create: true,
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
		/* После скрытия загрузчика */
		loaderChildAfterLeave() {
			this.loading.sections.specializations = true;
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
					{
						this.modal.type = "create";
						this.modal.status = true;

						this.clearModalData();
					}
					document.body.classList.add("modal-open");
					break;
				case "edit":
					{
						this.modal.type = "edit";
						this.modal.status = true;
					}
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
		// Проверка поля имени
		checkModalInput(dataKey, inputType) {
			let errorLog = {};
			switch (inputType) {
				case "text":
					errorLog = validate.checkInputText(this.currentSpecialization.data[dataKey].body);
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
		/* Основные действия                                    */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		/* Открытие специализации для редактирования */
		editSpecialization(specialization) {
			this.clearModalData();

			for (let key in this.currentSpecialization.data) {
				this.currentSpecialization.data[key].body = specialization[key];
			}

			this.openModal("edit");
		},
		/* Открытие специализации для создания */
		createSpecialization() {
			this.openModal("create");
		},
		/* _____________________________________________________*/
		/* Сохранение, обновление и удаление                    */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		/* Добавление специализации */
		addSpecialization() {
			if (this.checkModalInputsAll(["name"])) return;
			try {
				if (
					this.table.body.some(
						(item) => item.name === this.currentSpecialization.data.name.body
					)
				) {
					this.$store.commit("addDebugger", {
						title: "Ошибка.",
						body: "Такая специализация уже существует.",
						type: "error",
					});

					return;
				}

				this.table.body.push({
					id: shared.getMaxId(this.table.body) + 1,
					name: this.currentSpecialization.data.name.body,
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
		/* Пометка на удаление */
		removeSpecialization(specialization) {
			specialization.delete = !specialization.delete;
		},
		/* Обновление содержимого */
		updateSpecialization() {
			if (this.checkModalInputsAll(["name"])) return;
			try {
				let specializationToUpdate = this.table.body.find((item) => {
					if (item.id === this.currentSpecialization.data.id.body) {
						return item;
					}
				});

				specializationToUpdate.name = this.currentSpecialization.data.name.body;
				
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
		saveSpecializationsChanges() {
			this.disabled.specializations.save = true;

			axios({
				method: "post",
				url: `${this.$store.getters.urlApi}` + `save-specializations-changes`,
				headers: {
					Accept: "application/json",
					Authorization: `Bearer ${localStorage.getItem("token")}`,
				},
				data: {
					specializations: this.table.body,
				},
			})
				.then((response) => {
					if (response.data.status) {
						shared.updateId(this.table.body, response.data.data);
						shared.clearDeletes(this.table.body);
						shared.clearFlags(this.table.body);

						this.$store.commit("addDebugger", {
							title: "Успешно!",
							body: response.data.message,
							type: "completed",
						});
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
					this.disabled.specializations.save = false;
				});
		},
	},
	mounted() {
		axios({
			method: "get",
			headers: {
				Accept: "application/json",
			},
			url: `${this.$store.getters.urlApi}` + `get-specializations-all`,
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
				this.loading.loader.specializations = false;
			});
	},
};
</script>

<style scoped>
.container-specializations {
	display: flex;
	flex-direction: column;
	gap: 10px;
}
</style>
