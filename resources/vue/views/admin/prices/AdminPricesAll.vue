<template>
	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                  МОДАЛЬНОЕ ОКНО                   |-->
	<!--|___________________________________________________|-->
	<Modal ref="modal" :settings="modal">
		<template #title>
			{{ modal.values.title }}
		</template>

		<template #body>
			<VueInput
				v-model="currentPrice.data.file.value"
				ref="fileImage"
				:type="'file'"
				:placeholder="'Загрузите табличный файл'"
				:error="currentPrice.errors.file.status"
			>
				<template #label> ФАЙЛ </template>
				<template #error>
					{{ currentPrice.errors.file.message }}
				</template>
			</VueInput>
		</template>

		<template #footer>
			<ButtonDefault @click="createPrice" :disabled="disabled.prices.create">
				<Icon :name="'add'" :fill="'white'" :width="'23px'" :height="'23px'" />
				Добавить
			</ButtonDefault>
		</template>
	</Modal>

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
			<button-default
				@click.prevent="downloadPricesFiles"
				:disabled="disabled.prices.download"
				:look="'white'"
			>
				<Icon
					:name="'download'"
					:fill="'var(--primary-color)'"
					:width="'28px'"
					:height="'28px'"
				/>
				Выгрузить
			</button-default>

			<button-default
				@click.prevent="savePricesFiles"
				:disabled="disabled.prices.save"
				:look="'white'"
			>
				<Icon :name="'save'" :fill="'var(--primary-color)'" :width="'28px'" :height="'28px'" />
				Сохранить
			</button-default>
		</template>

		<template #body>
			<BaseTable
				v-if="loading.sections.prices"
				:table="table"
				@create="openModalСreate"
				@edite="downloadFile"
				@delete="removePriceFile"
			/>

			<LoaderChild
				:isLoading="loading.loader.prices"
				:minHeight="300"
				@loaderChildAfterLeave="loaderChildAfterLeave"
			></LoaderChild>
		</template>
	</block-once>
</template>

<script>
import LoaderChild from "../../../components/modules/LoaderChild.vue";
import Modal from "../../../components/modules/modal/Modal.vue";
import Empty from "../../../components/modules/Empty.vue";
import BaseTable from "../../../components/modules/table/BaseTable.vue";
import VueInput from "../../../components/modules/input/VueInput.vue";

import BlockOnce from "../../../components/ui/admin/blocks/BlockOnce.vue";
import BlockTitle from "../../../components/ui/admin/blocks/BlockTitle.vue";
import InfoBar from "../../../components/ui/admin/InfoBar.vue";

import ButtonDefault from "../../../components/ui/admin/buttons/ButtonDefault.vue";
import ButtonClaim from "../../../components/ui/admin/buttons/ButtonClaim.vue";

import Icon from "../../../components/modules/icon/Icon.vue";

import api from "../../../services/api";
import validate from "../../../services/validate";
import shared from "../../../services/shared";
import files from "../../../services/files";

export default {
	components: {
		LoaderChild,
		Modal,
		Empty,
		BaseTable,
		VueInput,

		BlockOnce,
		BlockTitle,

		InfoBar,

		ButtonClaim,
		ButtonDefault,

		Icon,
	},
	data() {
		return {
			/* Кнопки */
			disabled: {
				prices: {
					save: false,
					create: false,
					download: false,
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
					{ name: "id", text: "ID", columnType: "id" },
					{
						name: "filename",
						text: "Файл",
						columnType: "default",
						columnSize: "auto",
					},
					{
						name: "date",
						text: "Дата загрузки",
						columnType: "default",
						columnSize: "300px",
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
						date: "Не определено",
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

		/* Выгрузка */
		downloadPricesFiles() {
			this.disabled.prices.download = true;

			api({
				method: "post",
				url: this.$store.getters.urlApi + `make-prices-files`,
			})
				.then((response) => {
					if (!response) return;

					// Перенаправляем пользователя на ссылку для скачивания архива
					window.location.href = `/api/download-prices-archive`;
				})
				.catch((error) => {
					this.$store.commit("addDebugger", {
						title: "Ошибка.",
						body: error,
						type: "error",
					});
				})
				.finally(() => {
					this.disabled.prices.download = false;
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
