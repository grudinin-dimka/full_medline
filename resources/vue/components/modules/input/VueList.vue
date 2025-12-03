<template>
	<div class="list">
		<div class="list__head" v-if="$slots.title">
			<div class="list__head-title">
				<slot name="title"></slot>
			</div>

			<div class="list__head-search" :class="{ 'active': searchValue }">
				<div class="list__head-search-icon">
					<svg
						xmlns="http://www.w3.org/2000/svg"
						height="24px"
						viewBox="0 -960 960 960"
						width="24px"
						v-if="!searchValue"
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
						@click="searchValue = ''"
						v-else
					>
						<path
							d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"
						/>
					</svg>
				</div>

				<input
					class="list__head-search-input"
					type="text"
					name=""
					id=""
					placeholder="Поиск"
					v-model="searchValue"
				/>
			</div>
		</div>

		<div class="list__body" ref="listBody" :class="{ 'list__body--radial': !$slots.title }">
			<template v-if="type === 'checkbox'">
				<div class="list__body-checkbox">
					<label
						class="list__body-checkbox-item"
						v-for="(item, index) in getSortedList"
						:key="item[option]"
					>
						<div class="list__checkbox-label">
							<span>{{ index + 1 + ")" }}</span>
							<span>{{ item.label }}</span>
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
					class="list__body-item"
					:class="look"
					v-for="(item, index) in getSortedList"
					:key="item.name"
					@click="$emit('selectValue', item)"
				>
					<div class="list__body-item-label">
						<span>{{ item[option] ?? "Нет значения" }}</span>
					</div>
				</label>
			</template>
		</div>

		<div
			class="list__footer"
			:class="{ 'list__footer--checkbox': type === 'checkbox' }"
			v-if="isPagination"
		>
			<VuePagination :settings="settings" :arrayLength="getListLength" @changePage="changePage" />
		</div>
	</div>
</template>

<script>
import VuePagination from "../VuePagination.vue";

export default {
	name: "VueList",
	components: {
		VuePagination,
	},
	emits: ["selectValue", "update:modelValue"],
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
			type: [Number, null],
			default: null,
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
		/* Получение длинны списка */
		getListLength() {
			if (this.searchValue !== "") {
				return this.getSortedList.length;
			}

			return this.list.length;
		},

		/* Получение отсортированного списка */
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

		/* Получение выбранных значений */
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
		/* Переключение страниц */
		changePage(page) {
			if (page > Math.ceil(this.getListLength / this.settings.elements.range)) {
				return;
			} else if (page < 1) {
				return;
			}

			this.settings.pages.current = page;
		},

		/* Получение высоты */
		setBodyHeight() {
			let element = window.getComputedStyle(document.body);

			let listBodyPadding = element.getPropertyValue("--list-body-padding").replace(/\D/gi, "");
			let listBodyGap = element.getPropertyValue("--list-body-gap").replace(/\D/gi, "");
			let listItemHeight = element.getPropertyValue("--list-item-height").replace(/\D/gi, "");

			let result = +listItemHeight * this.elements + +listBodyGap * (this.elements - 1);

			switch (this.type) {
				case "list":
					result += +listBodyPadding * 2;
					break;
			}

			this.$refs.listBody.style.height = `${result}px`;
		},
	},
	mounted() {
		if (this.elements) {
			this.$nextTick(() => this.setBodyHeight());
		};
	},
};
</script>

<style>
.list {
	flex-grow: 1;
	box-sizing: border-box;
	display: flex;
	flex-direction: column;
}

.list * {
	user-select: none;
}

.list__input {
	padding: 10px;

	border-top: 0px;
	border-right: var(--default-border-width);
	border-bottom: var(--default-border-width);
	border-left: var(--default-border-width);
	border-style: var(--default-border-style);
	border-color: var(--default-border-color);
}

