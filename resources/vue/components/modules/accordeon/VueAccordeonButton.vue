<template>
	<div class="accordeon__button">
		<slot></slot>
	</div>
</template>
<script>
export default {};
</script>

<style scoped>
.accordeon__button {
	cursor: pointer;

	display: flex;
	justify-content: center;
	align-items: center;

	padding: var(--accordeon-button-padding);
	border: var(--accordeon-button-border);
	border-radius: var(--accordeon-button-border-radius);

	width: 40px;
	height: 40px;

	font-size: 1.125rem;

	background-color: var(--accordeon-button-background-color);

	transition: all 0.35s;
}

.accordeon__button:hover {
	border: var(--accordeon-head-name-border-hover);
	background-color: var(--accordeon-head-name-hover-background-color);
}
</style>
