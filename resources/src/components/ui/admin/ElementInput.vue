<template>
   <input
      :type="type"
      :value="value"
      :placeholder="placeholder"
   />
</template>

<script>
export default {
   props: {
      type: {
         type: String,
         required: true,
         default: '',
      },
      value: {
         type: String,
         required: true,
         default: '',
      },
      placeholder: {
         type: String,
         required: true,
         default: '',
      },
   },
};
</script>

<style scoped>
input {
	box-sizing: border-box;
	outline: none;

	padding: 10px;
	border: 2px solid var(--input-border-color-inactive);
	border-radius: 10px;

	width: 100%;
	height: 58px;

	font-size: 18px;
	caret-color: var(--input-border-color-active);

	transition: all 0.2s;
}

input::file-selector-button {
	cursor: pointer;
	background-color: var(--button-default-color);
	border: 0px;
	border-radius: 5px;
	color: white;
	padding: 5px;
}

input::file-selector-button:hover {
	background-color: var(--button-default-color-hover);
}

input:focus {
	border: 2px solid var(--input-border-color-active);
}

input.error {
	background-color: var(--input-background-color-error);
	border: 2px solid var(--input-border-color-error);

	caret-color: red;
}
</style>
