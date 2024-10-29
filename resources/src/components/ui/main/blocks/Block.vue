<template>
	<section :style="{ minHeight: `${minHeight}px` }">
		<slot></slot>
	</section>
</template>

<script>
export default {
   props: {
      minHeight: {
         type: Number,
         default: '400',
      },
   },
}
</script>

<style scoped>
section {
	display: flex;
	flex-direction: column;
	align-items: center;
	gap: 20px;

	margin: 20px 30px;
}
</style>
