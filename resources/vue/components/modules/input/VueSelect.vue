<template>
	<VueInput :error="error">
		<template #label v-if="$slots.label">
			<slot name="label"></slot>
		</template>

		<template #input>
			<select v-model="currentOption">
				<template v-for="item in options">
					<option :value="item.value" :disabled="item?.disabled ?? false">
						{{ item.label }}
					</option>
				</template>
			</select>
		</template>

		<template #error v-if="error">
			<slot name="error"></slot>
		</template>
	</VueInput>
</template>

<script>
import VueInput from "./VueInput.vue";

export default {
	name: "VueSelect",
	components: { VueInput },
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
		placeholder: {
			type: String,
			default: "Введите значение",
		},
		error: {
			type: Boolean,
			default: false,
		},
	},
	computed: {
		currentOption: {
			get() {
				return this.modelValue;
			},
			set(value) {
				this.$emit("update:modelValue", value);
			},
		},
	},
};
</script>

<style>
/* select */
.vue-input :is(select) {
	display: flex;
	align-items: center;
	cursor: pointer;
	box-sizing: border-box;

	border: var(--select-border);
	border-radius: var(--select-border-radius);

	padding: var(--select-padding);
	height: 62px;

	color: var(--select-color);
	font-size: var(--select-font-size);
	font-family: var(--select-font-family);
	background-color: var(--select-background-color);

	transition: var(--select-transition);
}

.vue-input select::picker-icon {
	color: #999;
	transition: 0.2s rotate;
}

.vue-input select:open::picker-icon {
	rotate: 180deg;
}

.vue-input select,
.vue-input ::picker(select) {
	appearance: base-select;
}

.vue-input ::picker(select) {
	top: var(--select-top);

	border: none;

	opacity: 0;
	transition: all 0.2s allow-discrete;
}

.vue-input option {
	color: var(--option-font-color);
	display: flex;
	justify-content: flex-start;
	gap: 20px;

	border: var(--option-border);
	background: var(--option-background-color);
	padding: var(--option-padding);
	transition: 0.2s;
}

.vue-input option:not(option:last-of-type) {
	border-bottom: none;
}

.vue-input option:nth-of-type(odd) {
	background: var(--option-background-color-odd);
}

.vue-input :is(option:hover):not(:disabled) {
	background-color: var(--select-background-color-hover);
}

.vue-input :is(option:focus):not(:disabled) {
	color: var(--option-color-focus);
}

.vue-input option:disabled {
	color: var(--option-color-disabled);
}

.vue-input option::checkmark {
	order: 1;
	margin-left: auto;
}

.vue-input ::picker(select):popover-open {
	opacity: 1;
}

@starting-style {
	.vue-input ::picker(select):popover-open {
		opacity: 0;
	}
}
</style>
