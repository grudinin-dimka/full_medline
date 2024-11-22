<template>
	<div
		class="container-input-once"
		:class="{
			create: type === 'create',
			delete: type === 'delete',
			disabled: type === 'disabled',
			password: type === 'password',
		}"
	>
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
}

.container-input-once > label > span {
	font-size: 18px;
	color: var(--primary-color);

	transition: all 0.2s;
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

.container-input-once.disabled > :is(input, select) {
	border: 2px solid var(--input-border-color-inactive);
	background-color: rgba(240, 240, 240, 1);
}

.container-input-once.password > :is(input, select):focus {
	border: 2px solid #cebc40;
	caret-color: #cebc40;
	background-color: white;
}

.container-input-once.disabled > label > span {
	color: #969696;
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

.container-input-once.create > input::file-selector-button {
	border: 2px solid #44a533;
	background-color: #44a533;
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

.container-input-once:is(.create, .delete, .disabled, .password) > input.error {
	background-color: var(--input-background-color-error);
	border: 2px solid var(--input-border-color-error);

	caret-color: red;
}

.container-input-once > input::placeholder {
	color: var(--input-placeholder-color);
}
</style>
