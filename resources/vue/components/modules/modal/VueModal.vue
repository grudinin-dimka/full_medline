<template>
	<teleport to="body">
		<transition name="modal">
			<div
				v-show="isOpen"
				class="modal"
				:class="{
					[settings.values.look]: settings.values.look,
					hidden: !isOpen,
					clamped: settings.clamped,
					unclamped: !settings.clamped,
				}"
				ref="modal"
				@click.self="settings.touch ? $emit('touch') : close()"
			>
				<transition name="modal-container">
					<div class="modal__container" :class="{ thin: settings.thin }" v-show="isOpen">
						<!-- Верхняя часть окна -->
						<div class="modal__head">
							<!-- Заголовок -->
							<div class="modal__head-title" v-if="$slots.title">
								<slot name="title"></slot>
							</div>
							<!-- Кнопки -->
							<div class="modal__head-buttons">
								<slot name="buttons" v-if="$slots.buttons"></slot>
								<button @click.prevent="close">
									<svg
										data-v-05b9c86b=""
										xmlns="http://www.w3.org/2000/svg"
										width="16"
										height="16"
										viewBox="0 0 14 14"
									>
										<path
											data-v-05b9c86b=""
											d="M1.4 14L0 12.6L5.6 7L0 1.4L1.4 0L7 5.6L12.6 0L14 1.4L8.4 7L14 12.6L12.6 14L7 8.4L1.4 14Z"
										></path>
									</svg>
								</button>
							</div>
						</div>
						<!-- Тело окна -->
						<div class="modal__body" v-if="$slots.body">
							<slot name="body"></slot>
						</div>
						<!-- Нижняя часть окна -->
						<div class="modal__footer" v-if="$slots.footer">
							<slot name="footer"></slot>
						</div>
					</div>
				</transition>
			</div>
		</transition>
	</teleport>
</template>

<script>
export default {
	emits: ["touch"],
	props: {
		/* 
			settings: {
				thin: false,
				clamped: false,
				values: {
					title: "",
					look: "default",
				},
			},
		*/
		settings: {
			type: Object,
			default: {},
		},
		look: {
			type: String,
			default: "",
		},
	},
	data() {
		return {
			isOpen: false,
			keydownHandler: null,
		};
	},
	methods: {
		open() {
			this.isOpen = true;
			this.$store.commit("registerModal", this);

			document.body.classList.add("modal-open");
		},

		close() {
			this.isOpen = false;

			if (!this.$store.getters.isOpenMoreModal) {
				document.body.classList.remove("modal-open");
			}

			this.$store.commit("unregisterModal", this);
		},
	},
	mounted() {
		if (this.settings.zIndex) {
			this.$refs.modal.style.zIndex = this.settings.zIndex;
		}
	},
};
</script>

<style scoped>
.modal-enter-active,
.modal-leave-active {
	transition: opacity 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
	opacity: 0;
}

.modal-container-enter-active,
.modal-container-leave-active {
	transition: opacity 0.3s ease, transform 0.3s ease;
}

.modal-container-enter-from,
.modal-container-leave-to {
	opacity: 0;
	transform: scale(0.7);
}

.modal {
	box-sizing: border-box;
	position: fixed;
	z-index: 500;
	top: 0px;
	left: 0px;

	border: var(--modal-border);
	border-radius: var(--modal-border-radius);
	padding: 20px;

	width: 100%;
	height: 100%;

	background: var(--modal-background-color);
}

.modal.clamped {
	display: flex;
	justify-content: safe center;
	align-items: center;
}

.modal.unclamped {
	display: grid;
	grid-template-columns: auto;

	overflow: auto;
}

.modal.hidden {
	overflow: hidden;
}

.modal__container {
	box-sizing: border-box;
	display: flex;
	flex-direction: column;
	gap: var(--modal-container-gap);

	padding: var(--modal-container-padding);

	background: var(--modal-container-background-color);
	border-radius: var(--modal-container-border-radius);
	width: 100%;
	max-width: 1000px;
}

.modal.clamped > .modal__container {
	max-height: calc(100dvh - 60px);
	overflow-y: auto;
}

.modal.unclamped > .modal__container {
	margin: auto;
}

.modal__container.thin {
	max-width: 600px;
}

/* Голова */
.modal__head {
	display: flex;
	justify-content: space-between;
	margin-bottom: 10px;
}

.modal__head-title {
	user-select: none;
	display: flex;
	align-items: center;
	gap: 5px;
	font-size: 22px;
	margin: 0px;
	padding: 0px;

	color: var(--primary-color);
}

.modal__head-buttons {
	display: flex;
	justify-content: flex-end;
	gap: 5px;
}

.modal__head-buttons > button {
	cursor: pointer;
	background-color: rgba(255, 255, 255, 0);
	border: 0px;
	border-radius: 100px;
	padding: 0px;

	width: 30px;
	height: 30px;

	transition: all 0.2s ease;
}

.modal__head-buttons button > svg {
	fill: rgb(150, 150, 150);
}

.modal__head-buttons button:hover > svg {
	fill: rgb(0, 0, 0);
}

/* Тело */
.modal__body {
	display: flex;
	flex-direction: column;
	gap: 10px;
	overflow: auto;
}

.modal__body::-webkit-scrollbar {
	width: 20px;
}

.modal__body::-webkit-scrollbar-track {
	background-color: rgb(255, 255, 255);
}

.modal__body::-webkit-scrollbar-thumb {
	background-color: rgb(220, 220, 220);
	border-left: 10px;
	border-color: rgb(255, 255, 255);
	border-style: solid;
}

.modal__body::-webkit-scrollbar-thumb:hover {
	background-color: rgb(230, 230, 230);
	cursor: all-scroll;
}

/* Футер */
.modal__footer {
	display: flex;
	justify-content: flex-end;
	gap: 10px;
}

@media screen and (max-width: 1000px) {
	.modal__container {
		margin: 20px;
	}
}

@media screen and (max-width: 960px) {
	.modal__container {
		min-width: 0px;
		width: 100%;
	}
}

@media screen and (max-width: 860px) {
	.modal-img {
		display: flex;
		flex-direction: column;
		justify-content: justify;
		align-items: justify;
		min-height: none;
	}
}

@media screen and (max-width: 500px) {
	.modal {
		padding: 0px;
	}

	.modal__body {
		height: 100%;
	}

	.modal__container {
		justify-content: space-between;
		margin: 0px;
		height: 100%;
		border-radius: 0px;
		max-height: 100%;
	}

	.modal.clamped > .modal__container {
		max-height: 100dvh;
		overflow-y: auto;
	}
}
</style>
