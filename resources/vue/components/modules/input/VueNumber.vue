<template>
	<div class="vue-input__number">
		<vue-input-label v-if="$slots.label">
			<slot name="label"></slot>
		</vue-input-label>

		<input
			v-model="currentNumber"
			class="vue-input__number-input"
			type="text"
			:placeholder="placeholder"
			:inputmode="'numeric'"
			:autocomplete="autocomplete"
			ref="inputNumber"
		/>

		<div class="vue-input__number-buttons">
			<button class="vue-input__number-button" @click="increment">
				<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px">
					<path d="M440-440H200v-80h240v-240h80v240h240v80H520v240h-80v-240Z" />
				</svg>
			</button>
			<button class="vue-input__number-button" @click="decrement">
				<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px">
					<path d="M200-440v-80h560v80H200Z" />
				</svg>
			</button>
		</div>

		<VueInputError v-if="error" :is-top="!$slots.label">
			<slot name="error"></slot>
		</VueInputError>
	</div>
</template>

<script>
import VueInputLabel from "./ui/VueInputLabel.vue";
import VueInputError from "./ui/VueInputError.vue";

export default {
	name: "VueNumber",
	components: { VueInputLabel, VueInputError },
	props: {
		modelValue: {
			type: [Number, String],
			default: "",
			required: true,
		},
		min: {
			type: Number,
			default: null,
		},
		max: {
			type: Number,
			default: null,
		},
		placeholder: {
			type: String,
			default: "Введите значение",
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
	computed: {
		currentNumber: {
			get() {
				return Number(this.modelValue);
			},

			set(value) {
				this.guardInput(value);
			},
		},
	},
	methods: {
		guardInput(value) {
			if (value === "") {
				this.$emit("update:modelValue", this.min);
				this.$refs.inputNumber.value = this.min;

				return;
			}

			if (/[\D]/g.test(value)) {
				this.$refs.inputNumber.value = this.modelValue;
				this.$emit("update:modelValue", this.modelValue);

				return;
			}

			if (this.min !== null && this.min > value) {
				this.$refs.inputNumber.value = this.min;
				this.$emit("update:modelValue", this.min);

				return;
			}

			if (this.max !== null && this.max < value) {
				this.$refs.inputNumber.value = this.max;
				this.$emit("update:modelValue", this.max);

				return;
			}

			this.$emit("update:modelValue", value);
		},

		increment() {
			if (this.max !== null && this.max <= this.modelValue) {
				return;
			}

			this.$emit("update:modelValue", Number(this.modelValue) + 1);
		},

		decrement() {
			if (this.min !== null && this.min >= this.modelValue) {
				return;
			}

			this.$emit("update:modelValue", Number(this.modelValue) - 1);
		},
	},
};
</script>

<style>
.vue-input__number {
	display: flex;
	position: relative;
	align-items: center;
	gap: var(--input-number-gap);

	caret-color: var(--input-caret-color);
	box-sizing: border-box;

	width: 100%;

	border-radius: var(--input-border-radius);
	border: var(--input-border);

	height: var(--input-height);

	color: var(--input-color);
	background-color: var(--input-background-color);

	transition: var(--input-transition);
}

.vue-input__number:has(input:focus) {
	border: var(--input-border-focus);
}

.vue-input__number-label {
	z-index: var(--input-label-z-index);
	top: var(--input-label-top);
	left: var(--input-label-left);
	position: absolute;

	border-radius: var(--input-label-border-radius);
	padding: var(--input-label-padding);
	background-color: var(--input-label-background-color);

	font-weight: var(--input-label-font-weight);
	color: var(--input-label-color);
	font-size: var(--input-label-font-size);
}

.vue-input__number-input {
	border: none;
	caret-color: var(--input-caret-color);
	margin: 0px 0px 0px 15px;

	color: var(--input-color);
	font-size: var(--input-font-size);
	font-family: var(--input-font-family);
	background-color: var(--input-background-color);

	width: calc(100% - 60px);
	height: calc(var(--input-height) - var(--input-border-width) * 2);

	transition: var(--input-transition);
}

.vue-input__number-buttons {
	display: flex;
	gap: var(--input-number-buttons-gap);

	margin: var(--input-number-buttons-margin);
}

.vue-input__number-button {
	cursor: pointer;

	border: 0px;
	border-radius: var(--input-number-button-border-radius);

	height: var(--input-number-button-height);
	width: var(--input-number-button-width);
	background-color: var(--input-number-button-background-color);

	transition: var(--input-number-button-transition);
}

.vue-input__number-button:hover {
	border: 0px;
	background-color: rgba(0, 0, 0, 0);
}

.vue-input__number-button svg {
	fill: var(--input-number-button-svg-fill);
}

.vue-input__number-button:hover svg {
	fill: var(--input-number-button-svg-fill-hover);
}
</style>
