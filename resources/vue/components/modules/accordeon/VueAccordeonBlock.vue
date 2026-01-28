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
	gap: calc(var(--default-gap) / 2);

	padding: 0px var(--default-padding) var(--default-padding) var(--default-padding);

	font-size: 1.125rem;
}
</style>
