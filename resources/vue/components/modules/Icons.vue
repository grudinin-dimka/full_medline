<script>
import Icons from "../../assets/json/Icons.json";

export default {
	name: "Icons",

	props: {
		name: String,
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
			for (let i = 0; i < this.icons.length; i++) {
				if (this.icons[i].name == name) {
					return this.icons[i].path;
				}
			}

			return this.icons[0].path;
		},
	},
};
</script>

<template>
	<svg
		class="icon"
		xmlns="http://www.w3.org/2000/svg"
		:height="height"
		:width="width"
		viewBox="0 -960 960 960"
	>
		<path :d="getIconPath(name)" />
	</svg>
</template>