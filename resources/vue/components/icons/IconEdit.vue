<template>
	<svg
		:width="width"
		:height="height"
		:class="type"
		xmlns="http://www.w3.org/2000/svg"
		viewBox="0 -960 960 960"
	>
		<path
			d="M200-200h57l391-391-57-57-391 391v57Zm-80 80v-170l528-527q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L290-120H120Zm640-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z"
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
