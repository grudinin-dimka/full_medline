<template>
	<!-- checkbox -->
	<label class="checkbox" v-if="type == 'checkbox'" :class="{ error: error }">
		<input
			class="checkbox-input"
			type="checkbox"
			v-model="currentCheckbox"
			@change="$emit('update:modelValue', $event.target.checked)"
		/>

		<div class="checkbox-new" aria-hidden="true">
			<div class="checkbox-new_checked">
				<svg
					xmlns="http://www.w3.org/2000/svg"
					height="30px"
					viewBox="0 -960 960 960"
					width="30px"
				>
					<path d="M382-240 154-468l57-57 171 171 367-367 57 57-424 424Z" />
				</svg>
			</div>
		</div>

		<span class="checkbox-label">
			<slot name="label"></slot>
		</span>
	</label>

	<!-- other -->
	<div class="input" v-else>
		<div class="input__label" v-if="$slots.label">
			<slot name="label"></slot>
		</div>

		<template v-if="type == 'number'">
			<input
				v-model="currentNumber"
				type="number"
				:min="min"
				:max="max"
				:placeholder="placeholder"
				:inputmode="inputmode"
				:autocomplete="autocomplete"
				@input.prevent="guardInput('number', $event.target.value.trim())"
			/>
		</template>

		<template v-else-if="type == 'password'">
			<input
				:type="hide ? 'password' : 'text'"
				:value="modelValue"
				:placeholder="placeholder"
				:inputmode="inputmode"
				autocomplete="off"
				@input="$emit('update:modelValue', $event.target.value.trim())"
			/>

			<div class="input__icon" @click="changeType">
				<svg
					v-if="hide"
					xmlns="http://www.w3.org/2000/svg"
					height="28px"
					viewBox="0 -960 960 960"
					width="28px"
				>
					<path
						d="m644-428-58-58q9-47-27-88t-93-32l-58-58q17-8 34.5-12t37.5-4q75 0 127.5 52.5T660-500q0 20-4 37.5T644-428Zm128 126-58-56q38-29 67.5-63.5T832-500q-50-101-143.5-160.5T480-720q-29 0-57 4t-55 12l-62-62q41-17 84-25.5t90-8.5q151 0 269 83.5T920-500q-23 59-60.5 109.5T772-302Zm20 246L624-222q-35 11-70.5 16.5T480-200q-151 0-269-83.5T40-500q21-53 53-98.5t73-81.5L56-792l56-56 736 736-56 56ZM222-624q-29 26-53 57t-41 67q50 101 143.5 160.5T480-280q20 0 39-2.5t39-5.5l-36-38q-11 3-21 4.5t-21 1.5q-75 0-127.5-52.5T300-500q0-11 1.5-21t4.5-21l-84-82Zm319 93Zm-151 75Z"
					/>
				</svg>

				<svg
					v-else
					xmlns="http://www.w3.org/2000/svg"
					height="28px"
					viewBox="0 -960 960 960"
					width="28px"
				>
					<path
						d="M480-320q75 0 127.5-52.5T660-500q0-75-52.5-127.5T480-680q-75 0-127.5 52.5T300-500q0 75 52.5 127.5T480-320Zm0-72q-45 0-76.5-31.5T372-500q0-45 31.5-76.5T480-608q45 0 76.5 31.5T588-500q0 45-31.5 76.5T480-392Zm0 192q-146 0-266-81.5T40-500q54-137 174-218.5T480-800q146 0 266 81.5T920-500q-54 137-174 218.5T480-200Zm0-300Zm0 220q113 0 207.5-59.5T832-500q-50-101-144.5-160.5T480-720q-113 0-207.5 59.5T128-500q50 101 144.5 160.5T480-280Z"
					/>
				</svg>
			</div>
		</template>

		<template v-else-if="type == 'phone'">
			<input
				type="tel"
				placeholder="+7(___)-___-__-__"
				v-mask="'+7(###)-###-##-##'"
				:value="modelValue"
				:inputmode="inputmode"
				autocomplete="off"
				@input="$emit('update:modelValue', $event.target.value.trim())"
			/>
		</template>

		<template v-else-if="type == 'mask'">
			<input
				type="tel"
				:placeholder="placeholder"
				v-mask="mask"
				:value="modelValue"
				:inputmode="inputmode"
				autocomplete="off"
				@input="$emit('update:modelValue', $event.target.value.trim())"
			/>
		</template>

		<template v-else-if="type == 'search'">
			<input
				:type="'search'"
				:value="modelValue"
				:placeholder="placeholder"
				:inputmode="inputmode"
				autocomplete="off"
				@input="$emit('update:modelValue', $event.target.value.trim())"
			/>

			<div v-if="modelValue" class="input__icon-search" @click="$emit('update:modelValue', '')">
				<svg
					data-v-05b9c86b=""
					xmlns="http://www.w3.org/2000/svg"
					width="16"
					height="16"
					viewBox="0 0 14 14"
				>
					<path
						data-v-05b9c86b=""
						d="M1.4 14L0 12.6L5.6 7L0 1.4L1.4 0L7 5.6L12.6 0L14 1.4L8.4 7L14 12.6L12.6 14L7 8.4L1.4 14Z"
					></path>
				</svg>
			</div>
		</template>

		<template v-else-if="type == 'file'">
			<div class="input__wrapper">
				<label class="input__file-button">
					<input
						name="file"
						type="file"
						class="input input__file"
						ref="image"
						@change="handleFileChange"
					/>

					<span class="input__file-icon-wrapper" ref="imageWrapper">
						<span class="input__file-text" ref="imageText">
							<svg
								class="input__file-icon"
								width="20"
								height="26"
								viewBox="0 0 16 20"
								fill="none"
								xmlns="http://www.w3.org/2000/svg"
							>
								<path
									d="M7 17H9V12.825L10.6 14.425L12 13L8 9L4 13L5.425 14.4L7 12.825V17ZM2 20C1.45 20 0.979167 19.8042 0.5875 19.4125C0.195833 19.0208 0 18.55 0 18V2C0 1.45 0.195833 0.979167 0.5875 0.5875C0.979167 0.195833 1.45 0 2 0H10L16 6V18C16 18.55 15.8042 19.0208 15.4125 19.4125C15.0208 19.8042 14.55 20 14 20H2ZM9 7V2H2V18H14V7H9Z"
								/>
							</svg>

							<template v-if="hasFile">
								{{ fileName ?? "Файл загружен" }}
							</template>
							<template v-else>
								{{ placeholder ? placeholder : "Файл не загружен" }}
							</template>
						</span>
					</span>
				</label>
				<div class="input__file-button-error" v-if="false">error</div>
			</div>
		</template>

		<template v-else-if="type == 'textarea'">
			<textarea
				:value="modelValue"
				:rows="rows"
				:placeholder="placeholder"
				:autocomplete="autocomplete"
				:style="{ resize: resize }"
				@input="$emit('update:modelValue', $event.target.value.trim())"
			></textarea>
		</template>

		<template v-else-if="type == 'select'">
			<select v-model="currentOption">
				<template v-for="item in options">
					<option :value="item.value" :disabled="item?.disabled ?? false">
						{{ item.label }}
					</option>
				</template>
			</select>
		</template>

		<template v-else>
			<input
				:type="type"
				:value="modelValue"
				:placeholder="placeholder"
				:inputmode="inputmode"
				autocomplete="off"
				@input="$emit('update:modelValue', $event.target.value.trim())"
			/>
		</template>

		<div v-if="error" class="input__error" :class="{ top: !$slots.label }">
			<slot name="error"></slot>
		</div>
	</div>
