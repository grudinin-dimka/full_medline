<template>
	<div class="block-two" ref="blockTwo">
		<div class="block-two__item">
			<div class="block-two__head">
				<div class="block-two__head-title">
					<slot name="one-title"></slot>
				</div>
				<div class="block-two__head-options">
					<slot name="one-options"></slot>
				</div>
			</div>
			<div class="block-two__body" :style="{ minHeight: `${minHeight}px` }">
				<slot name="one-body"></slot>
			</div>
			<div class="block-two__buttons">
				<slot name="one-buttons"></slot>
			</div>
		</div>
		<div class="block-two__item">
			<div class="block-two__head">
				<div class="block-two__head-title">
					<slot name="two-title"></slot>
				</div>
				<div class="block-two__head-options">
					<slot name="two-options"></slot>
				</div>
			</div>
			<div class="block-two__body" :style="{ minHeight: `${minHeight}px` }">
				<slot name="two-body"></slot>
			</div>
			<div class="block-two__buttons">
				<slot name="two-buttons"></slot>
			</div>
		</div>
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
			default: 200,
		},
	},
};
</script>

<style scoped>
.block-two {
	display: flex;
	flex-direction: row;
	flex-wrap: wrap;
	gap: 20px;
}

.block-two__item {
	flex: 1 0 350px;
	display: flex;
	flex-direction: column;
	justify-content: space-between;
	gap: 20px;

	background-color: white;
	height: auto;
	border-radius: 15px;
	padding: 20px;

	max-width: 100%;

	box-shadow: 0px 4px 10px -10px rgba(0, 0, 0, 0.99);
	transition: all 0.5s;
}

.block-two__head {
	display: flex;
	justify-content: space-between;
	align-items: center;
}

.block-two__head-options {
	display: flex;
	gap: 10px;
}

.block-two__body {
	height: 100%;
}

.block-two__buttons {
	display: flex;
	justify-content: flex-end;
	gap: 10px;
}

.block-two__head-title {
	font-size: 24px;
	font-weight: 500;
}

@media screen and (width <= 600px) {
	.block-two {
		flex-direction: column;
	}

	.block-two__item {
		flex: 1 0 200px;
	}
}
</style>
