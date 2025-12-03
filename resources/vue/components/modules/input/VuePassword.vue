<template>
	<VueInput :error="error">
		<template #label v-if="$slots.label">
			<slot name="label"></slot>
		</template>

		<template #input>
			<input
				:type="passwordHide ? 'password' : 'text'"
				:value="modelValue"
				:placeholder="placeholder"
				:inputmode="inputmode"
				autocomplete="off"
				@input="$emit('update:modelValue', $event.target.value.trim())"
			/>

			<div class="vue-input__icon" @click="changePasswordType">
				<svg
					v-if="passwordHide"
					xmlns="http://www.w3.org/2000/svg"
					height="28px"
					viewBox="0 -960 960 960"
					width="28px"
				>
					<path
						d="M160-440q-50 0-85-35t-35-85q0-50 35-85t85-35q50 0 85 35t35 85q0 50-35 85t-85 35ZM80-200v-80h800v80H80Zm400-240q-50 0-85-35t-35-85q0-50 35-85t85-35q50 0 85 35t35 85q0 50-35 85t-85 35Zm320 0q-50 0-85-35t-35-85q0-50 35-85t85-35q50 0 85 35t35 85q0 50-35 85t-85 35Z"
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
						d="M160-440q-50 0-85-35t-35-85q0-50 35-85t85-35q50 0 85 35t35 85q0 50-35 85t-85 35Zm640 0q-50 0-85-35t-35-85q0-50 35-85t85-35q50 0 85 35t35 85q0 50-35 85t-85 35Zm-220-54L414-660q14-10 31-15t35-5q50 0 85 35t35 85q0 18-5 35t-15 31ZM792-56 648-200H80v-80h488L56-792l56-56 736 736-56 56Z"
					/>
				</svg>
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
	name: "VuePassword",
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
	data() {
		return {
			passwordHide: true,
		};
	},
	methods: {
		changePasswordType() {
			this.passwordHide = !this.passwordHide;
		},
	},
};
</script>
