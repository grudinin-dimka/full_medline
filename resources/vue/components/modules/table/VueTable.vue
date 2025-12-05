<template>
	<div class="vue-table">
		<div class="vue-table__header">
			<div class="vue-table__header-left">
				<select v-model="settings.elements.range">
					<option v-for="value in [10, 20, 30]">
						{{ value }}
					</option>
				</select>
				<span>из {{ table.body.length }} записей</span>
			</div>

			<div class="vue-table__header-right">
				<VueTableButtonHead @click="isFilter = !isFilter">
					<template v-if="!isFilter">
						<svg
							xmlns="http://www.w3.org/2000/svg"
							width="18"
							height="18"
							viewBox="0 0 24 24"
							fill="var(--primary-color)"
						>
							<path
								d="M9.33333 20V17.3333H14.6667V20H9.33333ZM4 13.3333V10.6667H20V13.3333H4ZM0 6.66667V4H24V6.66667H0Z"
							></path>
						</svg>

						<span>Вкл. фильтр полей</span>
					</template>

					<template v-else>
						<svg
							width="18"
							height="18"
							viewBox="0 0 24 24"
							fill="var(--primary-color)"
							xmlns="http://www.w3.org/2000/svg"
						>
							<g clip-path="url(#clip0_1_8)">
								<path
									d="M22.2749 24L0 1.72509L1.72509 0L24 22.2749L22.2749 24ZM17.4931 12.348L15.0719 9.92686H20.1261V12.348H17.4931ZM11.4401 6.29508L9.01892 3.8739H23.7579V6.29508H11.4401ZM10.4414 18.401V15.9798H15.2837V18.401H10.4414ZM5.59899 12.348V9.92686H10.623V12.348H5.59899ZM1.96721 6.29508V3.8739H4.56999V6.29508H1.96721Z"
								/>
							</g>
							<defs>
								<clipPath id="clip0_1_8">
									<rect width="24" height="24" fill="white" />
								</clipPath>
							</defs>
						</svg>

						<span>Выкл. фильтр полей</span>
					</template>
				</VueTableButtonHead>

				<VueTableButtonHead v-if="table.options.create" @click="$emit('create')">
					<slot name="create" v-if="$slots.create"></slot>

					<template v-else>
						<svg
							width="18"
							height="18"
							viewBox="0 0 24 24"
							fill="var(--primary-color)"
							xmlns="http://www.w3.org/2000/svg"
						>
							<path d="M11 21V13H3V11H11V3H13V11H21V13H13V21H11Z" />
						</svg>

						<span>Добавить</span>
					</template>
				</VueTableButtonHead>

				<div class="vue-table__search" :class="{ 'active': searchInput }">
					<div class="vue-table__search-icon">
						<svg
							xmlns="http://www.w3.org/2000/svg"
							height="24px"
							viewBox="0 -960 960 960"
							width="24px"
							v-if="!searchInput"
						>
							<path
								d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z"
							/>
						</svg>

						<svg
							xmlns="http://www.w3.org/2000/svg"
							height="24px"
							viewBox="0 -960 960 960"
							width="24px"
							@click="searchInput = ''"
							v-else
						>
							<path
								d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"
							/>
						</svg>
					</div>

					<input class="vue-table__search-input" v-model="searchInput" type="text" placeholder="Поиск" />
				</div>
			</div>
		</div>

		<!-- Таблица -->
		<table class="vue-table__table">
			<!--  Заголовки таблицы  -->
			<thead class="vue-table__thead">
				<tr class="vue-table__thead-row">
					<th
						class="vue-table__thead-cell"
						:style="{ width: deleteHeadsInTableHead(table.head)[key].columnSize }"
						v-for="(value, key) in deleteHeadsInTableHead(table.head)"
					>
						<div class="vue-table__cell-content">
							{{ value.title }}

							<div
								class="vue-table__content-sort"
								v-if="['id', 'string', 'number', 'list', 'boolean', 'time'].includes(value.type)"
							>
								<svg
									v-if="this.sorting.sortField == value.name"
									class="th__sort-icon"
									:class="{ rotate: this.sorting.sortType == 'up' }"
									width="16"
									height="16"
									viewBox="0 0 15 9"
									xmlns="http://www.w3.org/2000/svg"
									@click="changeTypeSort(value.name)"
								>
									<path
										d="M8.20711 0.792893C7.81658 0.402369 7.18342 0.402369 6.79289 0.792893L0.428932 7.15685C0.0384079 7.54738 0.0384079 8.18054 0.428932 8.57107C0.819456 8.96159 1.45262 8.96159 1.84315 8.57107L7.5 2.91421L13.1569 8.57107C13.5474 8.96159 14.1805 8.96159 14.5711 8.57107C14.9616 8.18054 14.9616 7.54738 14.5711 7.15685L8.20711 0.792893ZM8.5 2.5V1.5H6.5V2.5H8.5Z"
									/>
								</svg>

								<svg
									v-else
									class="th__sort-icon"
									width="24"
									height="24"
									viewBox="0 0 24 24"
									xmlns="http://www.w3.org/2000/svg"
									@click="changeTypeSort(value.name)"
								>
									<path d="M3 18V16H9V18H3ZM3 13V11H15V13H3ZM3 8V6H21V8H3Z" />
								</svg>
							</div>
						</div>
					</th>
					<th class="vue-table__thead-cell" style="width: 270px" v-if="this.table.options.update">
						<div>Действия</div>
					</th>
				</tr>

				<!-- Поля фильтра -->
				<tr class="vue-table__filter-row" v-if="isFilter">
					<td class="vue-table__filter-cell" v-for="(value, key) in filterFields">
						<!-- Фильтр: идентификатор, число -->
						<template v-if="['id', 'number'].includes(filterFields.find((field) => field.name === value.name).type)">
							<div class="vue-table__filter vue-table__filter--number">
								<VueNumber
									v-model="filterFields.find((field) => field.name === value.name).filter"
									:placeholder="'Поиск'"
								/>
							</div>
						</template>

						<!-- Фильтр: Строка -->
						<template v-else-if="['string'].includes(filterFields.find((field) => field.name === value.name).type)">
							<div class="vue-table__filter">
								<VueValues
									v-model="filterFields.find((field) => field.name === value.name).filter"
									:type="'text'"
									:placeholder="'Поиск'"
								/>

								<VueSelector
									v-model="filterFields.find((field) => field.name === value.name).register"
									:list="[
										{
											value: false,
											label: 'Без регистра',
										},
										{
											value: true,
											label: 'С регистром',
										},
									]"
									:size="2"
									:is-search="false"
									:is-clear="false"
								/>
							</div>
						</template>

						<!-- Фильтр: время -->
						<template v-else-if="['time'].includes(filterFields.find((field) => field.name === value.name).type)">
							<div class="vue-table__filter vue-table__filter--time">
								<VueDateTime
									v-model.trim="filterFields.find((field) => field.name === value.name).from"
									@change="filterChangedStatus(value, 'on')"
								>
								</VueDateTime>

								<VueDateTime
									v-model.trim="filterFields.find((field) => field.name === value.name).to"
									@change="filterChangedStatus(value, 'on')"
								>
								</VueDateTime>
							</div>
						</template>

						<!-- Фильтр: список -->
						<template v-else-if="['list'].includes(filterFields.find((field) => field.name === value.name).type)">
							<div class="vue-table__filter vue-table__filter--list">
								<VueValues
									v-model="filterFields.find((field) => field.name === value.name).filter"
									:type="'text'"
									:placeholder="'Поиск'"
								/>

								<!-- <input
									class="vue-table__filter-field"
									type="text"
									:id="`vue-table-` + value.name"
									:list="filterFields.find((field) => field.name === value.name).name"
									placeholder="Поиск"
									v-model="filterFields.find((field) => field.name === value.name).filter"
								/>
								<datalist
									:id="filterFields.find((field) => field.name === value.name).name"
								>
									<option
										v-for="value in filterFields.find(
											(field) => field.name === value.name
										).values"
									>
										{{ value.text }}
									</option>
								</datalist> -->
							</div>
						</template>

						<!-- Фильтр: не поддерживается -->
						<template v-else>
							<div></div>
						</template>
					</td>
					<td class="vue-table__filter-cell" v-if="this.table.options.update">
						<div></div>
					</td>
				</tr>
			</thead>

			<!--  Тело таблицы  -->
			<tbody class="vue-table__tbody" v-if="displayTable.length !== 0">
				<tr
					class="vue-table__tbody-row"
					v-for="row in displayTable"
					:class="{
						'vue-table__tbody-row--create': table.body.find(
							(item) => item[getNameOfColumnTypeById] == row[getNameOfColumnTypeById]
						).create,
						'vue-table__tbody-row--delete': table.body.find(
							(item) => item[getNameOfColumnTypeById] == row[getNameOfColumnTypeById]
						).delete,
					}"
				>
					<!-- Поля -->
					<td
						v-for="(value, key) in deleteOptionsInTableRow(row)"
						:style="getStyleOfField(key, 'column')"
						class="vue-table__tbody-cell"
					>
						<div :style="getStyleOfField(key, 'data')">
							<!-- Строка -->
							<template v-if="typeOfField(key) == 'id'">
								{{ value }}
							</template>

							<!-- Строка -->
							<template v-else-if="typeOfField(key) == 'string'">
								{{ formatString(value) }}
							</template>

							<!-- Число -->
							<template v-if="typeOfField(key) == 'number'">
								{{ formatNumber(value) }}
							</template>

							<!-- Логический -->
							<template v-else-if="typeOfField(key) == 'boolean'">
								{{ formatBoolean(value) }}
							</template>

							<!-- Время -->
							<template v-else-if="typeOfField(key) == 'time'">
								{{ formatDate(value) }}
							</template>

							<!-- Список -->
							<template v-else-if="typeOfField(key) == 'list'">
								{{
									table.head.find((field) => field.name == key)?.values.find((item) => item.value == value)
										?.label ?? "Не найдено"
								}}
							</template>

							<!-- Кастомный слот -->
							<template v-else-if="typeOfField(key) == 'slot'">
								<slot
									:name="key"
									v-bind:row="
										table.body.find((item) => item[getNameOfColumnTypeById] == row[getNameOfColumnTypeById])
									"
								></slot>
							</template>
						</div>
					</td>

					<!-- Действия -->
					<td class="vue-table__tbody-cell" v-if="table.options.update || table.options.delete">
						<div class="table__buttons">
							<template v-if="table.options.update">
								<VueTableButton
									:wide="true"
									v-if="table.options.update"
									@click="
										$emit(
											'edite',
											this.table.body.find(
												(item) => item[getNameOfColumnTypeById] == row[getNameOfColumnTypeById]
											)
										)
									"
								>
									<slot name="edit" v-if="$slots.edit"></slot>

									<template v-else>
										<svg
											width="22"
											height="22"
											viewBox="0 0 24 24"
											fill="white"
											xmlns="http://www.w3.org/2000/svg"
										>
											<path
												d="M5 19H6.425L16.2 9.225L14.775 7.8L5 17.575V19ZM3 21V16.75L16.2 3.575C16.4 3.39167 16.6208 3.25 16.8625 3.15C17.1042 3.05 17.3583 3 17.625 3C17.8917 3 18.15 3.05 18.4 3.15C18.65 3.25 18.8667 3.4 19.05 3.6L20.425 5C20.625 5.18333 20.7708 5.4 20.8625 5.65C20.9542 5.9 21 6.15 21 6.4C21 6.66667 20.9542 6.92083 20.8625 7.1625C20.7708 7.40417 20.625 7.625 20.425 7.825L7.25 21H3ZM15.475 8.525L14.775 7.8L16.2 9.225L15.475 8.525Z"
											/>
										</svg>

										<span>Изменить</span>
									</template>
								</VueTableButton>
							</template>

							<template v-if="table.options.delete">
								<VueTableButton
									:wide="true"
									:look="
										this.table.body.find((item) => item[getNameOfColumnTypeById] == row[getNameOfColumnTypeById])
											.create
											? 'disabled'
											: 'delete'
									"
									v-if="
										table.options.delete &&
										!table.body.find((item) => item[getNameOfColumnTypeById] == row[getNameOfColumnTypeById])
											.delete
									"
									@click="
										$emit(
											'delete',
											this.table.body.find(
												(item) => item[getNameOfColumnTypeById] == row[getNameOfColumnTypeById]
											)
										)
									"
								>
									<slot name="delete" v-if="$slots.delete"></slot>

									<template v-else>
										<svg
											width="22"
											height="22"
											viewBox="0 0 24 24"
											fill="white"
											xmlns="http://www.w3.org/2000/svg"
										>
											<path
												d="M7 21C6.45 21 5.97917 20.8042 5.5875 20.4125C5.19583 20.0208 5 19.55 5 19V6H4V4H9V3H15V4H20V6H19V19C19 19.55 18.8042 20.0208 18.4125 20.4125C18.0208 20.8042 17.55 21 17 21H7ZM17 6H7V19H17V6ZM9 17H11V8H9V17ZM13 17H15V8H13V17Z"
											/>
										</svg>

										<span>Удалить</span>
									</template>
								</VueTableButton>

								<VueTableButton
									:wide="true"
									v-else="table.options.delete"
									@click="
										$emit(
											'delete',
											this.table.body.find(
												(item) => item[getNameOfColumnTypeById] == row[getNameOfColumnTypeById]
											)
										)
									"
								>
									<slot name="restore" v-if="$slots.restore"></slot>

									<template v-else>
										<svg
											width="22"
											height="22"
											viewBox="0 0 24 24"
											fill="white"
											xmlns="http://www.w3.org/2000/svg"
										>
											<path
												d="M11 16H13V11.85L14.6 13.4L16 12L12 8L8 12L9.4 13.4L11 11.85V16ZM7 21C6.45 21 5.97917 20.8042 5.5875 20.4125C5.19583 20.0208 5 19.55 5 19V6H4V4H9V3H15V4H20V6H19V19C19 19.55 18.8042 20.0208 18.4125 20.4125C18.0208 20.8042 17.55 21 17 21H7ZM17 6H7V19H17V6Z"
											/>
										</svg>

										<span>Вернуть</span>
									</template>
								</VueTableButton>
							</template>
						</div>
					</td>
				</tr>
			</tbody>
			<!-- Пустая таблица -->
			<tbody class="vue-table__tbody" v-else>
				<tr class="vue-table__tbody-row">
					<td class="vue-table__tbody-cell" :colspan="Object.keys(this.table.head).length + 1">
						<div class="table__tbody-empty">Пустая таблица</div>
					</td>
				</tr>
			</tbody>
		</table>

		<VueTablePagination :settings="settings" :arrayLength="tableLength" @changePage="changePage" />
	</div>
