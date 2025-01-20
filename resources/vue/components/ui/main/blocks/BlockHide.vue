<template>
	<div class="block-hide" ref="blockHide">
		<slot></slot>
	</div>
</template>

<script>
export default {
	props: {
		minHeight: {
			type: Number,
			default: null,
		},
	},
	mounted() {
		if (this.minHeight) {
			this.$refs.blockHide.style.minHeight = `${this.minHeight}px`;
		}
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
