<template>
	<label class="vue-checkbox" :class="{ error: error }">
		<input
			class="vue-checkbox-input"
			type="checkbox"
			v-model="checkbox"
		/>

		<div class="vue-checkbox-new" aria-hidden="true">
			<div class="vue-checkbox-new_checked">
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

		<span class="vue-checkbox-label">
			<slot name="label"></slot>
		</span>
	</label>
</template>

<script>
export default {
	name: "VueCheckbox",
	props: {
		modelValue: {
			type: [String, Number, Boolean, null, Object],
			default: "",
			required: true,
		},
		error: {
			type: Boolean,
			default: false,
		},
	},
	computed: {
		checkbox: {
			get() {
				return this.modelValue;
			},

			set(value) {
				this.$emit("update:modelValue", newValue);
			},
		},
	},
};
</script>

<style>
/* chekbox */
.vue-checkbox {
	cursor: pointer;
	position: relative;
	width: fit-content;

	display: grid;
	grid-template-columns: auto auto;
	gap: var(--input-gap);
	width: 100%;
}

.vue-checkbox-input {
	appearance: none;
	position: relative;
	width: 35px;
	height: 35px;
	border-radius: 10px;
	border: var(--input-border);

	transition: all 0.2s;
}

.vue-checkbox-input:hover {
	border: var(--input-border-focus);
}

.vue-checkbox-label {
	font-size: 1.25rem;

	transition: all 0.2s;
}

.vue-checkbox-new_checked,
.vue-checkbox-input + .vue-checkbox-new > .checkbox-new_unchecked {
	display: none;
}

.vue-checkbox-input:checked {
	border: var(--input-border-focus);

	display: initial;
}

.vue-checkbox-input:checked + .vue-checkbox-new > .vue-checkbox-new_checked {
	fill: var(--primary-color);
	display: initial;
}

.vue-checkbox-input:checked + .vue-checkbox-new {
	color: #000000;
}

.vue-checkbox-new {
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

.vue-checkbox-input:disabled {
	background: gray;
}

.vue-checkbox.error .vue-checkbox-input {
	border: var(--input-error-border);
}

.vue-checkbox.error .vue-checkbox-label {
	color: var(--input-error-color);
}

.vue-checkbox.error .vue-checkbox-input {
	border: var(--input-error-border);
}

.vue-checkbox.error .vue-checkbox-new .vue-checkbox-new_checked {
	fill: var(--input-error-color);
}
</style>
