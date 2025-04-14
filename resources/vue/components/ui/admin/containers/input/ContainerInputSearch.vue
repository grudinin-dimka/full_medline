<template>
	<div class="input_search" :class="look">
		<label v-if="label">
			<slot name="label"></slot>
		</label>

		<input
			type="text"
			:value="modelValue"
			:placeholder="placeholder"
			autocomplete="off"
			@input="$emit('update:modelValue', $event.target.value)"
		/>

		<div class="clear" v-if="modelValue !== ''" @click="$emit('update:modelValue', '')">
			<svg
				xmlns="http://www.w3.org/2000/svg"
				height="20px"
				viewBox="0 -960 960 960"
				width="20px"
				fill="black"
			>
				<path
					d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"
				/>
			</svg>
		</div>

		<div v-if="error" class="error">
			<slot name="error"></slot>
		</div>
	</div>
</template>

<script>
export default {
	props: {
		modelValue: {
			type: [String, Number, Boolean, null],
			default: "",
			required: true,
		},
		placeholder: {
			type: String,
			default: "Введите значение",
		},
		look: {
			type: String,
			default: "",
		},
		autocomplete: {
			type: String,
			default: "off",
		},
		label: {
			type: Boolean,
			default: false,
		},
		error: {
			type: Boolean,
			default: false,
		},
	},
	data() {
		return {
			hide: true,
		};
	},
	methods: {
		changeType() {
			this.hide = !this.hide;
		},
	},
};
</script>

<style scoped>
.input_search {
	position: relative;
	display: flex;
	flex-direction: column;
	gap: 5px;
}

.input_search > .icon {
	cursor: pointer;
	position: absolute;
	bottom: 16px;
	right: 10px;
}

.input_search > .icon > svg {
	fill: rgba(0, 0, 0, 0.5);
}

.input_search > .icon:hover > svg {
	fill: rgba(0, 0, 0, 1);
}

.input_search > input {
	box-sizing: border-box;
	outline: none;

	padding: 10px;
	border: var(--input-border);
	border-radius: var(--input-border-radius);

	width: 100%;
	height: 58px;

	font-size: 20px;
	caret-color: var(--input-caret-color);
	background-color: white;

	transition: all 0.2s;
}

.input_search > input:focus {
	border: var(--input-border-focus);
}

.input_search > input::placeholder {
	color: rgba(0, 0, 0, 0.5);
}

.input_search > .error {
	user-select: none;
	position: absolute;
	right: 10px;
	top: 15px;

	border-radius: 7.5px;
	padding: 5px 10px;

	background-color: #f7cccc;
	color: rgb(186, 43, 43);

	animation: show 0.2s ease;
}

.input_search > .clear {   
   cursor: pointer;
   position: absolute;
   top: 18px;
   right: 10px;
}
</style>
