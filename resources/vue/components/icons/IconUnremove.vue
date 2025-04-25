<template>
	<svg
		:width="width"
		:height="height"
		:class="look"
		xmlns="http://www.w3.org/2000/svg"
		viewBox="0 -960 960 960"
	>
		<path
			d="M440-320h80v-166l64 62 56-56-160-160-160 160 56 56 64-62v166ZM280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520Zm-400 0v520-520Z"
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
		look: {
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

svg.white {
	fill: white;
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
