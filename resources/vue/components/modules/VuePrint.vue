<template>
	<div class="vue-print" ref="print">
		<div
			class="vue-print__body"
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
			this.clearPaginate();

			for (let point in this.settings.styles) {
				root.querySelectorAll(point).forEach((el) => {
					for (let key in this.settings.styles[point]) {
						el.style[key] = this.settings.styles[point][key];
					}
				});
			}

			this.$nextTick(() => {
				this.paginate();
			});
		},

		paginate() {
			const root = this.$refs.printBody;
			if (!root) return;

			// Получаем реальную высоту контента
			const contentHeightPx = root.scrollHeight; // важно: scrollHeight, а не getBoundingClientRect().height!

			let pageHeightPx = this.getPageHeightPx();

			// Сколько полных страниц?
			const pageCount = Math.ceil(contentHeightPx / pageHeightPx);

			// Добавляем линии после каждой страницы (кроме последней)
			for (let i = 1; i < pageCount; i++) {
				const line = document.createElement("div");
				line.className = "vue-print__body-line";
				line.style.pointerEvents = `none`;
				line.style.position = `absolute`;
				line.style.left = `0`;
				line.style.top = `${i * pageHeightPx}px`;
				line.style.zIndex = `10`;
				line.style.width = `100%`;
				line.style.height = `1px`;
				line.style.border = `0.5px dashed rgba(0, 0, 0, 0.25)`;

				root.appendChild(line);
			}
		},

		clearPaginate() {
			const root = this.$refs.printBody;
			if (!root) return;

			root.querySelectorAll(".vue-print__body-line").forEach((el) => el.remove());
		},

		getPageHeightPx() {
			const mmToPx = (mm) => mm * 3.7795275591;

			const sizes = {
				A3: { portrait: 420, landscape: 297 },
				A4: { portrait: 297, landscape: 210 },
				A5: { portrait: 210, landscape: 148 },
			};

			const mm = sizes[this.settings.template ?? "A4"][this.settings.direction ?? "portrait"];

			return mmToPx(mm); // минус padding
		},

		print() {
			const root = this.$refs.printBody;
			if (!root) return;

			this.clearPaginate();
			window.print();
			this.paginate();
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
.vue-print {
	margin: auto;
	width: 100%;
}

.vue-print * {
	font-family: "Times New Roman", Times, serif;
}

.vue-print__body {
	position: relative;
	border: 1px solid rgba(0, 0, 0, 0.25);
	padding: 10mm;

	box-sizing: border-box;
	margin: 0 auto;
	width: 100%;
}

.vue-print__body.portrait.A3 {
	max-width: 297mm;
	min-height: 420mm;
}

.vue-print__body.landscape.A3 {
	max-width: 420mm;
	min-height: 297mm;
}

.vue-print__body.portrait.A4 {
	max-width: 210mm;
	min-height: 297mm;
}

.vue-print__body.landscape.A4 {
	max-width: 297mm;
	min-height: 210mm;
}

.vue-print__body.portrait.A5 {
	max-width: 148mm;
	min-height: 210mm;
}

.vue-print__body.landscape.A5 {
	max-width: 210mm;
	min-height: 148mm;
}

@media screen and (width <= 500px) {
	.vue-print__body {
		border: 0px solid rgba(0, 0, 0, 0.25);
		padding: 0;

		width: auto;
	}
}

@media print {
	.vue-print__body {
		border: none;
		padding: 0px 10mm 10mm 10mm;

		min-height: 0px !important;
	}

	.vue-print__body-line {
		display: none !important;
		border: none !important;
	}
}
</style>
