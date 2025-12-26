<template>
	<div class="list">
		<div class="list__input" v-if="isSearch">
			<input type="text" name="" id="" placeholder="Поиск" v-model="searchValue" />
		</div>

		<template v-if="type === 'checkbox'">
			<div class="list__checkbox">
				<label class="list__checkbox-item" v-for="(item, index) in getSortedList" :key="item[option]">
					<div class="list__checkbox-label">
						<span>{{ index + 1 + ")" }}</span>
						<span>{{ item[option] ?? "Нет значения" }}</span>
					</div>

					<input
						class="list__checkbox-input"
						type="checkbox"
						:value="item"
						v-model="selectedValues"
					/>

					<div class="list__checkbox-icon" aria-hidden="true">
						<div class="list__checkbox-icon--checked">
							<svg
								xmlns="http://www.w3.org/2000/svg"
								height="30px"
								viewBox="0 -960 960 960"
								width="30px"
							>
								<path d="M382-240 154-468l57-57 171 171 367-367 57 57-424 424Z" />
							</svg>
						</div>
						<div class="list__checkbox-icon--unchecked"></div>
					</div>
				</label>
			</div>
		</template>

		<template v-if="type === 'list'">
			<label
				class="list__list"
				:class="look"
				v-for="(item, index) in getSortedList"
				:key="item.name"
				@click="$emit('selectValue', item)"
			>
				<div class="list__list-label">
					<span>{{ item[option] ?? "Нет значения" }}</span>
				</div>
			</label>
		</template>
	</div>

	<Pagination
		v-if="isPagination"
		:settings="settings"
		:arrayLength="getListLength"
		@changePage="changePage"
	/>
</template>

<script>
import Pagination from "./Pagination.vue";

export default {
	components: {
		Pagination,
	},
	emits: ["selectValue", 'update:modelValue'],
	props: {
		modelValue: {
			type: Array,
			default: () => [],
			required: false,
		},
		list: {
			type: Array,
			default: () => [],
			required: true,
		},
		isSearch: {
			type: Boolean,
			default: true,
		},
		isPagination: {
			type: Boolean,
			default: true,
		},
		type: {
			type: String,
			default: "checkbox",
		},
		look: {
			type: String,
			default: "",
		},
		range: {
			type: Number,
			default: 5,
		},
		elements: {
			type: Number,
			default: 10,
		},
		option: {
			type: String,
			default: "name",
		},
	},
	data() {
		return {
			searchValue: "",
			settings: {
				pages: {
					range: this.range,
					current: 1,
				},
				elements: {
					range: this.elements,
				},
			},
		};
	},
	computed: {
		getListLength() {
			if (this.searchValue !== "") {
				return this.getSortedList.length;
			}

			return this.list.length;
		},
		getSortedList() {
			let copyList = [...this.list];

			if (this.isSearch) {
				if (this.searchValue !== "") {
					copyList = copyList.filter((item) => {
						return item[this.option].toLowerCase().includes(this.searchValue.toLowerCase());
					});
				}
			}

			if (this.isPagination) {
				return copyList.splice(
					(this.settings.pages.current - 1) * this.settings.elements.range,
					this.settings.elements.range
				);
			}

			return copyList;
		},
		selectedValues: {
			get() {
				return this.modelValue;
			},
			set(value) {
				this.$emit("update:modelValue", value);
			},
		},
	},
	methods: {
		changePage(page) {
			if (page > Math.ceil(this.getListLength / this.settings.elements.range)) {
				return;
			} else if (page < 1) {
				return;
			}

			this.settings.pages.current = page;
		},
	},
};
</script>

<style scoped>
* {
	user-select: none;
}

/* Основной контейнер */
.list {
	display: flex;
	flex-direction: column;
	gap: var(--list-gap);

	background-color: var(--list-background-color);
}

.list > .list__input > input {
	box-sizing: border-box;

	border: var(--list-input-border);
	border-radius: var(--list-input-border-radius);
	padding: var(--list-input-padding);
	margin-bottom: 15px;

	width: 100%;
	height: 50px;
	font-size: 1.125rem;
	background-color: var(--list-input-background-color);
}

/* Чекбокс */
.list__checkbox { 
	display: flex;
	flex-direction: column;
	gap: 10px;
}

.list__checkbox > .list__checkbox-item {
	cursor: pointer;

	position: relative;

	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 10px;

	border: var(--list-checkbox-border);
	border-radius: var(--list-checkbox-border-radius);
	padding: var(--list-checkbox-padding);

	color: var(--list-checkbox-font-color);

	font-size: 1.125rem;
	background-color: var(--list-checkbox-background-color);

	transition: all 0.2s ease;
}

.list__checkbox > .list__checkbox-item:hover {
	border: var(--default-border-focus);
	background-color: var(--item-background-color-active);
}

.list__checkbox-input {
	appearance: none;
	position: relative;

	border-radius: 7.5px;
	border: var(--default-border);

	width: 30px;
	height: 30px;

	transition: all 0.2s;
}

.list__checkbox-input:hover {
	border: var(--default-border-focus);
	background-color: var(--default-background-color);
}

.list__checkbox-label {
	display: flex;
	gap: 10px;
}

.list__checkbox-icon {
	position: absolute;
	top: 12px;
	right: 10px;
	display: flex;
	width: 30px;
	height: 30px;
	padding: 3px;
	justify-content: center;
	align-items: center;
	border: 1px solid transparent;
	border-radius: 20px;
	user-select: none;
}

.list__checkbox-icon--checked,
.list__checkbox-input:checked + .list__checkbox-icon > .list__checkbox-icon--unchecked {
	display: none;
}

.list__checkbox-input:checked {
	border: var(--default-border-focus);
	background-color: var(--default-background-color);
}

.list__checkbox-input:checked + .list__checkbox-icon,
.radio-input:checked + .radio-new {
	color: #000000;
}

/* .list__checkbox > .list__checkbox-item:has(.list__checkbox-input:checked) {
	color: var(--primary-color);
} */

.list__checkbox-input:checked + .list__checkbox-icon > .list__checkbox-icon--checked,
.list__checkbox-input:not(:checked) + .list__checkbox-icon > .list__checkbox-icon--unchecked {
	display: initial;
	fill: var(--primary-color);
}

.list__checkbox-input:disabled {
	background: gray;
}

/* Список */
.list__list {
	cursor: pointer;

	position: relative;

	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: var(--list-list-gap);

	border: var(--list-list-border);

	border-radius: var(--list-list-border-radius);
	padding: 12.5px	;

	height: 50px;
	width: 100%;

	color: black;

	font-size: 1.125rem;
	background-color: var(--color-background-list);

	transition: all 0.2s ease;
}

.list__list:hover {
	border: var(--default-border-focus);
	background-color: var(--item-background-color-active);
}

.list__list.selected {
	background-color: var(--color-background-list-selected);
	color: white;
}
</style>
