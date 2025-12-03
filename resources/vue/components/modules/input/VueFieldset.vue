<template>
	<fieldset
		:class="{ 'clear': clear }"
		:style="{
			'--fieldset-grid-template-columns': `repeat(${count}, 1fr)`,
			'--fieldset-grid-gap': getGap(gap),
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
	name: "VueFieldset",
	props: {
		direction: {
			type: String,
			default: "row",
		},
		gap: {
			type: [String, Number],
			default: "10px",
		},
		count: {
			type: Number,
			default: 1,
		},
		clear: {
			type: Boolean,
			default: false,
		},
	},
	methods: {
		getGap() {
			if (typeof this.gap === "number") {
				return `${this.gap}px`;
			} else {
				return this.gap;
			}
		},
	},
};
</script>

<style scoped>
fieldset {
	display: grid;
	grid-template-columns: var(--fieldset-grid-template-columns);
	gap: var(--fieldset-grid-gap);

	border: var(--fieldset-border);
	border-radius: var(--fieldset-border-radius);
	padding: var(--fieldset-padding);

	background-color: var(--fieldset-background-color);

	transition: var(--fieldset-transition);
}

fieldset.clear {
	border: 0px;
	padding: 0px;
}

fieldset > legend {
	display: flex;
	align-items: center;
	gap: var(--fieldset-legend-gap);

	font-size: var(--fieldset-legend-font-size);
	color: var(--fieldset-legend-color);
}

fieldset:focus-within {
	border: var(--input-border-focus);
}

fieldset.clear:focus-within {
	border: 0px;
}

@media screen and (width <= 1000px) {
	fieldset {
		grid-template-columns: repeat(1, 1fr);
	}
}
</style>
