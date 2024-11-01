<template>
	<svg
		:width="width"
		:height="height"
		viewBox="0 0 20 22"
		xmlns="http://www.w3.org/2000/svg"
		:class="{
			modal: type == 'modal',
		}"
	>
		<path
			d="M2.5 19.5556H6.25V12.2222H13.75V19.5556H17.5V8.55556L10 3.05556L2.5 8.55556V19.5556ZM0 22V7.33333L10 0L20 7.33333V22H11.25V14.6667H8.75V22H0Z"
		/>
	</svg>
</template>

<script>
export default {
	props: {
		width: {
			type: Number,
			default: 30,
		},
		height: {
			type: Number,
			default: 30,
		},
		type: {
			type: String,
			default: "default",
		},
	},
};
</script>

<style scoped>
svg {
	cursor: pointer;
	fill: black;
}

svg:hover {
	fill: black;
}

svg.modal {
	fill: var(--primary-color);
}
</style>
