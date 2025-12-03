<template>
	<VueInput :error="error">
		<template #label v-if="$slots.label">
			<slot name="label"></slot>
		</template>

		<template #input>
			<template v-if="type == 'phone'">
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

				<div
					v-if="modelValue"
					class="input__icon-search"
					@click="$emit('update:modelValue', '')"
				>
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
		autocomplete: {
			type: String,
			default: "off",
		},
		error: {
			type: Boolean,
			default: false,
		},
	},
	methods: {
		guardInput(type, value) {
			switch (type) {
				case "number":
					this.$emit("update:modelValue", this.guardInputNumber(value));
					break;

				default:
					break;
			}
		},

		guardInputNumber(value) {
			if (Number.isNaN(Number(value))) {
				return this.modelValue;
			}

			return value;
		},
	},
};
</script>