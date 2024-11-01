<template>
	<div class="icon-load">
		<span class="loader"></span>
	</div>
</template>

<script>
export default {
	props: {
		width: {
			type: Number,
			default: 30,
		},
		height: {
			type: Number,
			default: 30,
		},
	},
};
</script>

<style scoped>
.icon-load {
	display: flex;
	justify-content: center;
	align-items: center;
}

.loader {
	width: 28px;
	height: 28px;
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
