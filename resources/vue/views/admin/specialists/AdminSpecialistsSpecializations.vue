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
				<template #input>
					<input
						type="text"
						ref="inputName"
						placeholder="Название специализации"
						v-model="currentSpecialization.data.name.value"
						:class="{
							error: currentSpecialization.errors.name.status,
						}"
						@input="currentSpecialization.data.name.edited = true"
					/>
				</template>
				<template #error>
					<span class="error" v-if="currentSpecialization.errors.name.status">
						{{ this.currentSpecialization.errors.name.message }}
					</span>
				</template>
			</container-input-once>
		</template>
		<template #footer>
			<block-buttons>
				<button-default @click="addSpecialization" v-if="modal.type == 'create'">
					<icon-add :width="23" :height="23" :look="'white'"/>
					Добавить
				</button-default>
				<button-default @click="updateSpecialization" v-if="modal.type == 'edit'">
					<icon-edit :width="28" :height="28" :look="'white'"/>
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
			<button-default
				@click.prevent="saveSpecializationsChanges"
				:disabled="disabled.specializations.save"
				:look="'white'"
			>
				<icon-save :width="28" :height="28" />
				Сохранить
			</button-default>
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
import IconAdd from "../../../components/icons/IconAdd.vue";
import IconEdit from "../../../components/icons/IconEdit.vue";
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
		IconAdd,
		IconEdit,
		IconLoad,
		axios,
	},
	data() {
		return {
			/* Кнопки */
			disabled: {
				specializations: {
					save: false,
				},
			},

			/* Загрузчик */
			loading: {
				loader: {
					specializations: true,
				},
				sections: {
					specializations: false,
				},
			},

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

			/* Форма */
			currentSpecialization: {
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
						value: "",
						edited: false,
					},
					name: {
						value: "",
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
			switch (type) {
				case "create":
					{
						this.modal.type = "create";
						this.modal.status = true;

						shared.clearObjectFull(this.currentSpecialization);
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

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                 Специализации                     |*/
		/* |___________________________________________________|*/
		/* _____________________________________________________*/
		/* Основные действия                                    */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		/* Открытие специализации для редактирования */
		editSpecialization(specialization) {
			shared.clearObjectFull(this.currentSpecialization);
			shared.setData(specialization, this.currentSpecialization);

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
			if (
				validate.checkInputsAll(this.currentSpecialization, [
					{
						key: "name",
						type: "text",
					},
				])
			)
				return;

			try {
				if (
					this.table.body.some(
						(item) => item.name === this.currentSpecialization.data.name.value
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
					name: this.currentSpecialization.data.name.value,
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
			if (
				validate.checkInputsAll(this.currentSpecialization, [
					{
						key: "name",
						type: "text",
					},
				])
			)
				return;

			try {
				let specializationToUpdate = this.table.body.find((item) => {
					if (item.id === this.currentSpecialization.data.id.value) {
						return item;
					}
				});

				specializationToUpdate.name = this.currentSpecialization.data.name.value;

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
