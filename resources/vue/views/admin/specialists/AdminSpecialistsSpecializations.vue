<template>
	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                  МОДАЛЬНОЕ ОКНО                   |-->
	<!--|___________________________________________________|-->
	<Modal ref="modal" :settings="modal">
		<template #title>
			{{ modal.values.title }}
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
			<template v-if="modal.values.look == 'create'">
				<button-default @click="addSpecialization">
					<Icon :name="'add'" :fill="'white'" :width="'23px'" :height="'23px'" />
					Добавить
				</button-default>
			</template>

			<template v-if="modal.values.look == 'default'">
				<button-default @click="updateSpecialization">
					<Icon :name="'edit'" :fill="'white'" :width="'28px'" :height="'28px'" />
					Обновить
				</button-default>
			</template>
		</template>
	</Modal>

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
				<Icon :name="'save'" :fill="'var(--primary-color)'" :width="'28px'" :height="'28px'" />
				Сохранить
			</button-default>
		</template>

		<template #body>
			<BaseTable
				v-if="loading.sections.specializations"
				:table="table"
				@create="openModalСreate"
				@edite="openModalEdite"
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
import Modal from "../../../components/modules/modal/Modal.vue";
import InfoBar from "../../../components/ui/admin/InfoBar.vue";
import BaseTable from "../../../components/modules/table/BaseTable.vue";
import LoaderChild from "../../../components/modules/LoaderChild.vue";
import BlockOnce from "../../../components/ui/admin/blocks/BlockOnce.vue";

import ContainerInputOnce from "../../../components/ui/admin/containers/input/ContainerInputOnce.vue";

import ButtonDefault from "../../../components/ui/admin/buttons/ButtonDefault.vue";
import ButtonRemove from "../../../components/ui/admin/buttons/ButtonRemove.vue";
import ButtonClaim from "../../../components/ui/admin/buttons/ButtonClaim.vue";

import Icon from "../../../components/modules/icon/Icon.vue";

import axios from "axios";
import shared from "../../../services/shared";
import sorted from "../../../services/sorted";
import validate from "../../../services/validate";

export default {
	components: {
		Modal,

		InfoBar,
		BaseTable,
		LoaderChild,
		BlockOnce,

		ContainerInputOnce,

		ButtonDefault,
		ButtonClaim,
		ButtonRemove,

		Icon,

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
				thin: false,
				clamped: false,
				values: {
					title: "",
					look: "default",
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
		/* |                 МОДАЛЬНОЕ ОКНО                    |*/
		/* |___________________________________________________|*/
		/* Открытие модального окна */
		openModal(name, title, look) {
			this[name].values.title = title;
			this[name].values.look = look;

			this.$refs[name].open();
		},

		/* Открытие модального окна для добавления */
		openModalСreate() {
			shared.clearObjectFull(this.currentSpecialization);

			this.openModal("modal", "СПЕЦИАЛИЗАЦИЯ", "create");
		},

		/* Открытие модального окна для добавления */
		openModalEdite(value) {
			shared.clearObjectFull(this.currentSpecialization);
			shared.setData(value, this.currentSpecialization);

			this.openModal("modal", "СПЕЦИАЛИЗАЦИЯ", "default");
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                 Специализации                     |*/
		/* |___________________________________________________|*/
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

				this.$refs.modal.close();
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

				this.$refs.modal.close();
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
