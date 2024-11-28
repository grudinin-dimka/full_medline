<template>
	<svg
		:width="width"
		:height="height"
		viewBox="0 0 28 22"
		xmlns="http://www.w3.org/2000/svg"
		:class="{
			edit: type == 'edit',
			create: type == 'create',
		}"
	>
		<path
			d="M2.8 22C2.03 22 1.37083 21.7307 0.8225 21.1922C0.274167 20.6536 0 20.0063 0 19.25V2.75C0 1.99375 0.274167 1.34635 0.8225 0.807813C1.37083 0.269271 2.03 0 2.8 0H25.2C25.97 0 26.6292 0.269271 27.1775 0.807813C27.7258 1.34635 28 1.99375 28 2.75V19.25C28 20.0063 27.7258 20.6536 27.1775 21.1922C26.6292 21.7307 25.97 22 25.2 22H2.8ZM14 12.375L2.8 5.5V19.25H25.2V5.5L14 12.375ZM14 9.625L25.2 2.75H2.8L14 9.625ZM2.8 5.5V2.75V19.25V5.5Z"
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
	fill: black;
}

svg:hover {
	fill: black;
}

svg.edit {
	fill: var(--primary-color);
}

svg.create {
	fill: #44a533;
}
</style>
