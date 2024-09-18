<template>
	<info-bar>
		<template v-slot:title>Специалисты</template>
		<template v-slot:addreas>specialists</template>
	</info-bar>

	<block-once>
		<block-title>
			<template #title>Список врачей</template>
			<template #buttons>
				<icon-save :width="28" :height="28" @click="saveSpecialistHides" />
			</template>
		</block-title>

		<loader-child
			:isLoading="loading.loader"
			:minHeight="200"
			@loaderChildAfterLeave="loaderChildAfterLeave"
		/>

		<admin-specialists-table
			v-show="loading.table"
			:specialists="specialists"
			@touchHideSpecialist="hideSpecialist"
			@touchUseFilter="filterSpecialists"
			@touchRemoveSpecialist="removeSpecialist"
		/>

		<block-buttons>
			<button-default @click="$router.push('especialists/new')"> Добавить </button-default>
		</block-buttons>
	</block-once>
</template>

<script>
import InfoBar from "../../../components/ui/admin/InfoBar.vue";

import LoaderChild from "../../../components/includes/LoaderChild.vue";

import ElementInputLabel from "../../../components/ui/admin/ElementInputLabel.vue";
import BlockOnce from "../../../components/ui/admin/BlockOnce.vue";
import BlockTitle from "../../../components/ui/admin/BlockTitle.vue";
import BlockButtons from "../../../components/ui/admin/BlockButtons.vue";
import AdminSpecialistsTable from "./AdminSpecialistsAllTable.vue";
import SpanError from "../../../components/ui/admin/SpanError.vue";

import ButtonDefault from "../../../components/ui/admin/ButtonDefault.vue";
import ButtonRemove from "../../../components/ui/admin/ButtonRemove.vue";

import IconSave from "../../../components/icons/IconSave.vue";

import axios from "axios";

import { RouterView, RouterLink } from "vue-router";

