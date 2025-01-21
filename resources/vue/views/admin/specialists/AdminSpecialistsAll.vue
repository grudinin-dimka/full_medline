<template>
	<info-bar>
		<template v-slot:title>Специалисты</template>
		<template v-slot:addreas>specialists</template>
	</info-bar>

	<block-once :minHeight="200">
		<block-title>
			<template #title>Список врачей</template>
			<template #buttons>
				<icon-load :width="28" :height="28" v-if="disabled.specialists.save" />
				<icon-save :width="28" :height="28" @click="saveSpecialistChanges" v-else />
			</template>
		</block-title>

		<div class="container-specialists" v-if="loading.table">
			<admin-specialists-table
				:specialists="getSpecialists"
				@touchHideSpecialist="hideSpecialist"
				@touchUseFilter="filterSpecialists"
				@touchRemoveSpecialist="removeSpecialist"
			/>

			<pagination
				v-if="specialists.length > 0"
				:arrayLength="specialists.length"
				:settings="paginationSpecialists"
				@changePage="changePageSpecialists"
			/>
		</div>

		<loader-child
			:isLoading="loading.loader"
			:minHeight="300"
			@loaderChildAfterLeave="loaderChildAfterLeave"
		/>

		<block-buttons>
			<button-default @click="$router.push('especialists/new')"> Добавить </button-default>
		</block-buttons>
	</block-once>
</template>

<script>
import InfoBar from "../../../components/ui/admin/InfoBar.vue";

import LoaderChild from "../../../components/includes/LoaderChild.vue";
import Pagination from "../../../components/ui/admin/pagination/Pagination.vue";

import ElementInputLabel from "../../../components/ui/admin/elements/ElementInputLabel.vue";
import BlockOnce from "../../../components/ui/admin/blocks/BlockOnce.vue";
import BlockTitle from "../../../components/ui/admin/blocks/BlockTitle.vue";
import BlockButtons from "../../../components/ui/admin/blocks/BlockButtons.vue";
import AdminSpecialistsTable from "./AdminSpecialistsAllTable.vue";
import SpanError from "../../../components/ui/admin/SpanError.vue";

import ButtonDefault from "../../../components/ui/admin/buttons/ButtonDefault.vue";
import ButtonRemove from "../../../components/ui/admin/buttons/ButtonRemove.vue";

import IconSave from "../../../components/icons/IconSave.vue";
import IconLoad from "../../../components/icons/IconLoad.vue";

import axios from "axios";
import sorted from "../../../services/sorted";
import shared from "../../../services/shared";

import { RouterView, RouterLink } from "vue-router";

export default {
	components: {
		InfoBar,
		LoaderChild,
		Pagination,
		ElementInputLabel,
		BlockOnce,
		BlockTitle,
		BlockButtons,
		AdminSpecialistsTable,
		SpanError,
		ButtonDefault,
		ButtonRemove,
		IconSave,
		IconLoad,
		axios,
		sorted,
		shared,
		RouterView,
		RouterLink,
	},
	data() {
		return {
			loading: {
				loader: true,
				table: false,
			},
			disabled: {
				specialists: {
					save: false,
				},
			},
			paginationSpecialists: {
				pages: {
					current: 1,
					range: 5,
				},
				elements: {
					range: 10,
				},
			},
			specialists: [],
		};
	},
	computed: {
		getSpecialists() {
			return [...this.specialists].splice(
				(this.paginationSpecialists.pages.current - 1) *
					this.paginationSpecialists.elements.range,
				this.paginationSpecialists.elements.range
			);
		},
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
		/* |                    ПАГИНАЦИЯ                      |*/
		/* |___________________________________________________|*/
		/* _____________________________________________________*/
		/* 1. Основные действия                                 */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		/* Изменение текущей страницы */
		changePageSpecialists(pageNumber) {
			// Проверка на превышение количества страниц
			if (
				pageNumber >
				Math.ceil(this.specialists.length / this.paginationSpecialists.elements.range)
			) {
				return;
			} else if (pageNumber < 1) {
				return;
			}

			this.paginationSpecialists.pages.current = pageNumber;
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
					if (type == "default") sorted.sortById("up", this.specialists);
					if (type == "reverse") sorted.sortById("down", this.specialists);
					break;
				case "name":
					if (type == "default") sorted.sortByName("up", this.specialists);
					if (type == "reverse") sorted.sortByName("down", this.specialists);
					break;
				case "specialization":
					if (type == "default") sorted.sortBySpecialization("up", this.specialists);
					if (type == "reverse") sorted.sortBySpecialization("down", this.specialists);
					break;
				case "hide":
					if (type == "default") sorted.sortByHide("up", this.specialists);
					if (type == "reverse") sorted.sortByHide("down", this.specialists);
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
			this.disabled.specialists.save = true;

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
						this.disabled.specialists.save = false;
						shared.clearDeletes(this.specialists);

						let debbugStory = {
							title: "Успешно!",
							body: "Данные сохранились.",
							type: "Completed",
						};
						this.$store.commit("debuggerState", debbugStory);
					} catch (error) {
						this.disabled.specialists.save = false;

						let debbugStory = {
							title: "Ошибка.",
							body: "После сохранения что-то пошло не так.",
							type: "Error",
						};
						this.$store.commit("debuggerState", debbugStory);
					}
				})
				.catch((error) => {
					this.disabled.specialists.save = false;

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

<style scoped>
.container-specialists {
	display: flex;
	flex-direction: column;
	gap: 10px;
}
</style>
