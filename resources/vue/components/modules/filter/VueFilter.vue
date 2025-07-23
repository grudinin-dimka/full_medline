<template>
	<div class="filter" ref="filter">
		<div
			class="filter-title"
			@click="$emit('changeFilterStatus', filter.status ? false : true, filter.name)"
			:class="{ active: filter.status }"
		>
			<div class="name">
				<slot name="title"></slot>
			</div>
		</div>
		<div class="filter-body" :class="{ active: filter.status }">
			<div class="filter__body-input">
				<input type="text" placeholder="Введите название" v-model="name" />
			</div>
			<ol>
				<li
					@click="$emit('selectAll')"
					:class="{ active: filter.all }"
					:style="{ color: 'var(--primary-color)' }"
				>
					<div>Все</div>
					<div class="check" :class="{ active: filter.all }">
						<svg
							xmlns="http://www.w3.org/2000/svg"
							height="26px"
							viewBox="0 -960 960 960"
							width="26px"
						>
							<path d="M382-240 154-468l57-57 171 171 367-367 57 57-424 424Z" />
						</svg>
					</div>
				</li>

				<template v-if="getFilteredList.length > 0">
					<VueFIlterList
						v-for="child in getFilteredList"
						:node="child"
						:key="child.id"
						:disabled="filter.all"
						:selected="filter.selected"
						@selectChild="selectChild"
						@selectParent="selectParent"
					/>
				</template>
				<li v-else class="empty">Ничего не найдено.</li>
			</ol>
		</div>
	</div>
</template>

<script>
import VueFIlterList from "./VueFIlterList.vue";

import sorted from "../../../services/sorted";
import { nextTick } from "vue";
import shared from "../../../services/shared";

export default {
	components: {
		VueFIlterList,

		sorted,
	},
	props: {
		filter: {
			type: Object,
			required: true,
			default: () => {},
		},
		list: {
			type: Array,
			required: true,
			default: [],
		},
	},
	data() {
		return {
			name: "",
		};
	},
	watch: {
		"filter.status"(newQuery) {
			if (newQuery) {
				this.name = "";
			}

			nextTick(() => {
				this.changeFilterBodyPosition();
			});
		},
	},
	computed: {
		getFilteredList() {
			let filteredList = shared.getRecursiveCopy(this.list);

			if (this.name !== "") {
				/* Фильтрация повторений по названия */
				filteredList = filteredList.filter(
					(item, index, arr) => index === arr.findIndex((t) => t.name === item.name)
				);

				/* Фильтрация по вводу значения */
				filteredList = filteredList.filter((item) => {
					if (item.name.toLowerCase().includes(this.name.toLowerCase())) {
						return item;
					}

					if (item?.children && item.children.length > 0) {
						let innerChildrens = this.getChidrensByName(item.children, this.name);

						if (innerChildrens.length > 0) {
							item.children = innerChildrens;

							return item;
						}
					}
				});
			}

			sorted.sortStringByKey("up", filteredList, "name");

			return filteredList;
		},
	},
	methods: {
		/* Обработка клика вне компонента */
		handleClickOutside(event) {
			if (this.$refs.filter && !this.$refs.filter.contains(event.target)) {
				this.$emit("changeFilterStatus", false, this.filter.name);
			}
		},

		/* Изменение позиции компонента */
		changeFilterBodyPosition() {
			let filterTitle = this.$refs.filter.children[0];
			let filterTitlePosition = filterTitle.getBoundingClientRect();

			let filterBody = this.$refs.filter.children[1];
			let filterBodyWidth = filterBody.offsetWidth;

			let windowWidth = window.document.documentElement.clientWidth;

			let result = windowWidth - (filterTitlePosition.x + filterBodyWidth);

			if (result > 0) {
				filterBody.classList.add("left");
				filterBody.classList.remove("rigth");
			} else {
				filterBody.classList.add("rigth");
				filterBody.classList.remove("left");
			}
		},

		/* Получение списка элементов по значению */
		getChidrensByName(array, name) {
			let filterArray = [];

			for (let i = 0; i < array.length; i++) {
				if (array[i].name.toLowerCase().includes(name.toLowerCase())) {
					filterArray.push(array[i]);

					continue;
				}

				if (array[i]?.children && array[i].children.length > 0) {
					let innerChildrens = this.getChidrensByName(array[i].children, name);

					if (innerChildrens.length > 0) {
						array[i].children = innerChildrens;

						filterArray.push(array[i]);
					}
				}
			}

			return filterArray;
		},

		/* Выбор родителя */
		selectParent(item) {
			this.$emit("selectItemParent", item, this.filter.name);
		},

		/* Выбор наследника */
		selectChild(item) {
			this.$emit("selectItemChild", item, this.filter.name);
		},
	},
	mounted() {
		document.addEventListener("click", this.handleClickOutside);

		window.addEventListener("reload", this.changeFilterBodyPosition);
		window.addEventListener("load", this.changeFilterBodyPosition);
		window.addEventListener("resize", this.changeFilterBodyPosition);
	},
	unmounted() {
		document.removeEventListener("click", this.handleClickOutside);

		window.removeEventListener("reload", this.changeFilterBodyPosition);
		window.removeEventListener("load", this.changeFilterBodyPosition);
		window.removeEventListener("resize", this.changeFilterBodyPosition);
	},
};
</script>

<style>
.filter {
	position: relative;
}

.filter > .filter-title {
	cursor: pointer;
	user-select: none;
	display: flex;
	justify-content: space-between;
	align-items: center;
	gap: 10px;

	padding: 10px;
	border: var(--input-border);
	border-radius: var(--input-border-radius);

	font-size: 1.125rem;
	background-color: white;

	transition: all 0.2s;
}

