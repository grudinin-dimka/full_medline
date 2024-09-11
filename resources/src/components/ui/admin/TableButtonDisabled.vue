<template>
	<button disabled>
		<slot></slot>
	</button>
</template>

<script>
</script>

<style scoped>
button {
	cursor:not-allowed;
   box-sizing: border-box;
	flex: 1 0 130px;
   min-width: 150px;

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