</template>

<script>
import VueIcon from "../../ui/VueIcon.vue";

import VueTablePagination from "./VueTablePagination.vue";
import VueTableButton from "./VueTableButton.vue";
import VueTableButtonHead from "./VueTableButtonHead.vue";

import VueDateTime from "../input/VueDateTime.vue";
import VueValues from "../input/VueValues.vue";
import VueNumber from "../input/VueNumber.vue";
import VueSelector from "../input/VueSelector.vue";

import sorted from "../../../services/sorted";

export default {
	components: {
		VueIcon,

		VueTablePagination,
		VueTableButton,
		VueTableButtonHead,

		VueDateTime,
		VueValues,
		VueSelector,
		VueNumber,
	},
	props: {
		table: {
			type: Object,
			default: {},
			required: true,
		},
		icon: {
			type: String,
			default: "",
		},
	},
	emits: ["create", "edite", "delete", "button"],
	data() {
		return {
			/* Пагинация */
			settings: {
				pages: {
					range: 5,
					current: 1,
				},
				elements: {
					range: 10,
				},
			},

			/* Сортировка */
			sorting: {
				sortField: "",
				sortType: "",
			},

			searchInput: "",

			isFilter: false,
			tableLength: 0,
			filterFields: [],
		};
	},
	computed: {
		/* Отображаемая таблица */
		displayTable() {
			// 1. Создаем копию таблицы с нужными колонками
			let tableBody = this.table.body.map((row) =>
				this.table.head.reduce((acc, column) => {
					if (column.options?.data?.formatter) {
						acc[column.name] = column.options.data.formatter(row[column.name]);
					} else {
						acc[column.name] = row[column.name];
					}

					return acc;
				}, {})
			);

			// 2. Применяем поиск
			if (this.searchInput) {
				const searchTerm = this.searchInput.toLowerCase();
				tableBody = tableBody.filter((row) => Object.values(row).join(" ").toLowerCase().includes(searchTerm));
			}

			// 3. Оптимизированная фильтрация
			if (this.isFilter) {
				this.filterFields.forEach((filterField) => {
					if (!filterField.filter) return;

					const filterValue = this.normalizeString(filterField.filter);
					const fieldName = filterField.name;
					const fieldType = filterField.type;

					tableBody = tableBody.filter((row) => {
						const cellValue = row[fieldName];

						switch (fieldType) {
							case "id":
							case "number":
							case "list":
								return this.normalizeString(cellValue).includes(filterValue);
								break;
							case "string":
								if (filterField.register) {
									return cellValue.includes(filterValue);
								} else {
									return cellValue.toLowerCase().includes(filterValue.toLowerCase());
								}
							case "time":
								if (!filterField.from && !filterField.to) return true;

								const cellDate = new Date(cellValue);

								if (filterField.from && !filterField.to) {
									return cellDate > new Date(filterField.from);
								}

								if (!filterField.from && filterField.to) {
									return cellDate < new Date(filterField.to);
								}

								return cellDate > new Date(filterField.from) && cellDate < new Date(filterField.to);

								break;
							default:
								return true;
						}
					});
				});
			} else {
				// Сброс фильтров
				this.filterFields.forEach((field) => (field.filter = ""));
			}

			// 3.5. Обновление длины
			this.updateTableLength(tableBody.length);

			// 4. Сортировка
			const sortField = this.sorting.sortField;
			const sortType = this.sorting.sortType;

			switch (this.typeOfField(sortField)) {
				case "id":
				case "number":
				case "boolean":
					sorted.sortNumberByKey(sortType, tableBody, sortField);
					break;
				default:
					sorted.sortStringByKey(sortType, tableBody, sortField);
					break;
			}

			// 5. Пагинация
			const start = (this.settings.pages.current - 1) * this.settings.elements.range;
			const end = this.settings.pages.current * this.settings.elements.range;

			return tableBody.slice(start, end);
		},

		/* Скрытые колонки */
		getTableHeadHideNames() {
			return this.table.head.filter((column) => column.hide).map((column) => column.name);
		},

		/* Имя колонки с типом id */
		getNameOfColumnTypeById() {
			return this.table.head.find((column) => column.type == "id").name;
		},
	},
	methods: {
		// Вспомогательная функция для нормализации строк
		normalizeString(str, type = "") {
			switch (type) {
				case "lower":
					return String(str).trim().toLowerCase();
					break;
				case "upper":
					return String(str).trim().toUpperCase();
					break;
				default:
					return String(str).trim();
					break;
			}
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                     Таблица                       |*/
		/* |___________________________________________________|*/
		/* Удаление поля id из tbody */
		deleteOptionsInTableRow(row) {
			const newRow = { ...row };

			for (let i = 0; i < this.getTableHeadHideNames.length; i++) {
				delete newRow[this.getTableHeadHideNames[i]];
			}

			return newRow;
		},

		/* Удаление поля id из thead */
		deleteHeadsInTableHead(head) {
			const newHead = head.filter((row) => !this.getTableHeadHideNames.includes(row.name));

			return newHead;
		},

		/* Удаление поля id из thead */
		updateTableLength(length) {
			this.tableLength = length;
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                   Определители                    |*/
		/* |___________________________________________________|*/
		/* Тип поля */
		typeOfField(name) {
			for (let i = 0; i < this.table.head.length; i++) {
				if (this.table.head[i].name == name) {
					return this.table.head[i].type;
				}
			}
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                   Сортировка                      |*/
		/* |___________________________________________________|*/
		/* Сортировка */
		changeTypeSort(key) {
			if (this.sorting.sortField == key) {
				if (this.sorting.sortType == "down") {
					this.sorting.sortType = "up";
				} else {
					this.sorting.sortType = "down";
				}
			} else {
				this.sorting.sortField = key;
				this.sorting.sortType = "up";
			}
		},

		/* Стиль поля */
		getStyleOfField(name, type) {
			for (let i = 0; i < this.table.head.length; i++) {
				if (this.table.head[i].name == name) {
					if (this.table.head[i]?.options?.[type]?.style) {
						return this.table.head[i].options[type].style;
					} else {
						return "";
					}
				}
			}
		},

		/* Переключение страниц */
		changePage(page) {
			if (page > Math.ceil(this.table.body.length / this.settings.elements.range)) {
				return;
			} else if (page < 1) {
				return;
			}

			this.settings.pages.current = page;
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                 Форматирование                    |*/
		/* |___________________________________________________|*/
		/* Форматирование: string */
		formatString(value) {
			if (!value || value === null) {
				return "";
			}

			return String(value);
		},

		/* Форматирование: number */
		formatNumber(value) {
			if (Number.isNaN(Number(value)) || value === null) {
				return "";
			}

			return Number(value);
		},

		/* Форматирование: boolean */
		formatBoolean(value) {
			if (value === null) {
				return "";
			}

			return value ? "Да" : "Нет";
		},

		/* Форматирование: date */
		formatDate(value) {
			if (!value) {
				return "";
			}

			let currentDate = new Date(value);

			return currentDate.toLocaleString("ru", {
				month: "short",
				day: "numeric",
				year: "numeric",
				hour: "numeric",
				minute: "numeric",
			});
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                     Фильтры                       |*/
		/* |___________________________________________________|*/
		/* Изменение фильтра */
		filterChanged(field) {
			for (let i = 0; i < this.filterFields.length; i++) {
				if (this.filterFields[i].name == field.name) {
					this.filterFields[i] = field;
					break;
				}
			}
		},

		/* Изменение статуса фильтра */
		filterChangedStatus(field, status) {
			let currentField = this.filterFields.find((itterateField) => itterateField.name == field.name);

			currentField.filter = status;
		},
	},
	created() {
		/* Определение сортировки по умолчанию */
		this.sorting.sortField = this.table.head[0].name;
		this.sorting.sortType = "up";

		/* Определение полей фильтра */
		for (let i = 0; i < this.table.head.length; i++) {
			// Пропускаем поле id
			if (this.table.head[i].hide) {
				continue;
			}

			switch (this.table.head[i].type) {
				case "string":
					this.filterFields.push({
						name: this.table.head[i].name,
						type: this.table.head[i].type,
						register: true,
						filter: "",
					});
					break;
				case "time":
					this.filterFields.push({
						name: this.table.head[i].name,
						type: "time",
						from: "",
						to: "",
					});
					break;
				case "list":
					this.filterFields.push({
						name: this.table.head[i].name,
						type: "list",
						filter: "",
						values: this.table.head[i].values,
					});
					break;
				default:
					this.filterFields.push({
						name: this.table.head[i].name,
						type: this.table.head[i].type,
						filter: "",
					});
					break;
			}
		}
	},
};
</script>

<style scoped>
/* Компонент */
.vue-table {
	display: flex;
	flex-direction: column;
	gap: var(--default-gap);

	min-width: 800px;

	color: var(--main-font-color);

	animation: var(--table-animation);
}

/* Компонент: Шапка */
.vue-table__header {
	display: flex;
	justify-content: space-between;
	align-items: center;
}

.vue-table__header-left {
	display: flex;
	align-items: center;
	gap: calc(var(--default-gap) / 2);
}

.vue-table__header-left > select {
	cursor: pointer;

	padding: 0px 10px;
	height: 50px;

	border-radius: 7.5px;
	background-color: white;
	border: 0px;

	font-size: 1.125rem;
}

.vue-table__header-right {
	display: flex;
	gap: calc(var(--default-gap) / 2);
}

.vue-table__header-right > input {
	box-sizing: border-box;

	background-color: white;
	border: var(--default-border);
	border-radius: 100px;
	padding: calc(var(--default-padding) / 2) var(--default-padding);

	height: 50px;

	font-size: 1.125rem;
}

.vue-table__search {
	position: relative;
	box-sizing: border-box;
	overflow: hidden;
	display: flex;

	border: var(--table-search-border);
	border-radius: var(--table-search-border-radius);
	padding: var(--table-search-padding);

	width: var(--table-search-width);
	height: var(--table-search-height);

	background-color: var(--table-search-background-color);

	transition: var(--table-search-transition);
}

.vue-table__search:is(:hover, .active) {
	width: var(--table-search-active-width);
	height: var(--table-search-active-height);
}

.vue-table__search:has(.vue-table__search-input:focus) {
	width: var(--table-search-active-width);
	border: var(--table-search-border-active);

	background-color: var(--table-search-active-background-color);
}

.vue-table__search-icon {
	cursor: pointer;
	position: absolute;
	display: flex;
	justify-content: center;
	align-items: center;
	left: 0px;
	top: 0px;

	width: var(--table-search-icon-width);
	height: var(--table-search-icon-height);

	background-color: var(--table-search-icon-background-color);
}

.vue-table__search-icon > svg {
	fill: var(--table-search-icon-fill);

	width: var(--table-search-icon-svg-width);
	height: var(--table-search-icon-svg-height);
}

.vue-table__search-icon > svg:hover {
	fill: var(--table-search-icon-fill-hover);
}

.vue-table__search-input {
	margin-left: var(--table-search-input-margin-left);
	width: var(--table-search-input-width);
	font-size: var(--table-search-font-size);

	background-color: var(--table-search-input-background-color);
	border: var(--table-search-input-border);
}

/* Компонент: Шапка -> Кнопки */
.table__tbody-empty {
	display: flex;
	justify-content: center;
}

.table-button-listMany {
	color: var(--color-button-devices-text);
	background-color: var(--color-button-devices-background);
}

.table-button-listMany:hover {
	color: var(--color-button-hover-devices-text);
	background-color: var(--color-button-hover-devices-background);
}

/* Компонент: Шапка -> Сортировка */
.vue-table__content-sort {
	display: flex;
	justify-content: center;
	align-items: center;

	width: 25px;
	height: 25px;
}

.th__sort-icon {
	cursor: pointer;
	fill: var(--th-sort-icon-fill);

	transition: var(--th-sort-icon-transition);
}

.th__sort-icon:hover {
	fill: var(--th-sort-icon-fill-hover);
}

.th__sort-icon.rotate {
	transform: rotate(180deg);
}

/* Компонент: Таблица */
.vue-table__table {
	border-collapse: collapse;
	width: 100%;
}

/* Компонент: Таблица -> Фильтры */
.vue-table__filter {
	display: grid;
	grid-template-columns: 1fr;
	gap: 10px;
	margin: 10px;
}

.vue-table__filter--string {
	grid-template-columns: 1fr auto;
}

.vue-table__filter--time {
	grid-template-columns: auto;
}

.vue-table__filter-field {
	box-sizing: border-box;
	height: var(--table-filter-field-height);
	width: var(--table-filter-field-width);

	border: var(--table-filter-field-border);
	border-radius: var(--table-filter-field-border-radius);
	padding: var(--table-filter-field-padding);

	font-size: var(--table-filter-field-font-size);
	color: var(--table-filter-field-font-color);
	caret-color: var(--table-filter-field-caret-color);

	background-color: var(--table-filter-field-background-color);

	transition: var(--table-filter-field-transition);
}

.vue-table__filter-field:focus {
	border: var(--table-filter-field-border-focus);
	background-color: var(--table-filter-field-background-color-focus);
}

.vue-table__filter-button {
	cursor: pointer;
	box-sizing: border-box;

	display: flex;
	justify-content: center;
	align-items: center;

	width: 60px;
	height: 60px;

	border: var(--filter-button-border);
	border-radius: var(--filter-button-border-radius);

	font-size: var(--filter-button-font-size);
	color: var(--filter-button-font-color);

	transition: var(--filter-button-transition);
}

.vue-table__filter-button:is(:hover, .active) {
	border: var(--filter-button-border-hover);
	background-color: var(--filter-button-background-color-hover);
}

/* Компонент: Таблица -> Строка */
:is(.vue-table__thead-row, .vue-table__tbody-row):nth-child(even) > td {
	background-color: var(--table-td-even-background-color);
}

:is(.vue-table__thead-row, .vue-table__tbody-row):nth-child(even) > td > div {
	display: flex;
	flex-direction: row;
	align-items: center;
	gap: var(--table-td-gap);

	color: black;
	font-size: 1rem;
}

:is(.vue-table__thead-row, .vue-table__tbody-row):nth-child(odd) > td {
	background-color: var(--table-td-odd-background-color);
}

:is(.vue-table__thead-row, .vue-table__tbody-row):nth-child(odd) > td > div {
	display: flex;
	flex-direction: row;
	align-items: center;
	gap: var(--table-td-gap);

	color: black;
	font-size: 1rem;

	transition: all 0.2s;
}

.vue-table__tbody-row {
	transition: 0.25s;
}

.vue-table__tbody-row.vue-table__tbody-row--create > td > div {
	color: var(--input-create-color);
}

.vue-table__tbody-row.vue-table__tbody-row--create > td {
	background-color: var(--input-create-background-color-hover);
}

.vue-table__tbody-row.vue-table__tbody-row--delete > td > div {
	color: var(--input-delete-color);
}

.vue-table__tbody-row.vue-table__tbody-row--delete > td {
	background-color: var(--input-delete-background-color-hover);
}

/* Компонент: Таблица -> Ячейка */
:is(.vue-table__filter-cell, .vue-table__thead-cell, .vue-table__tbody-cell) {
	border-top: var(--table-cell-border-top);
	border-right: var(--table-cell-border-right);
	border-bottom: var(--table-cell-border-bottom);
	border-left: var(--table-cell-border-left);
	border-style: var(--table-cell-border-style);
	border-color: var(--table-cell-border-color);

	height: 100%;

	transition: var(--table-cell-border-transition);
}

:is(.vue-table__thead-cell, .vue-table__tbody-cell) > div {
	padding: 0px 10px;
	border-radius: 0px;
	min-height: 50px;

	transition: all 0.2s;
}

.vue-table__thead-cell > div {
	box-sizing: border-box;
	display: flex;
	flex-direction: row;
	align-items: center;
	justify-content: space-between;
	gap: var(--table-th-gap);

	color: var(--primary-color);
	background-color: var(--table-th-background-color);
	white-space: nowrap;
	font-size: 1.125rem;
}

.vue-table__thead-cell div span {
	font-size: var(--font-size-x-medium);
	font-weight: bold;
}

.vue-table__thead-cell div .icon {
	width: 25px;
	height: 25px;

	fill: var(--color-main-text);
}

.table-footer {
	display: flex;
	flex-direction: row;
	align-items: center;
	justify-content: center;

	margin-top: 20px;

	gap: 10px;
}

@media screen and (width < 500px) {
	table {
		width: auto;
		display: block;
		overflow-x: scroll;
	}
}
</style>
