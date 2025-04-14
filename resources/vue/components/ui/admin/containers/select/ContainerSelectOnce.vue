<template>
	<div class="container-select-once" :class="{ create: type === 'create' }">
		<label>
			<slot name="title"></slot>
		</label>
		<slot name="select"></slot>
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
.container-select-once {
	flex: 1 0 350px;
	display: flex;
	flex-direction: column;
	gap: 5px;
}

.container-select-once > label > span {
	font-size: 18px;
	color: var(--primary-color);
}

.container-select-once > select {
	box-sizing: border-box;
	outline: none;

	padding: 10px;
	border: 1px solid rgba(0, 0, 0, 0.25);
	border-radius: 10px;

	width: 100%;
	height: 58px;

	font-size: 20px;
	caret-color: var(--input-border-color-active);

	transition: all 0.2s;
}

.container-select-once > select:focus {
	border: 1px solid var(--input-border-color-active);
}

.container-select-once > option::placeholder {
	color: var(--input-placeholder-color);
}

.container-select-once.create > :is(input:focus, select:focus) {
	border: 1px solid #44a533;
}

.container-select-once.create > label > span {
	color: #44a533;
}

.container-select-once > select.error {
	background-color: var(--input-background-color-error);
	border: 1px solid var(--input-border-color-error);

	caret-color: red;
}

@media screen and (max-width: 450px) {
	.container-select-once {
		flex: 1 0 200px;
	}
}
</style>
