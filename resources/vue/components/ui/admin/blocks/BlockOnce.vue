<template>
	<div class="block-once" ref="blockOnce" :style="{ minHeight: `${minHeight}px` }">
		<slot></slot>
	</div>
</template>

<script>
export default {
	props: {
		minHeight: {
			type: Number,
			default: 200,
		},
	},
};
</script>

<style>
.block-once {
	display: flex;
	flex-direction: column;
	gap: 20px;

	box-sizing: border-box;

	background-color: white;
	height: auto;
	border-radius: 15px;

	padding: 20px;

	box-shadow: 0px 4px 10px -10px rgba(0, 0, 0, 0.99);

	transition: all 0.5s;
}
</style>
