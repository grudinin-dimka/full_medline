<template>
	<button :disabled="disabled" :class="{ disabled: disabled }">
		<slot></slot>
	</button>
</template>

<script>
export default {
	props: {
		disabled: {
			type: Boolean,
			default: false,
		},
	},
};
</script>

<style scoped>
button {
	flex: 1 0 120px;
	cursor: pointer;
	min-width: 120px;

	padding: 7.5px 0px;
	border: 0px solid black;
	border-radius: 7.5px;

	font-size: 16px;
	color: white;

	background-color: var(--button-remove-color);
}

button.disabled {
	cursor: not-allowed;
}

button:hover {
	background-color: var(--button-remove-color-hover);
}

@media screen and (max-width: 700px) {
	button {
		flex: 0 0 120px;
	}
}
</style>
