<template>
	<VueInput :error="error">
		<template #label v-if="$slots.label">
			<slot name="label"></slot>
		</template>

		<template #input>
			<div class="selector" ref="selector">
				<div class="selector__control" :class="{ open: isOpen }" @click="isOpen = !isOpen">
					<div class="selector__control-label">
						{{ modelValue !== "" ? list.find((item) => item.value === modelValue)?.label : placeholder }}
					</div>

					<button
						class="selector__control-button"
						v-if="modelValue !== '' && isClear"
						@click="$emit('update:modelValue', '')"
					>
						<svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px">
							<path
								d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"
							/>
						</svg>
					</button>

					<button class="selector__control-button">
						<VueIcon :name="'Arrow'" :fill="'black'" :width="'15px'" :height="'15px'" />
					</button>
				</div>

				<div class="selector__menu" v-if="isOpen" :class="{ 'top': isTop }">
					<div class="selector__menu-search" v-if="isSearch">
						<div v-if="name !== ''" class="selector__menu-search-icon" @click.stop.prevent="name = ''">
							<svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px">
								<path
									d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"
								/>
							</svg>
						</div>

						<input
							ref="searchInput"
							v-if="isSearch"
							class="selector__menu-input"
							type="text"
							placeholder="Введите название"
							v-model="name"
						/>
					</div>

					<ul class="selector__menu-list" :style="{ maxHeight: getMenuHeight }">
						<li
							class="selector__list-item"
							v-for="item in getFilteredList"
							v-if="getFilteredList.length > 0"
							:class="{ active: item.value === modelValue, disabled: item.disabled }"
							@click="select(item)"
						>
							{{ item.label }}
						</li>
						<li class="selector__list-item empty" v-else>Ничего нет</li>
					</ul>
				</div>
			</div>
		</template>

		<template #error v-if="error">
			<slot name="error"></slot>
		</template>
	</VueInput>
</template>

<script>
import VueInput from "./VueInput.vue";

