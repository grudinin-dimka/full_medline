<template>
	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                  МОДАЛЬНОЕ ОКНО                   |-->
	<!--|___________________________________________________|-->
	<VueModal ref="modal" :settings="modal">
		<template #title>
			{{ modal.values.title }}
		</template>

		<template #body>
			<VueFile
				v-model="currentPrice.data.file.value"
				ref="fileImage"
				:type="'document'"
				:placeholder="'Загрузите табличный файл'"
				:error="currentPrice.errors.file.status"
			>
				<template #label>
					<VueIcon :name="'Article'" :fill="'var(--primary-color)'" :width="'20px'" :height="'20px'" />
					ФАЙЛ
				</template>
				<template #error>
					{{ currentPrice.errors.file.message }}
				</template>
			</VueFile>
		</template>

		<template #footer>
			<VueButton @click="createPrice" :disabled="disabled.prices.create">
				<VueIcon :name="'Add'" :fill="'white'" :width="'26px'" :height="'26px'" />
				Добавить
			</VueButton>
		</template>
	</VueModal>

	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                    СПИСОК ЦЕН                     |-->
	<!--|___________________________________________________|-->
	<info-bar>
		<template v-slot:title>Цены</template>
		<template v-slot:addreas>prices</template>
	</info-bar>

	<block-once :minHeight="200">
		<template #title>СПИСОК ЦЕН</template>

		<template #options>
			<VueButton @click.prevent="downloadPricesXML" :disabled="disabled.prices.xml" :look="'inverse'">
				<VueIcon :name="'Download 2'" :fill="'var(--primary-color)'" :width="'28px'" :height="'28px'" />
				Выгрузить (.XML)
			</VueButton>

			<VueButton @click.prevent="downloadPricesXLSX" :disabled="disabled.prices.xlsx" :look="'inverse'">
				<VueIcon :name="'Download 2'" :fill="'var(--primary-color)'" :width="'28px'" :height="'28px'" />
				Выгрузить (.XLSX)
			</VueButton>

			<VueButton @click.prevent="savePricesFiles" :disabled="disabled.prices.save" :look="'inverse'">
				<VueIcon :name="'Save'" :fill="'var(--primary-color)'" :width="'28px'" :height="'28px'" />
				Сохранить
			</VueButton>

			<VueButton
				:look="'inverse'"
				@click="
					$store.commit('openWiki', {
						title: 'СПИСОК ЦЕН',
						component: 'PricesAll',
					})
				"
				:minWidth="'30px'"
			>
				<VueIcon :name="'Info'" :fill="'var(--primary-color)'" :width="'30px'" :height="'30px'" />
			</VueButton>
		</template>

		<template #body>
			<VueTable
				v-if="loading.sections.prices"
				:table="table"
				@create="openModalСreate()"
				@edite="downloadFile"
				@delete="removePriceFile"
			/>

			<VueLoader
				:isLoading="loading.loader.prices"
				:isChild="true"
				:minHeight="300"
				@afterLeave="loaderChildAfterLeave"
			/>
		</template>
	</block-once>
</template>

<script>
import VueInput from "../../../components/modules/input/VueInput.vue";
import Empty from "../../../components/modules/Empty.vue";

import InfoBar from "../../../components/ui/admin/InfoBar.vue";
import BlockOnce from "../../../components/ui/admin/blocks/BlockOnce.vue";
import BlockTitle from "../../../components/ui/admin/blocks/BlockTitle.vue";

import api from "../../../mixin/api";
import validate from "../../../services/validate";
import shared from "../../../services/shared";
import files from "../../../services/files";

