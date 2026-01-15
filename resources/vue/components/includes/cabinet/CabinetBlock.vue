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
						{
							label: 'A3',
							value: 'A3',
						},
					]"
				/>

				<VueCascader
					v-model="fontSize"
					:name="'font-size'"
					:list="[
						{
							label: '12px',
							value: '12',
						},
						{
							label: '14px',
							value: '14',
						},
						{
							label: '16px',
							value: '16',
						},
						{
							label: '18px',
							value: '18',
						},
						{
							label: '20px',
							value: '20',
						},
					]"
				/>
			</div>

			<VuePrint
				v-model="currentItem.data.description.value"
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
							lineHeight: `${lineHeight}px`,
							fontFamily: `${fontFamily}`,
						},
						'blockquote': {
							padding: '0px',
							margin: '0px',
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

	<BlockOnce :minHeight="'150px'">
		<template #body>
			<div class="cabinet-block">
				<div class="cabinet-block__filters">
					<div class="filters__item">
						<VueValues v-model="filters.text" :type="'search'" :placeholder="'Введите текст'" />
					</div>

					<div class="filters__item">
						<VueSelector
							v-model="filters.title"
							:placeholder="'Выберите заголовок'"
							:list="getListNaims"
							:is-clear="true"
							:is-search="true"
						/>
					</div>
				</div>

				<!-- Список элементов -->
				<template v-if="loading.sections.list">
					<div class="cabinet-block__list" v-if="list.length > 0">
						<div class="cabinet-block__list-item" v-for="item in getList">
							<div class="cabinet-block__item-label">
								{{ item.date }}
							</div>

							<div class="cabinet-block__item-list">
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
																fontSize: `${fontSize}px`,
																marginBottom: `${marginBottom}px`,
																lineHeight: `${lineHeight}px`,
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

			<template v-if="loading.sections.list">
				<Empty v-if="list.length <= 0" :minHeight="'200px'" />
			</template>

			<!-- Загрузка элементов -->
			<VueLoader :isLoading="loading.loader.list" :isChild="true" @afterLeave="loaderChildAfterLeave" />
		</template>
	</BlockOnce>
</template>

<script>
import VuePrint from "../../../components/modules/VuePrint.vue";

import VueAccordeon from "../../../components/modules/accordeon/VueAccordeon.vue";
import VueAccordeonBlock from "../../../components/modules/accordeon/VueAccordeonBlock.vue";
import VueAccordeonButton from "../../../components/modules/accordeon/VueAccordeonButton.vue";

import Empty from "../../../components/modules/Empty.vue";
import BlockOnce from "../../../components/ui/admin/blocks/BlockOnce.vue";

import capi from "../../../mixin/capi";
import shared from "../../../services/shared";

export default {
	components: {
		VuePrint,

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
			/* Настройки */
			direction: "portrait",
			template: "A4",

			/* Фильтры */
			filters: {
				text: "",
				title: "",
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

			fontSize: '14',
			marginBottom: 10,
			lineHeight: 20,
			fontFamily: "Times New Roman",

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
			let days = new Set();

			for (let i = 0; i < this.list.length; i++) {
				days.add(this.list[i].date);
			}

			let array = [];

			days.forEach((day) => {
				array.push({
					date: new Date(day).toLocaleString("ru", { day: "numeric", month: "numeric", year: "numeric" }),
					list: this.list.filter((item) => item.date == day),
				});
			});

			/* Фильтрация: по наименованию */
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

			/* Фильтрация: по наименованию */
			if (this.filters.title) {
				for (let i = 0; i < array.length; i++) {
					array[i].list = array[i].list.filter((item) => item.title == this.filters.title);
				}
			}

			/* Очистка пустых ячеек массива */
			array = array.filter((item) => item.list.length > 0);

			return array;
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
			window.print();
			document.head.removeChild(style);
		},

		/* Сброс настроек печати */
		resetPrintSettings() {
			this.fontSize = 16;
			this.marginBottom = 10;
			this.lineHeight = 20;
			this.fontFamily = "Times New Roman";
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
.print__modal-settings {
	position: sticky;
	top: 0;

	display: flex;
	flex-direction: row;
	flex-wrap: wrap;
	gap: var(--default-gap);
}

.cabinet-block {
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

.cabinet-block__list {
	display: flex;
	flex-direction: column;
	gap: var(--default-gap);

	animation: show-bottom-to-top-15 0.4s ease-in-out;
}

.cabinet-block__list-item {
	display: flex;
	flex-direction: column;
	gap: calc(var(--default-gap) / 2);
}

.cabinet-block__item-label {
	font-size: 1.5rem;

	color: var(--primary-color);
}

.cabinet-block__item-list {
	display: flex;
	flex-direction: column;
	gap: calc(var(--default-gap) / 2);
}

@media screen and (width <= 1200px) {
	.cabinet-block__filters {
		grid-template-columns: 1fr;
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
