<template>
	<div
		class="debugger"
		:class="{
			completed: $store.state.debugger.type.completed,
			error: $store.state.debugger.type.error,
		}"
		v-if="$store.state.debugger.show"
	>
		<div class="debugger-close" @click="$store.commit('debuggerCloseClick')">
			<svg
				class="debugger-close-svg"
				xmlns="http://www.w3.org/2000/svg"
				height="24px"
				viewBox="0 -960 960 960"
				width="24px"
			>
				<path
					d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"
				/>
			</svg>
		</div>
		<div class="debugger-title">
			{{ $store.state.debugger.title }}
		</div>
		<div class="debugger-body">
			{{ $store.state.debugger.body }}
		</div>
	</div>
</template>

<script>
</script>

<style scoped>
.debugger {
	position: fixed;
	z-index: 600;
	top: 20px;
	right: 20px;

	padding: 20px;
	border: 2px solid rgba(255, 255, 255, 0);
	border-radius: 10px;

	width: 350px;

	color: white;

	box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.15);

	animation: show 0.5s ease-out;
}

.debugger.completed {
	background-color: #00abbd;
}

.debugger.error {
	background-color: #bd0000;
}

.debugger-close {
	display: flex;
	justify-content: center;
	align-items: center;

	border: 0px;
	border-radius: 50%;

	height: 30px;
	width: 30px;
}

.debugger-close:hover {
	background-color: rgba(255, 255, 255, 0.1);
}

.debugger-close-svg {
	fill: white;
}

.debugger.completed .debugger-close-svg {
	fill: white;
}

.debugger.error .debugger-close-svg {
	fill: white;
}

.debugger-title {
	font-size: 18px;
	margin-bottom: 10px;
}

.debugger-close {
	cursor: pointer;
	position: absolute;
	right: 10px;
	top: 10px;
}

@keyframes show {
	0% {
		opacity: 0;
		transform: translateY(-100px);
	}
	100% {
		opacity: 1;
		transform: translateY(0);
	}
}
</style>