export default {
	components: {
		InfoBar,
		LoaderChild,
		ElementInputLabel,
		BlockOnce,
		BlockTitle,
		BlockButtons,
		AdminSpecialistsTable,
		SpanError,
		ButtonDefault,
		ButtonRemove,
		IconSave,
		axios,
		RouterView,
		RouterLink,
	},
	data() {
		return {
			loading: {
				loader: true,
				table: false,
			},
			specialists: [],
		};
	},
	methods: {
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                   Загрузчик                       |*/
		/* |___________________________________________________|*/
		/* После скрытия элементы */
		loaderChildAfterLeave() {
			this.loading.table = true;
		},
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                  Специалисты                      |*/
		/* |___________________________________________________|*/
		/* _____________________________________________________*/
		/* 1. Фильтрация                                        */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		filterSpecialists(column, type) {
			switch (column) {
				case "id":
					if (type == "default") {
						this.specialists.sort((a, b) => {
							if (a.id > b.id) {
								return 1;
							}
							if (a.id < b.id) {
								return -1;
							}
							// a должно быть равным b
							return 0;
						});
					}

					if (type == "reverse") {
						this.specialists.sort((a, b) => {
							if (a.id < b.id) {
								return 1;
							}
							if (a.id > b.id) {
								return -1;
							}
							// a должно быть равным b
							return 0;
						});
					}
					break;
				case "name":
					if (type == "default") {
						this.specialists.sort((a, b) => {
							let aName = a.name.toLowerCase();
							let bName = b.name.toLowerCase();

							let aNameFirstLetter = aName[0].charCodeAt(0);
							let bNameFirstLetter = bName[0].charCodeAt(0);

							if (aNameFirstLetter > bNameFirstLetter) {
								return 1;
							}
							if (aNameFirstLetter < bNameFirstLetter) {
								return -1;
							}
							// a должно быть равным b
							return 0;
						});
					}

					if (type == "reverse") {
						this.specialists.sort((a, b) => {
							let aName = a.name.toLowerCase();
							let bName = b.name.toLowerCase();

							let aNameFirstLetter = aName[0].charCodeAt(0);
							let bNameFirstLetter = bName[0].charCodeAt(0);

							if (aNameFirstLetter < bNameFirstLetter) {
								return 1;
							}
							if (aNameFirstLetter > bNameFirstLetter) {
								return -1;
							}
							// a должно быть равным b
							return 0;
						});
					}

					break;
				case "specialization":
					if (type == "default") {
						this.specialists.sort((a, b) => {
							let aName = a.specializations.toLowerCase();
							let bName = b.specializations.toLowerCase();

							let aNameFirstLetter = aName.charCodeAt(0);
							let bNameFirstLetter = bName.charCodeAt(0);

							if (aNameFirstLetter > bNameFirstLetter) {
								return 1;
							}
							if (aNameFirstLetter < bNameFirstLetter) {
								return -1;
							}
							// a должно быть равным b
							return 0;
						});
					}

					if (type == "reverse") {
						this.specialists.sort((a, b) => {
							let aName = a.specializations.toLowerCase();
							let bName = b.specializations.toLowerCase();

							let aNameFirstLetter = aName.charCodeAt(0);
							let bNameFirstLetter = bName.charCodeAt(0);

							if (aNameFirstLetter < bNameFirstLetter) {
								return 1;
							}
							if (aNameFirstLetter > bNameFirstLetter) {
								return -1;
							}
							// a должно быть равным b
							return 0;
						});
					}

					break;
				case "hide":
					if (type == "default") {
						this.specialists.sort((a, b) => {
							if (a.hide < b.hide) {
								return 1;
							}
							if (a.hide > b.hide) {
								return -1;
							}
							// a должно быть равным b
							return 0;
						});
					}

					if (type == "reverse") {
						this.specialists.sort((a, b) => {
							if (a.hide > b.hide) {
								return 1;
							}
							if (a.hide < b.hide) {
								return -1;
							}
							// a должно быть равным b
							return 0;
						});
					}

					break;
				default:
					break;
			}
		},
		/* _____________________________________________________*/
		/* 2. Сохранение, обновление, удаление                  */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		// Скрытие выбранного доктора
		hideSpecialist(selectedSpecialist) {
			let specialistToHide = this.specialists.filter((specialist) => {
				if (selectedSpecialist.id === specialist.id) {
					return specialist;
				}
			});

			specialistToHide[0].hide = !specialistToHide[0].hide;
		},
		// Удаление выбранного доктора
		removeSpecialist(id) {
			let specialistToDelete = this.specialists.filter((specialist) => {
				if (specialist.id === id) {
					return specialist;
				}
			});

			specialistToDelete[0].delete = !specialistToDelete[0].delete;
		},
		// Скрытие выбранного доктора
		saveSpecialistHides() {
			// Получение массива докторов с сервера
			axios({
				method: "post",
				headers: {
					Accept: "application/json",
					Authorization: `Bearer ${localStorage.getItem("token")}`,
				},
				data: {
					specialists: this.specialists,
				},
				url: `${this.$store.state.axios.urlApi}` + `save-specialists-hides`,
			})
				.then((response) => {
					let debbugStory = {
						title: "Успешно!",
						body: "Данные о специалистах обновлены в базе данных.",
						type: "Completed",
					};
					this.$store.commit("debuggerState", debbugStory);
				})
				.catch((error) => {
					let debbugStory = {
						title: "Ошибка.",
						body: "Произошла ошибка при сохранении изменений.",
						type: "Error",
					};
					this.$store.commit("debuggerState", debbugStory);
					return;
				});
		},
	},
	mounted() {
		// Получение массива докторов с сервера
		axios({
			method: "post",
			headers: {
				Accept: "application/json",
			},
			url: `${this.$store.state.axios.urlApi}` + `get-specialists-short`,
		})
			.then((response) => {
				this.specialists = response.data;

				this.loading.loader = false;
			})
			.catch((error) => {
				console.log(error);
			});
	},
};
</script>

<style scoped></style>
