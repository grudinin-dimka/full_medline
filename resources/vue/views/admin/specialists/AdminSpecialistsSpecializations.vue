<template>
	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                  МОДАЛЬНОЕ ОКНО                   |-->
	<!--|___________________________________________________|-->
	<VueModal ref="modal" :settings="modal">
		<template #title>
			{{ modal.values.title }}
		</template>

		<template #body>
			<VueValues
				v-model.trim="currentSpecialization.data.name.value"
				:type="'text'"
				:placeholder="'Введите название'"
				:error="currentSpecialization.errors.name.status"
			>
				<template #label>
					<VueIcon :name="'Title'" :fill="'var(--primary-color)'" :width="'20px'" :height="'20px'" />
					НАЗВАНИЕ
				</template>
				<template #error>
					{{ currentSpecialization.errors.name.message }}
				</template>
			</VueValues>
		</template>

		<template #footer>
			<template v-if="modal.values.look == 'create'">
				<VueButton @click="addSpecialization">
					<VueIcon :name="'Add'" :fill="'white'" :width="'26px'" :height="'26px'" />
					Добавить
				</VueButton>
			</template>

			<template v-if="modal.values.look == 'default'">
				<VueButton @click="updateSpecialization">
					<VueIcon :name="'Edit'" :fill="'white'" :width="'28px'" :height="'28px'" />
					Обновить
				</VueButton>
			</template>
		</template>
	</VueModal>

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
			<VueButton @click.prevent="saveSpecializationsChanges" :disabled="disabled.specializations.save" :look="'inverse'">
				<VueIcon :name="'Save'" :fill="'var(--primary-color)'" :width="'28px'" :height="'28px'" />
				Сохранить
			</VueButton>

			<VueButton
				:look="'inverse'"
				@click="
					$store.commit('openWiki', {
						title: 'Специализации',
						component: 'SpecialistsSpecializations',
					})
				"
				:minWidth="'30px'"
			>
				<VueIcon :name="'Info'" :fill="'var(--primary-color)'" :width="'30px'" :height="'30px'" />
			</VueButton>
		</template>

		<template #body>
			<VueTable
				v-if="loading.sections.specializations"
				:table="table"
				@create="openModalСreate()"
				@edite="openModalEdite"
				@delete="removeSpecialization"
			/>

			<VueLoader
				:isLoading="loading.loader.specializations"
				:isChild="true"
				:minHeight="300"
				@afterLeave="loaderChildAfterLeave"
			/>
		</template>
	</block-once>
</template>

<script>
import VueInput from "../../../components/modules/input/VueInput.vue";

import InfoBar from "../../../components/ui/admin/InfoBar.vue";
import BlockOnce from "../../../components/ui/admin/blocks/BlockOnce.vue";

import api from "../../../mixin/api";
import shared from "../../../services/shared";
import sorted from "../../../services/sorted";
import validate from "../../../services/validate";

export default {
	components: {
		VueInput,

		InfoBar,
		BlockOnce,
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
				touch: true,
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
					{
						name: "id",
						title: "ID",
						type: "id",
						hide: true,
					},
					{
						name: "name",
						title: "Название",
						type: "string",
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
				if (this.table.body.some((item) => item.name === this.currentSpecialization.data.name.value)) {
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

			api({
				method: "post",
				url: this.$store.getters.urlApi + `save-specializations-changes`,
				headers: {
					Accept: "application/json",
				},
				data: {
					specializations: this.table.body,
				},
			})
				.then((response) => {
					if (!response) return;

					shared.updateId(this.table.body, response.data.result);
					shared.clearDeletes(this.table.body);
					shared.clearFlags(this.table.body);
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
		api({
			method: "get",
			url: this.$store.getters.urlApi + `get-specializations-all`,
		})
			.then((response) => {
				if (!response) return;

				this.table.body = response.data.result;

				for (let key in this.table.body) {
					this.table.body[key].create = false;
					this.table.body[key].delete = false;
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
