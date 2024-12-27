<template>
	<div v-if="status" class="container-empty" ref="empty">
		<div>Пока здесь ничего нет...</div>
	</div>
</template>

<script>
export default {
	props: {
		status: {
			type: Boolean,
			default: true,
		},
		minHeight: {
			type: Number,
			default: null,
		},
	},
	mounted() {
		if (this.minHeight) {
			this.$refs.empty.style.minHeight = `${this.minHeight}px`;
		}
	},
};
</script>

<style scoped>
.container-empty {
	user-select: none;

	display: flex;
	justify-content: center;
	align-items: center;
	text-align: center;

	margin: auto;

	font-size: 20px;
	color: #c7c7c7;

	animation: show-bottom-to-top-15 0.5s ease-in-out;
}
</style>
