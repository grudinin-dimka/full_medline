<template>
	<svg
		:width="width"
		:height="height"
		:class="type"
		xmlns="http://www.w3.org/2000/svg"
		viewBox="0 -960 960 960"
	>
		<path d="M440-440H200v-80h240v-240h80v240h240v80H520v240h-80v-240Z" />
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
		type: {
			type: String,
			default: "edit",
		},
	},
};
</script>

<style scoped>
svg {
	cursor: pointer;
}

svg.edit {
	fill: var(--primary-color);
}

svg.delete {
	fill: #d74949;
}

svg.create {
	fill: rgb(62, 208, 76);
}
</style>
