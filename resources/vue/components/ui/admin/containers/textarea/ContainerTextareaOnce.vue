<template>
	<div
		class="container-textarea-once"
		:class="type"
	>
		<label>
			<slot name="title"></slot>
		</label>
		<slot name="textarea"></slot>
		<div class="error-counter">
			<slot name="error"></slot>
			<slot name="counter"></slot>
		</div>
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
.container-textarea-once {
	display: flex;
	flex-direction: column;
	gap: 5px;
}

.container-textarea-once > label > span {
	font-size: 18px;
	color: var(--primary-color);
}

.container-textarea-once > textarea {
	box-sizing: border-box;
	outline: none;
	resize: none;

	padding: 10px;
	border: var(--input-border);
	border-radius: var(--input-border-radius);

	width: 100%;

	font-size: 20px;
	caret-color: var(--input-caret-color);

	transition: all 0.2s;
}

.container-textarea-once > textarea:focus {
	border: var(--input-border-focus);
}

.container-textarea-once > textarea.error {
	background-color: var(--input-error-background-color);
	border: var(--input-error-border);

	caret-color: var(--input-error-caret-color);
}

.container-textarea-once > textarea::placeholder {
	color: var(--input-placeholder-color);
}

.container-textarea-once.create > :is(input:focus, select:focus, textarea:focus) {
	border: var(--input-create-border-focus);
}

.container-textarea-once.create > label > span {
	color: var(--input-create-color);
}

.container-textarea-once.delete > label > span {
	color: var(--input-delete-color);
}

.error-counter {
	display: flex;
}

.error-counter > span.counter {
	margin-left: auto;
}
</style>
