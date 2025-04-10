<template>
	<div class="container-input">
		<slot></slot>
	</div>
</template>
<script>
export default {};
</script>

<style scoped>
.container-input {
	display: flex;
	flex-direction: column;
	gap: 15px;

	width: 100%;
}
</style>
