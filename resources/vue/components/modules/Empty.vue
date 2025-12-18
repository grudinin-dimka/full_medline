<template>
	<div class="container-empty" ref="empty">
		<div>Пока здесь ничего нет...</div>
	</div>
</template>

<script>
export default {
	props: {
		minHeight: {
			type: [Number, String],
			default: null,
		},
	},
	mounted() {
		switch (typeof this.minHeight) {
			case "string":
				this.$refs.empty.style.minHeight = this.minHeight;

				break;
			case "number":
				this.$refs.empty.style.minHeight = this.minHeight + "px";

				break;
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
