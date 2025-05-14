<template>
	<button :disabled="disabled">
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
	},
};
</script>

<style scoped>
button {
	position: relative;
	cursor: pointer;

	padding: 15px 30px;
	max-height: 54px;
	min-width: 160px;
	border: 0px solid black;
	border-radius: 100px;
	font-size: 20px;
	color: white;

	background-color: var(--button-delete-background-color);
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
	background-color: var(--button-delete-background-color-hover);
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

@keyframes rotation {
	0% {
		transform: rotate(0deg);
	}
	100% {
		transform: rotate(360deg);
	}
}
</style>
