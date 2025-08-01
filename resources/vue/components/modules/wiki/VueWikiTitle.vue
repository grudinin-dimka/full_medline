<template>
	<h1>
		<slot></slot>
	</h1>
</template>

<script>
export default {};
</script>

<style scoped>
h1 {
   font-size: var(--wiki-title-font-size);
   font-weight: var(--wiki-title-font-weight);
   color: var(--wiki-title-font-color);

   margin: var(--wiki-title-margin);
   padding: var(--wiki-title-padding);

   text-align: var(--wiki-title-text-align);
}
</style>
