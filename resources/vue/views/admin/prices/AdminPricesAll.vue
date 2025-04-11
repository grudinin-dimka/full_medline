<template>
	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                  МОДАЛЬНОЕ ОКНО                   |-->
	<!--|___________________________________________________|-->
	<admin-modal ref="modal" @touchCloseModal="closeModal('modal')" :modal="modal">
		<template #title>
			<span class="create" v-if="modal.type == 'create'"> ЗАГРУЗКА ФАЙЛА С ЦЕНАМИ </span>
			<span v-if="modal.type == 'edit'"> ФАЙЛ С ЦЕНАМИ (РЕДАКТИРОВАНИЕ)</span>
		</template>
		<template #body>
			<container-input-once :type="(modal.type = 'create')">
				<template #title>
					<span>ФАЙЛ*</span>
					<span v-if="false"> (ИЗМЕНЕНО) </span>
				</template>
				<template #input>
					<input
						type="file"
						ref="fileUpload"
						:class="{
							error: currentPrice.errors.file.status,
						}"
					/>
				</template>
				<template #error>
					<span class="error" v-if="currentPrice.errors.file.status">
						{{ currentPrice.errors.file.body }}
					</span>
				</template>
			</container-input-once>
		</template>
		<template #footer>
			<block-buttons>
				<ButtonClaim @click="createPrice" :disabled="disabled.prices.create">
					Загрузить
				</ButtonClaim>
			</block-buttons>
		</template>
	</admin-modal>

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
			<icon-load :width="28" :height="28" v-if="disabled.prices.save" />
			<icon-save :width="28" :height="28" @click="savePricesFiles" v-else />
		</template>

		<template #body>
			<BaseTable
				v-if="loading.sections.prices"
				:table="table"
				@create="openModal('create', 'modal')"
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
import AdminModal from "../../../components/includes/admin/AdminModal.vue";
import Empty from "../../../components/modules/Empty.vue";
import BaseTable from "../../../components/modules/table/BaseTable.vue";

import BlockOnce from "../../../components/ui/admin/blocks/BlockOnce.vue";
import BlockTitle from "../../../components/ui/admin/blocks/BlockTitle.vue";
import InfoBar from "../../../components/ui/admin/InfoBar.vue";

import TableButtonDefault from "../../../components/ui/admin/tables/TableButtonDefault.vue";
import TableButtonRemove from "../../../components/ui/admin/tables/TableButtonRemove.vue";
import TableButtonDisabled from "../../../components/ui/admin/tables/TableButtonDisabled.vue";

import ContainerInputOnce from "../../../components/ui/admin/containers/input/ContainerInputOnce.vue";

import ButtonDefault from "../../../components/ui/admin/buttons/ButtonDefault.vue";
import ButtonClaim from "../../../components/ui/admin/buttons/ButtonClaim.vue";
import BlockButtons from "../../../components/ui/admin/blocks/BlockButtons.vue";

import IconSave from "../../../components/icons/IconSave.vue";
import IconLoad from "../../../components/icons/IconLoad.vue";

import validate from "../../../services/validate";
import shared from "../../../services/shared";
import axios from "axios";

