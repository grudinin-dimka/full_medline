<template>
	<button class="remove">
		<slot></slot>
	</button>
</template>

<script>
export default {};
</script>

<style scoped>
button.remove {
	cursor: pointer;

	padding: 15px 30px;
	max-height: 54px;
	min-width: 160px;
	border: 0px solid black;
	border-radius: 10px;

	font-size: 20px;
	color: white;

	background-color: var(--button-remove-color);
	transition: all 0.2s;
}

button.remove:hover {
	background-color: var(--button-remove-color-hover);
}

.loader {
	width: 26px;
	height: 26px;
	border: 3px solid #fff;
	border-bottom-color: transparent;
	border-radius: 50%;
	display: inline-block;
	box-sizing: border-box;
	animation: rotation 1s linear infinite;
}

@keyframes rotation {
	0% {
		transform: rotate(0deg);
	}
	100% {
		transform: rotate(360deg);
	}
}
</style>
