<template>
	<block-once :minHeight="100">
		<template #title> СТАТИСТИКА </template>
		<template #body>
			<BaseTable
				v-if="loading.sections.statistics"
				:table="table"
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

			options: {
				chart: {
					id: "vuechart-example",
				},
				xaxis: {
					categories: ["30.04", "01.05", "02.05", "03.05", "04.05", "05.05", "06.05"],
				},
			},
			series: [
				{
					name: "series-1",
					data: [30, 40, 45, 50, 49, 60, 70, 91],
				},
			],

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
						columnSize: "200px",
					},
					{
						name: "ip",
						text: "ip",
						columnType: "default",
						columnSize: "200px",
					},
					{
						name: "user_agent",
						text: "Браузер",
						columnType: "default",
						columnSize: "200px",
					},
					{
						name: "type",
						text: "Тип события",
						columnType: "default",
						columnSize: "300px",
					},
					{
						name: "meta",
						text: "Описание",
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