export default {
	name: "VueSelector",
	components: { VueInput },
	props: {
		modelValue: {
			type: [String, Number, null, Boolean],
			default: "",
			required: true,
		},
		list: {
			type: Array,
			default: [],
			required: true,
		},
		size: {
			type: Number,
			default: 5,
		},
		placeholder: {
			type: String,
			default: "Ничего не выбрано",
		},
		isClear: {
			type: Boolean,
			default: true,
		},
		isSearch: {
			type: Boolean,
			default: true,
		},
		error: {
			type: Boolean,
			default: false,
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

			return filteredList;
		},

		getMenuHeight() {
			let element = window.getComputedStyle(document.body);

			let menuListItemMinHeight = element.getPropertyValue("--selector-menu-list-item-min-height").replace(/\D/gi, "");
			let menuListGap = element.getPropertyValue("--selector-menu-list-gap").replace(/\D/gi, "");
			let controlPadding = element.getPropertyValue("--selector-control-padding").replace(/\D/gi, "");
			let menuBorderWidth = element.getPropertyValue("--selector-menu-border-width").replace(/\D/gi, "");

			let result = menuListItemMinHeight * this.size + +controlPadding * 3;

			return `${result}px`;
		},

		isTop() {
			return true;
		},
	},
	methods: {
		/* Выбор значения */
		select(item) {
			if (item.disabled) {
				return;
			}

			this.isOpen = false;
			this.$emit("update:modelValue", item.value);
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

<style>
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
	gap: var(--selector-control-gap);

	border: var(--selector-control-border);
	border-radius: var(--selector-control-border-radius);
	padding: var(--selector-control-padding);

	height: var(--selector-control-height);

	caret-color: var(--selector-control-caret-color);
	background-color: var(--selector-control-background-color);

	font: var(--selector-control-font);

	transition: var(--selector-control-transition);
}

.selector__control-label {
	display: flex;
	align-items: center;
	flex-grow: 1;

	overflow: hidden;
	white-space: nowrap;

	height: var(--selector-control-label-height);
	width: var(--selector-control-label-width);

	font: var(--selector-control-label-font);
}

.selector__control-button {
	cursor: pointer;
	display: flex;
	justify-content: center;
	align-items: center;

	border: var(--selector-control-button-border);
	border-radius: var(--selector-control-button-border-radius);
	background-color: var(--selector-control-button-background-color);

	aspect-ratio: 1 / 1;
	width: auto;
	height: auto;
}

.selector__control-button > svg {
	fill: var(--selector-control-button-svg-fill);
}

.selector__control-button:hover > svg {
	fill: var(--selector-control-button-svg-fill-hover);
}

.selector__control.open {
	border: var(--selector-control-border-focus);
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
	gap: var(--selector-menu-gap);

	box-sizing: border-box;
	position: absolute;
	z-index: var(--selector-menu-z-index);
	top: var(--selector-menu-top);
	left: var(--selector-menu-left);

	border-top: var(--selector-menu-border-top);
	border-right: var(--selector-menu-border-right);
	border-bottom: var(--selector-menu-border-bottom);
	border-left: var(--selector-menu-border-left);
	border-style: var(--selector-menu-border-style);
	border-color: var(--selector-menu-border-color);

	border-radius: var(--selector-menu-border-radius);
	padding: var(--selector-menu-padding);

	width: var(--selector-menu-width);
	background-color: var(--selector-menu-background-color);

	transition: var(--selector-menu-transition);
	box-shadow: var(--selector-menu-box-shadow);
	animation: var(--selector-menu-animation);
}

.selector__menu-search {
	position: relative;
}

.selector__menu-search-icon {
	cursor: pointer;
	position: absolute;
	top: 15px;
	right: 15px;

	fill: var(--selector-control-button-svg-fill);
}

.selector__menu-search-icon:hover {
	fill: var(--selector-control-button-svg-fill-hover);
}

.selector__menu-input {
	box-sizing: border-box;

	outline: none;
	padding: var(--selector-menu-input-padding);
	border: var(--selector-menu-input-border);
	border-radius: var(--selector-menu-input-border-radius);

	min-height: var(--selector-menu-input-height);
	width: var(--selector-menu-input-width);

	font: var(--selector-menu-input-font);
	transition: var(--selector-menu-input-transition);
}

.selector__menu-input:focus {
	border: var(--selector-menu-input-border-focus);
}

.selector__menu-list {
	display: flex;
	flex-direction: column;
	gap: var(--selector-menu-list-gap);
	overflow-y: auto;

	padding: var(--selector-menu-list-padding);
	margin: var(--selector-menu-list-margin);
}

.selector__list-item {
	user-select: none;
	white-space: nowrap;
	display: flex;
	align-items: center;

	height: var(--selector-menu-list-item-height);
	min-height: var(--selector-menu-list-item-min-height);

	border: var(--selector-menu-list-item-border);
	padding: var(--selector-menu-list-item-padding);
	border-radius: var(--selector-menu-list-item-border-radius);

	font: var(--selector-menu-list-item-font);

	background-color: var(--selector-menu-list-item-background-color);
}

.selector__list-item:hover {
	background-color: var(--selector-menu-list-item-background-color-hover);
}

.selector__list-item.empty {
	border: var(--selector-menu-list-item-empty-border);

	color: var(--selector-menu-list-item-empty-color);
	background-color: var(--selector-menu-list-item-empty-background-color);
}

.selector__list-item.active {
	border: var(--selector-menu-list-item-active-border);

	color: var(--selector-menu-list-item-active-color);
	background-color: var(--selector-menu-list-item-active-background-color);
}

.selector__list-item.disabled {
	border: var(--selector-menu-list-item-disabled-border);

	color: var(--selector-menu-list-item-disabled-color);
	background-color: var(--selector-menu-list-item-disabled-background-color);
}

@media screen and (max-width: 500px) {
	.selector__list-item {
		text-wrap: balance;
	}
}
</style>
