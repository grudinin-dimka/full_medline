<template>
	<!-- Filter -->
	<ModalTime ref="modalTime" @changed="filterChanged" />

	<!-- Container -->
	<div class="table">
		<!-- Container header -->
		<div class="table__header">
			<!-- Left side -->
			<div class="table__header-left">
				<select v-model="settings.elements.range">
					<option v-for="value in [10, 20, 30]">
						{{ value }}
					</option>
				</select>
				<span>из {{ table.body.length }} записей</span>
			</div>

			<!-- Right side -->
			<div class="table__header-right">
				<BaseTableButtonHead @click="filter = !filter">
					<svg
						data-v-0db40482=""
						xmlns="http://www.w3.org/2000/svg"
						height="24px"
						width="24px"
						viewBox="0 -960 960 960"
						fill="var(--primary-color)"
					>
						<path
							d="M120-240v-80h240v80H120Zm0-200v-80h480v80H120Zm0-200v-80h720v80H120Z"
						></path>
					</svg>
					<span v-if="!filter">Вкл. фильтр полей</span>
					<span v-else>Выкл. фильтр полей</span>
				</BaseTableButtonHead>

				<BaseTableButtonHead v-if="table.options.create" @click="$emit('create')">
					<svg
						data-v-0db40482=""
						xmlns="http://www.w3.org/2000/svg"
						height="24px"
						width="24px"
						viewBox="0 -960 960 960"
						fill="var(--primary-color)"
					>
						<path d="M440-440H200v-80h240v-240h80v240h240v80H520v240h-80v-240Z"></path>
					</svg>
					<span>Добавить</span>
				</BaseTableButtonHead>

				<input v-model="searchInput" type="text" placeholder="Поиск" />
			</div>
		</div>

		<!-- Таблица -->
		<table>
			<!--  Заголовки таблицы  -->
			<thead>
				<tr>
					<th
						:style="{ width: removeIdHead(table.head)[key].columnSize }"
						v-for="(value, key) in removeIdHead(table.head)"
					>
						<div>
							{{ value.text }}

							<div
								class="sort"
								v-if="
									value.columnType == 'default' ||
									value.columnType == 'list' ||
									value.columnType == 'time'
								"
							>
								<Icons
									v-if="this.sorting.sortField == value.name"
									:class="
										this.sorting.sortType == 'asc' ? 'appear-rotate' : 'reverse-rotate'
									"
									:width="'16px'"
									:height="'16px'"
									name="arrow"
									@click="changeTypeSort(value.name)"
								>
								</Icons>
								<Icons
									v-else
									:width="'16px'"
									:height="'16px'"
									name="sort"
									@click="changeTypeSort(value.name)"
								>
								</Icons>
							</div>
						</div>
					</th>
					<th style="width: 270px" v-if="this.table.canModals || this.table.options.update">
						<div>Действия</div>
					</th>
				</tr>

				<!-- Поля фильтра -->
				<tr v-if="filter">
					<td v-for="(value, key) in filterFields">
						<!-- Фильтр по умолчанию -->
						<div
							v-if="
								filterFields.find((field) => field.name === value.name).type == 'default'
							"
						>
							<input
								class="filter-field"
								type="text"
								placeholder="Поиск"
								v-model="filterFields.find((field) => field.name === value.name).filter"
							/>
						</div>
						<!-- Фильтр по времени -->
						<div
							v-else-if="
								filterFields.find((field) => field.name === value.name).type == 'time'
							"
						>
							<BaseTableButton :wide="true" @click="openFilter(value.name)">
								Диапазон
							</BaseTableButton>
						</div>

						<!-- Фильтр по списку || Фильтр по спискам -->
						<div
							v-else-if="
								filterFields.find((field) => field.name === value.name).type == 'list' ||
								filterFields.find((field) => field.name === value.name).type == 'button'
							"
						>
							<input
								class="filter-field"
								type="text"
								:list="filterFields.find((field) => field.name === value.name).name"
								placeholder="Поиск"
								v-model="filterFields.find((field) => field.name === value.name).filter"
							/>
							<datalist :id="filterFields.find((field) => field.name === value.name).name">
								<option
									v-for="value in filterFields.find((field) => field.name === value.name)
										.values"
								>
									{{ value.text }}
								</option>
							</datalist>
						</div>
						<div v-else>
							<div></div>
						</div>
					</td>
					<td v-if="this.table.canModals || this.table.options.update">
						<div></div>
					</td>
				</tr>
			</thead>

			<!--  Тело таблицы  -->
			<tbody v-if="displayTable.length !== 0">
				<tr
					v-for="row in displayTable"
					:class="{
						create: table.body.find((item) => item.id == row.id).create,
						delete: table.body.find((item) => item.id == row.id).delete,
					}"
				>
					<!-- Поля -->
					<td v-for="(value, key) in removeIdTableBody(row)">
						<div :style="{ justifyContent: justifyOfField(key) }">
							<!-- По умолчанию -->
							<template v-if="typeOfField(key) == 'default' || typeOfField(key) == 'time'">
								{{ value === "" ? "-" : value }}
							</template>

							<!-- Список -->
							<template v-else-if="typeOfField(key) == 'list'">
								{{
									table.head
										.find((field) => field.name == key)
										?.values.find((item) => item.value == value)?.label ?? "Не найдено"
								}}
							</template>

							<!-- Логический -->
							<template v-else-if="typeOfField(key) == 'boolean'">
								{{ value ? "Да" : "Нет" }}
							</template>

							<!-- Кнопка -->
							<template v-else-if="typeOfField(key) == 'button'">
								<BaseTableButton
									:wide="true"
									@click="
										$emit(
											'button',
											this.table.body.find((item) => item.id == row.id)
										)
									"
								>
									<Icons name="devices" />
									<span>{{
										table.head.find((field) => field.name == key).options.buttonName
									}}</span>
								</BaseTableButton>
							</template>

							<!-- Кастомный слот -->
							<template v-else>
								<slot
									:name="typeOfField(key)"
									v-bind:row="table.body.find((item) => item.id == row.id)"
								></slot>
							</template>
						</div>
					</td>
					<!-- Действия -->
					<td v-if="table.canModals || table.options.update">
						<div class="table__buttons">
							<BaseTableButton
								:wide="true"
								v-if="table.options.update"
								@click="
									$emit(
										'edite',
										this.table.body.find((item) => item.id == row.id)
									)
								"
							>
								<Icons name="edit" :width="'22px'" :height="'22px'" />
								<span>Изменить</span>
							</BaseTableButton>

							<BaseTableButton
								:wide="true"
								:look="'delete'"
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
								<Icons name="delete" />
								<span>Удалить</span>
							</BaseTableButton>

							<BaseTableButton
								:wide="true"
								v-else="table.options.delete"
								@click="
									$emit(
										'delete',
										this.table.body.find((item) => item.id == row.id)
									)
								"
							>
								<Icons name="delete" />
								<span>Вернуть</span>
							</BaseTableButton>
						</div>
					</td>
				</tr>
			</tbody>
			<!-- Пустая таблица -->
			<tbody v-else>
				<tr>
					<td :colspan="Object.keys(this.table.head).length + 1">
						<div class="table__tbody-empty">Пустая таблица</div>
					</td>
				</tr>
			</tbody>
		</table>

		<Pagination :settings="settings" :arrayLength="table.body.length" @changePage="changePage" />
	</div>
