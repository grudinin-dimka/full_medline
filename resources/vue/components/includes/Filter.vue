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
			<svg
				xmlns="http://www.w3.org/2000/svg"
				height="24px"
				viewBox="0 -960 960 960"
				width="24px"
				fill="black"
			>
				<path d="M480-344 240-584l56-56 184 184 184-184 56 56-240 240Z" />
			</svg>
		</div>
		<div class="filter-body" :class="{ active: filter.status }">
			<div class="container-input">
				<input type="text" placeholder="Введите название" v-model="name" />
			</div>
			<ol>
				<li @click="$emit('selectAll')">
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
					Все
				</li>
				<li
					v-if="getFilteredList.length > 0"
					v-for="item in getFilteredList"
					:class="{ all: filter.all }"
					@click="$emit('selectItem', item, filter.name)"
				>
					<div
						class="check"
						:class="{ active: filter.selected.includes(item), all: filter.all }"
					>
						<svg
							xmlns="http://www.w3.org/2000/svg"
							height="26px"
							viewBox="0 -960 960 960"
							width="26px"
						>
							<path d="M382-240 154-468l57-57 171 171 367-367 57 57-424 424Z" />
						</svg>
					</div>
					{{ item?.name }}
				</li>
				<li v-else class="empty">Ничего не найдено.</li>
			</ol>
		</div>
	</div>
</template>

<script>
export default {
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
	computed: {
		getFilteredList() {
			let filteredList = this.list;

			filteredList = this.list.filter((item) => {
				if (item.name.toLowerCase().includes(this.name.toLowerCase())) {
					return item;
				}
			});

			return filteredList;
		},
	},
	data() {
		return {
			name: "",
		};
	},
	mounted() {
		// Добавляем обработчик клика вне компонента
		document.addEventListener("click", this.handleClickOutside);
	},
	beforeDestroy() {
		// Удаляем обработчик клика вне компонента
		document.removeEventListener("click", this.handleClickOutside);
	},
	methods: {
		handleClickOutside(event) {
			// Проверка клика вне компонента
			if (this.$refs.filter && !this.$refs.filter.contains(event.target)) {
				this.$emit("changeFilterStatus", false, this.filter.name);
			}
		},
		selectItem(id) {
			if (this.selected.includes(id)) {
				this.selected = this.selected.filter((item) => item !== id);
				return;
			}

			this.selected.push(id);
		},
	},
};
</script>

<style>
.filter {
	position: relative;
}

.filter > .filter-title {
	cursor: pointer;
	display: flex;
	justify-content: space-between;
	align-items: center;
	gap: 10px;

	padding: 10px;
	border: 2px solid var(--input-border-color-inactive);
	border-radius: 10px;

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

.filter > .filter-title:is(:hover, .active) {
	background-color: rgba(0, 0, 0, 0.05);
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
	top: 55px;
	left: 0px;
	display: flex;
	flex-direction: column;
	gap: 10px;

	padding: 10px;
	border-radius: 10px;
	min-width: 400px;
	max-width: 650px;

	opacity: 0;
	visibility: hidden;

	background-color: white;
	box-shadow: 0px 0px 15px 5px rgba(0, 0, 0, 0.1);
}

.filter > .filter-body > .container-input {
	display: flex;
	flex-direction: column;
}

.filter > .filter-body > .container-input > input {
	box-sizing: border-box;

	outline: none;
	padding: 15px;
	border: 2px solid var(--input-border-color-inactive);
	border-radius: 10px;

	font-size: 1.125rem;
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
	background-color: var(--button-default-color);
	color: white;
}

.filter > .filter-body > .container-buttons > button.create:hover {
	background-color: var(--button-default-color-hover);
	color: white;
}

.filter > .filter-body > .container-buttons > button.clear {
	background-color: rgba(255, 0, 0, 0.1);
	color: rgb(255, 94, 94);
}

.filter > .filter-body > .container-buttons > button.create:hover {
	background-color: var(--button-default-color-hover);
	color: white;
}

.filter > .filter-body.active {
	opacity: 1;
	visibility: visible;
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
	cursor: pointer;
	display: flex;
	align-items: center;
	gap: 10px;

	padding: 10px;
	white-space: nowrap;
}

.filter > .filter-body > ol > li:hover {
	padding: 10px;
	border-radius: 10px;
	background-color: rgba(0, 0, 0, 0.05);
}

.filter > .filter-body > ol > li.empty {
	color: rgb(150, 150, 150);
	margin: auto;
}

.filter > .filter-body > ol > li > .check {
	position: relative;
	display: flex;
	justify-content: center;
	align-items: center;

	width: 20px;
	height: 20px;

	border: 2px solid var(--input-border-color-inactive);
	border-radius: 5px;
}

.filter > .filter-body > ol > li > .check > svg {
	position: absolute;

	opacity: 0;
	visibility: hidden;

	fill: var(--button-default-color);
}

.filter > .filter-body > ol > li > .check.active > svg {
	opacity: 1;
	visibility: visible;
}

.filter > .filter-body > ol > li.all {
	color: rgba(0, 0, 0, 0.5);
}

.filter > .filter-body > ol > li.all .check > svg {
	fill: rgb(150, 150, 150);
}
</style>
