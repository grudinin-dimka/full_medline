<template>
	<div class="block-hide" ref="blockHide" :style="{ minHeight: `${minHeight}px` }">
		<slot></slot>
	</div>
</template>

<script>
export default {
	props: {
		minHeight: {
			type: Number,
			default: "400",
		},
	},
};
</script>

<style>
.block-hide {
	display: flex;
	align-items: center;
	flex-direction: column;

	gap: 20px;

	overflow: hidden;
	box-sizing: border-box;
	height: auto;

	margin: 20px 30px;

	max-width: 100%;

	transition: all 0.5s;
}

@media screen and (width <= 1400px) {
	.block-hide {
		align-items: stretch;
	}
}
</style>
