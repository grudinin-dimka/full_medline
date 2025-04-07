<template>
	<transition
		@after-enter="$emit('loaderChildAfterEnter')"
		@after-leave="$emit('loaderChildAfterLeave')"
	>
		<div v-show="isLoading" class="container-loader" ref="loaderChild">
			<span class="loader"></span>
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
		minHeight: {
			type: Number,
			default: null,
		},
	},
	mounted() {
		if (this.minHeight) {
			this.$refs.loaderChild.style.minHeight = `${this.minHeight}px`;
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

@keyframes swirlAdded {
	from {
		opacity: 0;
		rotate: 0;
		scale: 0.1;
	}
	to {
		opacity: 1;
		rotate: 360deg;
		scale: 1;
	}
}

.container-loader {
	display: flex;
	justify-content: center;
	align-items: center;

	margin: auto;

	min-height: max(75dvh, 400px);
}

.loader {
	color: var(--primary-color);
	font-size: 10px;
	width: 1em;
	height: 1em;
	border-radius: 50%;
	position: relative;
	text-indent: -9999em;
	animation: mulShdSpin 1.3s infinite linear;
	transform: translateZ(0);
}

.v-enter-active,
.v-leave-active {
	transition: opacity 0.3s ease;
}

.v-enter-from,
.v-leave-to {
	opacity: 0;
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
