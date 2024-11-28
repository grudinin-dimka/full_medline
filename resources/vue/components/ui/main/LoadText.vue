<template>
	<transition appear>
		<span v-if="isLoading">
         <slot></slot>
		</span>
	</transition>
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
.v-enter-active,
.v-leave-active {
  transition: opacity 0.5s ease;
}

.v-enter-from,
.v-leave-to {
  opacity: 0;
}

span {
   color: rgb(139, 139, 139);
}
</style>
