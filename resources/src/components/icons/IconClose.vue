<template>
	<svg
		xmlns="http://www.w3.org/2000/svg"
		:width="width"
		:height="height"
		viewBox="0 -960 960 960"
		fill="black"
	>
		<path
			d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"
		/>
	</svg>
</template>

<script>
export default {
	props: {
		width: {
			type: Number,
			default: 20,
		},
		height: {
			type: Number,
			default: 20,
		},
	},
};
</script>

<style scoped>
svg {
   cursor: pointer;
	fill: rgba(0, 0, 0, 0.3);
}

svg:hover {
	fill: rgba(0, 0, 0, 0.6);
}
</style>