.list__input > input {
	box-sizing: border-box;

	border: var(--list-input-border);
	border-radius: var(--list-input-border-radius);
	padding: var(--list-input-padding);

	width: 100%;
	height: 50px;
	font-size: 1.125rem;
	background-color: var(--list-input-background-color);
}

/* list: head */
.list__head {
	display: flex;
	justify-content: space-between;
	align-items: center;
	gap: var(--list-head-gap);

	border: var(--list-head-border);
	border-radius: var(--list-head-border-radius);
	padding: var(--list-head-padding);

	height: var(--list-head-height);
	font: var(--list-head-font);
	color: var(--list-head-color);

	background-color: var(--list-head-background-color);
}

.list__head-search {
	position: relative;
	box-sizing: border-box;
	overflow: hidden;
	display: flex;

	border: var(--list-head-search-border);
	border-radius: var(--list-head-search-border-radius);
	padding: var(--list-head-search-padding);

	width: var(--list-head-search-width);
	height: var(--list-head-search-height);

	background-color: var(--list-head-search-background-color);

	transition: var(--list-head-search-transition);
}

.list__head-search:is(:hover, .active) {
	width: var(--list-head-search-width-hover);
	height: var(--list-head-search-height-hover);

	background-color: var(--list-head-search-background-color-hover);
}

.list__head-search:has(.list__head-search-input:focus) {
	width: var(--list-head-search-width-hover);
	border: var(--list-head-search-border-focus);

	background-color: var(--list-head-search-background-color-hover);
}

.list__head-search-icon {
	cursor: pointer;
	position: absolute;
	display: flex;
	justify-content: center;
	align-items: center;
	left: var(--list-head-search-icon-left);
	top: var(--list-head-search-icon-top);

	width: var(--list-head-search-icon-width);
	height: var(--list-head-search-icon-height);

	background-color: var(--list-head-search-icon-background-color);
}

.list__head-search-icon > svg {
	fill: var(--list-head-search-icon-svg-fill);

	width: var(--list-head-search-icon-svg-width);
	height: var(--list-head-search-icon-svg-height);
}

.list__head-search-icon > svg:hover {
	fill: var(--list-head-search-icon-svg-fill-hover);
}

.list__head-search-input {
	margin-left: var(--list-head-search-input-margin-left);
	width: var(--list-head-search-input-width);
	font: var(--list-head-search-input-font);

	background-color: var(--list-head-search-input-background-color);
	border: var(--list-head-search-input-border);
}

/* list: body */
.list__body {
	display: flex;
	flex-direction: column;
	gap: var(--list-body-gap);

	padding: var(--list-body-padding);

	border-top: var(--list-body-border-top);
	border-right: var(--list-body-border-right);
	border-bottom: var(--list-body-border-bottom);
	border-left: var(--list-body-border-left);
	border-style: var(--list-body-border-style);
	border-color: var(--list-body-border-color);
	border-radius: var(--list-body-border-radius);

	background-color: var(--list-body-background-color);
}

.list__body.list__body--radial {
	padding: var(--list-body-padding--radial);

	border-top: var(--list-body-border-top--radial);
	border-right: var(--list-body-border-right--radial);
	border-bottom: var(--list-body-border-bottom--radial);
	border-left: var(--list-body-border-left--radial);
	border-style: var(--list-body-border-style--radial);
	border-color: var(--list-body-border-style--radial);
	border-radius: var(--list-body-border-radius--radial);

	background-color: var(--list-body-background-color--radial);
}

/* list: body -> item */
.list__body-item {
	cursor: pointer;

	position: relative;

	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: var(--list-item-gap);

	border: var(--list-item-border);
	border-radius: var(--list-item-border-radius);
	padding: var(--list-item-padding);

	height: var(--list-item-height);
	width: var(--list-item-width);
	color: var(--list-item-color);
	font-size: var(--list-item-font);
	background-color: var(--list-item-background-color);

	transition: var(--list-item-transition);
}

.list__body-item:hover {
	border: var(--list-item-border-hover);
	background-color: var(--list-item-background-color-hover);
}

