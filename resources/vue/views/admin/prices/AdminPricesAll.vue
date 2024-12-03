<template>
	<!-- STOP делал подгрузку цен -->

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
		<block-title>
			<template #title>СПИСОК ЦЕН</template>
			<template #buttons>
				<icon-load :width="28" :height="28" v-if="disabled.prices.save" />
				<icon-save :width="28" :height="28" @click="savePricesFiles" v-else />
			</template>
		</block-title>

		<div class="eprices" v-if="loading.sections.prices">
			<div
				class="item"
				:class="{
					active: price.id === getActivePriceid,
					create: price.create,
					delete: price.delete,
				}"
				v-for="price in prices"
				:key="price.id"
			>
				<div class="info">
					<div class="radio" @click="updateStatus(price)">
						<input
							class="radio-input"
							type="radio"
							:id="'radio' + price.id"
							v-model="radioPrice"
							:value="price.id"
						/>
						<label class="radio-label" :for="'radio' + price.id">{{ price.filename }}</label>
					</div>
					<div class="date">{{ formatDate(price.created_at) }}</div>
				</div>
				<div class="buttons">
					<template v-if="radioPrice == price.id">
						<a :href="price.path" download>
							<TableButtonDefault>Скачать</TableButtonDefault>
						</a>
						<TableButtonDisabled>Удалить</TableButtonDisabled>
					</template>
					<template v-else>
						<template v-if="price.create">
							<a :href="price.path" download>
								<TableButtonDefault>Скачать</TableButtonDefault>
							</a>
							<TableButtonDisabled> Удалить </TableButtonDisabled>
						</template>
						<template v-if="price.delete">
							<TableButtonDisabled> Скачать </TableButtonDisabled>
							<TableButtonDefault @click="updateDeleteElement(price)">
								Вернуть
							</TableButtonDefault>
						</template>
						<template v-if="!price.delete && !price.create">
							<a :href="price.path" download>
								<TableButtonDefault>Скачать</TableButtonDefault>
							</a>
							<TableButtonRemove @click="updateDeleteElement(price)">
								Удалить
							</TableButtonRemove>
						</template>
					</template>
				</div>
			</div>
		</div>

		<LoaderChild
			:isLoading="loading.loader.prices"
			:minHeight="300"
			@loaderChildAfterLeave="loaderChildAfterLeave"
		></LoaderChild>

		<BlockButtons>
			<ButtonDefault @click="openModal('create', 'modal')"> Добавить </ButtonDefault>
		</BlockButtons>
	</block-once>
</template>

