<template>
	<button :class="look" :disabled="disabled">
		<span class="loader" v-if="disabled"></span>
		<div class="content" :class="{ disabled: disabled }">
			<slot></slot>
		</div>
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
button {
	position: relative;
	cursor: pointer;
	display: flex;
	justify-content: center;
	align-items: center;
	gap: 10px;

	padding: 15px 20px;
	max-height: 54px;
	min-width: 160px;
	border: 0px solid black;
	border-radius: 100px;

	font-size: 20px;
	color: white;

	background-color: var(--button-default-color);
	transition: all 0.2s;
}

button > .content {
	cursor: pointer;
	display: flex;
	justify-content: center;
	align-items: center;
	gap: 10px;
}

button > .content.disabled {
	visibility: hidden;
	opacity: 0;
}

button:hover {
	background-color: var(--button-background-color-hover);
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
	position: absolute;

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
