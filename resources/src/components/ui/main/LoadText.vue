<template>
   <span
      v-if="isLoading"
   >
      <slot></slot>
   </span>
</template>

<script>
export default {
	props: {
		isLoading: {
			type: Boolean,
			default: true,
		},
	},
};
</script>

<style scoped>
span {
	color: rgb(151, 151, 151);
	animation: myAnim 3s infinite linear;

   opacity: 1;
   visibility: visible;

   transition: all 0.5s ease;
}

@keyframes myAnim {
	0%,
	50%,
	100% {
		opacity: 1;
	}

	25%,
	75% {
		opacity: 0;
	}
}
</style>
