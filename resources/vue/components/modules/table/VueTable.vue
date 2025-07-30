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
				</VueTableButtonHead>

				<input v-model="searchInput" type="text" placeholder="Поиск" />
			</div>
		</div>

		<!-- Таблица -->
		<table class="vue-table__table">
			<!--  Заголовки таблицы  -->
			<thead class="vue-table__thead">
				<tr class="vue-table__thead-row">
					<th
						class="vue-table__thead-cell"
						:style="{ width: removeIdHead(table.head)[key].columnSize }"
						v-for="(value, key) in removeIdHead(table.head)"
					>
						<div class="vue-table__cell-content">
							{{ value.text }}

							<div
								class="vue-table__content-sort"
								v-if="
									['string', 'number', 'list', 'boolean', 'time'].includes(
										value.columnType
									)
								"
							>
								<VueIcon
									class="th__content__sort-icon"
									:class="{ rotate: this.sorting.sortType == 'up' }"
									v-if="this.sorting.sortField == value.name"
									:name="'arrow'"
									:width="'16px'"
									:height="'16px'"
									:fill="'black'"
									@click="changeTypeSort(value.name)"
								>
								</VueIcon>

								<VueIcon
									v-else
									class="th__content__sort-icon"
									:name="'sort'"
									:width="'24px'"
									:height="'24px'"
									:fill="'black'"
									@click="changeTypeSort(value.name)"
								>
								</VueIcon>
							</div>
						</div>
					</th>
					<th
						class="vue-table__thead-cell"
						style="width: 270px"
						v-if="this.table.options.update"
					>
						<div>Действия</div>
					</th>
				</tr>

				<!-- Поля фильтра -->
				<tr class="vue-table__filter-row" v-if="isFilter">
					<td class="vue-table__filter-cell" v-for="(value, key) in filterFields">
						<!-- Фильтр: строка, число -->
						<template
							v-if="
								['string', 'number'].includes(
									filterFields.find((field) => field.name === value.name).type
								)
							"
						>
							<div class="vue-table__filter vue-table__filter--default">
								<input
									class="vue-table__filter-field"
									type="text"
									placeholder="Поиск"
									v-model="filterFields.find((field) => field.name === value.name).filter"
								/>
							</div>
						</template>

						<!-- Фильтр: время -->
						<template
							v-else-if="
								['time'].includes(
									filterFields.find((field) => field.name === value.name).type
								)
							"
						>
							<div class="vue-table__filter vue-table__filter--time">
								<input
									class="vue-table__filter-field"
									type="datetime-local"
									v-model.trim="
										filterFields.find((field) => field.name === value.name).from
									"
									@change="filterChangedStatus(value, 'on')"
								/>
								<input
									class="vue-table__filter-field"
									type="datetime-local"
									v-model.trim="filterFields.find((field) => field.name === value.name).to"
									@change="filterChangedStatus(value, 'on')"
								/>
							</div>
						</template>

						<!-- Фильтр: список -->
						<template
							v-else-if="
								['list'].includes(
									filterFields.find((field) => field.name === value.name).type
								)
							"
						>
							<div class="vue-table__filter vue-table__filter--list">
								<input
									class="vue-table__filter-field"
									type="text"
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
								</datalist>
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
						'vue-table__tbody-row--create': table.body.find((item) => item.id == row.id)
							.create,
						'vue-table__tbody-row--delete': table.body.find((item) => item.id == row.id)
							.delete,
					}"
				>
					<!-- Поля -->
					<td class="vue-table__tbody-cell" v-for="(value, key) in removeIdTableBody(row)">
						<div :style="{ justifyContent: justifyOfField(key) }">
							<!-- Строка -->
							<template v-if="typeOfField(key) == 'string'">
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
									table.head
										.find((field) => field.name == key)
										?.values.find((item) => item.value == value)?.label ?? "Не найдено"
								}}
							</template>

							<!-- Кастомный слот -->
							<template v-else-if="typeOfField(key) == 'slot'">
								<slot
									:name="key"
									v-bind:row="table.body.find((item) => item.id == row.id)"
								></slot>
							</template>
						</div>
					</td>

					<!-- Действия -->
					<td
						class="vue-table__tbody-cell"
						v-if="table.options.update || table.options.delete"
					>
						<div class="table__buttons">
							<VueTableButton
								:wide="true"
								v-if="table.options.update"
								@click="
									$emit(
										'edite',
										this.table.body.find((item) => item.id == row.id)
									)
								"
							>
								<VueIcon :name="'edit'" :width="'22px'" :height="'22px'" :fill="'white'" />

								<span>Изменить</span>
							</VueTableButton>

							<VueTableButton
								:wide="true"
								:look="
									this.table.body.find((item) => item.id == row.id).create
										? 'disabled'
										: 'delete'
								"
								v-if="
									table.options.delete &&
									!table.body.find((item) => item.id == row.id).delete
								"
								@click="
									$emit(
										'delete',
										this.table.body.find((item) => item.id == row.id)
									)
								"
							>
								<VueIcon
									:name="'delete'"
									:width="'16px'"
									:height="'16px'"
									:fill="'white'"
								/>

								<span>Удалить</span>
							</VueTableButton>

							<VueTableButton
								:wide="true"
								v-else="table.options.delete"
								@click="
									$emit(
										'delete',
										this.table.body.find((item) => item.id == row.id)
									)
								"
							>
								<VueIcon
									:name="'delete'"
									:width="'16px'"
									:height="'16px'"
									:fill="'white'"
								/>

								<span>Вернуть</span>
							</VueTableButton>
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

		<VueTablePagination
			:settings="settings"
			:arrayLength="table.body.length"
			@changePage="changePage"
		/>
	</div>
