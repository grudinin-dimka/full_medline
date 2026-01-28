<template>
	<!-- Модальное окно: Печать -->
	<VueModal ref="modalPrint" :settings="modalPrint">
		<template #title>
			{{ modalPrint.values.title }}
		</template>

		<template #body>
			<div class="print__modal-settings">
				<VueCascader
					v-model="direction"
					:name="'direction'"
					:list="[
						{
							label: 'Вертикальное',
							value: 'portrait',
						},
						{
							label: 'Горизонтальное',
							value: 'landscape',
						},
					]"
				/>

				<VueCascader
					v-model="template"
					:name="'template'"
					:list="[
						{
							label: 'A5',
							value: 'A5',
						},
						{
							label: 'A4',
							value: 'A4',
						},
					]"
				/>

				<VueCascader
					v-model="fontSize"
					:name="'font-size'"
					:list="[
						{
							label: '12',
							value: '12',
						},
						{
							label: '14',
							value: '14',
						},
						{
							label: '16',
							value: '16',
						},
					]"
				/>

				<VueCascader
					v-model="lineHeight"
					:name="'interval'"
					:list="[
						{
							label: '1.0',
							value: '1.0',
						},
						{
							label: '1.5',
							value: '1.5',
						},
						{
							label: '2.0',
							value: '2.0',
						},
					]"
				/>

				<VueButton :look="'inverse'" @click="resetPrint" class="flexEnd">
					<VueIcon :name="'Refresh'" :fill="'var(--primary-color)'" :width="'28px'" :height="'28px'" />
					Сбросить
				</VueButton>
			</div>

			<VuePrint
				v-model="currentItem.data.description.value"
				ref="printModal"
				:settings="{
					direction: direction,
					template: template,
					styles: {
						'div': {
							width: '100%',
						},
						'*': {
							fontSize: `${fontSize}px`,
							marginBottom: `${marginBottom}px`,
							lineHeight: `${lineHeight}em`,
							fontFamily: `${fontFamily}`,
						},
						'table': {
							borderCollapse: 'collapse',
							width: '100%',
						},
						'.Apple-tab-span': {
							whiteSpace: 'normal',
						},
						'tr': {
							width: 'auto',
						},
						'th': {
							padding: '2.5px 5px',
							width: 'auto',
						},
						'td': {
							padding: '2.5px 5px',
							width: 'auto',
						},
					},
				}"
			/>
		</template>

		<template #footer>
			<VueButton @click="printWindow">
				<VueIcon :name="'Print'" :fill="'white'" :width="'28px'" :height="'28px'" />
				Печать
			</VueButton>
		</template>
	</VueModal>

	<BlockOnce :minHeight="'100px'">
		<template #body>
			<div class="filters">
				<VueValues class="filters__item text" v-model="filters.text" :type="'search'" :placeholder="'Введите текст'" />

				<VueSelector
					class="filters__item title"
					v-model="filters.title"
					:placeholder="'Выберите заголовок'"
					:list="getListNaims"
					:is-clear="true"
					:is-search="true"
				/>

				<VueDate class="filters__item date" v-model="filters.date" :type="'date'" :placeholder="'Выберите дату'" />
			</div>
		</template>
	</BlockOnce>

	<BlockOnce :minHeight="'100px'">
		<template #body>
			<div class="cabinet">
				<div class="cabinet__pagination" v-if="loading.sections.list">
					<div class="cabinet__pagination-count">{{ getListSlice.length }} записей из {{ getList.length }}</div>
					<div class="cabinet__pagination-pagination">
						<VuePagination :settings="settings" :arrayLength="getList.length" @changePage="changePage" />
					</div>
				</div>

				<div class="cabinet__block">
					<!-- Список элементов -->
					<template v-if="loading.sections.list">
						<div class="cabinet__dates" v-if="getList.length > 0">
							<div class="cabinet__block-date" v-for="item in getListSlice">
								<div class="cabinet__block-date-label">
									<VueIcon
										:name="'Calendar Clock'"
										:fill="'var(--primary-color)'"
										:width="'24px'"
										:height="'24px'"
									/>
									{{
										new Date(item.date).toLocaleString("ru", { day: "numeric", month: "numeric", year: "numeric" })
									}}
								</div>

								<div class="cabinet__block-date-list">
									<VueAccordeon v-for="value in item.list">
										<template #name> {{ value.title }} </template>

										<template #buttons>
											<VueAccordeonButton @click="openModalPrint(value)">
												<VueIcon :name="'Print'" :fill="'black'" :width="'24px'" :height="'24px'" />
											</VueAccordeonButton>
										</template>

										<template #body>
											<VueAccordeonBlock>
												<div class="travels__once-description-block">
													<VuePrint
														v-model="value.description"
														:settings="{
															width: '100%',
															clear: {
																spacing: true,
																styles: true,
															},
															styles: {
																'*': {
																	fontSize: `${14}px`,
																	marginBottom: `${10}px`,
																	lineHeight: `${20}px`,
																},
																'table': {
																	borderCollapse: 'collapse',
																	borderSpacing: '0',
																},
																'td': {
																	padding: '5px 10px',
																},
															},
														}"
													/>
												</div>
											</VueAccordeonBlock>
										</template>
									</VueAccordeon>
								</div>
							</div>
						</div>
					</template>
				</div>
			</div>

			<template v-if="loading.sections.list">
				<Empty v-if="getList.length <= 0" :minHeight="'244px'" />
			</template>

			<!-- Загрузка элементов -->
			<VueLoader
				:minHeight="'244px'"
				:isLoading="loading.loader.list"
				:isChild="true"
				@afterLeave="loaderChildAfterLeave"
			/>
		</template>
	</BlockOnce>
