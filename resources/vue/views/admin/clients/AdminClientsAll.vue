<template>
	<info-bar>
		<template #title>Клиенты</template>
		<template #addreas>clients</template>
	</info-bar>

	<block-once :minHeight="200">
		<template #title>КЛИЕНТЫ</template>

		<template #options>
			<VueButton
				@click.prevent="updateClientsFromServe"
				:disabled="disabled.clients.save"
				:look="'inverse'"
			>
				<VueIcon
					:name="'Save'"
					:fill="'var(--primary-color)'"
					:width="'28px'"
					:height="'28px'"
				/>
				Сохранить
			</VueButton>
		</template>

		<template #body>
			<VueTable v-if="loading.sections.clients" :table="table" />

			<VueLoader
				:isLoading="loading.loader.clients"
				:isChild="true"
				:minHeight="300"
				@afterLeave="loaderChildAfterLeave"
			/>
		</template>
	</block-once>
</template>

<script>
import InfoBar from "../../../components/ui/admin/InfoBar.vue";
import BlockOnce from "../../../components/ui/admin/blocks/BlockOnce.vue";
import BlockTitle from "../../../components/ui/admin/blocks/BlockTitle.vue";

import api from "../../../mixin/api";

export default {
	components: {
		InfoBar,
		BlockOnce,
		BlockTitle,
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
					{
						name: "id",
						title: "ID",
						type: "id",
						hide: true,
					},
					{
						name: "name",
						title: "ФИО",
						type: "string",
						style: {
							column: {
								width: "300px",
							},
						},
					},
					{
						name: "snils",
						title: "СНИЛС",
						type: "string",
						style: {
							column: {
								width: "200px",
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
