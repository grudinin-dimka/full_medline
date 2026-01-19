<template>
	<div class="print" ref="print">
		<div
			class="print__body"
			ref="printBody"
			v-html="sanitizeHtml(modelValue)"
			:class="{ [settings.direction]: settings.direction, [settings.template]: settings.template }"
		></div>
	</div>
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
			const root = this.$refs.printBody;
			if (!root) return;

			// Очистим старые линии (если пересчитываем)
			root.querySelectorAll(".page-divider").forEach((el) => el.remove());

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
	margin: auto;
	width: 100%;
}

.print * {
	font-family: "Times New Roman", Times, serif;
}

.print__body {
	border: 1px solid rgba(0, 0, 0, 0.5);
	padding: 10mm;

	margin: 0 auto;
	width: 100%;
}

.print__body.portrait.A3 {
	max-width: 277mm;
	min-height: 400mm;
}

.print__body.landscape.A3 {
	max-width: 400mm;
	min-height: 277mm;
}

.print__body.portrait.A4 {
	max-width: 190mm;
	min-height: 277mm;
}

.print__body.landscape.A4 {
	max-width: 277mm;
	min-height: 190mm;
}

.print__body.portrait.A5 {
	max-width: 128mm;
	min-height: 190mm;
}

.print__body.landscape.A5 {
	max-width: 190mm;
	min-height: 128mm;
}

.print__body-line {
	position: absolute;
	top: 30px;
	left: 0px;

	width: 100%;
	height: 5px;

	background-color: black;
}

@media print {
	.print__body {
		border: none;
		padding: 0;
	}

	.print__controls {
		display: none;
	}
}
</style>
