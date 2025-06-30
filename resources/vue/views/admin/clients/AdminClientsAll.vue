<template>
	<info-bar>
		<template #title>Клиенты</template>
		<template #addreas>clients</template>
	</info-bar>

	<block-once :minHeight="200">
		<template #title>КЛИЕНТЫ</template>

		<template #options>
			<button-default
				@click.prevent="updateClientsFromServe"
				:disabled="disabled.clients.save"
				:look="'white'"
			>
				<Icon :name="'save'" :fill="'var(--primary-color)'" :width="'28px'" :height="'28px'" />
				Сохранить
			</button-default>
		</template>

		<template #body>
			<BaseTable v-if="loading.sections.clients" :table="table" />

			<loader-child
				:isLoading="loading.loader.clients"
				:minHeight="200"
				@loaderChildAfterLeave="loaderChildAfterLeave"
			/>
		</template>
	</block-once>
</template>

<script>
import LoaderChild from "../../../components/modules/LoaderChild.vue";
import InfoBar from "../../../components/ui/admin/InfoBar.vue";
import BlockOnce from "../../../components/ui/admin/blocks/BlockOnce.vue";
import BlockTitle from "../../../components/ui/admin/blocks/BlockTitle.vue";

import BaseTable from "../../../components/modules/table/BaseTable.vue";

import ButtonDefault from "../../../components/ui/admin/buttons/ButtonDefault.vue";

import Icon from "../../../components/modules/icon/Icon.vue";

import api from "../../../services/api";

export default {
	components: {
		LoaderChild,
		BlockOnce,
		BlockTitle,

		InfoBar,

		BaseTable,

		ButtonDefault,

		Icon,
	},
	data() {
		return {
			/* Загрузчик */
			loading: {
				loader: {
					clients: true,
				},
				sections: {
					clients: false,
				},
			},

			/* Кнопки */
			disabled: {
				clients: {
					save: false,
				},
			},

			/* Таблица */
			table: {
				// Настройки
				options: {
					create: false,
					delete: false,
					update: false,
					report: false,
				},

				// Колонки
				head: [
					{ name: "id", text: "ID", columnType: "id" },
					{
						name: "name",
						text: "ФИО",
						columnType: "default",
						columnSize: "300px",
					},
					{
						name: "snils",
						text: "СНИЛС",
						columnType: "default",
						columnSize: "100px",
					},
					{
						name: "barcode",
						text: "Баркод",
						columnType: "default",
						columnSize: "100px",
					},
					{
						name: "points",
						text: "Баллы",
						columnType: "default",
						columnSize: "100px",
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
			for (let key in this.loading.loader) {
				if (!this.loading.loader[key]) {
					this.loading.sections[key] = true;
				}
			}
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |             Обновление расписания                 |*/
		/* |___________________________________________________|*/
		updateClientsFromServe() {
			this.disabled.clients.save = true;

			api({
				method: "post",
				url: this.$store.getters.urlApi + `save-clieint-points`,
			})
				.then((response) => {
					this.table.body = response.data.result;
				})
				.finally(() => {
					this.disabled.clients.save = false;
				});
		},
	},
	mounted() {
		api({
			method: "get",
			url: this.$store.getters.urlApi + `clieints`,
		})
			.then((response) => {
				if (!response) return;

				this.table.body = response.data.result;
			})
			.catch((error) => {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			})
			.finally(() => {
				this.loading.loader.clients = false;
			});
	},
};
</script>

<style scoped></style>
