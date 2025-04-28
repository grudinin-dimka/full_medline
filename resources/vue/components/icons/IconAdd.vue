<template>
	<svg
		:width="width"
		:height="height"
		:class="look"
		viewBox="0 0 14 14"
		xmlns="http://www.w3.org/2000/svg"
	>
		<path d="M6 8H0V6H6V0H8V6H14V8H8V14H6V8Z" />
	</svg>
</template>

<script>
export default {
	props: {
		width: {
			type: Number,
			default: 30,
		},
		height: {
			type: Number,
			default: 30,
		},
		look: {
			type: String,
			default: "",
		},
	},
};
</script>

<style scoped>
svg {
	cursor: pointer;
	fill: var(--primary-color);
}

svg.white {
	fill: white;
}

svg.create {
	fill: var(--input-create-color);
}
</style>
