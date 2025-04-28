<template>
	<button class="table-button" :class="{ wide: wide, [look]: look }" :disabled="look == 'disabled'">
		<slot></slot>
	</button>
</template>

<script>
export default {
	props: {
		look: {
			type: String,
			default: "default",
		},
		wide: {
			type: Boolean,
			default: false,
		},
	},
};
</script>

<style scoped>
.table-button {
	cursor: pointer;
	display: flex;
	align-items: center;
	gap: 5px;

	background-color: var(--button-background-color);
	border: var(--button-border);
	border-radius: var(--button-border-radius);
	color: var(--button-font-color);
	height: 30px;

	font-size: 1rem;
	padding: 5px 10px;

	transition: all 0.2s;
}

.table-button:hover {
	background-color: var(--button-background-color-hover);
}

.table-button.delete {
	background-color: var(--button-background-color-delete);
}

.table-button.delete:hover {
	background-color: var(--button-background-color-delete-hover);
}

.table-button.disabled {
	cursor: not-allowed;
	background-color: var(--button-background-color-disabled);
}

.table-button.disabled:hover {
	background-color: var(--button-background-color-disabled-hover);
}

.wide {
	width: 100%;
}
</style>
