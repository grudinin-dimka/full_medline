<template>
	<fieldset class="container-input-three" v-if="fieldset">
		<legend>
			<slot name="legend"></slot>
		</legend>
		<div class="item">
			<label>
				<slot name="title-one"></slot>
			</label>
			<slot name="input-one"></slot>
		</div>
		<div class="item">
			<label>
				<slot name="title-two"></slot>
			</label>
			<slot name="input-two"></slot>
		</div>
		<div class="item">
			<label>
				<slot name="title-three"></slot>
			</label>
			<slot name="input-three"></slot>
		</div>
	</fieldset>

	<div class="container-input-three" v-else>
		<div class="item">
			<label>
				<slot name="title-one"></slot>
			</label>
			<slot name="input-one"></slot>
		</div>
		<div class="item">
			<label>
				<slot name="title-two"></slot>
			</label>
			<slot name="input-two"></slot>
		</div>
		<div class="item">
			<label>
				<slot name="title-three"></slot>
			</label>
			<slot name="input-three"></slot>
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
fieldset.container-input-three {
	margin-top: 5px;
	border: 2px solid var(--input-border-color-inactive);
	border-radius: 15px;
	padding: 10px;
	transition: all 0.2s;
	background-color: rgb(255, 255, 255);
}

fieldset.container-input-three:focus-within {
	border: 2px solid var(--input-border-color-active);
}

legend {
	font-size: 18px;
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

.container-input-three > .item > input::placeholder {
	color: var(--input-placeholder-color);
}
</style>
