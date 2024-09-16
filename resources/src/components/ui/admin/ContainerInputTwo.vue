<template>
	<fieldset class="container-inputs-two" v-if="fieldset">
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
	</fieldset>

	<div class="container-inputs-two" v-else>
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
fieldset.container-inputs-two {
	border: 2px solid var(--input-border-color-inactive);
	border-radius: 10px;
	padding: 10px;
	transition: all 0.2s;
	background-color: rgb(255, 255, 255);
}

fieldset.container-inputs-two:focus-within {
	border: 2px solid var(--input-border-color-active);
}

.container-inputs-two {
	display: flex;
	flex-wrap: wrap;
   gap: 10px;
}

.container-inputs-two > .item {
	flex: 1 0 350px;
	display: flex;
   flex-direction: column;
   gap: 5px;
}

.container-inputs-two > .item > label {
	font-size: 18px;
}

.container-inputs-two > .item > input, 
.container-inputs-two > .item > select
{
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

.container-inputs-two > .item > input:focus,
.container-inputs-two > .item > select:focus
{
	border: 2px solid var(--input-border-color-active);
}
</style>
