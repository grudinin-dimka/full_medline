<template>
	<VueInput :error="error">
		<template #label v-if="$slots.label">
			<slot name="label"></slot>
		</template>

		<template #input>
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

		<template #error v-if="error">
			<slot name="error"></slot>
		</template>
	</VueInput>
</template>

<script>
import VueInput from "./VueInput.vue";

export default {
	name: "VueValues",
	components: { VueInput },
	props: {
		modelValue: {
			type: [String, Number, Boolean, null, Object],
			default: "",
			required: true,
		},
		inputmode: {
			type: String,
			default: "text",
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
		autocomplete: {
			type: String,
			default: "off",
		},
		error: {
			type: Boolean,
			default: false,
		},
	},
};
</script>
