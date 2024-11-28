<template>
	<div class="buttons-wide">
		<slot></slot>
	</div>
</template>

<script>
export default {};
</script>

<style scoped>
.buttons-wide {
	display: flex;
	gap: 10px;
	justify-content: stretch;
}

@media screen and (max-width: 460px) {
	.buttons-wide {
		flex-direction: column;
	}
}
</style>
