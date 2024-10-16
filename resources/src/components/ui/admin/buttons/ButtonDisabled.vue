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

	background-color: var(--button-disabled-color);
}

button.default:hover {
	background-color: var(--button-disabled-color-hover);
}
</style>
