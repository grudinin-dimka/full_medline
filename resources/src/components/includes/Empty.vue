<template>
	<div
		v-if="status"
		class="container-empty"
		:style="{
			minHeight: `${minHeight}px`,
		}"
	>
		<div>Пока здесь ничего нет...</div>
	</div>
</template>

<script>
export default {
	props: {
		status: {
			type: Boolean,
			default: true,
		},
		minHeight: {
			type: Number,
			default: 400,
		},
	},
};
</script>

<style scoped>
.container-empty {
   user-select: none;

   display: flex;
   justify-content: center;
   align-items: center;
   text-align: center;

   font-size: 20px;
   color: #c7c7c7;
}
</style>
