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
					fullscreen: settings.fullscreen,
				}"
				ref="modal"
				@click.self="settings.touch ? $emit('touch') : close()"
			>
				<transition name="modal-container">
					<div
						class="modal__container"
						:class="{
							thin: settings.thin,
						}"
						v-show="isOpen"
					>
						<!-- Верхняя часть окна -->
						<div class="modal__header">
							<!-- Заголовок -->
							<div class="modal__header-title" v-if="$slots.title">
								<slot name="title"></slot>
							</div>
							<!-- Кнопки -->
							<div class="modal__header-buttons">
								<slot name="buttons" v-if="$slots.buttons"></slot>

								<button class="modal__header-close" @click.prevent="close">
									<VueIcon name="Close" :width="'24px'" :height="'24px'"/>
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
import VueIcon from "../../ui/VueIcon.vue";

export default {
	emits: ["touch"],
	components: {
		VueIcon
	},
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
	z-index: var(--modal-z-index);
	top: 0px;
	left: 0px;

	border: var(--modal-border);
	border-radius: var(--modal-border-radius);
	padding: 20px;

	width: var(--modal-width);
	height: var(--modal-height);

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

.modal.fullscreen {
	border-radius: 0px;

	padding: 0px;
}

.modal__container {
	box-sizing: border-box;
	display: flex;
	flex-direction: column;
	gap: var(--modal-container-gap);

	padding: var(--modal-container-padding);

	background: var(--modal-container-background-color);
	border-radius: var(--modal-container-border-radius);
	width: var(--modal-container-width);
	max-width: var(--modal-container-max-width);
}

.modal.clamped > .modal__container {
	margin: var(--modal-clamped-container-margin);

	max-height: var(--modal-clamped-container-max-height);
}

.modal.unclamped > .modal__container {
	margin: var(--modal-unclamped-container-margin);
}

.modal__container.thin {
	max-width: var(--modal-container-thin-max-width);
}

.modal.fullscreen > .modal__container {
	border-radius: var(--modal-fullscreen-container-border-radius);

	width: var(--modal-fullscreen-container-width);
	height: var(--modal-fullscreen-container-height);

	max-width: var(--modal-fullscreen-container-max-width);
	max-height: var(--modal-fullscreen-container-max-height);

	margin: var(--modal-fullscreen-container-margin);
}

.modal.fullscreen > .modal__container > .modal__header {
	border-radius: var(--modal-fullscreen-header-border-radius);
}

.modal.fullscreen > .modal__container > .modal__footer {
	border-radius: var(--modal-fullscreen-footer-border-radius);
}

.modal.fullscreen > .modal__container > .modal__body {
	height: var(--modal-fullscreen-body-height);
}

/* Голова */
.modal__header {
	display: flex;
	flex-direction: var(--modal-header-flex-direction);
	justify-content: var(--modal-header-justify-content);
	align-items: var(--modal-header-align-items);
	gap: var(--modal-header-gap);

	border-radius: var(--modal-header-border-radius);
	border-top: var(--modal-header-border-top);
	border-right: var(--modal-header-border-right);
	border-bottom: var(--modal-header-border-bottom);
	border-left: var(--modal-header-border-left);
	border-style: var(--modal-header-border-style);
	border-color: var(--modal-header-border-color);

	padding: var(--modal-header-padding);
}

.modal__header-title {
	display: flex;
	align-items: center;
	gap: 5px;
	font-size: 22px;
	margin: 0px;
	padding: 0px;
}

.modal__header-buttons {
	display: flex;
	justify-content: flex-end;
	gap: 10px;
}

.modal__header-close {
	cursor: pointer;
	background-color: var(--modal-header-close-background-color);
	border: var(--modal-header-close-border);
	border-radius: var(--modal-header-close-border-radius);
	padding: var(--modal-header-close-padding);

	transition: var(--modal-header-close-transition);
}

.modal__header-close:hover {
	border: var(--modal-header-close-border-hover);
	background-color: var(--modal-header-close-background-color-hover);
}

.modal__header-close:hover svg {
	fill: var(--modal-header-close-icon-fill-hover);
}

/* Тело */
.modal__body {
	display: flex;
	flex-direction: var(--modal-body-flex-direction);
	justify-content: var(--modal-body-justify-content);
	align-items: var(--modal-body-align-items);
	gap: var(--modal-body-gap);

	border-radius: var(--modal-body-border-radius);
	border-top: var(--modal-body-border-top);
	border-right: var(--modal-body-border-right);
	border-bottom: var(--modal-body-border-bottom);
	border-left: var(--modal-body-border-left);
	border-style: var(--modal-body-border-style);
	border-color: var(--modal-body-border-color);

	padding: var(--modal-body-padding);
}

.modal.clamped .modal__body {
	overflow: auto;
}

/* Футер */
.modal__footer {
	display: flex;
	justify-content: flex-end;
	gap: var(--modal-footer-gap);

	border-radius: var(--modal-footer-border-radius);
	border-top: var(--modal-footer-border-top);
	border-right: var(--modal-footer-border-right);
	border-bottom: var(--modal-footer-border-bottom);
	border-left: var(--modal-footer-border-left);
	border-style: var(--modal-footer-border-style);
	border-color: var(--modal-footer-border-color);

	padding: var(--modal-footer-padding);
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
}
</style>
