<template>
	<div
		class="container-textarea-once"
		:class="{ create: type === 'create', delete: type === 'delete' }"
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
			required: true,
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

.container-textarea-once.create > :is(input:focus, select:focus, textarea:focus) {
	border: 2px solid #44a533;
}

.container-textarea-once.create > label > span {
	color: #44a533;
}

.container-textarea-once.delete > label > span {
	color: #a53333;
}

.error-counter {
	display: flex;
}

.error-counter > span.counter {
	margin-left: auto;
}
</style>