<script>
import LoaderChild from "../../../components/includes/LoaderChild.vue";
import AdminModal from "../../../components/includes/admin/AdminModal.vue";

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
			disabled: {
				prices: {
					save: false,
					create: false,
				},
			},
			loading: {
				loader: {
					prices: true,
				},
				sections: {
					prices: false,
				},
			},
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
			radioPrice: null,
			prices: [],
		};
	},
	computed: {
		getActivePriceid() {
			return this.prices.find((price) => price.status).id;
		},
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
						let debbugStory = {
							title: "Ошибка.",
							body: "Низвестный тип открытия модального окна.",
							type: "Error",
						};
						this.$store.commit("debuggerState", debbugStory);
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
		/* Изменение статуса */
		updateStatus(price) {
			this.prices.forEach((item) => {
				if (item.id === price.id) {
					this.radioPrice = price.id;
					item.status = true;
					item.delete = false;
				} else {
					item.status = false;
				}
			});
		},
		/* Пометка на удаление */
		updateDeleteElement(price) {
			let currentPrice = this.prices.find((item) => item.id === price.id);

			currentPrice.delete = !currentPrice.delete;
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
				url: `${this.$store.state.axios.urlApi}` + `upload-file`,
				headers: {
					"Content-Type": "multipart/form-data",
					Authorization: `Bearer ${localStorage.getItem("token")}`,
				},
				data: formData,
			})
				.then((response) => {
					if (response.data.status) {
						try {
							this.prices.push({
								id: shared.getMaxId(this.prices) + 1,
								filename: response.data.data.replace("/storage/prices/", ""),
								path: response.data.data,
								status: false,
								created_at: new Date(),
								delete: false,
								create: true,
							});

							this.updateStatus(this.prices[this.prices.length - 1]);

							this.disabled.prices.create = false;

							this.closeModal("modal");
						} catch (error) {
							this.disabled.prices.create = false;

							let debbugStory = {
								title: "Ошибка.",
								body: "Не удалось обновить данные после загрузки файла.",
								type: "Error",
							};
							this.$store.commit("debuggerState", debbugStory);
						}
					} else {
						this.disabled.prices.create = false;

						let debbugStory = {
							title: "Ошибка.",
							body: response.data.message,
							type: "Error",
						};
						this.$store.commit("debuggerState", debbugStory);
					}
				})
				.catch((error) => {
					this.disabled.prices.create = false;

					let debbugStory = {
						title: "Ошибка.",
						body: "Не удалось загрузить изображение.",
						type: "Error",
					};
					this.$store.commit("debuggerState", debbugStory);
				});
		},
		savePricesFiles() {
			let formData = new FormData();
			formData.append("pricesFiles", JSON.stringify(this.prices));

			this.disabled.prices.save = true;

			axios({
				method: "post",
				url: `${this.$store.state.axios.urlApi}` + `save-prices-changes`,
				headers: {
					ContentType: "multipart/form-data",
					Authorization: `Bearer ${localStorage.getItem("token")}`,
				},
				data: formData,
			})
				.then((response) => {
					if (response.data.status) {
						try {
							console.log(response.data);

							shared.updateId(this.prices, response.data.data);
							shared.clearDeletes(this.prices);
							shared.clearFlags(this.prices);
							shared.updateOrders(this.prices);

							for (let key in response.data.data) {
								if (response.data.data[key].old === this.radioPrice) {
									this.radioPrice = response.data.data[key].new;
									break;
								}
							}

							this.disabled.prices.save = false;

							let debbugStory = {
								title: "Успешно!",
								body: response.data.message,
								type: "Completed",
							};
							this.$store.commit("debuggerState", debbugStory);
						} catch (error) {
							this.disabled.prices.save = false;

							let debbugStory = {
								title: "Ошибка.",
								body: "Не удалось обновить данные после загрузки изображения.",
								type: "Error",
							};
							this.$store.commit("debuggerState", debbugStory);
						}
					} else {
						this.disabled.prices.save = false;

						let debbugStory = {
							title: "Ошибка.",
							body: response.data.message,
							type: "Error",
						};
						this.$store.commit("debuggerState", debbugStory);
					}
				})
				.catch((error) => {
					this.disabled.prices.save = false;

					let debbugStory = {
						title: "Ошибка.",
						body: "Не удалось сохранить данные.",
						type: "Error",
					};
					this.$store.commit("debuggerState", debbugStory);
				});
		},
	},
	mounted() {
		axios({
			method: "post",
			headers: {
				Accept: "application/json",
				Authorization: `Bearer ${localStorage.getItem("token")}`,
			},
			url: `${this.$store.state.axios.urlApi}` + `get-prices-files-all`,
		})
			.then((response) => {
				if (response.data.status) {
					this.prices = response.data.data;

					let priceActive = this.prices.find((item) => item.status == true);
					if (priceActive) {
						this.radioPrice = priceActive.id;
					}

					this.prices.forEach((item) => {
						item.create = false;
						item.delete = false;
					});
				} else {
					let debbugStory = {
						title: "Ошибка.",
						body: response.data.message,
						type: "Error",
					};
					this.$store.commit("debuggerState", debbugStory);
				}
			})
			.catch((error) => {
				console.log(error);
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

.eprices > .item.active {
	background-color: #f2feff;
	border: 2px solid var(--input-border-color-active);
}

.eprices > .item.delete {
	border: 2px solid #ec7b7b;
}

.eprices > .item.delete:has(.info > .radio) {
	border: 2px solid #ec7b7b;
	background-color: #fff2f2;
}

.eprices > .item.create {
	border: 2px solid var(--create-border-color);
}

.eprices > .item.create:has(.info > .radio) {
	border: 2px solid var(--create-border-color);
	background-color: var(--create-background-color);
}

.eprices > .item > .buttons {
	display: flex;
	align-items: center;
	gap: 10px;
}

/* radio */
.radio {
	width: fit-content;
	display: flex;
	align-items: center;
}

.radio-label {
	margin-top: 3px;
	padding-left: 5px;
}

.radio-input {
	appearance: none;
	position: relative;
	width: 20px;
	height: 20px;
	background: #ffffff;
	border-radius: 10px;
	border: 2px solid #9a9a9a;
}

.radio-input {
	border-radius: 50%;
}

.radio-input::after {
	content: "\2714";
	position: absolute;
	top: 2px;
	left: 2px;
	width: 0px;
	height: 0px;
	font-size: 26px;
	overflow: hidden;
}

.radio-input::after {
	content: "\1F78B";
	color: white;
}

.radio-input::after {
	content: "";
	position: absolute;
	width: 0px;
	height: 0px;
	font-size: 20px;
	background: var(--primary-color);
	border-radius: 100px;
	background-repeat: no-repeat;
}

.radio-input:checked::after {
	width: 12px;
	height: 12px;
}
</style>
