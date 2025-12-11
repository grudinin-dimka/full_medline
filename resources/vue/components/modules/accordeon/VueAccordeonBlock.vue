<template>
	<div class="accordeon__block">
		<slot></slot>
	</div>
</template>

<script>
export default {};
</script>

<style scoped>
.accordeon__block {
	display: flex;
	flex-direction: column;
	gap: 10px;

	padding: 0px 20px 20px 20px;
}
</style>
