<template>
	<transition @after-leave="$emit('afterLeave')">
		<span v-show="isLoading">
			<span class="loader">
				<slot></slot>
			</span>
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
.v-enter-active {
	animation: swirlAdded 1s;
	transition: opacity 0.3s ease;
}

.v-enter-from,
.v-leave-to {
	opacity: 0;
}

.loader {
	color: rgb(139, 139, 139);

	animation: LoadText 2s infinite linear;
	transition: all 0.3s;
}

.v-enter-active,
.v-leave-active {
	transition: opacity 0.3s ease;
}

.v-enter-from,
.v-leave-to {
	opacity: 0;
}

@keyframes LoadText {
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