.filter > .filter-title > .name {
	display: flex;
	justify-content: space-between;
	align-items: center;
	gap: 5px;
}

.filter > .filter-title:is(.active) {
	border: var(--input-border-focus);
}

.filter > .filter-title > svg {
	transition: all 0.2s;
}

.filter > .filter-title.active > svg {
	transform: rotate(180deg);
}

.filter > .filter-body {
	box-sizing: border-box;
	position: absolute;
	z-index: 1;
	top: 55px;
	display: flex;
	flex-direction: column;
	gap: 10px;

	padding: 10px;
	border-radius: 15px;
	min-width: 400px;
	max-width: 650px;

	opacity: 0;
	visibility: hidden;
	transform: scale(0.7);
	transform: translateY(-10px);

	background-color: white;
	box-shadow: 0px 0px 15px 5px rgba(0, 0, 0, 0.1);

	transition: all 0.2s;
}

.filter > .filter-body.left {
	left: 0px;
}

.filter > .filter-body.rigth {
	right: 0px;
}

.filter__body-input {
	display: flex;
	flex-direction: column;
}

.filter__body-input > input {
	box-sizing: border-box;

	outline: none;
	padding: 15px;
	border: var(--input-border);
	border-radius: var(--input-border-radius);

	font-size: 1.125rem;
}

.filter__body-input > input:focus {
	border: var(--input-border-focus);
}

.filter__body-input > .clear {
	position: absolute;
	top: 9px;
	right: 15px;
	cursor: pointer;
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 5px;

	height: 58px;

	border-radius: 10px;
	border: 0px solid white;

	font-size: 1.125rem;
	color: white;
	background-color: rgba(0, 0, 0, 0);
}

.filter__body-input > .clear > svg {
	fill: rgba(0, 0, 0, 1);
}

.filter > .filter-body > .container-buttons {
	display: flex;
	justify-content: flex-end;
	gap: 10px;
}

.filter > .filter-body > .container-buttons > button {
	cursor: pointer;
	padding: 10px;
	font-size: 1.125rem;
	border-radius: 10px;
	border: 0px solid white;
	background-color: rgba(0, 0, 0, 0.05);
}

.filter > .filter-body > .container-buttons > button:hover {
	background-color: rgba(0, 0, 0, 0.1);
}

.filter > .filter-body > .container-buttons > button.create {
	background-color: var(--button-background-color);
	color: white;
}

.filter > .filter-body > .container-buttons > button.create:hover {
	background-color: var(--button-background-color-hover);
	color: white;
}

.filter > .filter-body > .container-buttons > button.clear {
	background-color: rgba(255, 0, 0, 0.1);
	color: rgb(255, 94, 94);
}

.filter > .filter-body > .container-buttons > button.create:hover {
	background-color: var(--button-background-color-hover);
	color: white;
}

.filter > .filter-body.active {
	opacity: 1;
	visibility: visible;
	transform: translateY(0px);
}

.filter > .filter-body > ol {
	list-style: none;
	display: flex;
	flex-direction: column;
	gap: 5px;

	width: auto;
	max-height: 430px;
	overflow-y: auto;

	font-size: 1.125rem;

	padding: 0px;
	margin: 0px;
}

/* Скролбар блока с контентом. */
.filter > .filter-body > ol::-webkit-scrollbar {
	width: 20px;
}

.filter > .filter-body > ol::-webkit-scrollbar-track {
	background-color: rgb(255, 255, 255);
	border-radius: 10px;
	margin: 2px;
}

.filter > .filter-body > ol::-webkit-scrollbar-thumb {
	background-color: rgb(220, 220, 220);
	border: 6px solid rgb(255, 255, 255);
	border-radius: 10px;
}

.filter > .filter-body > ol::-webkit-scrollbar-thumb:hover {
	background-color: rgb(230, 230, 230);
	cursor: all-scroll;
}
/* Конец. */

.filter > .filter-body > ol > li {
	user-select: none;
	cursor: pointer;
	display: flex;
	justify-content: space-between;
	align-items: center;
	gap: 10px;

	padding: 10px;
	min-width: 500px;
	background-color: rgba(255, 255, 255, 1);
}

.filter > .filter-body > ol > li.empty {
	color: rgb(150, 150, 150);
}

.filter > .filter-body > ol > li.active {
	position: sticky;
	top: 0px;
}

.filter > .filter-body > ol > li > .check {
	display: flex;
	justify-content: center;
	align-items: center;

	width: 20px;
	height: 20px;

	border: var(--input-border);
	border-radius: 5px;
}

.filter > .filter-body > ol > li > .check > svg {
	opacity: 0;
	visibility: hidden;

	fill: var(--primary-color);
	transition: all 0.2s;
}

.filter > .filter-body.active > ol > li > .check.active > svg {
	opacity: 1;
	visibility: visible;
}

.filter > .filter-body > ol > li.all {
	color: rgba(0, 0, 0, 0.5);
}

.filter > .filter-body > ol > li.all .check > svg {
	fill: rgb(150, 150, 150);
}

.filter > .filter-body > ol > li:hover {
	padding: 10px;
	border-radius: 10px;
	background-color: rgb(242, 242, 242);
}

.filter > .filter-body > ol > li.parent {
	color: var(--primary-color);
}

@media screen and (width <= 1000px) {
	.filter {
		flex: 1 0 300px;
	}
}

@media screen and (width <= 710px) {
	.filter > .filter-body {
		min-width: 0px;
		max-width: none;
		width: 100%;
	}

	.filter > .filter-body > ol > li {
		min-width: 0px;
	}

	.filter > .filter-body > ol > li {
		white-space: normal;
		text-overflow: ellipsis;
		word-break: break-all;
	}
}

@media screen and (width <= 500px) {
	.filter {
		flex: 1 0 200px;
	}
}
</style>
