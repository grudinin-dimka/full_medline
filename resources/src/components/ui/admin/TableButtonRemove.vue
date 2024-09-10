<template>
	<button :disabled="disabled" :class="{ disabled : disabled }">
		<slot></slot>
	</button>
</template>

<script>
export default {
   props: {
      disabled: {
         type: Boolean,
         default: false
      }
   }
};
</script>

<style scoped>
button {
	flex: 1 0 130px;
	cursor: pointer;
   min-width: 150px;

	padding: 5px 10px;
	border: 0px solid black;
	border-radius: 5px;

	font-size: 16px;
	color: white;

	background-color: var(--button-remove-color);
}

button.disabled {
	cursor:not-allowed;
}

button:hover {
	background-color: var(--button-remove-color-hover);
}
</style>
