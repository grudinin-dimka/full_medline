<template>
	<div
		class="selector"
		ref="selector"
		:class="{
			error: type === 'error',
			create: type === 'create',
		}"
	>
		<div class="selector__control" :class="{ open: isOpen }">
			<div class="selector__control-label" @click="isOpen = !isOpen">
				{{
					modelValue !== ""
						? list.find((item) => item.value === modelValue).label
						: placeholder
				}}
			</div>

			<div
				class="selector__control-clear"
				v-if="modelValue !== '' && cleared"
				@click="$emit('update:modelValue', '')"
			>
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

			<div class="selector__control-arrow" @click="isOpen = !isOpen">
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

		<div class="selector__menu" :class="{ open: isOpen }">
			<div class="selector__menu-input" v-if="filter">
				<input type="text" placeholder="Введите название" v-model="name" />
			</div>

			<ul class="selector__menu-list">
				<li
					class="selector__list-item"
					v-for="item in getFilteredList"
					v-if="getFilteredList.length > 0"
					:class="{ active: item.value === modelValue }"
					@click="select(item.value)"
				>
					{{ item.label }}
				</li>
				<li class="selector__list-item empty" v-else>Ничего нет</li>
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
		cleared: {
			type: Boolean,
			default: true,
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
.selector {
	width: 100%;

	position: relative;
	display: inline-block;
}

.selector__control {
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

	height: 62px;

	caret-color: var(--input-border-color-active);
	background-color: white;

	font-size: 20px;
	font-family: "Rubik";

	transition: all 0.2s;
}

.selector__control > .selector__control-label {
	display: flex;
	align-items: center;
	flex-grow: 1;

	height: 62px;
	overflow: hidden;
	white-space: nowrap;

	width: 100%;
	overflow: hidden;
}

.selector__control > :is(.selector__control-clear, .selector__control-arrow) {
	cursor: pointer;
	display: flex;
	justify-content: center;
	align-items: center;
}

.selector__control.open {
	border: var(--input-border-focus);
}

.selector__menu-input > input {
	box-sizing: border-box;

	outline: none;
	padding: 15px;
	border: var(--input-border);
	border-radius: var(--input-border-radius);

	width: 100%;

	font-size: 1.125rem;
	transition: all 0.2s;
}

.selector__menu-input > input:focus {
	border: var(--input-border-focus);
}

.selector.error > .selector__control {
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

.selector.create > .selector__control.open {
	border: 1px solid #44a533;
}

/* Меню */
.selector__menu {
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

.selector__menu.open {
	visibility: visible;
	opacity: 1;
}

.selector__menu-list {
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
.selector__menu-list::-webkit-scrollbar {
	width: 20px;
}

.selector__menu-list::-webkit-scrollbar-track {
	background-color: rgb(255, 255, 255);
	border-radius: 10px;
	margin: 2px;
}

.selector__menu-list::-webkit-scrollbar-thumb {
	background-color: rgb(220, 220, 220);
	border: 6px solid rgb(255, 255, 255);
	border-radius: 10px;
}

.selector__menu-list::-webkit-scrollbar-thumb:hover {
	background-color: rgb(230, 230, 230);
	cursor: all-scroll;
}
/* Конец. */

.selector__list-item {
	user-select: none;
	/* white-space: nowrap; */

	padding: 10px;
	border-radius: 10px;

	font-family: "Rubik";
	font-size: 1.125rem;

	background-color: rgba(255, 255, 255, 1);
}

.selector__list-item:hover {
	background-color: rgb(242, 242, 242);
}

.selector__list-item.empty {
	color: rgb(150, 150, 150);
}

.selector__list-item.active {
	background-color: rgb(242, 242, 242);
}
</style>
