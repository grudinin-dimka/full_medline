<template>
	<transition-group name="debugger" tag="div" class="debugger">
		<div
			class="debugger__item"
			v-for="item in $store.getters.getDebuggers"
			:class="item.type"
			:key="item"
		>
			<div class="debugger__close" @click="$store.commit('removeDebugger', item)">
				<svg
					class="debugger__close-svg"
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
			<div class="debugger__title">
				{{ item.title }}
			</div>
			<div class="debugger__body">
				{{ item.body }}
			</div>
			<div class="debugger__progress"></div>
		</div>
	</transition-group>
</template>

<script></script>

<style scoped>
.debugger-move, /* применять переход к движущимся элементам */
.debugger-enter-active,
.debugger-leave-active {
	transition: all 0.5s ease;
}

.debugger-enter-from,
.debugger-leave-to {
	opacity: 0;
	transform: translateY(-15px);
}

/* убедитесь, что удаляющиеся элементы выведены из потока, чтобы 
анимации перемещения могли быть рассчитаны правильно. */
.debugger-leave-active {
	position: absolute;
}

.debugger {
	position: fixed;
	z-index: 600;
	top: 20px;
	right: 20px;

	display: flex;
	flex-direction: column;
	gap: 10px;

	width: 400px;
}

.debugger__item {
	position: relative;
	overflow-x: hidden;

	padding: 20px;
	border-radius: 15px;

	color: whitesmoke;

	box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.05);
}

.debugger__item.completed {
	background-color: #00abbd;
}

.debugger__item.error {
	background-color: #bd0000;
}

.debugger__close {
	display: flex;
	justify-content: center;
	align-items: center;

	border: 0px;
	border-radius: 50%;

	height: 30px;
	width: 30px;
}

.debugger__close:hover {
	background-color: rgba(255, 255, 255, 0.1);
}

.debugger__close-svg {
	fill: white;
}

.debugger.completed .debugger__close-svg {
	fill: white;
}

.debugger.error .debugger__close-svg {
	fill: white;
}

.debugger__title {
	font-size: 18px;
	margin-bottom: 10px;
}

.debugger__close {
	cursor: pointer;
	position: absolute;
	right: 10px;
	top: 10px;
}

.debugger__progress {
	position: absolute;
	bottom: 0px;
	left: 0px;

	height: 5px;
	width: 100%;

	background-color: rgba(255, 255, 255, 0.5);

	animation: progress 5s linear;
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

@keyframes progress {
	0% {
		width: 0%;
	}
	100% {
		width: 100%;
	}
}

@media screen and (width < 450px) {
	.debugger {
		right: 20px;
		left: 20px;
		top: 20px;
		width: auto;
	}
}
</style>
