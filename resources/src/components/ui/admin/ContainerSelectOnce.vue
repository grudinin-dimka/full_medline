<template>
	<div class="container-select-once">
		<div class="item">
			<label>
				<slot name="title"></slot>
			</label>
			<slot name="select"></slot>
			<slot name="error"></slot>
		</div>
	</div>
</template>

<script>
export default {
	props: {
		fieldset: {
			type: Boolean,
			default: false,
		},
	},
};
</script>

<style>
.container-select-once {
	display: flex;
	flex-wrap: wrap;
	gap: 10px;
}

.container-select-once > .item {
	flex: 1 0 350px;
	display: flex;
	flex-direction: column;
	gap: 5px;
}

.container-select-once > .item > label {
	font-size: 18px;
}

.container-select-once > .item > select {
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

.container-select-once > .item > option::placeholder {
	color: var(--input-placeholder-color);
}

.container-select-once > .item > select.error {
	background-color: var(--input-background-color-error);
	border: 2px solid var(--input-border-color-error);

	caret-color: red;
}

@media screen and (max-width: 450px) {
	.container-select-once > .item {
		flex: 1 0 200px;
	}
}
</style>
