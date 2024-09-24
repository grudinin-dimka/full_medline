<template>
	<fieldset class="container-input-two" v-if="fieldset">
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

	<div class="container-input-two" v-else>
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
		},
	},
};
</script>

<style>
fieldset > legend {
	font-size: 18px;
	color: var(--primary-color);
}

fieldset > legend > span {
	font-size: 18px;
	color: var(--primary-color);
}

fieldset > legend > span.create {
	font-size: 18px;
	color: #44a533;
}

fieldset.container-input-two {
	margin-top: 5px;
	border: 2px solid rgba(0, 0, 0, 0.2);
	border-radius: 15px;
	padding: 10px;
	transition: all 0.2s;
	background-color: rgba(255, 255, 255, 0.5);
}

fieldset.container-input-two:focus-within {
	border: 2px solid var(--input-border-color-active);
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

.container-input-two > .item > input,
.container-input-two > .item > select {
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

.container-input-two > .item > input:focus,
.container-input-two > .item > select:focus {
	border: 2px solid var(--input-border-color-active);
}

.container-input-two > .item > input::placeholder {
	color: var(--input-placeholder-color);
}

.container-input-two > .item > input.error,
.container-input-two > .item > select.error {
	background-color: var(--input-background-color-error);
	border: 2px solid var(--input-border-color-error);

	caret-color: red;
}

@media screen and (max-width: 450px) {
	.container-input-two > .item {
		flex: 1 0 200px;
	}
}
</style>
