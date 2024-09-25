<template>
	<info-bar>
		<template v-slot:title>Специалисты</template>
		<template v-slot:addreas>specialists</template>
	</info-bar>

	<block-once>
		<block-title>
			<template #title>Список врачей</template>
			<template #buttons>
				<icon-save :width="28" :height="28" @click="saveSpecialistChanges" />
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

import ElementInputLabel from "../../../components/ui/admin/elements/ElementInputLabel.vue";
import BlockOnce from "../../../components/ui/admin/blocks/BlockOnce.vue";
import BlockTitle from "../../../components/ui/admin/blocks/BlockTitle.vue";
import BlockButtons from "../../../components/ui/admin/blocks/BlockButtons.vue";
import AdminSpecialistsTable from "./AdminSpecialistsAllTable.vue";
import SpanError from "../../../components/ui/admin/SpanError.vue";

import ButtonDefault from "../../../components/ui/admin/buttons/ButtonDefault.vue";
import ButtonRemove from "../../../components/ui/admin/buttons/ButtonRemove.vue";

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
			// Объявляем объект Intl.Collator, который обеспечивает сравнение строк с учётом языка.
			const collator = new Intl.Collator("ru");

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
							let aFullName = a.family + " " + a.name + " " + a.surname;
							let bFullName = b.family + " " + b.name + " " + b.surname;

							return collator.compare(aFullName, bFullName);
						});
					}

					if (type == "reverse") {
						this.specialists.reverse((a, b) => {
							let aFullName = a.family + " " + a.name + " " + a.surname;
							let bFullName = b.family + " " + b.name + " " + b.surname;

							return collator.compare(aFullName, bFullName);
						});
					}

					break;
				case "specialization":
					if (type == "default") {
						this.specialists.sort((a, b) => {
							return collator.compare(a.specializations, b.specializations);
						});
					}

					if (type == "reverse") {
						this.specialists.reverse((a, b) => {
							return collator.compare(a.specializations, b.specializations);
						});
					}

					break;
				case "hide":
					if (type == "default") {
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

					if (type == "reverse") {
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
		saveSpecialistChanges() {
			let newArray = [];

			for (let key in this.specialists) {
				newArray.push(Object.assign({}, this.specialists[key]));
			}

			newArray.sort((a, b) => {
				if (a.id > b.id) {
					return 1;
				} else if (a.id < b.id) {
					return -1;
				} else {
					return 0;
				}
			});

			// Получение массива докторов с сервера
			axios({
				method: "post",
				url: `${this.$store.state.axios.urlApi}` + `save-specialists-changes`,
				headers: {
					Accept: "application/json",
					Authorization: `Bearer ${localStorage.getItem("token")}`,
				},
				data: {
					specialists: newArray,
				},
			})
				.then((response) => {
					try {
						// Получения нового массива, с данными помеченными на удаление
						let res = this.specialists.filter((item) => {
							if (item.delete == true) {
								return Object.assign({}, item);
							}
						});

						// Повторять, пока не будут удалены все элементы, помеченные на удаление
						while (res.length > 0) {
							/* Получение индекса элемента, помеченного на удаление из массива специалистов */
							this.specialists.splice(this.specialists.indexOf(res[0]), 1);
							/* Обновление списка с элементами, помеченными на удаление */
							res = this.specialists.filter((item) => {
								if (item.delete == true) {
									return Object.assign({}, item);
								}
							});
						}

						let debbugStory = {
							title: "Успешно!",
							body: "Данные сохранились.",
							type: "Completed",
						};
						this.$store.commit("debuggerState", debbugStory);
					} catch (error) {
						let debbugStory = {
							title: "Ошибка.",
							body: "После сохранения что-то пошло не так.",
							type: "Error",
						};
						this.$store.commit("debuggerState", debbugStory);
					}
				})
				.catch((error) => {
					let debbugStory = {
						title: "Ошибка.",
						body: "Данные почему-то не сохранились.",
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

				for (let key in this.specialists) {
					this.specialists[key].delete = false;
				}

				this.loading.loader = false;
			})
			.catch((error) => {
				console.log(error);
			});
	},
};
</script>

<style scoped></style>
