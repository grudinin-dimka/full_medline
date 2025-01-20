<template>
	<section ref="block">
		<slot></slot>
	</section>
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
			this.$refs.block.style.minHeight = `${this.minHeight}px`;
		}
	},
};
</script>

<style scoped>
section {
	display: flex;
	flex-direction: column;
	align-items: center;
	gap: 20px;

	margin: 20px 30px;
}
</style>