</template>

<script>
import Icons from "../Icons.vue";

import Modal from "../modal/Modal.vue";
import ModalTime from "../modal/filters/ModalTime.vue";

import Pagination from "../Pagination.vue";

import BaseTableButton from "./BaseTableButton.vue";
import BaseTableButtonHead from "./BaseTableButtonHead.vue";
import ButtonDefault from "../../ui/admin/buttons/ButtonDefault.vue";

import sorted from "../../../services/sorted";

export default {
	components: {
		Icons,
		Pagination,

		Modal,
		ModalTime,

		ButtonDefault,

		BaseTableButton,
		BaseTableButtonHead,
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
	emits: ["create", "edite", "delete", "report", "rowEditedListMany", "button"],
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

			filter: false,
			filterFields: [],
		};
	},
	computed: {
		displayTable() {
			let tableBody = this.table.body.map((row) => {
				const sortedData = this.table.head.reduce((acc, column) => {
					acc[column.name] = row[column.name];
					return acc;
				}, {});
				return sortedData;
			});

			// Заполнение пустых значений
			for (let i = 0; i < tableBody.length; i++) {
				for (let j = 0; j < this.table.head.length; j++) {
					if (tableBody[i][this.table.head[j].name] == null) {
						tableBody[i][this.table.head[j].name] = "...";
					}
				}
			}

			// Поиск по значению в общем поле ввода
			if (this.searchInput != "") {
				let strFrom, strTo;
				let searchBody = [];

				if (tableBody.length == 0) {
					return [];
				}

				for (let key of Object.keys(tableBody[0])) {
					for (let i = 0; i < tableBody.length; i++) {
						strFrom = tableBody[i][key];
						strFrom = String(strFrom);
						strFrom = strFrom.toLowerCase();
						strFrom = strFrom.trim();

						strTo = this.searchInput;
						strTo = String(strTo);
						strTo = strTo.toLowerCase();
						strTo = strTo.trim();

						if (strFrom.includes(strTo)) {
							if (!searchBody.includes(tableBody[i])) {
								searchBody.push(tableBody[i]);
							}
						}
					}
				}

				tableBody = searchBody;
			}

			// Фильтрация полей по полям ввода
			if (this.filter == true) {
				for (let i = 0; i < this.filterFields.length; i++) {
					// Проверка на пустое значение фильтра
					if (this.filterFields[i].filter != "") {
						let str, strFrom, strTo;
						let searchBody = [];

						for (let j = 0; j < tableBody.length; j++) {
							strFrom = this.filterFields[i].filter;
							if (!strFrom.match(/^[a-zA-Z]+$/)) {
								strFrom = strFrom.toLowerCase();
								strFrom = strFrom.trim();
							}

							// Значение поля по this.filterFields[i].name
							str = tableBody[j][this.filterFields[i].name];

							switch (this.filterFields[i].type) {
								case "time":
									strFrom = strFrom.split(" ")[0];

									if (
										this.filterFields[i].from == null ||
										this.filterFields[i].to == null
									) {
										searchBody.push(tableBody[j]);
									}

									if (
										new Date(this.filterFields[i].from).getTime() <=
											new Date(str).getTime() &&
										new Date(this.filterFields[i].to).getTime() >= new Date(str).getTime()
									) {
										searchBody.push(tableBody[j]);
									}
									break;
								case "button":
									if (str.length != 0) {
										for (let k = 0; k < str.length; k++) {
											strTo = str[k].text;
											strTo = String(strTo);
											if (!strTo.match(/^[a-zA-Z]+$/)) {
												strTo = strTo.toLowerCase().trim();
											}

											if (strTo.includes(strFrom)) {
												searchBody.push(tableBody[j]);
											}
										}
									}
									break;
								case "default":
								case "list":
									strTo = str;
									strTo = String(strTo);
									if (!strTo.match(/^[a-zA-Z]+$/)) {
										strTo = strTo.toLowerCase().trim();
									}

									if (strTo.includes(strFrom)) {
										searchBody.push(tableBody[j]);
									}
									break;
								default:
									break;
							}
						}

						tableBody = searchBody;
					}
				}
			} else {
				for (let i = 0; i < this.filterFields.length; i++) {
					this.filterFields[i].filter = "";
				}
			}

			// Сортировка
			let copyTable = [];
			let array = [];

			for (let i = 0; i < tableBody.length; i++) {
				array.push(tableBody[i][this.sorting.sortField]);
			}

			if (this.sorting.sortType == "asc") {
				sorted.sortString("up", array);
			} else {
				sorted.sortString("down", array);
			}

			for (let i = 0; i < array.length; i++) {
				for (let j = 0; j < tableBody.length; j++) {
					if (tableBody[j][this.sorting.sortField] == array[i]) {
						copyTable.push(tableBody[j]);
						tableBody.splice(j, 1);
						break;
					}
				}
			}

			tableBody = copyTable;

			// Pagination
			tableBody = tableBody.slice(
				(this.settings.pages.current - 1) * this.settings.elements.range,
				this.settings.pages.current * this.settings.elements.range
			);

			return tableBody;
		},

		lang() {
			// Языковые константы
			return this.$store.getters.getLanguage;
		},
	},
	methods: {
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                    События                       |*/
		/* |___________________________________________________|*/
		/* Добавление строки */
		modalAddRow() {
			this.$refs.modalNewRow.open(this.table);
		},
		/* Создание строки */
		rowCreated(data) {
			this.$emit("rowCreated", data);
		},
		/* Редактирование строки */
		modalEditRow(row) {
			this.$refs.modalEditRow.open(this.fromDisplayToBaseTable(row), this.table);
		},
		/* Редактирование строки */
		rowEdited(data) {
			this.updateFilterFields();
			this.$emit("rowEdited", data);
		},
		/* Удаление строки */
		modalRemoveRow(row) {
			this.$refs.modalDeleteRow.open(row, this.table);
		},
		/* Событие удаления */
		rowDeleted(data) {
			this.$emit("rowDeleted", data);
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                   Сортировка                      |*/
		/* |___________________________________________________|*/
		/* Сортировка */
		changeTypeSort(key) {
			if (this.sorting.sortField == key) {
				if (this.sorting.sortType == "asc") {
					this.sorting.sortType = "desc";
				} else {
					this.sorting.sortType = "asc";
				}
			} else {
				this.sorting.sortField = key;
				this.sorting.sortType = "desc";
			}
		},

		/* Тип поля */
		typeOfField(name) {
			for (let i = 0; i < this.table.head.length; i++) {
				if (this.table.head[i].name == name) {
					return this.table.head[i].columnType;
				}
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
		/* |                     Другое                        |*/
		/* |___________________________________________________|*/
		/* Открытие фильтра */
		openFilter(fieldName) {
			let field = this.filterFields.find((field) => field.name == fieldName);

			this.$refs.modalTime.open(field);
		},
		/* Изменение фильтра */
		filterChanged(field) {
			for (let i = 0; i < this.filterFields.length; i++) {
				if (this.filterFields[i].name == field.name) {
					this.filterFields[i] = field;
					break;
				}
			}
		},

		fromDisplayToBaseTable(rowFromDisplay) {
			return this.table.body.find((row) => row.id == rowFromDisplay.id);
		},

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
	},
	created() {
		// First sorting options
		this.sorting.sortField = this.table.head[0].name;
		this.sorting.sortType = "asc";

		let i = 0;
		if (this.table.head[0].columnType == "id") {
			i = 1;
		}

		// First filter options
		for (i; i < this.table.head.length; i++) {
			switch (this.table.head[i].columnType) {
				case "default":
					this.filterFields.push({
						name: this.table.head[i].name,
						type: "default",
						filter: "",
					});
					break;
				case "static":
					this.filterFields.push({
						name: this.table.head[i].name,
						type: "default",
						filter: "",
					});
					break;
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
				case "button":
					let values = [];

					for (let j = 0; j < this.table.body.length; j++) {
						for (let k = 0; k < this.table.body[j][this.table.head[i].name].length; k++) {
							const value = JSON.parse(
								JSON.stringify(this.table.body[j][this.table.head[i].name][k])
							);

							if (!values.find((item) => item.id === value.id && item.text === value.text)) {
								values.push(value);
							}
						}
					}

					this.filterFields.push({
						name: this.table.head[i].name,
						type: "button",
						filter: "",
						values: values,
					});
					break;

				default:
					this.filterFields.push({
						name: this.table.head[i].name,
						type: "none",
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
/* Название таблицы */
.info {
	display: flex;
	justify-content: space-between;
	gap: calc(var(--default-padding) / 2);

	min-height: 50px;

	animation: show 0.5s ease-in-out;
}

.info > .info-name {
	display: flex;
	align-items: center;
	gap: calc(var(--default-padding) / 2);

	font-size: 1.125rem;
	font-weight: bold;
}

.info > .info-name > .icon {
	width: 32px;
	height: 32px;

	fill: var(--primary-color);
}

.info > .info-buttons {
	display: flex;
	gap: calc(var(--default-padding) / 2);
}

/* Компонент таблица */
.table {
	display: flex;
	flex-direction: column;
	gap: var(--default-gap);

	min-width: 800px;
	overflow-y: auto;

	color: var(--main-font-color);

	animation: show-bottom-to-top-15 0.5s ease-in-out;
}

input.filter-field {
	width: 100%;

	border: var(--default-border);
	border-radius: calc(var(--button-border-radius) / 2);
	padding: calc(var(--default-padding) / 2);
	margin: 10px 0px;

	font-size: 1rem;
	color: var(--main-font-color);
}

.table__header {
	display: flex;
	justify-content: space-between;
	align-items: center;
}

.table__header-left {
	display: flex;
	align-items: center;
	gap: calc(var(--default-gap) / 2);
}

.table__header-left > select {
	cursor: pointer;

	padding: 0px 10px;
	height: 50px;

	border-radius: 7.5px;
	background-color: white;
	border: 0px;

	font-size: 1.125rem;
}

.table__header-right {
	display: flex;
	gap: calc(var(--default-gap) / 2);
}

.table__header-right > input {
	box-sizing: border-box;

	background-color: white;
	border: var(--default-border);
	border-radius: calc(var(--default-border-radius) / 2);
	padding: calc(var(--default-padding) / 2);

	height: 50px;

	font-size: 1.125rem;
}

/* Таблица */
table {
	border-collapse: collapse;
	width: 100%;
}

td,
th {
	border-top: 1px;
	border-right: 1px;
	border-bottom: 1px;
	border-left: 1px;
	border-style: solid;
	border-color: rgb(200, 200, 200);

	height: 100%;
}

th > div,
td > div {
	padding: 0px 10px;
	border-radius: 0px;
	min-height: 50px;
}

th > div {
	box-sizing: border-box;
	display: flex;
	flex-direction: row;
	gap: var(--table-th-gap);

	color: var(--primary-color);
	background-color: var(--table-th-background-color);
	white-space: nowrap;
	font-size: 1.125rem;
}

tr:nth-child(even) > td {
	background-color: var(--table-td-even-background-color);
}

tr:nth-child(even) > td > div {
	display: flex;
	flex-direction: row;
	align-items: center;
	gap: var(--table-td-gap);

	color: black;
	font-size: 1rem;
}

tr:nth-child(odd) > td {
	background-color: var(--table-td-odd-background-color);
}

tr:nth-child(odd) > td > div {
	display: flex;
	flex-direction: row;
	align-items: center;
	gap: var(--table-td-gap);

	color: black;
	font-size: 1rem;

	transition: all 0.2s;
}

th div {
	align-items: center;
	justify-content: space-between;
}

th div span {
	font-size: var(--font-size-x-medium);
	font-weight: bold;
}

th div .icon {
	width: 25px;
	height: 25px;

	fill: var(--color-main-text);
}

.table__tbody-empty {
	display: flex;
	justify-content: center;
}

tr {
	transition: 0.25s;
}

tr.create > td > div {
	color: var(--create-color);
}

tr.delete > td > div {
	color: var(--delete-color);
}

.table__buttons svg {
	fill: white;
}

.table-button-listMany {
	color: var(--color-button-devices-text);
	background-color: var(--color-button-devices-background);
}

.table-button-listMany:hover {
	color: var(--color-button-hover-devices-text);
	background-color: var(--color-button-hover-devices-background);
}

.table-footer {
	display: flex;
	flex-direction: row;
	align-items: center;
	justify-content: center;

	margin-top: 20px;

	gap: 10px;
}

.appear-rotate {
	animation: appear-rotate 0.5s ease-in-out;
	transform: rotate(180deg);
}

.sort {
	cursor: pointer;

	transition: all 0.2s;
}

@keyframes appear-rotate {
	0% {
		transform: rotate(0deg);
	}
	100% {
		transform: rotate(180deg);
	}
}

.reverse-rotate {
	animation: reverse-rotate 0.5s ease-in-out;
}

@keyframes reverse-rotate {
	0% {
		transform: rotate(180deg);
	}
	100% {
		transform: rotate(0deg);
	}
}
</style>
