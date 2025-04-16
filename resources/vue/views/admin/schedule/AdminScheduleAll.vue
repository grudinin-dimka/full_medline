<template>
	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                    РАСПИСАНИЕ                     |-->
	<!--|___________________________________________________|-->
	<info-bar>
		<template #title>Расписание</template>
		<template #addreas>schedule</template>
	</info-bar>

	<block-once :minHeight="200">
		<template #title>КЛИНИКИ</template>

		<template #options>
			<icon-load :width="28" :height="28" v-if="disabled.schedule.save" />
			<icon-save :width="28" :height="28" @click="updateSheduleFromServe" v-else />
		</template>

		<template #body>
			<BaseTable v-if="loading.sections.schedule" :table="tableCLinics" />

			<loader-child
				:isLoading="loading.loader.schedule"
				:minHeight="200"
				@loaderChildAfterLeave="loaderChildAfterLeave"
			/>
		</template>
	</block-once>

	<block-once :minHeight="200">
		<template #title>ДНИ</template>

		<template #body>
			<BaseTable v-if="loading.sections.schedule" :table="tableDays" />

			<loader-child
				:isLoading="loading.loader.schedule"
				:minHeight="200"
				@loaderChildAfterLeave="loaderChildAfterLeave"
			/>
		</template>
	</block-once>

	<block-once :minHeight="200">
		<template #title>СПЕЦИАЛИСТЫ</template>

		<template #body>
			<BaseTable v-if="loading.sections.schedule" :table="tableSchedules" />

			<loader-child
				:isLoading="loading.loader.schedule"
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
import BlockButtons from "../../../components/ui/admin/blocks/BlockButtons.vue";

import BaseTable from "../../../components/modules/table/BaseTable.vue";

import ButtonDefault from "../../../components/ui/admin/buttons/ButtonDefault.vue";

import IconLoad from "../../../components/icons/IconLoad.vue";
import IconSave from "../../../components/icons/IconSave.vue";

import axios from "axios";

export default {
	components: {
		LoaderChild,
		BlockOnce,
		BlockTitle,
		BlockButtons,

		InfoBar,

		BaseTable,

		ButtonDefault,

		IconLoad,
		IconSave,
	},
	data() {
		return {
			/* Загрузчик */
			loading: {
				loader: {
					schedule: true,
				},
				sections: {
					schedule: false,
				},
			},

			/* Кнопки */
			disabled: {
				schedule: {
					save: false,
				},
			},

			/* Таблица */
			tableDays: {
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
						name: "date",
						text: "Дата",
						columnType: "time",
						columnSize: "auto",
					},
				],

				// Элементы
				body: [],
			},

			/* Таблица */
			tableCLinics: {
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
						text: "Название",
						columnType: "default",
						columnSize: "auto",
					},
				],

				// Элементы
				body: [],
			},

			/* Таблица */
			tableSchedules: {
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
		/* После скрытия элементы */
		loaderChildAfterLeave() {
			this.loading.sections.schedule = true;
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |             Обновление расписания                 |*/
		/* |___________________________________________________|*/
		updateSheduleFromServe() {
			this.disabled.schedule.save = true;

			axios({
				method: "post",
				url: `${this.$store.getters.urlApi}` + `save-shedules-all`,
				headers: {
					Accept: "application/json",
					Authorization: `Bearer ${localStorage.getItem("token")}`,
				},
				data: {
					type: "manual",
				},
			})
				.then((response) => {
					this.$store.commit("addDebugger", {
						title: "Успешно!",
						body: "Расписание обновлено.",
						type: "completed",
					});
				})
				.catch((error) => {
					this.$store.commit("addDebugger", {
						title: "Ошибка.",
						body: error,
						type: "error",
					});
				})
				.finally(() => {
					this.disabled.schedule.save = false;
				});
		},
	},
	mounted() {
		axios({
			method: "get",
			headers: {
				Accept: "application/json",
				Authorization: `Bearer ${localStorage.getItem("token")}`,
			},
			url: `${this.$store.getters.urlApi}` + `get-shedules-all`,
		})
			.then((response) => {
				if (response.data.status) {
					this.tableDays.body = response.data.data.currentDays;
					this.tableCLinics.body = response.data.data.sheduleClinics;
					this.tableSchedules.body = response.data.data.shedules;
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
				this.loading.loader.schedule = false;
			});
	},
};
</script>

<style scoped></style>
