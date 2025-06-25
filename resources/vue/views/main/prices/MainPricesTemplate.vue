<template>
	<info-bar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link to="/prices">Цены</router-link>
		<span class="link-arrow"> / </span>
		<router-link
			:to="`/prices/${$route.params.city}/${$route.params.street}/${$route.params.house}`"
		>
			<load-text :isLoading="loading.loader.prices"> Загрузка... </load-text>
			<span class="address-name" v-if="loading.sections.prices">{{ filters.address.name }}</span>
		</router-link>
	</info-bar>

	<Block>
		<template v-if="loading.sections.prices">
			<div class="filter_blocks">
				<VueInput v-model="filters.name" :type="'search'" :placeholder="'Введите услугу'" />

				<!-- Фильтры -->
				<div class="filter_blocks-item">
					<VueFilter
						:filter="filters.category"
						:list="categories"
						@changeFilterStatus="changeFilterStatus"
						@selectItemChild="changeSelectedItemChild"
						@selectItemParent="changeSelectedItemParent"
						@selectAll="filters.category.all = !filters.category.all"
					>
						<template #title>
							<svg
								xmlns="http://www.w3.org/2000/svg"
								height="24px"
								viewBox="0 -960 960 960"
								width="24px"
								fill="black"
							>
								<path
									d="m260-520 220-360 220 360H260ZM700-80q-75 0-127.5-52.5T520-260q0-75 52.5-127.5T700-440q75 0 127.5 52.5T880-260q0 75-52.5 127.5T700-80Zm-580-20v-320h320v320H120Zm580-60q42 0 71-29t29-71q0-42-29-71t-71-29q-42 0-71 29t-29 71q0 42 29 71t71 29Zm-500-20h160v-160H200v160Zm202-420h156l-78-126-78 126Zm78 0ZM360-340Zm340 80Z"
								/>
							</svg>
							Категории
							<span v-if="filters.category.all"> (все) </span>
							<span v-if="filters.category.selected.length > 0 && !filters.category.all">
								({{ filters.category.selected.length }})
							</span>
						</template>
					</VueFilter>
				</div>

				<!-- Субъекты фильтров -->
				<div
					class="filter_blocks-item"
					v-if="filters.category.selected.length > 0 || filters.name"
				>
					<div class="filter__subject" @click="filters.name = ''" v-if="filters.name">
						<div class="prices__address-title">
							<svg
								xmlns="http://www.w3.org/2000/svg"
								height="22px"
								viewBox="0 -960 960 960"
								width="22px"
								fill="white"
							>
								<path
									d="M200-200h57l391-391-57-57-391 391v57Zm-80 80v-170l528-527q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L290-120H120Zm640-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z"
								/>
							</svg>
							{{ filters.name }}
						</div>
						<div class="close">
							<svg
								xmlns="http://www.w3.org/2000/svg"
								width="16px"
								height="16px"
								viewBox="0 -960 960 960"
								fill="white"
							>
								<path
									d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"
								/>
							</svg>
						</div>
					</div>
					<div
						class="filter__subject"
						:class="{
							disabled: filters.category.all,
						}"
						v-for="filter in getCategorySelected"
						:key="filter.id"
						@click="changeSelectedItemChild(filter, 'category')"
					>
						<div class="prices__address-title">
							<svg
								xmlns="http://www.w3.org/2000/svg"
								height="24px"
								viewBox="0 -960 960 960"
								width="24px"
								fill="white"
							>
								<path
									d="m260-520 220-360 220 360H260ZM700-80q-75 0-127.5-52.5T520-260q0-75 52.5-127.5T700-440q75 0 127.5 52.5T880-260q0 75-52.5 127.5T700-80Zm-580-20v-320h320v320H120Zm580-60q42 0 71-29t29-71q0-42-29-71t-71-29q-42 0-71 29t-29 71q0 42 29 71t71 29Zm-500-20h160v-160H200v160Zm202-420h156l-78-126-78 126Zm78 0ZM360-340Zm340 80Z"
								/>
							</svg>
							{{ filter.name }}
						</div>
						<div class="close">
							<svg
								xmlns="http://www.w3.org/2000/svg"
								width="16px"
								height="16px"
								viewBox="0 -960 960 960"
								fill="white"
							>
								<path
									d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"
								/>
							</svg>
						</div>
					</div>
					<div class="filter__subject" @click="clearSelectedItems(['category'])">
						<div class="prices__address-title">Сбросить фильтры</div>
						<div class="close">
							<svg
								xmlns="http://www.w3.org/2000/svg"
								width="16px"
								height="16px"
								viewBox="0 -960 960 960"
								fill="black"
							>
								<path
									d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"
								/>
							</svg>
						</div>
					</div>
				</div>
			</div>

			<!-- Цены -->
			<div class="prices">
				<div class="prices__address">
					<div
						class="prices__category"
						:class="{
							disabled: category.id === null,
						}"
						v-for="category in getCurrentCategories"
						v-if="getCurrentCategories.length > 0"
						:key="category.id"
					>
						<div class="prices__address-title">
							<svg
								xmlns="http://www.w3.org/2000/svg"
								height="30px"
								viewBox="0 -960 960 960"
								width="30px"
							>
								<path
									d="m260-520 220-360 220 360H260ZM700-80q-75 0-127.5-52.5T520-260q0-75 52.5-127.5T700-440q75 0 127.5 52.5T880-260q0 75-52.5 127.5T700-80Zm-580-20v-320h320v320H120Zm580-60q42 0 71-29t29-71q0-42-29-71t-71-29q-42 0-71 29t-29 71q0 42 29 71t71 29Zm-500-20h160v-160H200v160Zm202-420h156l-78-126-78 126Zm78 0ZM360-340Zm340 80Z"
								/>
							</svg>
							{{ category.name }}
						</div>
						<ol class="prices__values" v-if="category.id !== null">
							<li v-for="price in getCurrentPrices(category.id)" :key="price.id">
								<div class="prices__values-item">
									<div class="prices__values-name">{{ price.name }}</div>
									<div class="prices__values-price">{{ formatPrice(price.price) }}</div>
									<div class="prices__values-valute">руб.</div>
								</div>
							</li>
						</ol>
						<ol class="prices__values" v-if="category.id === null">
							<li>
								<div class="prices__values-item">
									<div class="prices__values-name">Отсутствует...</div>
									<div class="prices__values-price"></div>
									<div class="prices__values-valute"></div>
								</div>
							</li>
						</ol>
					</div>
					<div class="prices__categories--none" v-else>Ничего нет...</div>
				</div>
			</div>
		</template>

		<loader-child
			:isLoading="loading.loader.prices"
			@loaderChildAfterLeave="loaderChildAfterLeave"
		/>
	</Block>
