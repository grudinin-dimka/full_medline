<template>
	<button class="disabled">
		<slot></slot>
	</button>
</template>

<script>
export default {};
</script>

<style scoped>
button.disabled {
	cursor: not-allowed;

	padding: 15px 30px;
	border: 0px solid black;
	border-radius: 10px;

	font-size: 20px;
	color: white;

	background-color: var(--button-disabled-background-color);
	transition: all 0.2s;
}

button.default:hover {
	background-color: var(--button-disabled-background-color-hover);
}
</style>
