<template>
	<div class="vue-switch" :class="{ 'error': error }">
		<div class="vue-switch__label">
			<slot name="label"></slot>
		</div>
		<button class="vue-switch__button" :class="{ 'active': modelValue }" @click="change">
			<div class="vue-switch__circle"></div>
		</button>
	</div>
</template>
<script>
export default {
	name: "VueSwitch",
	props: {
		modelValue: {
			type: [Boolean, Number],
			default: false,
			required: true,
		},
		error: {
			type: Boolean,
			default: false,
		},
	},
	methods: {
		change() {
			switch (typeof this.modelValue) {
				case "boolean":
					this.$emit("update:modelValue", !this.modelValue);
					break;
				case "number":
					if (this.modelValue == 1) {
						this.$emit("update:modelValue", 0);
					} else {
						this.$emit("update:modelValue", 1);
					}
					break;
			}
		},
	},
};
</script>

<style>
.vue-switch {
	display: grid;
	grid-template-columns: 1fr var(--switch-button-width);
	justify-content: center;
	align-items: center;

	gap: var(--switch-gap);
}

.vue-switch__label {
	height: var(--switch-label-height);
	padding: var(--switch-label-padding);

	color: var(--input-color);
	font-size: var(--switch-label-font-size);
	font-family: var(--switch-label-font-family);

	text-align: justify;
}

.vue-switch__button {
	cursor: pointer;
	position: relative;

	border-radius: var(--switch-button-border-radius);
	border: var(--switch-button-border);

	width: var(--switch-button-width);
	height: var(--switch-button-height);

	background-color: var(--switch-button-background-color);

	transition: var(--switch-button-transition);
}

.vue-switch__button.active {
	background-color: var(--switch-button-background-color--active);
}

.vue-switch__circle {
	position: absolute;
	top: var(--switch-circle-top);
	left: var(--switch-circle-left);

	width: var(--switch-circle-width);
	height: var(--switch-circle-height);

	background-color: var(--switch-circle-background-color);
	border-radius: var(--switch-circle-border-radius);

	transition: var(--switch-circle-transition);
}

.vue-switch__button.active > .vue-switch__circle {
	transform: var(--switch-circle-transform--active);
}

.vue-switch.error .vue-switch__label {
	color: var(--error-background-color);
}
</style>