</template>

<script>
import InfoBar from "../../../components/ui/main/InfoBar.vue";
import LoaderChild from "../../../components/modules/LoaderChild.vue";
import Block from "../../../components/ui/main/Block.vue";
import LoadText from "../../../components/ui/main/LoadText.vue";

import VueFilter from "../../../components/modules/filter/VueFilter.vue";
import VueInput from "../../../components/modules/input/VueInput.vue";

import Empty from "../../../components/modules/Empty.vue";

import api from "../../../services/api";
import sorted from "../../../services/sorted.js";

export default {
	components: {
		InfoBar,
		LoaderChild,
		Block,
		LoadText,

		VueFilter,
		VueInput,

		Empty,
	},
	data() {
		return {
			loading: {
				loader: {
					prices: true,
				},
				sections: {
					prices: false,
				},
			},
			filters: {
				name: "",
				address: "Тестовый",
				category: {
					status: false,
					all: true,
					name: "category",
					key: "categoryId",
					selected: [],
				},
			},
			prices: [],
			categories: [],
			categoriesList: [],
		};
	},
	computed: {
		/* Получение текущих категорий */
		getCurrentCategories() {
			let currentCategories = this.categoriesList;

			currentCategories = currentCategories.filter((category) => {
				let bool = false;

				let currentPrices = this.prices.filter((price) => {
					if (price.categoryId == category.id) {
						return price;
					}
				});

				currentPrices.forEach((price) => {
					if (price.name.toLowerCase().includes(this.filters.name.toLowerCase())) {
						bool = true;
					}
				});

				return bool;
			});

			if (!this.filters.category.all) {
				let resultCategories = [];
				this.filters.category.selected.forEach((item) => {
					let bool = false;
					let categoryName = item.name;
					let selectedCategory = null;
					currentCategories.forEach((category) => {
						if (item.name === category.name) {
							bool = true;
							selectedCategory = category;
						}
					});
					if (bool) {
						resultCategories.push(selectedCategory);
					} else {
						resultCategories.push({
							id: null,
							name: categoryName,
						});
					}
				});
				return resultCategories;
			}

			sorted.sortByName("up", currentCategories);

			return currentCategories;
		},

		/* Вывод выбранных категорий */
		getCategorySelected() {
			let selected = this.filters.category.selected;
			sorted.sortByName("up", this.filters.category.selected);

			return selected;
		},

		getActiveFilters() {
			return this.filters.category.selected.length;
		},
	},
	methods: {
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                   ЗАГРУЗЧИК                       |*/
		/* |___________________________________________________|*/
		/* После скрытия элементы */
		loaderChildAfterLeave() {
			this.loading.sections.prices = true;
		},
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                      ЦЕНЫ                         |*/
		/* |___________________________________________________|*/
		/* Получение текущих цен */
		getCurrentPrices(categoryId) {
			let currentPrices = [];

			currentPrices = this.prices.filter((price) => {
				if (price.categoryId == categoryId) {
					return price;
				}
			});

			if (this.filters.name !== "") {
				currentPrices = currentPrices.filter((price) => {
					if (price.name.toLowerCase().includes(this.filters.name.toLowerCase())) {
						return price;
					}
				});
			}

			sorted.sortStringByKey("up", currentPrices, "name");

			return currentPrices;
		},
		/* После скрытия элементы */
		changeFilterStatus(status, name) {
			this.filters[name].status = status;
		},
		/* Изменение выбранного дочернего элемента */
		changeSelectedItemChild(selectedItem, name) {
			let status = false;

			// Проверяем есть ли выбранный элемент в массиве
			for (let i = 0; i < this.filters[name].selected.length; i++) {
				if (selectedItem.name == this.filters[name].selected[i].name) {
					status = true;
					break;
				}
			}

			// Если выбранный элемент есть в массиве
			if (status) {
				this.filters[name].selected = this.filters[name].selected.filter((item) => {
					return item.name !== selectedItem.name;
				});
			} else {
				this.filters[name].selected.push(selectedItem);
			}
		},
		/* Изменение выбранного родительского элемента */
		changeSelectedItemParent(selectedItem, name) {
			let childItems = this.getChildItemFromParent(selectedItem);

			let status = {
				hasOnce: false,
				hasAll: true,
			};

			for (let i = 0; i < childItems.length; i++) {
				if (this.filters[name].selected.some((item) => item.name == childItems[i].name)) {
					status.hasOnce = true;
				} else {
					status.hasAll = false;
				}
			}

			// Проверка на наличие всех childItems в массиве
			if (status.hasAll) {
				this.filters[name].selected = this.filters[name].selected.filter((item) => {
					if (childItems.some((childItem) => childItem.name == item.name)) {
						return false;
					} else {
						return true;
					}
				});
			} else {
				// Проверка на наличие хотя бы одного childItem в массиве
				if (status.hasOnce) {
					for (let i = 0; i < childItems.length; i++) {
						if (this.filters[name].selected.some((item) => item.name == childItems[i].name)) {
							continue;
						}

						this.filters[name].selected.push(childItems[i]);
					}
				} else {
					for (let i = 0; i < childItems.length; i++) {
						this.filters[name].selected.push(childItems[i]);
					}
				}
			}
		},
		/* Получение дочерних элементов */
		getChildItemFromParent(parent) {
			let childs = [];

			if (parent.children && parent.children.length > 0) {
				for (let i = 0; i < parent.children.length; i++) {
					if (parent.children[i].children && parent.children[i].children.length > 0) {
						let innerChild = this.getChildItemFromParent(parent.children[i]);

						for (let j = 0; j < innerChild.length; j++) {
							childs.push(innerChild[j]);
						}
					} else {
						childs.push(parent.children[i]);
					}
				}
			}

			return childs;
		},
		changeSelectedItems(name, array) {
			this.filters[name].selected = array;
			this.filters[name].status = false;
		},
		clearSelectedItems(items) {
			for (let key in items) {
				this.filters[items[key]].selected = [];
				this.filters[items[key]].all = true;
			}

			this.filters.name = "";
		},
		formatPrice(price) {
			return price.toLocaleString("ru-RU");
		},
	},
	mounted() {
		api({
			method: "post",
			url: `${this.$store.getters.urlApi}` + `get-prices-template`,
			headers: {
				Accept: "application/json",
			},
			data: {
				city: this.$route.params.city,
				street: this.$route.params.street,
				house: this.$route.params.house,
			},
		})
			.then((response) => {
				if (!response) return;

				this.filters.address = response.data.result.address;
				this.categories = response.data.result.categories;
				this.categoriesList = response.data.result.categoriesList;
				this.prices = response.data.result.prices;
			})
			.catch((error) => {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			})
			.finally(() => {
				this.loading.loader.prices = false;
			});
	},
};
</script>

