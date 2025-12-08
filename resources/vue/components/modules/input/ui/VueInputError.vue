<template>
	<div class="vue-input__error" :class="{ top: isTop }">
		<slot></slot>
	</div>
</template>

<script>
export default {
	name: "VueInputError",
	props: {
		isTop: {
			type: Boolean,
			default: false,
		},
	},
};
</script>

<style>
.vue-input__error {
	user-select: none;
	position: absolute;
	z-index: 1;
	right: 10px;
	top: -10px;

	border: var(--error-border);
	border-radius: var(--error-border-radius);
	padding: var(--error-padding);

	background-color: var(--error-background-color);
	color: var(--error-color);

	max-width: 400px;

	animation: var(--error-animation);
}

.vue-input__error.top {
	right: 10px;
	top: -10px;
}
</style>
