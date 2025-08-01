<template>
	<p>
		<slot></slot>
	</p>
</template>

<script>
export default {};
</script>

<style scoped>
p {
	font-size: var(--wiki-paragraph-font-size);
	font-weight: var(--wiki-paragraph-font-weight);
	color: var(--wiki-paragraph-font-color);

	margin: var(--wiki-paragraph-margin);
	padding: var(--wiki-paragraph-padding);

	text-align: var(--wiki-paragraph-text-align);
}
</style>
