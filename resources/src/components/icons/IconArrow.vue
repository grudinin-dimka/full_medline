<template>
	<svg ref="svg"
      :width="width"
		:height="height"
      viewBox="0 0 15 9"
		xmlns="http://www.w3.org/2000/svg"
	>
		<path
			d="M8.20711 0.792893C7.81658 0.402369 7.18342 0.402369 6.79289 0.792893L0.428932 7.15685C0.0384079 7.54738 0.0384079 8.18054 0.428932 8.57107C0.819456 8.96159 1.45262 8.96159 1.84315 8.57107L7.5 2.91421L13.1569 8.57107C13.5474 8.96159 14.1805 8.96159 14.5711 8.57107C14.9616 8.18054 14.9616 7.54738 14.5711 7.15685L8.20711 0.792893ZM8.5 2.5V1.5H6.5V2.5H8.5Z"
		/>
	</svg>
</template>

<script>
export default {
   mounted() {
      this.$refs.svg.style.transform = `rotate(${this.rotate}deg)`;
   },
   data() {
      return {
         iconSvg: "",
      };
   },
	props: {
		width: {
			type: Number,
			default: 30,
		},
		height: {
			type: Number,
			default: 30,
		},
		rotate: {
			type: Number,
			default: 0,
		},
	},
};
</script>

<style scoped>
   svg {
      cursor: pointer;
      fill: var(--svg-icon-arrow-fill);
   }

   svg:hover {
      fill: var(--svg-icon-arrow-fill-hover);
   }
</style>
