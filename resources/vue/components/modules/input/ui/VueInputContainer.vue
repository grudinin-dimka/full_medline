<template>
	<div class="vue-input">
      <slot></slot>
   </div>
</template>

<script>
export default {
	name: "VueInputContainer",
};
</script>

<style>
.vue-input {
	position: relative;
	display: flex;
	flex-direction: column;
	gap: var(--input-gap);

	width: 100%;
}
</style>
