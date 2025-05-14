<template>
	<div
		class="select"
		ref="selector"
		:class="{
			error: type === 'error',
			create: type === 'create',
		}"
	>
		<div class="control" :class="{ open: isOpen }">
			<div class="label" @click="isOpen = !isOpen">
				{{
					modelValue !== ""
						? list.find((item) => item.value === modelValue).label
						: placeholder
				}}
			</div>
			<div class="clear" v-if="modelValue !== ''" @click="$emit('update:modelValue', '')">
				<svg
					xmlns="http://www.w3.org/2000/svg"
					height="20px"
					viewBox="0 -960 960 960"
					width="20px"
					fill="black"
				>
					<path
						d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"
					/>
				</svg>
			</div>
			<div class="arrow" @click="isOpen = !isOpen">
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
		</div>
		<div class="menu" :class="{ open: isOpen }">
			<div class="input" v-if="filter">
				<input type="text" placeholder="Введите название" v-model="name" />
			</div>
			<ul>
				<li
					v-for="item in getFilteredList"
					v-if="getFilteredList.length > 0"
					:class="{ active: item.value === modelValue }"
					@click="select(item.value)"
				>
					{{ item.label }}
				</li>
				<li v-else class="empty">Ничего нет</li>
			</ul>
		</div>
	</div>
</template>

<script>
import sorted from "../../services/sorted.js";

export default {
	components: {
		sorted,
	},
	props: {
		modelValue: {
			type: [String, Number],
			default: "",
			required: true,
		},
		list: {
			type: Array,
			default: [],
			required: true,
		},
		placeholder: {
			type: String,
			default: "Ничего не выбрано",
		},
		type: {
			type: String,
			default: "default",
		},
		filter: {
			type: Boolean,
			default: true,
		},
	},
	data() {
		return {
			name: "",
			isOpen: false,
			isFocus: false,
		};
	},
	watch: {
		isOpen(value) {
			if (value) {
				this.name = "";
			}
		},
		isFocus(value) {
			if (value) {
				this.isOpen = true;
			}
		},
	},
	computed: {
		getFilteredList() {
			let filteredList = this.list;

			/* Фильтрация по вводу значения */
			filteredList = filteredList.filter((item) => {
				return item?.label.toLowerCase().includes(this.name.toLowerCase());
			});

			sorted.sortString("up", filteredList);

			return filteredList;
		},
	},
	methods: {
		select(value) {
			this.isOpen = false;
			this.$emit("update:modelValue", value);
		},
		/* Обработка клика вне компонента */
		handleClickOutside(event) {
			if (this.$refs.selector && !this.$refs.selector.contains(event.target)) {
				this.isOpen = false;
			}
		},
	},
	mounted() {
		document.addEventListener("click", this.handleClickOutside);
	},
	unmounted() {
		document.removeEventListener("click", this.handleClickOutside);
	},
};
</script>

<style scoped>
.select {
	width: 100%;

	position: relative;
	display: inline-block;
}

.control {
	user-select: none;
	box-sizing: border-box;
	cursor: default;
	display: flex;
	justify-content: space-between;
	align-items: center;
	gap: 10px;

	border: 1px solid rgba(0, 0, 0, 0.25);
	border-radius: 10px;
	padding: 0px 10px 0px 10px;

	height: 58px;

	caret-color: var(--input-border-color-active);
	background-color: white;

	font-size: 20px;
	font-family: "Rubik";

	transition: all 0.2s;
}

.control > .label {
	display: flex;
	align-items: center;
	flex-grow: 1;

	height: 58px;
}

.control > :is(.clear, .arrow) {
	cursor: pointer;
	display: flex;
	justify-content: center;
	align-items: center;
}

.control.open {
	border: var(--input-border-focus);
}

.input > input {
	box-sizing: border-box;

	outline: none;
	padding: 15px;
	border: var(--input-border);
	border-radius: var(--input-border-radius);

	width: 100%;

	font-size: 1.125rem;
	transition: all 0.2s;
}

.input > input:focus {
	border: var(--input-border-focus);
}

.select.error > .control {
	background-color: var(--input-error-background-color);
	border: var(--input-error-border);

	border-radius: 10px;
	padding: 0px 10px 0px 10px;
	
	height: 58px;
	
	caret-color: red;

	font-size: 20px;
	font-family: "Rubik";

	transition: all 0.2s;
}

.select.create > .control.open {
	border: 1px solid #44a533;
}

/* Меню */
.menu {
	display: flex;
	flex-direction: column;
	gap: 10px;

	box-sizing: border-box;
	position: absolute;
	z-index: 1;
	top: 68px;
	left: 0px;

	width: 100%;
	border-radius: 15px;
	padding: 10px;

	visibility: hidden;
	opacity: 0;

	box-shadow: 0px 0px 15px 5px rgba(0, 0, 0, 0.1);
	background-color: white;

	transition: all 0.2s;
}

.menu.open {
	visibility: visible;
	opacity: 1;
}

.menu > ul {
	display: flex;
	flex-direction: column;
	gap: 5px;

	max-height: 430px;
	overflow-y: auto;

	padding: 0px;
	margin: 0px;
	list-style: none;
}

/* Скролбар блока с контентом. */
.menu > ul::-webkit-scrollbar {
	width: 20px;
}

.menu > ul::-webkit-scrollbar-track {
	background-color: rgb(255, 255, 255);
	border-radius: 10px;
	margin: 2px;
}

.menu > ul::-webkit-scrollbar-thumb {
	background-color: rgb(220, 220, 220);
	border: 6px solid rgb(255, 255, 255);
	border-radius: 10px;
}

.menu > ul::-webkit-scrollbar-thumb:hover {
	background-color: rgb(230, 230, 230);
	cursor: all-scroll;
}
/* Конец. */

.menu > ul > li {
	user-select: none;
	white-space: nowrap;

	padding: 10px;
	border-radius: 10px;

	font-family: "Rubik";
	font-size: 1.125rem;

	background-color: rgba(255, 255, 255, 1);
}

.menu > ul > li:hover {
	background-color: rgb(242, 242, 242);
}

.menu > ul > li.empty {
	color: rgb(150, 150, 150);
}

.menu > ul > li.active {
	background-color: rgb(242, 242, 242);
}
</style>