</template>

<script>
export default {
	props: {
		modelValue: {
			type: [String, Number, Boolean, null, Object],
			default: "",
			required: true,
		},
		options: {
			type: Array,
			default: [],
			required: false,
		},
		type: {
			type: String,
			default: "text",
		},
		inputmode: {
			type: String,
			default: "text",
		},

		/* textarea */
		rows: {
			type: Number,
			default: 1,
		},
		resize: {
			type: String,
			default: "none",
		},

		/* Минимальное и максимальное значения */
		min: {
			type: Number,
			default: null,
		},
		max: {
			type: Number,
			default: null,
		},

		/* Маска поля ввода */
		mask: {
			type: String,
			default: "",
		},

		placeholder: {
			type: String,
			default: "Введите значение",
		},
		look: {
			type: String,
			default: "",
		},
		autocomplete: {
			type: String,
			default: "off",
		},
		error: {
			type: Boolean,
			default: false,
		},
	},
	data() {
		return {
			hide: true,

			fileName: "",
			hasFile: false,

			currentNumber: this.modelValue,
			currentOption: this.modelValue, // инициализируем значением modelValue
			currentCheckbox: this.modelValue,
		};
	},
	watch: {
		modelValue(newValue) {
			switch (this.type) {
				case "select":
					this.currentOption = newValue;
					break;

				case "checkbox":
					this.currentCheckbox = newValue;
					break;

				case "number":
					this.currentNumber = newValue;
					break;
			}
		},

		currentOption(newValue) {
			this.$emit("update:modelValue", newValue);
		},

		currentNumber(newValue) {
			this.$emit("update:modelValue", newValue);
		},
	},
	selectedValues: {
		get() {
			return this.modelValue;
		},
		set(value) {
			this.$emit("update:modelValue", value);
		},
	},
	methods: {
		changeType() {
			this.hide = !this.hide;
		},

		onSelectChange(value) {
			this.currentOption = value;
			this.$emit("update:modelValue", value); // Обновляем родительское значение
		},

		files() {
			return this.$refs?.image.files ?? undefined;
		},

		clear() {
			this.hasFile = false;
			this.fileName = "";

			this.$refs.image.value = "";
		},

		handleFileChange(event) {
			const files = event.target.files;

			if (files && files.length > 0) {
				this.hasFile = true;

				this.fileName = event.target.files[0].name;
			}
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                     ПРОФИЛЬ                       |*/
		/* |___________________________________________________|*/
		guardInput(type, value) {
			if (type == "number") {
				if (this.min || this.max) {
					this.currentNumber = this.guardInputNumber(value);
				} else {
					this.currentNumber = value;
				}
			}
		},

		guardInputNumber(value) {
			if (this.min && this.max) {
				if (value > this.max) {
					return this.max;
				} else if (value < this.min) {
					return this.min;
				}

				return value;
			}

			if (this.min) {
				if (value < this.min) {
					return this.min;
				}

				return value;
			}

			if (this.max) {
				if (value > this.max) {
					return this.max;
				}

				return value;
			}
		},
	},
};
</script>

<style scoped>
.input {
	position: relative;
	display: flex;
	flex-direction: column;
	gap: var(--input-gap);

	width: 100%;
}

.input__icon {
	cursor: pointer;
	position: absolute;
	bottom: 14px;
	right: 10px;
}

.input__icon > svg {
	fill: rgba(255, 255, 255, 0.5);
}

.input__icon:hover > svg {
	fill: rgba(255, 255, 255, 1);
}

.input__icon-search {
	cursor: pointer;
	position: absolute;
	top: 22px;
	right: 18px;

	fill: var(--input-search-icon-color);
}

.input__label {
	color: var(--input-label-color);
	font-size: 1.125rem;
}

.input :is(input, textarea) {
	caret-color: var(--input-caret-color);
	box-sizing: border-box;

	border-radius: var(--input-border-radius);
	border: var(--input-border);

	height: 60px;

	color: var(--input-color);
	font-size: 1.25rem;
	font-family: "Rubik", sans-serif;
	background-color: var(--input-background-color);

	transition: all 0.2s ease;
}

.input input {
	padding: var(--input-padding);

	height: 60px;
}

.input textarea {
	padding: var(--textarea-padding);

	min-height: 150px;
	height: auto;
}

@media screen and (max-width: 600px) {
	.input :is(input, textarea) {
		width: 100%;
	}
}

/* Убрать стандартный крестик */
.input input[type="search"]::-webkit-search-cancel-button {
	display: none;
}

.input :is(input, textarea):focus {
	border: var(--input-border-focus);
}

.input :is(input, textarea)::placeholder {
	color: var(--input-placeholder-color);
}

.input .input__error {
	user-select: none;
	position: absolute;
	right: 10px;
	top: 15px;

	border: var(--error-border);
	border-radius: var(--error-border-radius);
	padding: var(--error-padding);

	background-color: var(--error-background-color);
	color: var(--error-color);

	max-width: 400px;

	animation: show-translate-x 0.2s ease;
}

.input .input__error.top {
	right: 10px;
	top: -10px;
}

/* Файл */
.input__wrapper {
	height: 100%;
	width: 100%;
	position: relative;
	text-align: center;

	display: flex;
	flex-direction: column;
}

.input__file {
	opacity: 0;
	visibility: hidden;
	position: absolute;
	width: 0px;
}

.input__file-icon-wrapper {
	box-sizing: border-box;
	margin-right: 15px;
	display: -webkit-box;
	display: -ms-flexbox;
	display: flex;
	flex-wrap: wrap;

	word-wrap: break-word;
	word-break: break-all;

	width: auto;
	height: 60px;
	padding: 0px 20px;

	-webkit-box-align: center;
	-ms-flex-align: center;
	align-items: center;
	-webkit-box-pack: center;
	-ms-flex-pack: center;
	justify-content: center;
}

.input__file-text {
	display: grid;
	justify-content: center;
	align-items: center;
	gap: 10px;

	grid-template-columns: auto auto;
}

.input__file-icon {
	fill: var(--input-file-icon-color);

	transition: all 0.2s ease;
}

.input__file-button {
	box-sizing: border-box;
	user-select: none;
	cursor: pointer;
	display: -webkit-box;
	display: -ms-flexbox;
	display: flex;
	-webkit-box-align: center;
	-ms-flex-align: center;
	align-items: center;
	-webkit-box-pack: start;
	-ms-flex-pack: start;

	border: var(--default-border);
	border-radius: calc(var(--default-border-radius) / 2);
	margin: 0 auto;

	width: 100%;

	font-size: 1.25rem;
	white-space: nowrap;
	overflow: hidden;

	color: var(--input-color);
	background-color: var(--input-background-color);

	transition: all 0.2s;
}

.input__file-button:is(:hover, .active, :focus) {
	border: var(--input-border-focus);
	color: var(--primary-color);
	background-color: var(--input-background-color);
}

.input__file-button:is(:hover, .active) .input__file-icon {
	fill: var(--primary-color);
}

.input__file-button.error {
	border: var(--input-error-border);
	border-radius: 10px 10px 0px 0px;

	color: var(--input-error-color);
	background-color: var(--input-error-background-color);
}

/* select */
.input :is(select) {
	cursor: pointer;
	box-sizing: border-box;

	border: var(--select-border);
	border-radius: var(--select-border-radius);

	padding: var(--select-padding);

	color: var(--select-color);
	font-size: 1.25rem;
	font-family: "Rubik", sans-serif;
	background-color: var(--select-background-color);

	transition: all 0.2s ease;
}

select,
::picker(select) {
	appearance: base-select;
}

select::picker-icon {
	color: #999;
	transition: 0.2s rotate;
}

select:open::picker-icon {
	rotate: 180deg;
}

::picker(select) {
	border: none;
}

option {
	color: var(--option-font-color);
	display: flex;
	justify-content: flex-start;
	gap: 20px;

	border: var(--option-border);
	background: var(--option-background-color);
	padding: var(--option-padding);
	transition: 0.2s;
}

option:not(option:last-of-type) {
	border-bottom: none;
}

option:nth-of-type(odd) {
	background: var(--input-background-color);
}

:is(option:hover):not(:disabled) {
	background-color: var(--select-background-color-hover);
}

:is(option:focus):not(:disabled) {
	color: var(--option-color-focus);
}

option:disabled {
	color: var(--option-color-disabled);
}

option::checkmark {
	order: 1;
	margin-left: auto;
}

::picker(select) {
	opacity: 0;
	transition: all 0.2s allow-discrete;
}

::picker(select):popover-open {
	opacity: 1;
}

@starting-style {
	::picker(select):popover-open {
		opacity: 0;
	}
}

::picker(select) {
	top: var(--select-top);
}

/* chekbox */
.checkbox {
	cursor: pointer;
	position: relative;
	width: fit-content;

	display: grid;
	grid-template-columns: auto auto;
	gap: var(--input-gap);
	width: 100%;
}

.checkbox-input {
	appearance: none;
	position: relative;
	width: 35px;
	height: 35px;
	border-radius: 10px;
	border: var(--input-border);

	transition: all 0.2s;
}

.checkbox-input:hover {
	border: var(--input-border-focus);
}

.checkbox-label {
	font-size: 1.25rem;

	transition: all 0.2s;
}

.checkbox-new_checked,
.checkbox-input + .checkbox-new > .checkbox-new_unchecked {
	display: none;
}

.checkbox-input:checked {
	border: var(--input-border-focus);

	display: initial;
}

.checkbox-input:checked + .checkbox-new > .checkbox-new_checked {
	fill: var(--primary-color);
	display: initial;
}

.checkbox-input:checked + .checkbox-new {
	color: #000000;
}

.checkbox-new {
	position: absolute;
	top: 0px;
	left: 0px;
	display: flex;
	width: 35px;
	height: 35px;
	padding: 3px;
	justify-content: center;
	align-items: center;
	border: 1px solid transparent;
	border-radius: 20px;
	user-select: none;
}

.checkbox-input:disabled {
	background: gray;
}

.checkbox.error .checkbox-input {
	border: var(--input-error-border);
}

.checkbox.error .checkbox-label {
	color: var(--input-error-color);
}

.checkbox.error .checkbox-input {
	border: var(--input-error-border);
}

.checkbox.error .checkbox-new .checkbox-new_checked {
	fill: var(--input-error-color);
}
</style>