export default {
	components: {
		VueInput,
		Empty,

		InfoBar,
		BlockOnce,
		BlockTitle,
	},
	data() {
		return {
			/* Кнопки */
			disabled: {
				prices: {
					save: false,
					create: false,
					xlsx: false,
					xml: false,
				},
			},

			/* Загрузчик */
			loading: {
				loader: {
					prices: true,
				},
				sections: {
					prices: false,
				},
			},

			/* Модальное окно */
			modal: {
				thin: false,
				clamped: false,
				touch: true,
				values: {
					title: "",
					look: "default",
				},
			},

			/* Форма */
			currentPrice: {
				errors: {
					file: {
						message: "",
						status: false,
					},
				},
				data: {
					file: {
						value: "",
						edited: false,
					},
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
					{
						name: "id",
						title: "ID",
						type: "id",
						hide: true,
					},
					{
						name: "filename",
						title: "Файл",
						type: "string",
					},
					{
						name: "date",
						title: "Дата загрузки",
						type: "time",
						options: {
							column: {
								style: {
									width: "200px",
								},
							},
						},
					},
				],

				// Элементы
				body: [],
			},

			prices: [],
		};
	},
	methods: {
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                   ЗАГРУЗЧИК                       |*/
		/* |___________________________________________________|*/
		/* После скрытия элементы */
		loaderChildAfterLeave() {
			this.loading.sections.prices = true;
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                 МОДАЛЬНОЕ ОКНО                    |*/
		/* |___________________________________________________|*/
		/* Открытие модального окна */
		openModal(name, title, look) {
			this[name].values.title = title;
			this[name].values.look = look;

			this.$refs[name].open();
		},

		/* Открытие модального окна для добавления */
		openModalСreate() {
			this.$refs.fileImage.clear();

			shared.clearObjectFull(this.currentPrice);

			this.openModal("modal", "ФАЙЛ ЦЕН", "create");
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                      ЦЕНЫ                         |*/
		/* |___________________________________________________|*/
		downloadFile(price) {
			// Перенаправляем пользователя на ссылку для скачивания файла
			window.location.href = price.path;
		},

		/* Изменение статуса */
		updateStatus(price) {
			this.table.body.forEach((item) => {
				if (item.id === price.id) {
					item.status = true;
					item.delete = false;
				} else {
					item.status = false;
				}
			});
		},

		/* Пометка на удаление */
		removePriceFile(price) {
			price.delete = !price.delete;
		},

		/* Форматирование даты */
		formatDate(created_at) {
			let date = new Date(created_at);

			let options = {
				month: "long",
				day: "numeric",
				year: "numeric",
			};

			return date.toLocaleString("ru", options);
		},

		/* Создание цены */
		createPrice() {
			if (
				validate.checkInputsAll(this.currentPrice, [
					{
						key: "file",
						type: "file",
						value: this.$refs.fileImage.files(),
						formats: ["ods", "xls", "xlsx"],
					},
				])
			)
				return;

			/* Загрузка файла */
			let formData = new FormData();
			formData.append("file", this.$refs.fileImage.files()[0]);
			formData.append("type", "prices");
			formData.append("formats", ["ods", "xls", "xlsx"]);

			this.disabled.prices.create = true;

			api({
				method: "post",
				url: this.$store.getters.urlApi + `upload-file`,
				headers: {
					"Content-Type": "multipart/form-data",
				},
				data: formData,
			})
				.then((response) => {
					if (!response) return;

					this.table.body.push({
						id: shared.getMaxId(this.table.body) + 1,
						filename: files.basename(response.data.result),
						path: response.data.result,
						date: null,
						delete: false,
						create: true,
					});

					this.$refs.modal.close();
				})
				.catch((error) => {
					this.$store.commit("addDebugger", {
						title: "Ошибка.",
						body: error,
						type: "error",
					});
				})
				.finally(() => {
					this.disabled.prices.create = false;
				});
		},

		/* Сохранение изменений */
		savePricesFiles() {
			let formData = new FormData();
			formData.append("pricesFiles", JSON.stringify(this.table.body));

			this.disabled.prices.save = true;

			api({
				method: "post",
				url: this.$store.getters.urlApi + `save-prices-changes`,
				headers: {
					ContentType: "multipart/form-data",
				},
				data: formData,
			})
				.then((response) => {
					if (!response) return;

					shared.updateId(this.table.body, response.data.result);
					shared.clearDeletes(this.table.body);
					shared.clearFlags(this.table.body);
					shared.updateOrders(this.table.body);
				})
				.catch((error) => {
					this.$store.commit("addDebugger", {
						title: "Ошибка.",
						body: error,
						type: "error",
					});
				})
				.finally(() => {
					this.disabled.prices.save = false;
				});
		},

		/* Выгрузка: XML */
		downloadPricesXML() {
			this.disabled.prices.xml = true;

			api({
				method: "post",
				url: this.$store.getters.urlApi + `make-prices-files/xml`,
			})
				.then((response) => {
					if (!response) return;

					// Перенаправляем пользователя на ссылку для скачивания архива
					window.location.href = response.data.result;
				})
				.catch((error) => {
					this.$store.commit("addDebugger", {
						title: "Ошибка.",
						body: error,
						type: "error",
					});
				})
				.finally(() => {
					this.disabled.prices.xml = false;
				});
		},

		/* Выгрузка: XLSX */
		downloadPricesXLSX() {
			this.disabled.prices.xlsx = true;

			api({
				method: "post",
				url: this.$store.getters.urlApi + `make-prices-files/xlsx`,
			})
				.then((response) => {
					if (!response) return;

					// Перенаправляем пользователя на ссылку для скачивания архива
					window.location.href = response.data.result;
				})
				.catch((error) => {
					this.$store.commit("addDebugger", {
						title: "Ошибка.",
						body: error,
						type: "error",
					});
				})
				.finally(() => {
					this.disabled.prices.xlsx = false;
				});
		},
	},
	mounted() {
		api({
			method: "get",
			url: this.$store.getters.urlApi + `get-prices-files-all`,
		})
			.then((response) => {
				if (!response) return;

				this.table.body = response.data.result;

				this.table.body.forEach((item) => {
					item.create = false;
					item.delete = false;
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
				this.loading.loader.prices = false;
			});
	},
};
</script>

<style scoped></style>
