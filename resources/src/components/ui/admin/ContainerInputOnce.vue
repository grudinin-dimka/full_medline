<template>
	<div class="container-inputs-once">
		<label>
			<slot name="title"></slot>
		</label>
		<slot name="input"></slot>
		<slot name="error"></slot>
	</div>
</template>

<script setup></script>

<style>
.container-inputs-once {
	display: flex;
	flex-direction: column;
	gap: 5px;
}

.container-inputs-once > label {
	font-size: 18px;
}

.container-inputs-once > input {
	box-sizing: border-box;
	outline: none;

	padding: 10px;
	border: 2px solid var(--input-border-color-inactive);
	border-radius: 10px;

	width: 100%;
	height: 58px;

	font-size: 20px;
	caret-color: var(--input-border-color-active);

	transition: all 0.2s;
}

.container-inputs-once > input:focus {
	border: 2px solid var(--input-border-color-active);
}

.container-inputs-once > input.error {
	background-color: var(--input-background-color-error);
	border: 2px solid var(--input-border-color-error);

	caret-color: red;
}

.container-inputs-once > input::placeholder {
	color: var(--input-placeholder-color);
}


span.error {
	color: var(--span-color-error);
}
</style>
