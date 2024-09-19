<template>
	<button disabled>
		<slot></slot>
	</button>
</template>

<script>
</script>

<style scoped>
button {
	flex: 1 0 120px;
	cursor:not-allowed;
   min-width: 120px;

	padding: 7.5px 0px;
	border: 0px solid black;
	border-radius: 5px;

	font-size: 16px;
	color: white;

	background-color: rgb(150, 150, 150);
}

button:hover {
	background-color: rgb(160, 160, 160);
}
</style>