</template>

<script>
import VueIcon from "../icon/VueIcon.vue";

import VueTablePagination from "./VueTablePagination.vue";
import VueTableButton from "./VueTableButton.vue";
import VueTableButtonHead from "./VueTableButtonHead.vue";

import sorted from "../../../services/sorted";

export default {
	components: {
		VueIcon,

		VueTablePagination,
		VueTableButton,
		VueTableButtonHead,
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
	emits: ["create", "edite", "delete", "report", "button"],
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
			filterFields: [],
		};
	},
	computed: {
		/* Отображаемая таблица */
		displayTable() {
			// 1. Создаем копию таблицы с нужными колонками
			let tableBody = this.table.body.map((row) =>
				this.table.head.reduce((acc, column) => {
					acc[column.name] = row[column.name];
					return acc;
				}, {})
			);

			// 2. Применяем поиск
			if (this.searchInput) {
				const searchTerm = this.searchInput.toLowerCase();
				tableBody = tableBody.filter((row) =>
					Object.values(row).join(" ").toLowerCase().includes(searchTerm)
				);
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
							case "string":
							case "number":
							case "list":
								return this.normalizeString(cellValue).includes(filterValue);

								break;
							case "time":
								if (!filterField.from && !filterField.to) return true;

								const cellDate = new Date(cellValue);

								if (filterField.from && !filterField.to) {
									return cellDate > new Date(filterField.from);
								}

								if (!filterField.from && filterField.to) {
									return cellDate < new Date(filterField.to);
								}

								return (
									cellDate > new Date(filterField.from) &&
									cellDate < new Date(filterField.to)
								);

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

			// 4. Сортировка
			const sortField = this.sorting.sortField;
			const sortType = this.sorting.sortType;

			switch (this.typeOfField(sortField)) {
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
	},
	methods: {
		// Вспомогательная функция для нормализации строк
		normalizeString(str) {
			if (typeof str !== "string") str = String(str);
			if (!str.match(/^[a-zA-Z]+$/)) {
				return str.toLowerCase().trim();
			}
			return str;
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                     Таблица                       |*/
		/* |___________________________________________________|*/
		fromDisplayToBaseTable(rowFromDisplay) {
			return this.table.body.find((row) => row.id == rowFromDisplay.id);
		},

		/* Обновление полей фильтра */
		updateFilterFields() {
			let values = [];

			for (let i = 0; i < this.removeIdHead(this.table.head).length; i++) {
				if (this.removeIdHead(this.table.head)[i].columnType == "button") {
					for (let j = 0; j < this.table.body.length; j++) {
						for (
							let k = 0;
							k < this.table.body[j][this.removeIdHead(this.table.head)[i].name].length;
							k++
						) {
							let value = JSON.stringify(
								this.table.body[j][this.removeIdHead(this.table.head)[i].name][k]
							);
							value = JSON.parse(value);

							if (!values.some((v) => v.name === value.name && v.text === value.text)) {
								values.push(value);
							}
						}
					}

					this.filterFields[i].values = values;
				}
			}
		},

		/* Удаление поля id из tbody */
		removeIdTableBody(row) {
			const newRow = { ...row };
			delete newRow.id;
			return newRow;
		},

		/* Удаление поля id из thead */
		removeIdHead(head) {
			const newHead = head.filter((row) => row.name != "id");
			return newHead;
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                   Определители                    |*/
		/* |___________________________________________________|*/
		/* Тип поля */
		typeOfField(name) {
			for (let i = 0; i < this.table.head.length; i++) {
				if (this.table.head[i].name == name) {
					return this.table.head[i].columnType;
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

		/* Тип поля */
		justifyOfField(name) {
			for (let i = 0; i < this.table.head.length; i++) {
				if (this.table.head[i].name == name) {
					return this.table.head[i].columnJustify ?? "flex-start";
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
				return "...";
			}

			return String(value);
		},

		/* Форматирование: number */
		formatNumber(value) {
			if (Number.isNaN(Number(value)) || value === null) {
				return "...";
			}

			return Number(value);
		},

		/* Форматирование: boolean */
		formatBoolean(value) {
			if (value === null) {
				return "...";
			}

			return value ? "Да" : "Нет";
		},

		/* Форматирование: date */
		formatDate(value) {
			if (!value) {
				return "...";
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
			let currentField = this.filterFields.find(
				(itterateField) => itterateField.name == field.name
			);

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
			if (this.table.head[i].columnType == "id") {
				continue;
			}

			switch (this.table.head[i].columnType) {
				case "time":
					this.filterFields.push({
						name: this.table.head[i].name,
						type: "time",
						from: null,
						to: null,
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
						type: this.table.head[i].columnType,
						filter: "",
					});
					break;
			}
		}

		this.updateFilterFields();
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
	overflow-y: auto;

	color: var(--main-font-color);

	animation: show-bottom-to-top-15 0.5s ease-in-out;
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

.th__content__sort-icon {
	transition: all 0.2s;
}

.th__content__sort-icon.rotate {
	transform: rotate(180deg);
}

/* Компонент: Таблица */
.vue-table__table {
	border-collapse: collapse;
	width: 100%;
}

/* Компонент: Таблица -> Фильтры */
.vue-table__filter {
	margin: 10px;
}

.vue-table__filter.vue-table__filter--time {
	display: flex;
	flex-direction: column;
}

.vue-table__filter-field {
	box-sizing: border-box;
	width: 100%;

	border: var(--default-border);
	border-radius: calc(var(--button-border-radius) / 2);
	padding: calc(var(--default-padding) / 2);

	font-size: 1rem;
	color: black;
	caret-color: var(--primary-color);
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
:is(
		.vue-table__thead-cell,
		.vue-table__filter-cell,
		.vue-table__thead-cell,
		.vue-table__tbody-cell
	) {
	border-top: 1px;
	border-right: 1px;
	border-bottom: 1px;
	border-left: 1px;
	border-style: solid;
	border-color: rgb(200, 200, 200);

	height: 100%;

	transition: all 0.2s;
}

:is(.vue-table__thead-cell, .vue-table__thead-cell, .vue-table__tbody-cell) > div {
	padding: 0px 10px;
	border-radius: 0px;
	min-height: 50px;

	transition: all 0.2s;
}

:is(.vue-table__thead-cell, .vue-table__filter-cell, .vue-table__thead-cell) > div {
	box-sizing: border-box;
	display: flex;
	flex-direction: row;
	gap: var(--table-th-gap);

	color: var(--primary-color);
	background-color: var(--table-th-background-color);
	white-space: nowrap;
	font-size: 1.125rem;
}

:is(.vue-table__thead-cell, .vue-table__filter-cell, .vue-table__thead-cell) div {
	align-items: center;
	justify-content: space-between;
}

:is(.vue-table__thead-cell, .vue-table__filter-cell, .vue-table__thead-cell) div span {
	font-size: var(--font-size-x-medium);
	font-weight: bold;
}

:is(.vue-table__thead-cell, .vue-table__filter-cell, .vue-table__thead-cell) div .icon {
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
