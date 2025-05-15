<template>
	<div class="buttons" :class="{ 'wide': wide }">
		<slot></slot>
	</div>
</template>

<script>
export default {
	props: {
		wide: {
			type: Boolean,
			default: false,
		},
	}
};
</script>

<style scoped>
.buttons {
	display: flex;
	gap: 10px;
	justify-content: flex-end;
}

.wide {
	justify-content: stretch;
}

@media screen and (max-width: 460px) {
	.buttons {
		flex-direction: column;
	}
}
</style>