<style scoped>
.filter_blocks {
	display: flex;
	flex-direction: column;
	gap: 10px;

	width: 1350px;
	animation: show 0.5s ease-out;
}

.filter_blocks > .container-input {
	position: relative;
	display: grid;
	grid-template-columns: 1fr auto;
}

.filter_blocks > .container-input > input {
	box-sizing: border-box;

	padding: 15px;
	border: var(--input-border);
	border-radius: var(--input-border-radius);
	outline: none;

	height: 58px;

	font-size: 1.125rem;

	transition: all 0.2s;
	caret-color: var(--input-border-color-active);
}

.filter_blocks > .container-input > input:focus {
	border: var(--input-border-focus);
}

.filter_blocks > .container-input > button {
	position: absolute;
	top: 0px;
	right: 5px;
	cursor: pointer;
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 5px;

	height: 58px;

	border-radius: 10px;
	border: 0px solid white;

	font-size: 1.125rem;
	color: white;
	background-color: rgba(0, 0, 0, 0);
}

.filter_blocks > .container-input > button > svg {
	fill: rgba(0, 0, 0, 1);
}

.filter_blocks > .container-input > button > svg:hover {
	fill: rgba(0, 0, 0, 1);
}

.filter_blocks > .filter_blocks-item {
	display: flex;
	flex-wrap: wrap;
	gap: 10px;

	border-radius: 10px;
}

