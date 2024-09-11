<template>
	<svg viewBox="0 0 232 176" xmlns="http://www.w3.org/2000/svg" :width="width" :height="height">
		<path
			d="M105.776 171C102.88 171 100.451 170.018 98.4919 168.053C96.5324 166.089 95.5526 163.654 95.5526 160.75V99.25L36.2552 23.4C33.6993 19.9833 33.3159 16.3958 35.1051 12.6375C36.8942 8.87917 40.0039 7 44.4342 7H187.566C191.996 7 195.106 8.87917 196.895 12.6375C198.684 16.3958 198.301 19.9833 195.745 23.4L136.447 99.25V160.75C136.447 163.654 135.468 166.089 133.508 168.053C131.549 170.018 129.12 171 126.224 171H105.776ZM116 92.075L166.607 27.5H65.3927L116 92.075Z"
		/>
	</svg>
</template>

<script>
export default {
	props: {
		width: {
			type: Number,
			default: 20,
		},
		height: {
			type: Number,
			default: 20,
		},
	},
};
</script>

<style scoped>
svg {
	cursor: pointer;
	fill: #297e87;
}

svg:hover {
	cursor: pointer;
	fill: white;
}
</style>
