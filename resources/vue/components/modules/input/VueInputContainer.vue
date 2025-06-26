<template>
	<fieldset
		:style="{
			'--fieldset-grid-template-columns': `repeat(${count}, 1fr)`,
			'--fieldset-grid-gap': gap,
		}"
	>
		<legend>
			<slot name="legend"></slot>
		</legend>

		<slot name="inputs"></slot>
	</fieldset>
</template>

<script>
export default {
	props: {
		direction: {
			type: String,
			default: "row",
		},
		gap: {
			type: String,
			default: "10px",
		},
		count: {
			type: Number,
			default: 1,
		},
	},
};
</script>

<style scoped>
fieldset {
	display: grid;
	grid-template-columns: var(--fieldset-grid-template-columns);
	gap: var(--fieldset-grid-gap);

	border: var(--default-border);
	border-radius: 15px;
	padding: 10px;

	background-color: rgba(255, 255, 255, 0.5);

	transition: all 0.2s;
}

fieldset > legend {
	font-size: 1.25rem;
	color: black;
}

fieldset:focus-within {
	border: var(--input-border-focus);
}

@media screen and (width <= 1000px) {
	fieldset {
		grid-template-columns: repeat(1, 1fr);
	}
}
</style>
