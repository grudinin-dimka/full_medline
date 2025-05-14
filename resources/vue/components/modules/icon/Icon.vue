<template>
	<svg
		xmlns="http://www.w3.org/2000/svg"
		:height="height"
		:width="width"
		:fill="fill"
		:viewBox="getIconViewBox(name)"
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
		width: {
			type: String,
			default: "26px",
		},
		height: {
			type: String,
			default: "26px",
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
};
</script>

<style scoped></style>
