<template>
	<block-once :minHeight="100">
		<template #title> СТАТИСТИКА </template>
		<template #body>
			<VueTable
				v-if="loading.sections.statistics"
				:table="table"
				@create="console.log('create')"
				@edite="console.log('edite')"
				@delete="console.log('delete')"
			/>

			<VueLoader
				:isLoading="loading.loader.statistics"
				:isChild="true"
				:minHeight="300"
				@afterLeave="loaderChildAfterLeave"
			/>
		</template>
	</block-once>
</template>

<script>
import BlockOnce from "../../../components/ui/admin/blocks/BlockOnce.vue";

import api from "../../../mixin/api";

export default {
	components: {
		BlockOnce,
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
					{
						name: "id",
						title: "ID",
						type: "id",
						hide: true,
					},
					{
						name: "created",
						title: "Дата",
						type: "time",
						options: {
							column: {
								style: {
									width: "200px",
								},
							},
							data: {
								style: {
									width: "auto",
								},
							},
						},
					},
					{
						name: "ip",
						title: "ip",
						type: "string",
						options: {
							column: {
								style: {
									width: "150px",
								},
							},
							data: {
								style: {
									width: "auto",
								},
							},
						},
					},
					{
						name: "user_agent",
						title: "Браузер",
						type: "string",
						options: {
							column: {
								style: {
									width: "175px",
								},
							},
							data: {
								style: {
									width: "auto",
								},
							},
						},
					},
					{
						name: "type",
						title: "Тип события",
						type: "string",
						options: {
							column: {
								style: {
									width: "175px",
								},
							},
							data: {
								style: {
									width: "auto",
								},
							},
						},
					},
					{
						name: "meta",
						title: "Описание",
						type: "string",
						options: {
							data: {
								style: {
									width: "auto",
									wordBreak: "break-all",
								},
							},
						},
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
		api({
			method: "get",
			url: this.$store.getters.urlApi + `get-tracking-statistics-list`,
			headers: {
				Accept: "application/json",
			},
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
				this.loading.loader.statistics = false;
			});
	},
};
</script>

<style scoped></style>
