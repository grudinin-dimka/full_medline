<template>
	<div class="vue-table__pagination" v-if="getPagesCount > 1">
		<!-- Кнопки пагинации в начале -->
		<div class="vue-table__buttons-left">
			<div
				class="vue-table__buttons-item vue-table__buttons-item--first"
				@click="$emit('changePage', 1)"
			>
				<<
			</div>
			<div
				class="vue-table__buttons-item vue-table__buttons-item--previous"
				@click="$emit('changePage', settings.pages.current - 1)"
			>
				<
			</div>
		</div>
		<!-- Страницы -->
		<div
			v-for="page in getPages"
			:key="page"
			class="vue-table__buttons-item"
			:class="{ active: page.link === settings.pages.current }"
			@click="$emit('changePage', page.link)"
		>
			{{ page.link }}
		</div>
		<!-- Кнопки пагинации в конце -->
		<div class="vue-table__buttons-right">
			<div
				class="vue-table__buttons-item vue-table__buttons-item--next"
				@click="$emit('changePage', settings.pages.current + 1)"
			>
				>
			</div>
			<div
				class="vue-table__buttons-item vue-table__buttons-item--last"
				@click="$emit('changePage', getPagesCount)"
			>
				>>
			</div>
		</div>
	</div>
</template>

<script>
export default {
	props: {
		/*
			settings: {
				pages: {
					range: 5,
					current: 1,
				},
				elements: {
					range: 10,
				},
			},
		*/
		settings: {
			type: Object,
			required: true,
		},
		arrayLength: {
			type: Number,
			required: true,
		},
	},
	watch: {
		getPagesCount: {
			handler(newQuestion) {
				if (newQuestion < this.settings.pages.current) {
					this.$emit("changePage", newQuestion);
				}
			},

			// принудительное выполнение немедленного обратного вызова
			immediate: true,
		},
	},
	computed: {
		/* Общее количество страниц */
		getPagesCount() {
			return Math.ceil(this.arrayLength / this.settings.elements.range);
		},
		/* Получение массива страниц */
		getPages() {
			let pages = [];

			// Пагинация, если всего страниц меньше или равно диапазону пагинации
			if (this.getPagesCount <= this.settings.pages.range) {
				for (let i = 1; i <= this.getPagesCount; i++) {
					pages.push({
						number: i,
						link: i,
					});
				}

				return pages;
			} else if (this.getPagesCount > this.settings.pages.range) {
				if (this.settings.pages.current == this.getPagesCount) {
					for (
						let i = this.settings.pages.current + 1 - this.settings.pages.range;
						i <= this.settings.pages.current;
						i++
					) {
						pages.push({
							number: i,
							link: i,
						});
					}

					return pages;
				}

				// Пагинация, если текущая страница выше диапазона пагинации
				if (this.settings.pages.current >= this.settings.pages.range) {
					for (
						let i = this.settings.pages.current + 1 - this.settings.pages.range;
						i <= this.settings.pages.current;
						i++
					) {
						pages.push({
							number: i + 1,
							link: i + 1,
						});
					}

					return pages;
				}

				// Если текущая страница не выше диапазона пагинации
				for (let i = 1; i <= this.settings.pages.range; i++) {
					pages.push({
						number: i,
						link: i,
					});
				}

				return pages;
			} else {
				console.log("Ничего не получилось.");
			}
		},
	},
	methods: {},
};
</script>

<style scoped>
svg {
	cursor: pointer;
	fill: var(--table-svg-fill);
}

svg:hover {
	fill: var(--table-svg-fill-hover);
}

.vue-table__pagination {
	display: flex;
	gap: var(--table-pagination-gap);
	justify-content: center;

	padding: var(--table-pagination-padding);
}

.vue-table__buttons-item {
	user-select: none;
	cursor: pointer;
	display: flex;
	justify-content: center;
	align-items: center;

	border-radius: var(--table-pagination-button-border-radius);
	border: var(--table-pagination-button-border);

	font-size: var(--table-pagination-button-font-size);
	height: var(--table-pagination-button-height);
	width: var(--table-pagination-button-width);

	background-color: var(--table-pagination-button-background-color);
	color: var(--table-pagination-button-color);

	text-align: center;

	transition: all 0.2s;
}

.vue-table__buttons-item:is(:hover, .active) {
	border: var(--table-pagination-button-border-hover);
	background-color: var(--table-pagination-button-background-color-hover);
}

:is(.vue-table__buttons-left, .vue-table__buttons-right) {
	display: flex;
	gap: 10px;
}

:is(.vue-table__buttons-left, .vue-table__buttons-right) > .vue-table__buttons-item {
	display: flex;
	justify-content: center;
	align-items: center;
}
</style>
