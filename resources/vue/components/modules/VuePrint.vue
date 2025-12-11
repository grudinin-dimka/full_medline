<template>
	<div class="print" ref="print" v-html="sanitizeHtml(modelValue)"></div>
</template>

<script>
export default {
	name: "VuePrint",
	props: {
		modelValue: {
			type: String,
			default: "",
		},

		settings: {
			type: Object,
			default: {
				width: "750px",
				styles: {
					"*": { fontSize: "14px", marginBottom: "10px", lineHeight: "20px" },
				},
			},
		},
	},
	watch: {
		modelValue() {
			this.$nextTick(() => {
				this.setStyle();
			});
		},
		settings: {
			handler() {
				this.$nextTick(() => {
					this.setStyle();
				});
			},
			deep: true,
		},
	},
	methods: {
		sanitizeHtml(html) {
			let wrapper = document.createElement("div");
			wrapper.innerHTML = html;

			wrapper.querySelectorAll("[style]").forEach((el) => {
				el.removeAttribute("style");
			});

			wrapper.innerHTML = wrapper.innerHTML.replaceAll("&nbsp;", " ");

			return wrapper.innerHTML;
		},

		setStyle() {
			const root = this.$refs.print;

			root.style.maxWidth = this.settings.width;

			for (let point in this.settings.styles) {
				root.querySelectorAll(point).forEach((el) => {
					for (let key in this.settings.styles[point]) {
						el.style[key] = this.settings.styles[point][key];
					}
				});
			}
		},
	},
	mounted() {
		this.$nextTick(() => {
			this.setStyle();
		});
	},
};
</script>

<style scoped>
.print {
	margin: 0 auto;

	font-family: "Times New Roman", Times, serif;
}

.print * {
	font-family: "Times New Roman", Times, serif;
}

@media print {
	.print__controls {
		display: none;
	}
}
</style>
