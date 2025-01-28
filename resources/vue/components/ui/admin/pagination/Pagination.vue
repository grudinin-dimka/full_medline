<template>
	<div class="pagination">
		<!-- Кнопки пагинации в начале -->
		<div class="buttons-left">
			<div class="item first" @click="$emit('changePage', 1)">
				<svg
					xmlns="http://www.w3.org/2000/svg"
					height="28px"
					viewBox="0 -960 960 960"
					width="28px"
					fill="#e8eaed"
				>
					<path
						d="M440-240 200-480l240-240 56 56-183 184 183 184-56 56Zm264 0L464-480l240-240 56 56-183 184 183 184-56 56Z"
					/>
				</svg>
			</div>
			<div class="item previous" @click="$emit('changePage', settings.pages.current - 1)">
				<svg
					xmlns="http://www.w3.org/2000/svg"
					height="28px"
					viewBox="0 -960 960 960"
					width="28px"
					fill="black"
				>
					<path d="M560-240 320-480l240-240 56 56-184 184 184 184-56 56Z" />
				</svg>
			</div>
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
			<div class="item next" @click="$emit('changePage', settings.pages.current + 1)">
				<svg
					xmlns="http://www.w3.org/2000/svg"
					height="28px"
					viewBox="0 -960 960 960"
					width="28px"
					fill="black"
				>
					<path d="M504-480 320-664l56-56 240 240-240 240-56-56 184-184Z" />
				</svg>
			</div>
			<div class="item last" @click="$emit('changePage', getPagesCount)">
				<svg
					xmlns="http://www.w3.org/2000/svg"
					height="28px"
					viewBox="0 -960 960 960"
					width="28px"
					fill="#e8eaed"
				>
					<path
						d="M383-480 200-664l56-56 240 240-240 240-56-56 183-184Zm264 0L464-664l56-56 240 240-240 240-56-56 183-184Z"
					/>
				</svg>
			</div>
		</div>
	</div>
</template>

<script>
export default {
	props: {
		settings: {
			type: Object,
			required: true,
		},
		arrayLength: {
			type: Number,
			required: true,
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

				// if (this.settings.pages.current >= this.settings.pages.range) {
				// 	for (
				// 		let i = this.settings.pages.current + 1 - this.settings.pages.range;
				// 		i <= this.settings.pages.current;
				// 		i++
				// 	) {
				// 		pages.push({
				// 			number: i + 1,
				// 			link: i + 1,
				// 		});
				// 	}

				// 	return pages;
				// }

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
};
</script>

<style scoped>
svg {
	cursor: pointer;
	fill: rgba(0, 171, 189, 0.5);
}

svg:hover {
	fill: rgba(0, 171, 189, 1);
}

.pagination {
	display: flex;
	gap: 12.5px;
	justify-content: center;
	padding: 5px;
}

.pagination > .item {
	cursor: pointer;
	display: flex;
	justify-content: center;
	align-items: center;

	color: var(--input-border-color-inactive);
	font-size: 1.275rem;
	width: 20px;
	text-align: center;
}

.pagination > .item:hover,
.pagination > .item.active {
	color: rgba(0, 171, 189, 1);
}

.pagination > .buttons-left {
	display: flex;
	gap: 0px;
}

.pagination > .buttons-right {
	display: flex;
	gap: 0px;
}

.pagination > .buttons-right > .item,
.pagination > .buttons-left > .item {
	display: flex;
	justify-content: center;
	align-items: center;
}
</style>