.list__body-item.selected {
	background-color: var(--list-item-background-color-selected);
	color: var(--list-item-color-selected);
}

/* Чекбокс */
.list__body-checkbox {
	display: flex;
	flex-direction: column;
	gap: 10px;
}

.list__body-checkbox-item {
	cursor: pointer;

	position: relative;

	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: var(--list-item-gap);

	border: var(--list-item-border);
	border-radius: var(--list-item-border-radius);
	padding: var(--list-item-padding);

	height: var(--list-item-height);
	width: var(--list-item-width);
	color: var(--list-item-color);
	font-size: var(--list-item-font);
	background-color: var(--list-item-background-color);

	transition: var(--list-item-transition);
}

.list__body-checkbox-item:hover {
	border: var(--list-item-border-hover);
	background-color: var(--list-item-background-color-hover);
}

.list__checkbox-input {
	appearance: none;
	position: relative;

	border-radius: var(--list-checkbox-input-border-radius);
	border: var(--list-checkbox-input-border);

	width: var(--list-checkbox-input-width);
	height: var(--list-checkbox-input-height);

	transition: var(--list-checkbox-input-transition);
}

.list__checkbox-input:hover {
	border: var(--list-checkbox-input-border--hover);
	background-color: var(--list-checkbox-input-background-color--hover);
}

.list__checkbox-label {
	display: flex;
	gap: 10px;
}

.list__checkbox-icon {
	position: absolute;
	top: var(--list-checkbox-icon-top);
	right: var(--list-checkbox-icon-right);
	display: flex;
	width: var(--list-checkbox-icon-width);
	height: var(--list-checkbox-icon-height);
	padding: var(--list-checkbox-icon-padding);
	justify-content: center;
	align-items: center;
	border: var(--list-checkbox-icon-border);
	border-radius: var(--list-checkbox-icon-border-radius);
	user-select: none;
}

.list__checkbox-icon--checked,
.list__checkbox-input:checked + .list__checkbox-icon > .list__checkbox-icon--unchecked {
	display: none;
}

.list__checkbox-input:checked {
	border: var(--list-checkbox-input-border--checked);
	background-color: var(--list-checkbox-input-background-color--checked);
}

.list__checkbox-input:checked + .list__checkbox-icon,
.radio-input:checked + .radio-new {
	color: #000000;
}

.list__checkbox > .list__body-checkbox-item:has(.list__checkbox-input:checked) {
}

.list__checkbox-input:checked + .list__checkbox-icon > .list__checkbox-icon--checked,
.list__checkbox-input:not(:checked) + .list__checkbox-icon > .list__checkbox-icon--unchecked {
	display: initial;
	fill: var(--list-checkbox-icon-fill);
}

.list__checkbox-input:disabled {
	background: gray;
}

/* list: footer */
.list__footer {
	padding: var(--list-footer-padding);

	border-top: var(--list-footer-border-top);
	border-right: var(--list-footer-border-right);
	border-bottom: var(--list-footer-border-bottom);
	border-left: var(--list-footer-border-left);
	border-style: var(--list-footer-border-style);
	border-color: var(--list-footer-border-color);
	border-radius: var(--list-footer-border-radius);

	height: var(--list-footer-height);
	background-color: var(--list-footer-background-color);
}

.list__footer.list__footer--checkbox {
	padding: var(--list-footer-padding--checkbox);

	border-top: var(--list-footer-border-top--checkbox);
	border-right: var(--list-footer-border-right--checkbox);
	border-bottom: var(--list-footer-border-bottom--checkbox);
	border-left: var(--list-footer-border-left--checkbox);
	border-style: var(--list-footer-border-style--checkbox);
	border-color: var(--list-footer-border-color--checkbox);
	border-radius: var(--list-footer-border-radius--checkbox);

	height: var(--list-footer-height--checkbox);
	background-color: var(--list-footer-background-color--checkbox);
}
</style>
