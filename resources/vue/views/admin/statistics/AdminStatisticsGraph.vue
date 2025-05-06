<template>
	<block-once :minHeight="100">
		<template #title> НЕДЕЛЯ </template>
		<template #body>
			<template v-if="loading.sections.week">
				<apexchart
					width="100%"
					height="500px"
					type="line"
					:options="week.options"
					:series="week.series"
				></apexchart>
			</template>

			<LoaderChild
				:isLoading="loading.loader.week"
				:minHeight="300"
				@loaderChildAfterLeave="loaderChildAfterLeave"
			></LoaderChild>
		</template>
	</block-once>

	<block-once :minHeight="100">
		<template #title> МЕСЯЦ </template>
		<template #body>
			<template v-if="loading.sections.month">
				<apexchart
					width="100%"
					height="500px"
					type="line"
					:options="month.options"
					:series="month.series"
				></apexchart>
			</template>

			<LoaderChild
				:isLoading="loading.loader.month"
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
					week: true,
					month: true,
				},
				sections: {
					week: false,
					month: false,
				},
			},

			/* Данные */
			week: {
				options: {
					stroke: {
						curve: "smooth",
					},
					chart: {
						id: "vuechart-week",
					},
					xaxis: {
						categories: [],
						type: "datetime",
					},
				},
				series: [],
			},

			month: {
				options: {
					stroke: {
						curve: "smooth",
					},
					chart: {
						id: "vuechart-month",
					},
					xaxis: {
						categories: [],
						type: "datetime",
					},
				},
				series: [],
			},
		};
	},
	methods: {
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                   ЗАГРУЗЧИК                       |*/
		/* |___________________________________________________|*/
		/* После скрытия элементы */
		loaderChildAfterLeave() {
			for (let key in this.loading.loader) {
				if (!this.loading.loader[key]) {
					this.loading.sections[key] = true;
				}
			}
		},
	},
	mounted() {
		/* Текущий день */
		let currentDay = new Date();

		/* Предидущий день */
		let previousDay = new Date(currentDay.getTime());

		/* На неделю */
		previousDay.setDate(currentDay.getDate() - 7);

		axios({
			method: "post",
			url: `${this.$store.getters.urlApi}` + `get-tracking-statistics-range`,
			headers: {
				Accept: "application/json",
				Authorization: `Bearer ${localStorage.getItem("token")}`,
			},
			data: {
				start: previousDay.toLocaleString("ru", {
					day: "numeric",
					month: "numeric",
					year: "numeric",
				}),
				end: currentDay.toLocaleString("ru", {
					day: "numeric",
					month: "numeric",
					year: "numeric",
				}),
			},
		})
			.then((response) => {
				if (response.data.status) {
					let counts = [];

               for (let key in response.data.data) {
						let countRequests = 0;

						if (Array.isArray(response.data.data[key])) {
							countRequests = response.data.data[key].length;
						}

						counts.push(countRequests);
                  
						this.week.options.xaxis.categories.push({
							data: [
								{
									x: new Date(key).getTime(),
									y: countRequests,
								},
							],
						});
					}


					// let counts = [];
					// for (let key of this.week.options.xaxis.categories) {
					// 	let countRequests = 0;

					// 	if (Array.isArray(response.data.data[key])) {
					// 		countRequests = response.data.data[key].length;
					// 	}

					// 	counts.push(countRequests);
					// }

					// this.week.series.push({
					// 	name: "Все запросы",
					// 	data: counts,
					// });

					this.$store.commit("addDebugger", {
						title: "Успешно!.",
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
				this.loading.loader.week = false;
			});

		/* На месяц */
		previousDay.setDate(currentDay.getDate() - 31);

		// axios({
		// 	method: "post",
		// 	url: `${this.$store.getters.urlApi}` + `get-tracking-statistics-range`,
		// 	headers: {
		// 		Accept: "application/json",
		// 		Authorization: `Bearer ${localStorage.getItem("token")}`,
		// 	},
		// 	data: {
		// 		start: previousDay.toLocaleString("ru", {
		// 			day: "numeric",
		// 			month: "numeric",
		// 			year: "numeric",
		// 		}),
		// 		end: currentDay.toLocaleString("ru", {
		// 			day: "numeric",
		// 			month: "numeric",
		// 			year: "numeric",
		// 		}),
		// 	},
		// })
		// 	.then((response) => {
		// 		console.log(response.data);

		// 		if (response.data.status) {
		// 			for (let key in response.data.data) {
		// 				this.month.options.xaxis.categories.push(key);
		// 			}

		// 			let counts = [];
		// 			for (let key of this.month.options.xaxis.categories) {
		// 				let countRequests = 0;

		// 				if (Array.isArray(response.data.data[key])) {
		// 					countRequests = response.data.data[key].length;
		// 				}

		// 				counts.push(countRequests);
		// 			}

		// 			this.month.series.push({
		// 				name: "Все запросы",
		// 				data: counts,
		// 			});

		// 			this.$store.commit("addDebugger", {
		// 				title: "Успешно!.",
		// 				body: response.data.message,
		// 				type: "completed",
		// 			});
		// 		} else {
		// 			this.$store.commit("addDebugger", {
		// 				title: "Ошибка.",
		// 				body: response.data.message,
		// 				type: "error",
		// 			});
		// 		}
		// 	})
		// 	.catch((error) => {
		// 		this.$store.commit("addDebugger", {
		// 			title: "Ошибка.",
		// 			body: error,
		// 			type: "error",
		// 		});
		// 	})
		// 	.finally(() => {
		// 		this.loading.loader.month = false;
		// 	});
	},
};
</script>

<style scoped></style>
