<template>
	<info-bar>
		<template #title>Расписание</template>
		<template #addreas>schedule</template>
	</info-bar>

	<block-once :minHeight="200">
		<template #title>КЛИНИКИ</template>

		<template #options>
			<button-default
				@click.prevent="updateSheduleFromServe"
				:disabled="disabled.schedule.save"
				:look="'white'"
			>
				<VueIcon :name="'save'" :fill="'var(--primary-color)'" :width="'28px'" :height="'28px'" />
				Сохранить
			</button-default>
		</template>

		<template #body>
			<VueTable v-if="loading.sections.schedule" :table="tableCLinics" />

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
			<VueTable v-if="loading.sections.schedule" :table="tableDays" />

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
			<VueTable v-if="loading.sections.schedule" :table="tableSchedules" />

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

import VueTable from "../../../components/modules/table/VueTable.vue";

import ButtonDefault from "../../../components/ui/admin/buttons/ButtonDefault.vue";

import VueIcon from "../../../components/modules/icon/VueIcon.vue";

import api from "../../../services/api";

export default {
	components: {
		LoaderChild,
		BlockOnce,
		BlockTitle,

		InfoBar,

		VueTable,

		ButtonDefault,

		VueIcon,
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

			api({
				method: "post",
				url: this.$store.getters.urlApi + `save-shedules-all/manual`,
			}).finally(() => {
				this.disabled.schedule.save = false;
			});
		},
	},
	mounted() {
		api({
			method: "get",
			url: this.$store.getters.urlApi + `get-shedules-all`,
		})
			.then((response) => {
				if (!response) return;

				this.tableDays.body = response.data.result.currentDays;
				this.tableCLinics.body = response.data.result.sheduleClinics;
				this.tableSchedules.body = response.data.result.shedules;
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
