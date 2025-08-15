<template>
	<div class="accordeon_block">
		<slot></slot>
	</div>
</template>

<script>
export default {};
</script>

<style scoped>
.accordeon_block {
	display: flex;
	flex-direction: column;
	gap: 10px;

	padding: 0px 20px 20px 20px;
}
</style>
