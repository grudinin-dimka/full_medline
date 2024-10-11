<template>
	<fieldset
		class="container-input-two-sub-sub"
		:class="{ create: type === 'create' }"
		v-if="fieldset"
	>
		<legend>
			<slot name="legend"></slot>
		</legend>
		<div class="item">
			<div class="input">
				<div class="item">
					<label>
						<slot name="title-one"></slot>
					</label>
					<slot name="input-one"></slot>
					<slot name="error-one"></slot>
				</div>
				<div class="item" v-if="subOne">
					<label>
						<slot name="title-sub-one"></slot>
					</label>
					<slot name="input-sub-one"></slot>
					<slot name="error-sub-one"></slot>
				</div>
			</div>
		</div>
		<div class="item">
			<div class="input">
				<div class="item">
					<label>
						<slot name="title-two"></slot>
					</label>
					<slot name="input-two"></slot>
					<slot name="error-two"></slot>
				</div>
				<div class="item" v-if="subTwo">
					<label>
						<slot name="title-sub-two"></slot>
					</label>
					<slot name="input-sub-two"></slot>
					<slot name="error-sub-two"></slot>
				</div>
			</div>
		</div>
	</fieldset>

	<div class="container-input-two-sub" :class="{ create: type === 'create' }" v-else>
		<div class="item">
			<div class="input">
				<div class="item">
					<label>
						<slot name="title-one"></slot>
					</label>
					<slot name="input-one"></slot>
					<slot name="error-one"></slot>
				</div>
				<div class="item" v-if="subOne">
					<label>
						<slot name="title-sub-one"></slot>
					</label>
					<slot name="input-sub-one"></slot>
					<slot name="error-sub-one"></slot>
				</div>
			</div>
		</div>
		<div class="item">
			<div class="input">
				<div class="item">
					<label>
						<slot name="title-two"></slot>
					</label>
					<slot name="input-two"></slot>
					<slot name="error-two"></slot>
				</div>
				<div class="item" v-if="subTwo">
					<label>
						<slot name="title-sub-two"></slot>
					</label>
					<slot name="input-sub-two"></slot>
					<slot name="error-sub-two"></slot>
				</div>
			</div>
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
		subOne: {
			type: Boolean,
			default: false,
			required: true,
		},
		subTwo: {
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
fieldset.container-input-two-sub-sub {
	margin-top: 5px;
	border: 2px solid rgba(0, 0, 0, 0.2);
	border-radius: 15px;
	padding: 10px;
	transition: all 0.2s;
	background-color: rgba(255, 255, 255, 0.5);
}

fieldset.container-input-two-sub-sub > legend {
	font-size: 18px;
	color: var(--primary-color);
}

fieldset.container-input-two-sub-sub.create:focus-within {
	border: 2px solid #44a533;
}

fieldset.container-input-two-sub-sub:focus-within {
	border: 2px solid var(--input-border-color-active);
}

fieldset.container-input-two-sub.create > legend > span {
	color: #44a533;
}

.container-input-two-sub-sub {
	display: flex;
	flex-wrap: wrap;
	gap: 10px;
}

.container-input-two-sub-sub > .item {
	flex: 1 0 350px;
	display: flex;
	flex-direction: column;
}

.container-input-two-sub-sub > .item > .input {
	display: flex;
	flex-direction: row;
	gap: 10px;
}

.container-input-two-sub-sub > .item > .input > .item {
	flex-grow: 1;
	display: flex;
	flex-direction: column;
	gap: 5px;
}

.container-input-two-sub-sub > .item > .input > .item > label {
	font-size: 18px;
}

.container-input-two-sub-sub > .item > .input > .item > :is(input, select) {
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

.container-input-two-sub-sub > .item > .input > .item > :is(input:focus, select:focus) {
	border: 2px solid var(--input-border-color-active);
}

.container-input-two-sub-sub.create > .item > .input > .item > :is(input:focus, select:focus) {
	border: 2px solid #44a533;
}

.container-input-two-sub-sub > .item > .input > .item > input::placeholder {
	color: var(--input-placeholder-color);
}

.container-input-two-sub-sub > .item > .input > .item > :is(input.error, select.error) {
	background-color: var(--input-background-color-error);
	border: 2px solid var(--input-border-color-error);

	caret-color: red;
}
</style>
