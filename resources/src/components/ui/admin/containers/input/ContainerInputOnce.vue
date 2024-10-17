<template>
	<div class="container-input-once" :class="{ create: type === 'create', delete: type === 'delete' }">
		<label>
			<slot name="title"></slot>
		</label>
		<slot name="input"></slot>
		<slot name="error"></slot>
	</div>
</template>

<script>
export default {
	props: {
		type: {
			type: String,
			default: "",
			required: true,
		},
	},
};
</script>

<style>
.container-input-once {
	display: flex;
	flex-direction: column;
	gap: 5px;
}

.container-input-once > label > span {
	font-size: 18px;
	color: var(--primary-color);
}

.container-input-once > :is(input, select) {
	box-sizing: border-box;
	outline: none;

	padding: 10px;
	border: 2px solid var(--input-border-color-inactive);
	border-radius: 10px;

	width: 100%;
	height: 58px;

	font-size: 20px;
	caret-color: var(--input-border-color-active);
	background-color: white;

	transition: all 0.2s;
}

.container-input-once > input::file-selector-button {
	cursor: pointer;

	background-color: var(--button-default-color);
	border: 0px;
	border-radius: 5px;
	color: white;
	padding: 5px;
}

.container-input-once > input.error::file-selector-button {
	background-color: var(--input-border-color-error);
	border: 2px solid var(--input-border-color-error);
}

.container-input-once.create > :is(input:focus, select:focus) {
	border: 2px solid #44a533;
}

.container-input-once.delete > :is(input:focus, select:focus) {
	border: 2px solid #a53333;
}

.container-input-once.create > label > span {
	color: #44a533;
}

.container-input-once.delete > label > span {
	color: #a53333;
}

.container-input-once > input:focus {
	border: 2px solid var(--input-border-color-active);
}

.container-input-once > input.error {
	background-color: var(--input-background-color-error);
	border: 2px solid var(--input-border-color-error);

	caret-color: red;
}

.container-input-once > input::placeholder {
	color: var(--input-placeholder-color);
}
</style>
