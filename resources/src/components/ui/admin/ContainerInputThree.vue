<template>
	<fieldset class="container-inputs-three" v-if="fieldset">
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

	<div class="container-inputs-three" v-else>
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
		}
	},
};
</script>

<style>
fieldset.container-inputs-three {
	border: 2px solid var(--input-border-color-inactive);
	border-radius: 10px;
	padding: 10px;
	transition: all 0.2s;
	background-color: rgb(255, 255, 255);
}

fieldset.container-inputs-three:focus-within {
	border: 2px solid var(--input-border-color-active);
}

legend {
	font-size: 18px;
}

.container-inputs-three {
	display: grid;
	grid-template-columns: 1fr 1fr 1fr;
	gap: 10px;
}

.container-inputs-three > .item {
	display: flex;
	flex-direction: column;
	gap: 5px;
}

.container-inputs-three > .item > label {
	font-size: 18px;
}

.container-inputs-three > .item > input {
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

.container-inputs-three > .item > input:focus {
	border: 2px solid var(--input-border-color-active);
}
</style>
