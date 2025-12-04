<template>
	<div class="vue-input__label">
		<slot></slot>
	</div>
</template>

<script>
export default {
	name: "VueInputLabel",
};
</script>

<style>
.vue-input__label {
	user-select: none;
	z-index: var(--input-label-z-index);
	top: var(--input-label-top);
	left: var(--input-label-left);
	position: absolute;

	display: flex;
	align-items: center;
	gap: 5px;

	border-radius: var(--input-label-border-radius);
	padding: var(--input-label-padding);
	background-color: var(--input-label-background-color);

	font-weight: var(--input-label-font-weight);
	color: var(--input-label-color);
	font-size: var(--input-label-font-size);
}
</style>
