<template>
	<div
		class="print"
		ref="print"
		v-html="sanitizeHtml(modelValue)"
		:class="{ [settings.direction]: settings.direction, [settings.template]: settings.template }"
	></div>
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
				direction: "portrait",
				template: "A4",
				clear: {
					styles: false,
					spacing: false,
				},
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

			// Очистка html
			if (this.settings.clear) {
				/* Очистка стилей */
				if (this.settings.clear.styles) {
					wrapper.querySelectorAll("[style]").forEach((el) => {
						el.removeAttribute("style");
					});
				}

				/* Очистка пробелов */
				if (this.settings.clear.spacing) {
					wrapper.innerHTML = wrapper.innerHTML.replaceAll("&nbsp;", " ");
				}
			}

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
	width: 100%;

	font-family: "Times New Roman", Times, serif;
}

.print * {
	font-family: "Times New Roman", Times, serif;
}

.print.portrait.A3 {
	max-width: 277mm;
}

.print.landscape.A3 {
	max-width: 400mm;
}

.print.portrait.A4 {
	max-width: 190mm;
}

.print.landscape.A4 {
	max-width: 277mm;
}

.print.portrait.A5 {
	max-width: 128mm;
}

.print.landscape.A5 {
	max-width: 190mm;
}

@media print {
	.print__controls {
		display: none;
	}
}
</style>
