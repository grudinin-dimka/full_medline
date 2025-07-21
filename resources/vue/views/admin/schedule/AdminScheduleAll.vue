<template>
	<info-bar>
		<template #title>Расписание</template>
		<template #addreas>schedule</template>
	</info-bar>

	<block-once :minHeight="200">
		<template #title>КЛИНИКИ</template>

		<template #options>
			<VueButton
				@click.prevent="updateSheduleFromServe"
				:disabled="disabled.schedule.save"
				:look="'inverse'"
			>
				<VueIcon
					:name="'save'"
					:fill="'var(--primary-color)'"
					:width="'28px'"
					:height="'28px'"
				/>
				Сохранить
			</VueButton>
		</template>

		<template #body>
			<VueTable v-if="loading.sections.schedule" :table="tableCLinics" />

			<VueLoader
				:isLoading="loading.loader.schedule"
				:isChild="true"
				:minHeight="200"
				@afterLeave="loaderChildAfterLeave"
			/>
		</template>
	</block-once>

	<block-once :minHeight="200">
		<template #title>ДНИ</template>

		<template #body>
			<VueTable v-if="loading.sections.schedule" :table="tableDays" />

			<VueLoader
				:isLoading="loading.loader.schedule"
				:isChild="true"
				:minHeight="200"
				@afterLeave="loaderChildAfterLeave"
			/>
		</template>
	</block-once>

	<block-once :minHeight="200">
		<template #title>СПЕЦИАЛИСТЫ</template>

		<template #body>
			<VueTable v-if="loading.sections.schedule" :table="tableSchedules" />

			<VueLoader
				:isLoading="loading.loader.schedule"
				:isChild="true"
				:minHeight="200"
				@afterLeave="loaderChildAfterLeave"
			/>
		</template>
	</block-once>
</template>

<script>
import InfoBar from "../../../components/ui/admin/InfoBar.vue";
import BlockOnce from "../../../components/ui/admin/blocks/BlockOnce.vue";
import BlockTitle from "../../../components/ui/admin/blocks/BlockTitle.vue";

import VueTable from "../../../components/modules/table/VueTable.vue";

import VueLoader from "../../../components/modules/VueLoader.vue";
import VueIcon from "../../../components/modules/icon/VueIcon.vue";
import VueButton from "../../../components/ui/VueButton.vue";

import api from "../../../mixin/api";

export default {
	components: {
		InfoBar,
		BlockOnce,
		BlockTitle,

		VueTable,

		VueLoader,
		VueIcon,
		VueButton,
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
