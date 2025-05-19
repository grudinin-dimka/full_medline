<template>
	<info-bar>
		<template v-slot:title>Специалисты</template>
		<template v-slot:addreas>specialists</template>
	</info-bar>

	<block-once :minHeight="100" :hide="true">
		<template #title>СПИСОК ВРАЧЕЙ</template>

		<template #options>
			<button-default @click.prevent="saveSpecialistChanges" :disabled="disabled.specialists.save" :look="'white'">
				<Icon :name="'save'" :fill="'var(--primary-color)'" :width="'28px'" :height="'28px'" />
				Сохранить
			</button-default>
		</template>

		<template #body>
			<BaseTable
				v-if="loading.sections.specialists"
				:table="table"
				@create="$router.push('especialists/new')"
				@edite="openSpecialist"
				@delete="removeSpecialist"
			>
				<template v-slot:hideSpecialist="{ row }">
					<div class="table__hide" v-if="row.hide" @click="hideSpecialist(row)">Да</div>
					<div class="table__hide" v-else @click="hideSpecialist(row)">Нет</div>
				</template>
			</BaseTable>

			<loader-child
				:isLoading="loading.loader.specialists"
				:minHeight="300"
				@loaderChildAfterLeave="loaderChildAfterLeave"
			/>
		</template>
	</block-once>
</template>

<script>
import InfoBar from "../../../components/ui/admin/InfoBar.vue";
import BaseTable from "../../../components/modules/table/BaseTable.vue";
import LoaderChild from "../../../components/modules/LoaderChild.vue";

import ElementInputLabel from "../../../components/ui/admin/elements/ElementInputLabel.vue";
import BlockOnce from "../../../components/ui/admin/blocks/BlockOnce.vue";
import BlockButtons from "../../../components/ui/admin/blocks/BlockButtons.vue";
import SpanError from "../../../components/ui/admin/SpanError.vue";

import ButtonDefault from "../../../components/ui/admin/buttons/ButtonDefault.vue";
import ButtonRemove from "../../../components/ui/admin/buttons/ButtonRemove.vue";

import Icon from "../../../components/modules/icon/Icon.vue";

import axios from "axios";
import sorted from "../../../services/sorted";
import shared from "../../../services/shared";

import { RouterView, RouterLink } from "vue-router";

export default {
	components: {
		InfoBar,
		BaseTable,
		LoaderChild,
		ElementInputLabel,

		BlockOnce,
		BlockButtons,

		SpanError,
		ButtonDefault,
		ButtonRemove,

		Icon,

		axios,
		sorted,
		shared,

		RouterView,
		RouterLink,
	},
	data() {
		return {
			/* Загрузчик */
			loading: {
				loader: {
					specialists: true,
				},
				sections: {
					specialists: false,
				},
			},

			/* Кнопки */
			disabled: {
				specialists: {
					save: false,
				},
			},

			paginationSpecialists: {
				pages: {
					current: 1,
					range: 5,
				},
				elements: {
					range: 10,
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
						columnSize: "600px",
					},
					{
						name: "specializations",
						text: "Специализации",
						columnType: "default",
						columnSize: "auto",
					},
					{
						name: "hideSpecialist",
						text: "Скрытие",
						columnType: "hideSpecialist",
						columnSize: "100px",
						columnJustify: "center",
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
			this.loading.sections.specialists = true;
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                  Специалисты                      |*/
		/* |___________________________________________________|*/
		/* _____________________________________________________*/
		/* Сохранение, обновление, удаление                     */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		openSpecialist(specialist) {
			this.$router.push({ name: "especialist-profile", params: { id: specialist.id } });
		},

		/* Скрытие выбранного доктора */
		hideSpecialist(specialist) {
			specialist.hide = !specialist.hide;
		},

		/* Удаление выбранного доктора */
		removeSpecialist(specialist) {
			specialist.delete = !specialist.delete;
		},

		/* Сохранение изменений */
		saveSpecialistChanges() {
			this.disabled.specialists.save = true;

			// Получение массива докторов с сервера
			axios({
				method: "post",
				url: `${this.$store.getters.urlApi}` + `save-specialists-changes`,
				headers: {
					Accept: "application/json",
					Authorization: `Bearer ${localStorage.getItem("token")}`,
				},
				data: {
					specialists: this.table.body,
				},
			})
				.then((response) => {
					if (response.data.status) {
						shared.clearDeletes(this.table.body);

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
					this.disabled.specialists.save = false;
				});
		},
	},
	mounted() {
		// Получение массива докторов с сервера
		axios({
			method: "get",
			headers: {
				Accept: "application/json",
			},
			url: `${this.$store.getters.urlApi}` + `get-specialists-short`,
		})
			.then((response) => {
				if (response.data.status) {
					this.table.body = response.data.data;

					for (let key in this.table.body) {
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
				this.loading.loader.specialists = false;
			});
	},
};
</script>

<style scoped>
.table__hide {
	user-select: none;
	cursor: pointer;
}

.table__hide:hover {
	color: var(--primary-color);
}
</style>
