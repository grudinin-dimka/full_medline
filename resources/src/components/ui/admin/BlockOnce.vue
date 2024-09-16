<template>
	<div class="block-once">
		<slot></slot>
	</div>
</template>

<script>
export default {};
</script>

<style>
.block-once {
	display: flex;
	flex-direction: column;
	gap: 20px;

	background-color: white;
	height: auto;
	border-radius: 10px;

	padding: 20px;

	max-width: 100%;

	box-shadow: 0px 4px 10px -10px rgba(0, 0, 0, 0.99);

	transition: all 0.5s;
}
</style>
