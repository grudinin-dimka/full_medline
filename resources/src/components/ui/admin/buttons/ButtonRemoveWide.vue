<template>
	<button :class="{ default: type == 'default', other: type == 'other' }" :disabled="disabled">
		<span class="loader" v-if="disabled"></span>
		<slot v-else></slot>
	</button>
</template>

<script>
export default {
	props: {
		type: {
			type: String,
			default: "default",
			required: true,
		},
		disabled: {
			type: Boolean,
			default: false,
		},
	},
};
</script>

<style scoped>
button {
	flex: 1 0 160px;
	cursor: pointer;

	padding: 15px 30px;
	max-height: 54px;
	border: 0px solid black;
	border-radius: 10px;

	font-size: 20px;
}

button.default {
	color: white;
	background-color: var(--button-remove-color);
}

button.default:hover {
	color: white;
	background-color: var(--button-remove-color-hover);
}

button.other {
	color: var(--button-remove-color-hover);
	background-color: #ffdddd;
}

button.other:hover {
	color: var(--button-remove-color-hover);
	background-color: #f3d3d3;
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