export default {
	components: {
		LoaderChild,
		AdminModal,
		Empty,
		BaseTable,

		BlockOnce,
		BlockTitle,

		InfoBar,

		TableButtonDefault,
		TableButtonRemove,
		TableButtonDisabled,

		ContainerInputOnce,
		BlockButtons,
		ButtonClaim,
		ButtonDefault,
		IconSave,
		IconLoad,
		validate,
		shared,
		axios,
	},
	data() {
		return {
			/* Модальное окно */
			modal: {
				title: "",
				status: false,
				type: null,
				style: {
					create: false,
					delete: false,
				},
				modules: {
					title: true,
					buttons: {
						hide: false,
						close: true,
					},
					images: false,
					body: true,
					footer: true,
				},
			},

			/* Кнопки */
			disabled: {
				prices: {
					save: false,
					create: false,
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

			/* Форма */
			currentPrice: {
				errors: {
					file: {
						body: "",
						status: false,
					},
				},
				data: {
					file: {
						body: "",
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
						columnSize: "auto",
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
		/* |                 Модальное окно                    |*/
		/* |___________________________________________________|*/
		/* _____________________________________________________*/
		/* 1. Основные действия                                 */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		/* Открытие */
		openModal(type = "edit", name = "modal") {
			this.$refs.fileUpload.value = null;

			switch (type) {
				case "create":
					{
						this[name].type = "create";
						this[name].status = true;
						this[name].style.create = true;
						this[name].style.delete = false;
					}
					document.body.classList.add("modal-open");
					break;
				case "edit":
					{
						this[name].type = "edit";
						this[name].status = true;
						this[name].style.delete = false;
						this[name].style.create = false;
					}
					document.body.classList.add("modal-open");
					break;
				default:
					{
						this.$store.commit("addDebugger", {
							title: "Ошибка.",
							body: "Низвестный тип открытия модального окна.",
							type: "error",
						});
					}
					break;
			}
		},
		/* Закрытие */
		closeModal(name = "modal") {
			this[name].status = false;
			if (name == "modal") {
				document.body.classList.remove("modal-open");
			}
		},
		/* _____________________________________________________*/
		/* 2. Работа с полями ввода модального окна             */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		// Проверка поля имени
		checkModalInput(dataKey, inputType) {
			let errorLog = {};
			switch (inputType) {
				case "text":
					errorLog = validate.checkInputText(this.currentSpecialization.data[dataKey].body);
					break;
				case "file":
					errorLog = validate.checkInputFile(this.$refs.fileUpload.files[0], [
						"application/vnd.oasis.opendocument.spreadsheet",
						"application/vnd.ms-excel",
						"application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
					]);
					break;
				default:
					break;
			}

			if (errorLog.status) {
				this.currentPrice.errors[dataKey].body = errorLog.message;
				this.currentPrice.errors[dataKey].status = true;

				return true;
			} else {
				this.currentPrice.errors[dataKey].body = "";
				this.currentPrice.errors[dataKey].status = false;

				return false;
			}
		},
		// Проверка всех полей ввода модального окна
		checkModalInputsAll(inputKeys) {
			let errorCount = 0;
			for (let i = 0; i < inputKeys.length; i++) {
				switch (inputKeys[i]) {
					// Для поля файл
					case "file":
						if (this.checkModalInput(inputKeys[i], "file")) {
							errorCount++;
						}
						break;
					// Для всех остальных полей
					default:
						if (this.checkModalInput(inputKeys[i], "text")) {
							errorCount++;
						}
						break;
				}
			}

			if (errorCount > 0) {
				return true;
			} else {
				return false;
			}
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
			if (this.checkModalInputsAll(["file"])) {
				return;
			}

			/* Загрузка файла */
			let formData = new FormData();
			formData.append("image", this.$refs.fileUpload.files[0]);
			formData.append("type", "prices");
			formData.append("formats", ["ods", "xls", "xlsx"]);

			this.disabled.prices.create = true;

			axios({
				method: "post",
				url: `${this.$store.getters.urlApi}` + `upload-file`,
				headers: {
					"Content-Type": "multipart/form-data",
					Authorization: `Bearer ${localStorage.getItem("token")}`,
				},
				data: formData,
			})
				.then((response) => {
					if (response.data.status) {
						try {
							this.table.body.push({
								id: shared.getMaxId(this.table.body) + 1,
								filename: response.data.data.replace("/storage/prices/", ""),
								path: response.data.data,
								date: new Date().toLocaleDateString("ru", {
									year: "numeric",
									month: "numeric",
									day: "numeric",
									hour: "numeric",
									minute: "numeric",
									second: "numeric",
								}),
								delete: false,
								create: true,
							});

							this.closeModal("modal");
						} catch (error) {
							this.$store.commit("addDebugger", {
								title: "Ошибка.",
								body: error,
								type: "error",
							});
						}
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
					this.disabled.prices.create = false;
				});
		},
		savePricesFiles() {
			let formData = new FormData();
			formData.append("pricesFiles", JSON.stringify(this.table.body));

			this.disabled.prices.save = true;

			axios({
				method: "post",
				url: `${this.$store.getters.urlApi}` + `save-prices-changes`,
				headers: {
					ContentType: "multipart/form-data",
					Authorization: `Bearer ${localStorage.getItem("token")}`,
				},
				data: formData,
			})
				.then((response) => {
					if (response.data.status) {
						try {
							shared.updateId(this.table.body, response.data.data);
							shared.clearDeletes(this.table.body);
							shared.clearFlags(this.table.body);
							shared.updateOrders(this.table.body);

							this.$store.commit("addDebugger", {
								title: "Успешно!",
								body: response.data.message,
								type: "completed",
							});
						} catch (error) {
							this.$store.commit("addDebugger", {
								title: "Ошибка.",
								body: error,
								type: "error",
							});
						}
					} else {
						if (response.data.data) {
							shared.updateId(this.table.body, response.data.data);
							shared.clearDeletes(this.table.body);
							shared.clearFlags(this.table.body);
							shared.updateOrders(this.table.body);
						}

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
					this.disabled.prices.save = false;
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
			url: `${this.$store.getters.urlApi}` + `get-prices-files-all`,
		})
			.then((response) => {
				if (response.data.status) {
					this.table.body = response.data.data;

					this.table.body.forEach((item) => {
						item.create = false;
						item.delete = false;
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
				this.loading.loader.prices = false;
			});
	},
};
</script>

<style scoped>
.eprices {
	display: flex;
	flex-direction: column;
	gap: 10px;

	font-size: 18px;

	animation: show-bottom-to-top-15 0.5s ease-in-out;
}

.eprices > .item {
	display: flex;

	padding: 15px;

	border: 2px solid var(--input-border-color-inactive);
	border-radius: 10px;

	transition: all 0.2s;
}

.eprices > .item > .info {
	flex: 1 0 300px;
	display: flex;
}

.eprices > .item > .info > div {
	flex: 1 0 300px;
	display: flex;
	align-items: center;
}

.eprices > .item.delete {
	border: 2px solid #ec7b7b;
	background-color: #fff2f2;
}

.eprices > .item.create {
	border: 2px solid var(--create-border-color);
	background-color: var(--create-background-color);
}

.eprices > .item > .buttons {
	display: flex;
	align-items: center;
	gap: 10px;
}

.eprices > .item > .buttons > a {
	flex: 1 0 120px;
	cursor: pointer;
	min-width: 120px;

	padding: 7.5px 0px;
	border: 0px solid black;
	border-radius: 7.5px;

	font-size: 1rem;
	text-align: center;
	text-decoration: none;
	color: white;

	background-color: var(--button-default-color);
}

.eprices > .item > .buttons > a:hover {
	background-color: var(--button-default-color-hover);
}

@media screen and (width <= 1300px) {
	.eprices > .item > .info > .date {
		display: none;
	}

	.eprices > .item > .info {
		flex: 1 0 0px;
		display: flex;
	}
}

@media screen and (width <= 700px) {
	.eprices > .item {
		display: flex;
		flex-direction: column;
		gap: 10px;
	}

	.eprices > .item > .buttons {
		display: flex;
		justify-content: flex-end;
		align-items: center;
	}
}
</style>
