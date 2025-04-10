<template>
	<block-once :minHeight="100">
		<template #title> СТАТИСТИКА </template>
		<template #body>
			<BaseTable
				v-if="loading.sections.statistics"
				:table="table"
				icon="devices"
				@create="console.log('create')"
				@edite="console.log('edite')"
				@delete="console.log('delete')"
			/>

			<LoaderChild
				:isLoading="loading.loader.statistics"
				:minHeight="300"
				@loaderChildAfterLeave="loaderChildAfterLeave"
			></LoaderChild>
		</template>
	</block-once>
</template>

<script>
import BlockOnce from "../../../components/ui/admin/blocks/BlockOnce.vue";
import LoaderChild from "../../../components/modules/LoaderChild.vue";

import BaseTable from "../../../components/modules/table/BaseTable.vue";

import axios from "axios";

export default {
	components: {
		BlockOnce,
		LoaderChild,

		BaseTable,
	},
	data() {
		return {
			loading: {
				loader: {
					statistics: true,
				},
				sections: {
					statistics: false,
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
						name: "created",
						text: "Дата",
						columnType: "time",
						columnSize: "auto",
					},
					{
						name: "ip",
						text: "ip",
						columnType: "default",
						columnSize: "auto",
					},
					{
						name: "meta",
						text: "Описание",
						columnType: "default",
						columnSize: "auto",
					},
					{
						name: "type",
						text: "Тип события",
						columnType: "default",
						columnSize: "auto",
					},
					{
						name: "user_agent",
						text: "Агент бразуера",
						columnType: "default",
						columnSize: "auto",
					},
				],

				// Элементы
				body: [],
			},

			statistics: [],
		};
	},
	methods: {
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                   ЗАГРУЗЧИК                       |*/
		/* |___________________________________________________|*/
		/* После скрытия элементы */
		loaderChildAfterLeave() {
			this.loading.sections.statistics = true;
		},
	},
	mounted() {
		axios({
			method: "get",
			url: `${this.$store.getters.urlApi}` + `get-tracking-statistics`,
			headers: {
				Accept: "application/json",
				Authorization: `Bearer ${localStorage.getItem("token")}`,
			},
		})
			.then((response) => {
				console.log(response.data);

				if (response.data.status) {
					this.table.body = response.data.data;
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
				this.loading.loader.statistics = false;
			});
	},
};
</script>

<style scoped></style>
