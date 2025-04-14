<template>
	<fieldset
		class="container-input-two"
		:class="{ create: type === 'create', delete: type === 'delete' }"
		v-if="fieldset"
	>
		<legend>
			<slot name="legend"></slot>
		</legend>
		<div class="item">
			<label>
				<slot name="title-one"></slot>
			</label>
			<slot name="input-one"></slot>
			<slot name="error-one"></slot>
		</div>
		<div class="item">
			<label>
				<slot name="title-two"></slot>
			</label>
			<slot name="input-two"></slot>
			<slot name="error-two"></slot>
		</div>
	</fieldset>

	<div class="container-input-two" :class="{ create: type === 'create' }" v-else>
		<div class="item">
			<label>
				<slot name="title-one"></slot>
			</label>
			<slot name="input-one"></slot>
			<slot name="error-one"></slot>
		</div>
		<div class="item">
			<label>
				<slot name="title-two"></slot>
			</label>
			<slot name="input-two"></slot>
			<slot name="error-two"></slot>
		</div>
	</div>
</template>

<script>
export default {
	props: {
		fieldset: {
			type: Boolean,
			default: false,
			required: true,
		},
		type: {
			type: String,
			default: "",
			required: true,
		},
	},
};
</script>

<style>
fieldset > legend {
	font-size: 18px;
	color: var(--primary-color);
}

fieldset.container-input-two {
	border: var(--default-border);
	border-radius: 15px;
	padding: 10px;
	transition: all 0.2s;
	background-color: rgba(255, 255, 255, 0.5);
}

fieldset.container-input-two.create:focus-within {
	border: 1px solid #44a533;
}

fieldset.container-input-two:focus-within {
	border: 1px solid var(--input-border-color-active);
}

fieldset.container-input-two.create > legend > span {
	color: #44a533;
}

fieldset.container-input-two.delete > legend > span {
	color: #a53333;
}

.container-input-two {
	display: flex;
	flex-wrap: wrap;
	gap: 10px;
}

.container-input-two > .item {
	flex: 1 0 350px;
	display: flex;
	flex-direction: column;
	gap: 5px;
}

.container-input-two > .item > label {
	font-size: 18px;
}

:is(.container-input-two > .item) > :is(input, select) {
	box-sizing: border-box;
	outline: none;

	padding: 10px;
	border: var(--default-border);
	border-radius: 10px;

	width: 100%;
	height: 58px;

	font-size: 20px;
	caret-color: var(--input-border-color-active);

	transition: all 0.2s;
}

.container-input-two > .item > :is(input:focus, select:focus) {
	border: 1px solid var(--input-border-color-active);
}

.container-input-two.create > .item > :is(input:focus, select:focus) {
	border: 1px solid #44a533;
}

.container-input-two.delete > .item > :is(input:focus, select:focus) {
	border: 1px solid #a53333;
}

.container-input-two > .item > input::placeholder {
	color: var(--input-placeholder-color);
}

.container-input-two > .item > :is(input.error, select.error) {
	background-color: var(--input-error-background-color);
	border: var(--input-error-border);

	caret-color: red;
}

.container-input-two > .item > input::file-selector-button {
	cursor: pointer;

	background-color: var(--button-default-color);
	border: 0px;
	border-radius: 5px;
	color: white;
	padding: 5px;
}

.container-input-two.create > .item > input::file-selector-button {
	border: 1px solid #44a533;
	background-color: #44a533;
}

.container-input-two > .item > input.error::file-selector-button {
	background-color: var(--input-error-background-color-file);
	border: var(--input-error-border);
}

@media screen and (max-width: 450px) {
	.container-input-two > .item {
		flex: 1 0 200px;
	}
}
</style>
