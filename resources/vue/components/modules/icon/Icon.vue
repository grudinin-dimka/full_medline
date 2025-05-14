<template>
	<svg
		xmlns="http://www.w3.org/2000/svg"
		ref="svg"
		:height="height"
		:width="width"
		:viewBox="getIconViewBox(name)"
		:style="{ '--fill': `${fill}`, '--fill-hover': `${hover || fill}` }"
	>
		<path :d="getIconPath(name)" />
	</svg>
</template>

<script>
import Icons from "./icons.json";

export default {
	name: "Icons",

	props: {
		name: {
			type: String,
			required: true,
			default: "",
		},
		fill: {
			type: String,
			default: "black",
		},
		hover: {
			type: String,
			default: "",
		},
		width: {
			type: String,
			default: "26px",
		},
		height: {
			type: String,
			default: "26px",
		},
		cursor: {
			type: String,
			default: "auto",
		},
		rotate: {
			type: Number,
			default: 0,
		},
	},
	data() {
		return {
			icons: Icons,
		};
	},
	methods: {
		getIconPath(name) {
			let icon = this.icons.find((item) => item.name == name);

			return icon.path ?? "";
		},

		getIconViewBox(name) {
			let icon = this.icons.find((item) => item.name == name);
			return icon?.viewBox ?? "0 0 24 24"; // значение по умолчанию
		},
	},
	mounted() {
		this.$refs.svg.style.cursor = `${this.cursor}`;
		this.$refs.svg.style.transform = `rotate(${this.rotate}deg)`;
	},
};
</script>

<style scoped>
svg {
	fill: var(--fill);
}

svg:hover {
	fill: var(--fill-hover);
}
</style>
