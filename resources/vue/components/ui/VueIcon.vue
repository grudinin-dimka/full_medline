<template>
	<svg
		xmlns="http://www.w3.org/2000/svg"
		ref="svg"
		:height="height"
		:width="width"
		:viewBox="getIconViewBox(name)"
		:style="{ '--fill': `${fill}`, '--fill-hover': `${hover || fill}` }"
	>
		<path v-for="icon in getIcon(name)" v-bind="icon" />
	</svg>
</template>

<script>
export default {
	name: "Icons",

	inject: ["icons"],

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
		transition: {
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
			default: "pointer",
		},
		rotate: {
			type: [Number, null],
			default: null,
		},
	},
	methods: {
		getIcon(name) {
			try {
				let icon = this.icons.find((item) => item.name == name);

				return icon.path ?? "";
			} catch (error) {
				console.error("Путь к иконке " + name + " не найден.");
			}
		},

		getIconPath(name) {
			try {
				let icon = this.icons.find((item) => item.name == name);

				return icon.path ?? "";
			} catch (error) {
				console.error("Путь к иконке " + name + " не найден.");
			}
		},

		getIconViewBox(name) {
			try {
				let icon = this.icons.find((item) => item.name == name);

				return icon?.viewBox ?? "0 0 24 24"; // значение по умолчанию
			} catch (error) {
				console.error("Не удалось найти разметку иконки " + name);
			}
		},
	},
	mounted() {
		this.$refs.svg.style.cursor = `${this.cursor}`;

		if (this.rotate) {
			this.$refs.svg.style.transform = `rotate(${this.rotate}deg)`;
		}

		if (this.transition) {
			this.$refs.svg.style.transition = `${this.transition}`;
		}
	},
};
</script>

<style scoped>
svg {
	fill: var(--fill);
}

svg:hover {
	fill: var(--fill-hover);

	transition: all 0.2s ease;
}
</style>
