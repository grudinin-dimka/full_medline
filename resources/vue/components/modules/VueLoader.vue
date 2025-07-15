<template>
	<transition @after-enter="$emit('afterEnter')" @after-leave="$emit('afterLeave')">
		<div v-show="isLoading" class="loader" ref="loader" :class="{ child: isChild }">
			<span class="loader__icon"></span>
		</div>
	</transition>
</template>

<script>
export default {
	props: {
		isLoading: {
			type: Boolean,
			default: true,
		},
		isChild: {
			type: Boolean,
			default: false,
		},
		minHeight: {
			type: Number,
			default: null,
		},
	},
	mounted() {
		if (this.minHeight) {
			this.$refs.loader.style.minHeight = `${this.minHeight}px`;
		}
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

.v-enter-active,
.v-leave-active {
	transition: opacity 0.3s ease;
}

.v-enter-from,
.v-leave-to {
	opacity: 0;
}

.loader {
	position: fixed;
	top: 0px;
	left: 0px;
	z-index: var(--loader-z-index);

	display: flex;
	justify-content: center;
	align-items: center;

	width: 100dvw;
	height: 100dvh;
	background-color: var(--loader-background-color);
}

.loader.child {
	position: static;

	display: flex;
	justify-content: center;
	align-items: center;

	width: auto;
	height: auto;
	background-color: var(--loader-child-background-color);
}

.loader__icon {
	color: var(--loader-icon-color);
	font-size: 10px;
	width: 1em;
	height: 1em;
	border-radius: 50%;
	position: relative;
	text-indent: -9999em;
	animation: mulShdSpin 1.3s infinite linear;
	transform: translateZ(0);
}

@keyframes mulShdSpin {
	0%,
	100% {
		box-shadow: 0 -3em 0 0.2em, 2em -2em 0 0em, 3em 0 0 -1em, 2em 2em 0 -1em, 0 3em 0 -1em,
			-2em 2em 0 -1em, -3em 0 0 -1em, -2em -2em 0 0;
	}
	12.5% {
		box-shadow: 0 -3em 0 0, 2em -2em 0 0.2em, 3em 0 0 0, 2em 2em 0 -1em, 0 3em 0 -1em,
			-2em 2em 0 -1em, -3em 0 0 -1em, -2em -2em 0 -1em;
	}
	25% {
		box-shadow: 0 -3em 0 -0.5em, 2em -2em 0 0, 3em 0 0 0.2em, 2em 2em 0 0, 0 3em 0 -1em,
			-2em 2em 0 -1em, -3em 0 0 -1em, -2em -2em 0 -1em;
	}
	37.5% {
		box-shadow: 0 -3em 0 -1em, 2em -2em 0 -1em, 3em 0em 0 0, 2em 2em 0 0.2em, 0 3em 0 0em,
			-2em 2em 0 -1em, -3em 0em 0 -1em, -2em -2em 0 -1em;
	}
	50% {
		box-shadow: 0 -3em 0 -1em, 2em -2em 0 -1em, 3em 0 0 -1em, 2em 2em 0 0em, 0 3em 0 0.2em,
			-2em 2em 0 0, -3em 0em 0 -1em, -2em -2em 0 -1em;
	}
	62.5% {
		box-shadow: 0 -3em 0 -1em, 2em -2em 0 -1em, 3em 0 0 -1em, 2em 2em 0 -1em, 0 3em 0 0,
			-2em 2em 0 0.2em, -3em 0 0 0, -2em -2em 0 -1em;
	}
	75% {
		box-shadow: 0em -3em 0 -1em, 2em -2em 0 -1em, 3em 0em 0 -1em, 2em 2em 0 -1em, 0 3em 0 -1em,
			-2em 2em 0 0, -3em 0em 0 0.2em, -2em -2em 0 0;
	}
	87.5% {
		box-shadow: 0em -3em 0 0, 2em -2em 0 -1em, 3em 0 0 -1em, 2em 2em 0 -1em, 0 3em 0 -1em,
			-2em 2em 0 0, -3em 0em 0 0, -2em -2em 0 0.2em;
	}
}
</style>
