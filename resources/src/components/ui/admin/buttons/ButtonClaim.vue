<template>
	<button class="claim">
		<slot></slot>
	</button>
</template>

<script></script>

<style scoped>
button.claim {
	cursor: pointer;

	padding: 15px 30px;
	border: 0px solid black;
	border-radius: 10px;

	font-size: 20px;
	color: white;

	background-color: var(--button-claim-color);
}

button.claim:hover {
	background-color: var(--button-claim-color-hover);
}
</style>
