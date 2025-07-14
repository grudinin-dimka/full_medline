<template>
	<div class="block" :class="{ hide: hide }" ref="block">
		<slot></slot>
	</div>
</template>

<script>
export default {
	props: {
		hide: {
			type: Boolean,
			default: false,
		},
		minHeight: {
			type: Number,
			default: null,
		},
	},
	mounted() {
		if (this.minHeight) {
			this.$refs.block.style.minHeight = `${this.minHeight}px`;
		}
	},
};
</script>

<style scoped>
.block {
	display: flex;
	flex-direction: column;
	align-items: center;
	gap: 20px;

	min-height: max(75dvh, 400px);

	margin: 20px 30px;
}

.block.hide {
	overflow: hidden;
	box-sizing: border-box;
	height: auto;

	max-width: 100%;
}

@media screen and (width <= 1400px) {
	.block.hide {
		align-items: stretch;
	}
}

@media screen and (width <= 750px) {
	.block {
		margin: 20px 20px;
	}
}
</style>
