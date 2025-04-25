<template>
	<button class="table-button-head" :class="{ wide: wide, [look]: look }" :disabled="disabled">
		<span class="loader" v-if="disabled" :class="{ [look]: look }"></span>
		<slot v-else></slot>
	</button>
</template>

<script>
export default {
	props: {
		look: {
			type: String,
			default: "default",
		},
		wide: {
			type: Boolean,
			default: false,
		},
		disabled: {
			type: Boolean,
			default: false,
		},
	},
};
</script>

<style scoped>
.table-button-head {
	cursor: pointer;
	display: flex;
	align-items: center;
	justify-content: center;
	gap: 5px;

	background-color: white;
	border: var(--default-border);
	border-radius: 100px;
	color: black;
	height: 50px;
	min-width: 100px;
	font-size: var(--button-font-size);

	padding: 0px 10px;

	transition: all 0.2s;
}

.table-button-head:hover {
	border: var(--default-border-active);
	background-color: var(--item-background-color-active);
}

.circle {
	border-radius: 50%;
	min-width: 50px;
}

.delete {
	background-color: #d44141;
}

.wide {
	width: 100%;
}

/* Загрузчик */
.loader {
	width: 26px;
	height: 26px;
	border: 3px solid #000000;
	border-bottom-color: transparent;
	border-radius: 50%;
	display: inline-block;
	box-sizing: border-box;
	animation: rotation 1s linear infinite;
}

.loader.inverse {
	width: 26px;
	height: 26px;
	border: 3px solid black;
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
