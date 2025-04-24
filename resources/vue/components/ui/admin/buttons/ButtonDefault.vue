<template>
	<button class="default" :class="look" :disabled="disabled">
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
		look: {
			type: String,
			default: "",
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
	transition: all 0.2s;
}

button.default:hover {
	background-color: var(--button-default-color-hover);
}

button.white {
	border: var(--input-border);
	color: black;

	background-color: white;
}

button.white:hover {
	border: var(--input-border-focus);
	background-color: var(--item-background-color-active);
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

button.white > .loader {
	width: 26px;
	height: 26px;
	border: 3px solid var(--primary-color);
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
