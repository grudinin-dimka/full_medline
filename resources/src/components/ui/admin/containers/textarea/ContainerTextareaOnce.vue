<template>
	<div class="container-textarea-once">
		<label>
			<slot name="title"></slot>
		</label>
		<slot name="textarea"></slot>
		<slot name="error"></slot>
	</div>
</template>

<script setup></script>

<style>
.container-textarea-once {
	display: flex;
	flex-direction: column;
	gap: 5px;
}

.container-textarea-once > label {
	font-size: 18px;
	color: var(--primary-color);
}

.container-textarea-once > textarea {
	box-sizing: border-box;
	outline: none;
   resize: none;

	padding: 10px;
	border: 2px solid var(--input-border-color-inactive);
	border-radius: 10px;

	width: 100%;

	font-size: 20px;
	caret-color: var(--input-border-color-active);

	transition: all 0.2s;
}

.container-textarea-once > textarea:focus {
	border: 2px solid var(--input-border-color-active);
}

.container-textarea-once > textarea.error {
	background-color: var(--input-background-color-error);
	border: 2px solid var(--input-border-color-error);

	caret-color: red;
}

.container-textarea-once > textarea::placeholder {
	color: var(--input-placeholder-color);
}


span.error {
	color: var(--span-color-error);
}
</style>
