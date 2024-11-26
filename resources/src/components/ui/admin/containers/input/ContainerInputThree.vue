<template>
	<fieldset class="container-input-three" :class="{ create: type === 'create' }" v-if="fieldset">
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
		<div class="item">
			<label>
				<slot name="title-three"></slot>
			</label>
			<slot name="input-three"></slot>
			<slot name="error-three"></slot>
		</div>
	</fieldset>

	<div class="container-input-three" :class="{ create: type === 'create' }" v-else>
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
		<div class="item">
			<label>
				<slot name="title-three"></slot>
			</label>
			<slot name="input-three"></slot>
			<slot name="error-three"></slot>
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
		type: {
			type: String,
			default: "",
			required: true,
		},
	},
};
</script>

<style>
fieldset.container-input-three {
	border: 2px solid var(--input-border-color-inactive);
	border-radius: 15px;
	padding: 10px;
	transition: all 0.2s;
	background-color: rgb(255, 255, 255);
}

fieldset.container-input-three.create > legend {
	font-size: 18px;
	color: var(--primary-color);
}

fieldset.container-input-three.create > legend {
	color: #44a533;
}

fieldset.container-input-three:focus-within {
	border: 2px solid var(--input-border-color-active);
}

fieldset.container-input-three.create:focus-within {
	border: 2px solid #44a533;
}

.container-input-three {
	display: flex;
	flex-wrap: wrap;
	gap: 10px;
}

.container-input-three > .item {
	flex: 1 0 350px;
	display: flex;
	flex-direction: column;
	gap: 5px;
}

.container-input-three > .item > label {
	font-size: 18px;
}

.container-input-three.create > .item > label {
	color: #44a533;
}

.container-input-three > .item > input {
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

.container-input-three > .item > input:focus {
	border: 2px solid var(--input-border-color-active);
}

.container-input-three.create > .item > input:focus {
	border: 2px solid #44a533;
}

.container-input-three > .item > input::placeholder {
	color: var(--input-placeholder-color);
}

.container-input-three > .item > :is(input.error, select.error) {
	background-color: var(--input-background-color-error);
	border: 2px solid var(--input-border-color-error);

	caret-color: red;
}

@media screen and (width <= 450px) {
	.container-input-three > .item {
		flex: 1 0 200px;
	}
}
</style>
