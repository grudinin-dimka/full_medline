<template>
	<div class="buttons">
		<slot></slot>
	</div>
</template>

<script>
export default {};
</script>

<style scoped>
.buttons {
	display: flex;
	gap: 10px;
	justify-content: flex-end;
}

@media screen and (max-width: 460px) {
	.buttons {
		flex-direction: column;
	}
}
</style>
