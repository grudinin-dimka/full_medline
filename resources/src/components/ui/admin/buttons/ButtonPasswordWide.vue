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
	background-color: var(--button-password-color);
}

button.default:hover {
	color: white;
	background-color: var(--button-password-color-hover);
}

button.other {
	color: #bbab30;
	background-color: #fff9cb;
}

button.other:hover {
	color: #bbab30;
	background-color: #f5efc2;
}

.loader {
	width: 30px;
	height: 30px;
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