.filter_blocks > .filter_blocks-item > .filter__subject {
	cursor: pointer;
	display: flex;
	align-items: center;
	gap: 5px;

	padding: 5px 10px;
	background-color: var(--button-background-color);
	border-radius: 10px;
	color: white;

	transition: all 0.2s;
}

.filter_blocks > .filter_blocks-item > .filter__subject:hover {
	background-color: var(--button-background-color-hover);
}

.filter_blocks > .filter_blocks-item > .filter__subject:last-child {
	color: black;
	background-color: rgb(240, 240, 240);
}

.filter_blocks > .filter_blocks-item > .filter__subject:last-child:hover {
	background-color: rgb(230, 230, 230);
}

.filter_blocks > .filter_blocks-item > .filter__subject > .prices__address-title {
	display: flex;
	justify-content: center;
	align-items: center;
	gap: 5px;
}

.filter_blocks > .filter_blocks-item > .filter__subject:first-child {
	padding: 5px 10px;
	border-radius: 10px;
}

.filter_blocks > .filter_blocks-item > .filter__subject > .close {
	cursor: pointer;
	display: flex;
	align-items: center;
	justify-content: center;

	padding: 5px;
	border-radius: 50px;
}

.filter_blocks > .filter_blocks-item > .filter__subject.disabled {
	background-color: rgb(150, 150, 150);
	color: white;
}

/* Цены */
.prices {
	width: 1350px;
	display: grid;
	grid-template-columns: repeat(1, 1fr);
}

.prices__address {
	display: flex;
	flex-direction: column;
	gap: 20px;
}

.prices__address > .prices__address-title {
	display: flex;
	justify-content: center;
	align-items: center;

	margin-top: 30px;

	font-size: 1.5rem;
	font-weight: 600;
	color: black;

	animation: show-bottom-to-top-15 0.5s ease-in-out;
}

.prices__address:first-child > .prices__address-title {
	margin-top: 10px;
}

.prices__address > .prices__address-title > svg {
	fill: var(--button-default-color);
}

.prices__category {
	display: flex;
	flex-direction: column;
	gap: 0px;

	border-radius: 20px;

	animation: show-bottom-to-top-15 0.5s ease-in-out;
}

.prices__categories--none {
	display: flex;
	justify-content: center;

	padding: 20px;

	font-size: 1.125rem;
	color: rgb(150, 150, 150);

	animation: show-bottom-to-top-15 0.5s ease-in-out;
}

.prices__category > .prices__address-title {
	display: flex;
	align-items: center;
	gap: 5px;

	font-size: 1.25rem;
	font-weight: 600;
	color: var(--primary-color);
	padding: 10px 0px;

	border-radius: 20px 20px 0px 0px;

	border: 0px solid #2d9aa7;
}

.prices__category > .prices__address-title > svg {
	fill: var(--primary-color);
}

.prices__category.disabled > .prices__address-title {
	color: rgb(150, 150, 150);
}

.prices__category.disabled > .prices__address-title > svg {
	fill: rgb(150, 150, 150);
}

.prices__category > .prices__values {
	padding: 0px 0px 0px 0px;
	margin: 0px;

	font-size: 1.125rem;
}

.prices__values > li {
	list-style: decimal-leading-zero;

	padding: 20px 0px;
	margin: 0px 0px 0px 70px;

	border-top: 0px;
	border-right: 0px;
	border-bottom: 1px;
	border-left: 0px;
	border-style: solid;
	border-color: #c3c3c3;

	transition: all 0.2s;
}

.prices__values-item {
	display: grid;
	grid-template-columns: 1fr 100px 50px;
	gap: 5px;
}

.prices__values-price {
	font-family: "Roboto", sans-serif;
	text-align: right;
	color: var(--primary-color);
}

@media screen and (width <= 1450px) {
	.prices {
		width: auto;
	}

	.filter_blocks {
		width: 100%;
	}
}

@media screen and (width <= 850px) {
	.prices__values > li {
		margin: 0px 0px 0px 30px;
	}

	.prices__values-item {
		grid-template-columns: 1fr 50px 50px;
	}

	.prices__values-name {
		text-overflow: ellipsis;
		word-break: break-all;
	}
}
</style>