</template>

<script>
import VuePrint from "../../../components/modules/VuePrint.vue";
import VuePagination from "../../modules/VuePagination.vue";

import VueAccordeon from "../../../components/modules/accordeon/VueAccordeon.vue";
import VueAccordeonBlock from "../../../components/modules/accordeon/VueAccordeonBlock.vue";
import VueAccordeonButton from "../../../components/modules/accordeon/VueAccordeonButton.vue";

import Empty from "../../../components/modules/Empty.vue";
import BlockOnce from "../../../components/ui/admin/blocks/BlockOnce.vue";

import capi from "../../../mixin/capi";
import shared from "../../../services/shared";
import sorted from "../../../services/sorted";

export default {
	components: {
		VuePrint,
		VuePagination,

		VueAccordeon,
		VueAccordeonBlock,
		VueAccordeonButton,

		Empty,
		BlockOnce,
	},
	props: {
		page: {
			type: String,
			required: true,
			default: "",
		},
	},
	data() {
		return {
			/* Пагинация */
			settings: {
				pages: {
					range: 3,
					current: 1,
				},
				elements: {
					range: 10,
				},
			},

			watchWide: window.matchMedia("(max-width: 500px)").matches,

			/* Настройки */
			direction: "portrait",
			template: "A4",
			lineHeight: "1.5",
			fontSize: "14",
			marginBottom: 10,
			fontFamily: "Times New Roman",

			/* Фильтры */
			filters: {
				text: "",
				title: "",
				date: "",
			},

			/* Загрузчик */
			loading: {
				loader: {
					list: true,
				},
				sections: {
					list: false,
				},
			},

			/* Модальное окно */
			modalPrint: {
				thin: false,
				clamped: true,
				touch: true,
				fullscreen: true,
				print: true,
				values: {
					title: "",
					look: "default",
				},
			},

			/* Форма */
			currentItem: {
				errors: {
					date: {
						status: false,
						message: null,
					},
					title: {
						status: false,
						message: null,
					},
					description: {
						status: false,
						message: null,
					},
				},
				data: {
					id: {
						value: "",
						edited: false,
					},
					date: {
						value: "",
						edited: false,
					},
					title: {
						value: "",
						edited: false,
					},
					type: {
						value: "",
						edited: false,
					},
					description: {
						value: "",
						edited: false,
					},
				},
			},

			/* Данные */
			list: [],
		};
	},
	computed: {
		getCurrentMonth() {
			return new Date(this.month).getMonth() + 1;
		},

		getCurrentYear() {
			return new Date(this.month).getFullYear();
		},

		getList() {
			// 1. Группировка по дням
			let days = new Set();

			for (let i = 0; i < this.list.length; i++) {
				days.add(this.list[i].date);
			}

			let array = [];

			days.forEach((day) => {
				array.push({
					date: day,
					list: this.list.filter((item) => item.date == day),
				});
			});

			// 2. Фильтрация: по тексту
			if (this.filters.text) {
				for (let i = 0; i < array.length; i++) {
					array[i].list = array[i].list.filter((item) => {
						let lowerNaim = item.title.toLowerCase();
						let lowerPriem = item.description.toLowerCase();

						return (
							lowerNaim.includes(this.filters.text.toLowerCase()) ||
							lowerPriem.includes(this.filters.text.toLowerCase())
						);
					});
				}
			}

			/* 3. Фильтрация: по наименованию */
			if (this.filters.title) {
				for (let i = 0; i < array.length; i++) {
					array[i].list = array[i].list.filter((item) => item.title == this.filters.title);
				}
			}

			/* 4. Фильтрация: по дате */
			if (this.filters.date) {
				let date = new Date(this.filters.date);
				date.setHours(0, 0, 0);

				for (let i = 0; i < array.length; i++) {
					array[i].list = array[i].list.filter((item) => {
						let itemDate = new Date(item.date);
						itemDate.setHours(0, 0, 0);

						return itemDate.getTime() == date.getTime();
					});
				}
			}

			/* 5. Очистка пустых ячеек массива */
			array = array.filter((item) => item.list.length > 0);

			/* 6. Сортировка */
			sorted.sortDateByKey("down", array, "date");

			return array;
		},

		getListSlice() {
			let start = (this.settings.pages.current - 1) * this.settings.elements.range;
			let end = this.settings.pages.current * this.settings.elements.range;

			return this.getList.slice(start, end);
		},

		getListNaims() {
			let list = new Set();

			for (let i = 0; i < this.list.length; i++) {
				list.add(this.list[i].title);
			}

			let array = [];

			list.forEach((title) => {
				array.push({
					value: title,
					label: title,
				});
			});

			return array;
		},
	},
	methods: {
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                   Загрузчик                       |*/
		/* |___________________________________________________|*/
		/* После скрытия элементы */
		loaderChildAfterLeave() {
			this.loading.sections.list = true;
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
		openModalPrint(object) {
			shared.clearObjectFull(this.currentItem);
			shared.setData(object, this.currentItem);

			this.openModal("modalPrint", object.title, "default");
		},

		/* Печать */
		printWindow() {
			const style = document.createElement("style");

			style.innerHTML = `
				@media print {
					@page {
						size: ${this.template} ${this.direction};
						margin: 10mm;
					}
				}
			`;

			document.head.appendChild(style);
			this.$refs.printModal.print();
			document.head.removeChild(style);
		},

		/* Сброс настроек печати */
		resetPrint() {
			this.direction = "portrait";
			this.template = "A4";
			this.lineHeight = "1.5";
			this.fontSize = "14";
			this.marginBottom = 10;
			this.fontFamily = "Times New Roman";
		},

		/* Переключение страниц */
		changePage(page) {
			let main = document.querySelector("main");

			main.scrollTo({
				top: 0,
				behavior: "smooth",
			});

			if (page > Math.ceil(this.list.length / this.settings.elements.range)) {
				return;
			} else if (page < 1) {
				return;
			}

			this.settings.pages.current = page;
		},
	},
	mounted() {
		capi({
			method: "post",
			url: `documents`,
			data: {
				type: this.page,
			},
		})
			.then((response) => {
				if (!response) return;

				this.list = response.data.result;
			})
			.catch((error) => {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			})
			.finally(() => {
				this.loading.loader.list = false;
			});
	},
};
</script>

<style scoped>
.filters {
	display: grid;
	grid-template-columns: 1fr 1fr;
	grid-template-areas:
		"text title"
		"date date";
	gap: calc(var(--default-gap) / 2);
}

.filters__item.text {
	grid-area: text;
}

.filters__item.title {
	grid-area: title;
}

.filters__item.date {
	grid-area: date;
}

.print__modal-settings {
	z-index: 1;
	position: sticky;
	top: 0;

	display: flex;
	flex-direction: row;
	flex-wrap: wrap;
	gap: var(--default-gap);
}

.cabinet {
	display: flex;
	flex-direction: column;
	gap: calc(var(--default-gap) / 2);
}

.cabinet__pagination {
	display: flex;
	justify-content: space-between;
	align-items: center;
	gap: var(--default-gap);
}

.cabinet__pagination-count {
	font-size: 1.25rem;
}

.cabinet__block {
	display: flex;
	flex-direction: column;
	gap: var(--default-gap);
}

.cabinet-block__filters {
	display: grid;
	grid-template-columns: 1fr 1fr;
	gap: calc(var(--default-gap) / 2);
}

.filters__item:first-of-type {
	flex: 1 0 auto;
}

.filters__item:last-of-type {
	flex: 1 0 auto;
}

.cabinet__dates {
	display: flex;
	flex-direction: column;
	gap: var(--default-gap);

	animation: show-bottom-to-top-15 0.4s ease-in-out;
}

.cabinet__block-date {
	display: flex;
	flex-direction: column;
	gap: calc(var(--default-gap) / 2);
}

.cabinet__block-date-label {
	display: flex;
	flex-direction: row;
	gap: calc(var(--default-gap) / 2);
	align-items: center;

	font-size: 1.5rem;

	color: var(--primary-color);
}

.cabinet__block-date-list {
	display: flex;
	flex-direction: column;
	gap: calc(var(--default-gap) / 2);
}

.flexEnd {
	margin-left: auto;
}

.travels__once-description-block {
	position: relative;

	max-height: 300px;
	overflow: hidden;
}

.travels__once-description-block::after {
	content: "";

	position: absolute;
	top: 0;
	right: 0;
	left: 0;
	bottom: 0;

	background: #ffffff;
	background: linear-gradient(180deg, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 1) 100%);
}

@media screen and (width <= 1200px) {
	.cabinet-block__filters {
		grid-template-columns: 1fr;
	}

	.cabinet__pagination {
		justify-content: center;
		order: 2;
	}

	.cabinet__pagination-count {
		display: none;
	}

	.cabinet__block {
		order: 1;
	}
}

@media screen and (width <= 700px) {
	.filters {
		grid-template-columns: 1fr;
		grid-template-areas:
			"text"
			"title"
			"date";
	}
}

@media screen and (width <= 600px) {
	.print__modal-settings {
		display: none;
	}
}

@media print {
	.print__modal-settings {
		display: none;
	}
}
</style>
