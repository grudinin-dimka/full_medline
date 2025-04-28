<template>
	<div
		class="container-input-once"
		:class="type"
	>
		<label v-if="$slots.title">
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

	transition: all 0.2s;
}

.container-input-once > :is(input, select) {
	box-sizing: border-box;
	outline: none;

	padding: 10px;
	border: var(--input-border);
	border-radius: var(--input-border-radius);

	width: 100%;
	height: 58px;

	font-size: 20px;
	caret-color: var(--input-border-focus);
	background-color: white;

	transition: all 0.2s;
}

.container-input-once.disabled > :is(input, select) {
	border: var(--input-border);
	background-color: rgba(240, 240, 240, 1);
}

.container-input-once.password > :is(input, select):focus {
	border: 1px solid #cebc40;
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
	border: 1px solid var(--input-border-color-error);
}

.container-input-once.create > :is(input:focus, select:focus) {
	border: 1px solid #44a533;
}

.container-input-once.create > input::file-selector-button {
	border: 1px solid #44a533;
	background-color: #44a533;
}

.container-input-once.delete > :is(input:focus, select:focus) {
	border: 1px solid #a53333;
}

.container-input-once.create > label > span {
	color: #44a533;
}

.container-input-once.delete > label > span {
	color: #a53333;
}

.container-input-once > input:focus {
	border: var(--input-border-focus);
}

.container-input-once > input.error {
	background-color: var(--input-error-background-color);
	border: var(--input-error-border);

	caret-color: var(--input-error-color);
}

.container-input-once:is(.create, .delete) > input.error {
	background-color: var(--input-error-background-color);
	border: var(--input-error-border);

	caret-color: var(--input-error-color);
}

.container-input-once > input.error::file-selector-button {
	background-color: var(--input-error-background-color-file);
	border: var(--input-error-border);
}

.container-input-once > input::placeholder {
	color: var(--input-placeholder-color);
}
</style>
