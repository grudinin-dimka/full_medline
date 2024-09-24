<template>
	<textarea
		:rows="rows"
		:cols="cols"
		:placeholder="placeholder"
		:value="value"
		@input="
			$emit('update:modelValue', $event.target.value),
         $emit('inputText')
		"
	>
	</textarea>
</template>

<script>
export default {
	props: {
		rows: {
			type: Number,
			required: true,
			default: 5,
		},
		cols: {
			type: Number,
			required: true,
			default: 10,
		},
		placeholder: {
			type: String,
			required: true,
			default: "",
		},
		value: {
			type: String,
			required: true,
			default: "",
		},
	},
};
</script>

<style scoped>
textarea {
	resize: none;
	box-sizing: border-box;
	outline: none;

	padding: 10px;
	border: 2px solid var(--input-border-color-inactive);
	border-radius: 10px;

	width: 100%;

	font-size: 18px;
	caret-color: var(--input-border-color-active);

	transition: all 0.2s;
}

textarea:focus {
	border: 2px solid var(--input-border-color-active);
}
</style>
