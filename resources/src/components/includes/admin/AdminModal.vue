<template>
	<div
		class="modal"
		:class="{
			active: modal.status,
			create: modal.slide.create,
			delete: modal.slide.delete,
		}"
		@click.self="closeModal()"
	>
		<div class="modal-container">
			<!-- Верхняя часть окна -->
			<div class="modal-head">
				<!-- Заголовок -->
				<div class="modal-head-title">
					<slot name="title"></slot>
				</div>
				<!-- Кнопки -->
				<div class="modal-head-buttons">
					<slot name="button-hide"></slot>
					<button class="button-close" @click="closeModal">
						<svg
							xmlns="http://www.w3.org/2000/svg"
							height="26px"
							width="26px"
							viewBox="0 -960 960 960"
							fill="black"
						>
							<path
								d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"
							/>
						</svg>
					</button>
				</div>
			</div>
			<div class="modal-img">
				<slot name="img"></slot>
				<slot name="img-input"></slot>
			</div>
			<!-- Тело окна -->
			<div class="modal-body">
				<slot name="body"></slot>
			</div>
			<!-- Нижняя часть окна -->
			<slot name="footer">
				<slot name="footer"></slot>
			</slot>
		</div>
	</div>
</template>

<script>
export default {
	props: {
		modal: Object,
		required: true,
		default: () => {
			return {
				status: false,
			};
		},
	},
	methods: {
		closeModal() {
			this.$emit("closeModal");
		},
	},
};
</script>

<style scoped>
.modal {
	position: fixed;
	display: flex;
	justify-content: center;
	align-items: center;
	z-index: 500;
	top: 0px;
	left: 0px;

	opacity: 0;
	visibility: hidden;

	width: 100%;
	height: 100%;

	background: rgba(0, 0, 0, 0.39);

	transition: all 0.3s;
}

.modal.active {
	opacity: 1;
	visibility: visible;
}

.modal-container {
	display: flex;
	flex-direction: column;
	gap: 20px;
	padding: 20px;
	box-sizing: border-box;

	background: #fff;
	border-radius: 10px;
	width: calc(100dvw - 60px);
	max-width: 900px;
	max-height: 100%;

	transition: all 0.3s;
	transform: scale(0.7);
}

.modal.active .modal-container {
	transform: scale(1);
}

.modal.create .modal-container {
	background-color: var(--create-background-color);
	border: 3px solid var(--create-border-color);
}

.modal.delete .modal-container {
	background-color: var(--delete-background-color);
	border: 2px solid var(--delete-border-color);
}

.modal-head {
	display: flex;
	justify-content: space-between;
	margin-bottom: 10px;
}

.modal-head-title {
	display: flex;
	align-items: center;
	gap: 5px;
	color: #00abbd;
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
	display: grid;
	grid-template-columns: 1fr 1fr;
	gap: 20px;
	min-height: 300px;
}

.modal-body {
	display: flex;
	flex-direction: column;
	gap: 20px;
	max-height: 500px;
	overflow: auto;
}

/* Скролбар блока с контентом. */
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
/* Конец. */
</style>
