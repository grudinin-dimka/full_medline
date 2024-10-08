<template>
	<div class="container-input-once" :class="{ create: type === 'create' }">
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

.container-input-once > label > span.create {
	color: #44a533;
}

.container-input-once > label > span.delete {
	color: #a53333;
}

.container-input-once > input {
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

.container-input-once.create > :is(input:focus, select:focus) {
	border: 2px solid #44a533;
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
