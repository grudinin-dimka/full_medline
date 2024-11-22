<template>
	<button class="default" :disabled="disabled">
		<span class="loader" v-if="disabled"></span>
		<slot v-else></slot>
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
button.default {
	cursor: pointer;
	display: flex;
	justify-content: center;
	align-items: center;

	padding: 15px 30px;
	max-height: 54px;
	min-width: 160px;
	border: 0px solid black;
	border-radius: 10px;

	font-size: 20px;
	color: white;

	background-color: var(--button-default-color);
}

button.default:hover {
	background-color: var(--button-default-color-hover);
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
