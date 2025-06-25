<template>
	<fieldset ref="fieldset">
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
			default: 2,
		},
	},
	mounted() {
		if (this.direction === "row") {
			this.$refs.fieldset.style.gridTemplateColumns = `repeat(${this.count}, 1fr)`;
			this.$refs.fieldset.style.gap = this.gap;
		}

      if (this.direction === "column") {
			this.$refs.fieldset.style.gridTemplateColumns = `1fr`;
			this.$refs.fieldset.style.gap = this.gap;
		}
	},
};
</script>

<style scoped>
fieldset {
	display: grid;
	grid-template-columns: repeat(2, 1fr);
	gap: 10px;

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
</style>
