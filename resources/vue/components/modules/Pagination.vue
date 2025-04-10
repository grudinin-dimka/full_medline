<template>
	<div class="pagination" v-if="getPagesCount > 1">
		<!-- Кнопки пагинации в начале -->
		<div class="buttons-left">
			<div class="item first" @click="$emit('changePage', 1)"><<</div>
			<div class="item previous" @click="$emit('changePage', settings.pages.current - 1)"><</div>
		</div>
		<!-- Страницы -->
		<div
			v-for="page in getPages"
			:key="page"
			class="item"
			:class="{ active: page.link === settings.pages.current }"
			@click="$emit('changePage', page.link)"
		>
			{{ page.link }}
		</div>
		<!-- Кнопки пагинации в конце -->
		<div class="buttons-right">
			<div class="item next" @click="$emit('changePage', settings.pages.current + 1)">></div>
			<div class="item last" @click="$emit('changePage', getPagesCount)">>></div>
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
	fill: var(--primary-color);
}

svg:hover {
	fill: var(--primary-color);
}

.pagination {
	display: flex;
	gap: var(--pagination-gap);
	justify-content: center;

	padding: var(--pagination-padding);
}

.pagination .item {
	user-select: none;
	cursor: pointer;
	display: flex;
	justify-content: center;
	align-items: center;

	border-radius: 100%;
	border: var(--pagination-border);

	font-size: 1rem;
	height: 30px;
	width: 30px;

	background-color: #ffffff;

	text-align: center;

	transition: all 0.2s;
}

.pagination .item:hover {
	border: var(--default-border-active);
	background-color: var(--item-background-color-active);
}

.pagination > .item.active {
	border: var(--default-border-active);
	background-color: var(--item-background-color-active);
}

.pagination > :is(.buttons-left, .buttons-right) {
	display: flex;
	gap: 10px;
}

.pagination > .buttons-right > .item,
.pagination > .buttons-left > .item {
	display: flex;
	justify-content: center;
	align-items: center;
}
</style>
