<template>
	<transition name="modal">
		<div
			v-show="modal.status"
			class="modal"
			:class="{
				create: modal.style.create,
				delete: modal.style.delete,
			}"
			@click.self="$emit('touchCloseModal')"
		>
			<transition name="modal-container">
				<div class="modal-container" v-show="modal.status">
					<!-- Верхняя часть окна -->
					<div class="modal-head">
						<!-- Заголовок -->
						<div class="modal-head-title">
							<slot name="title" v-if="modal.modules.title"></slot>
						</div>
						<!-- Кнопки -->
						<div class="modal-head-buttons">
							<slot name="buttonHide" v-if="modal.modules.buttons.hide"></slot>
							<button v-if="modal.modules.buttons.close" @click="$emit('touchCloseModal')">
								<IconClose :width="26" :height="26" />
							</button>
						</div>
					</div>
					<div class="modal-img" v-if="modal.modules.images">
						<slot name="img"></slot>
					</div>
					<!-- Тело окна -->
					<div class="modal-body" v-if="modal.modules.body">
						<slot name="body"></slot>
					</div>
					<!-- Нижняя часть окна -->
					<slot name="footer" v-if="modal.modules.footer">
						<slot name="footer"></slot>
					</slot>
				</div>
			</transition>
		</div>
	</transition>
</template>

<script>
import IconClose from "../../icons/IconClose.vue";

export default {
	components: {
		IconClose,
	},
	props: {
		modal: {
			type: Object,
			required: true,
			default: () => {},
		},
		required: true,
		default: () => {
			return {
				status: false,
			};
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
	position: fixed;
	display: flex;
	justify-content: center;
	align-items: center;
	z-index: 500;
	top: 0px;
	left: 0px;

	width: 100%;
	height: 100%;

	background: rgba(0, 0, 0, 0.39);
}

.modal-container {
	display: flex;
	flex-direction: column;
	gap: 20px;
	padding: 20px;
	margin: 30px;
	box-sizing: border-box;

	background: #fff;
	border-radius: 10px;
	width: 100%;
	max-width: 1000px;
	max-height: 100%;
}

/* .modal.create .modal-container {
	background-color: #d7f5d2;
	border: 0px solid #3cae1f;
} */

/* .modal.delete .modal-container {
	background-color: #f5d2d2;
	border: 0px solid #b93a3a;
} */

.modal-head {
	display: flex;
	justify-content: space-between;
	margin-bottom: 10px;
}

.modal > .modal-container > .modal-head > .modal-head-title {
	color: var(--primary-color);
}

.modal.create > .modal-container > .modal-head > .modal-head-title {
	color: #44a533;
}

.modal.delete > .modal-container > .modal-head > .modal-head-title {
	color: #a53333;
}

.modal-head-title {
	display: flex;
	align-items: center;
	gap: 5px;
	font-size: 22px;
	margin: 0px;
	padding: 0px;
}

.modal-head-buttons {
	display: flex;
	justify-content: flex-end;
	gap: 5px;
}

.modal-head-buttons button {
	cursor: pointer;
	background-color: rgba(255, 255, 255, 0);
	border: 0px;
	padding: 0px;
}

.button-close svg {
	fill: rgb(150, 150, 150);
}

.button-close svg:hover {
	fill: rgb(0, 0, 0);
}

.modal-img {
	display: flex;
	justify-content: center;
}

.modal-body {
	display: flex;
	flex-direction: column;
	gap: 10px;
	overflow: auto;
}

/* Скролбар блока со стандартным стилем*/
.modal-body::-webkit-scrollbar {
	width: 20px;
}

.modal-body::-webkit-scrollbar-track {
	background-color: rgb(255, 255, 255);
}

.modal-body::-webkit-scrollbar-thumb {
	background-color: rgb(220, 220, 220);
	border-left: 10px;
	border-color: rgb(255, 255, 255);
	border-style: solid;
}

.modal-body::-webkit-scrollbar-thumb:hover {
	background-color: rgb(230, 230, 230);
	cursor: all-scroll;
}

/* Скролбар блока со стилем "create" */
.modal.create .modal-body::-webkit-scrollbar-track {
	background-color: #f3fff0;
}

.modal.create .modal-body::-webkit-scrollbar-thumb {
	background-color: rgb(220, 220, 220);
	border-left: 10px;
	border-color: #f3fff0;
	border-style: solid;
}

.modal.create .modal-body::-webkit-scrollbar-thumb:hover {
	background-color: rgb(230, 230, 230);
	cursor: all-scroll;
}

/* Скролбар блока со стилем "delete" */
.modal.delete .modal-body::-webkit-scrollbar-track {
	background-color: #f3fff0;
}

.modal.delete .modal-body::-webkit-scrollbar-thumb {
	background-color: rgb(220, 220, 220);
	border-left: 10px;
	border-color: #f3fff0;
	border-style: solid;
}

.modal.delete .modal-body::-webkit-scrollbar-thumb:hover {
	background-color: rgb(230, 230, 230);
	cursor: all-scroll;
}

@media screen and (max-width: 1000px) {
	.modal-container {
		margin: 20px;
	}
}

@media screen and (max-width: 960px) {
	.modal-container {
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
	.modal-body {
		height: 100%;
	}

	.modal-container {
		justify-content: space-between;
		margin: 0px;
		height: 100%;
		border-radius: 0px;
	}
}
</style>
