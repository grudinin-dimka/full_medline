<template>
	<info-bar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link to="/prices">Цены</router-link>
	</info-bar>

	<Block :minHeight="600">
		<template v-if="loading.sections.prices">
			<div class="filter-blocks">
				<!-- Поле ввода -->
				<div class="container-input">
					<input type="text" placeholder="Введите услугу" v-model="filters.name" />
					<button class="clear" @click="filters.name = ''" v-if="filters.name">
						<svg
							xmlns="http://www.w3.org/2000/svg"
							height="20px"
							viewBox="0 -960 960 960"
							width="20px"
							fill="black"
						>
							<path
								d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"
							/>
						</svg>
					</button>
				</div>

				<!-- Фильтры -->
				<div class="container-filters">
					<Filter
						:filter="filters.address"
						:list="addresses"
						@changeFilterStatus="changeFilterStatus"
						@selectItemChild="changeSelectedItemChild"
						@selectItemParent="changeSelectedItemParent"
						@selectAll="filters.address.all = !filters.address.all"
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
									d="M240-200h120v-240h240v240h120v-360L480-740 240-560v360Zm-80 80v-480l320-240 320 240v480H520v-240h-80v240H160Zm320-350Z"
								/>
							</svg>
							Адрес клиники
							<span v-if="filters.address.all"> (все) </span>
							<span v-if="filters.address.selected.length > 0 && !filters.address.all">
								({{ filters.address.selected.length }})
							</span>
						</template>
					</Filter>
					<Filter
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
					</Filter>
				</div>

				<!-- Субъекты фильтров -->
				<div
					class="container-filters"
					v-if="
						filters.address.selected.length > 0 ||
						filters.category.selected.length > 0 ||
						filters.name
					"
				>
					<div class="filter-subject" @click="filters.name = ''" v-if="filters.name">
						<div class="title">
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
						class="filter-subject"
						:class="{
							disabled: filters.address.all,
						}"
						v-for="filter in getAddressSelected"
						:key="filter.id"
						@click="changeSelectedItemChild(filter, 'address')"
					>
						<div class="title">
							<svg
								xmlns="http://www.w3.org/2000/svg"
								height="24px"
								viewBox="0 -960 960 960"
								width="24px"
								fill="white"
							>
								<path
									d="M240-200h120v-240h240v240h120v-360L480-740 240-560v360Zm-80 80v-480l320-240 320 240v480H520v-240h-80v240H160Zm320-350Z"
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
					<div
						class="filter-subject"
						:class="{
							disabled: filters.category.all,
						}"
						v-for="filter in getCategorySelected"
						:key="filter.id"
						@click="changeSelectedItemChild(filter, 'category')"
					>
						<div class="title">
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
					<div class="filter-subject" @click="clearSelectedItems(['address', 'category'])">
						<div class="title">Сбросить фильтры</div>
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
			<div
				class="prices"
				v-if="getCurrentAddresses.length > 0 && getCurrentCategories.length > 0"
			>
				<div class="container-address" v-for="address in getCurrentAddresses" :key="address.id">
					<div class="title">
						{{ address.name }}
					</div>
					<div
						class="container-category"
						:class="{
							disabled: category.id === null,
						}"
						v-for="category in getCurrentCategories(address.id)"
						v-if="getCurrentCategories(address.id).length > 0"
						:key="category.id"
					>
						<div class="title">
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
						<ol class="container-price" v-if="category.id !== null">
							<li v-for="price in getCurrentPrices(category.id)" :key="price.id">
								<div class="text">
									<div class="name">{{ price.name }}</div>
									<div class="price">{{ formatPrice(price.price) }}</div>
									<div class="valute">руб.</div>
								</div>
							</li>
						</ol>
						<ol class="container-price" v-if="category.id === null">
							<li>
								<div class="text">
									<div class="name">Отсутствует...</div>
									<div class="price"></div>
									<div class="valute"></div>
								</div>
							</li>
						</ol>
					</div>
					<div class="container-category-none" v-else>Ничего нет...</div>
				</div>
			</div>
			<Empty :minHeight="300" v-else />
		</template>

		<loader-child
			:isLoading="loading.loader.prices"
			@loaderChildAfterLeave="loaderChildAfterLeave"
		/>
	</Block>
</template>

<script>
import FIlterList from "../../../components/includes/FIlterList.vue";

import InfoBar from "../../../components/ui/main/InfoBar.vue";
import LoaderChild from "../../../components/includes/LoaderChild.vue";
import Block from "../../../components/ui/main/blocks/Block.vue";
import BlockHide from "../../../components/ui/main/blocks/BlockHide.vue";
import Empty from "../../../components/includes/Empty.vue";
import Filter from "../../../components/includes/Filter.vue";

import ContainerInputOnce from "../../../components/ui/admin/containers/input/ContainerInputOnce.vue";

import IconContactHome from "../../../components/icons/contacts/IconContactHome.vue";

import axios, { all } from "axios";
import { useRoute } from "vue-router";
import sorted from "../../../services/sorted.js";

export default {
	components: {
		FIlterList,
		InfoBar,
		LoaderChild,
		Block,
		BlockHide,
		Empty,
		Filter,
		ContainerInputOnce,
		IconContactHome,
		axios,
		sorted,
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
				address: {
					status: false,
					all: false,
					name: "address",
					key: null,
					selected: [],
				},
				category: {
					status: false,
					all: false,
					name: "category",
					key: "categoryId",
					selected: [],
				},
			},
			prices: [],
			addresses: [],
			categories: [],
			categoriesList: [],
		};
	},
	watch: {
		"$route.query": {
			handler(newQuery) {
				// ...
			},
			immediate: true,
		},
		"filters.name"(newValue) {
			this.changeQuery();
		},
		"filters.address.all"(newValue) {
			this.changeQuery();
		},
		"filters.category.all"(newValue) {
			this.changeQuery();
		},
	},
	computed: {
		/* Вывод выбранных адрессов */
		getAddressSelected() {
			let selected = this.filters.address.selected;
			sorted.sortByName("up", this.filters.address.selected);

			return selected;
		},
		/* Вывод выбранных категорий */
		getCategorySelected() {
			let selected = this.filters.category.selected;
			sorted.sortByName("up", this.filters.category.selected);

			return selected;
		},
		/* Вывод текущих адрессов */
		getCurrentAddresses() {
			let currentAddresses = [];

			if (this.filters.address.all) {
				currentAddresses = this.addresses;
			} else {
				currentAddresses = this.filters.address.selected.filter((address) => {
					return this.addresses.includes(address);
				});
			}

			sorted.sortByName("up", currentAddresses);

			return currentAddresses ?? [];
		},
		getActiveFilters() {
			return this.filters.address.selected.length + this.filters.category.selected.length;
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

			sorted.sortNumberByKey("up", currentPrices, "price");

			return currentPrices;
		},
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                    ФИЛЬТРЫ                        |*/
		/* |___________________________________________________|*/
		/* _____________________________________________________*/
		/* 1. Изменение значений фильтров                       */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		/* Получение текущих категорий */
		getCurrentCategories(addressId) {
			let currentCategories = [];

			currentCategories = this.categoriesList.filter((category) => {
				if (
					category.addressId == addressId &&
					this.prices.find((price) => price.categoryId == category.id)
				) {
					return category;
				}
			});

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

			this.changeQuery();
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

			this.changeQuery();
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
		/* _____________________________________________________*/
		/* 2. Изменение query в строке                          */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		changeQuery() {
			let urlName = "none";
			let urlAddress = "none";
			let urlCategory = "none";

			if (this.filters.name !== "") {
				urlName = this.filters.name;
			}

			if (this.filters.address.all) {
				urlAddress = "all";
			} else {
				urlAddress = this.calculateQueryFilterAddress();
			}

			if (this.filters.category.all) {
				urlCategory = "all";
			} else {
				urlCategory = this.calculateQueryFilterCategory();
			}

			this.$router.push({
				query: {
					name: urlName,
					address: urlAddress,
					category: urlCategory,
				},
			});
		},
		calculateQueryFilterAddress() {
			let activeAddresses = [];

			if (this.filters.address.selected.length > 0) {
				this.filters.address.selected.forEach((address) => {
					if (this.addresses.includes(address)) {
						activeAddresses.push(address.name);
					}
				});

				return activeAddresses.join("%");
			} else {
				return "none";
			}
		},
		calculateQueryFilterCategory() {
			let activeCategories = [];

			if (this.filters.category.selected.length > 0) {
				for (let i = 0; i < this.categoriesList.length; i++) {
					this.filters.category.selected.forEach((category) => {
						if (activeCategories.includes(this.categoriesList[i].name)) {
							return;
						}

						if (this.categoriesList[i].name == category.name) {
							activeCategories.push(category.name);
						}
					});
				}

				return activeCategories.join("%");
			} else {
				return "none";
			}
		},
		/* _____________________________________________________*/
		/* 3. Считываение query из строки                       */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		getQuery() {
			if (this.$route.query.address == "all") {
				this.filters.address.all = true;
			} else {
				this.filters.address.all = false;

				let queryAddresses = this.$route.query.address.split("%");

				for (let i = 0; i < queryAddresses.length; i++) {
					for (let j = 0; j < this.addresses.length; j++) {
						if (queryAddresses[i] == this.addresses[j].name) {
							this.filters.address.selected.forEach((category) => {
								if (category.name == this.categoriesList[j].name) {
									return;
								}
							});

							this.filters.address.selected.push(this.addresses[j]);
						}
					}
				}
			}

			if (this.$route.query.category == "all") {
				this.filters.category.all = true;
			} else {
				this.filters.category.all = false;

				let queryCategories = this.$route.query.category.split("%");

				for (let i = 0; i < queryCategories.length; i++) {
					outer: for (let j = 0; j < this.categoriesList.length; j++) {
						if (queryCategories[i] == this.categoriesList[j].name) {
							for (let k = 0; k < this.filters.category.selected.length; k++) {
								if (this.filters.category.selected[k].name == this.categoriesList[j].name) {
									continue outer;
								}
							}

							this.filters.category.selected.push(this.categoriesList[j]);
						}
					}
				}
			}

			if (this.nameRoute !== "none") {
				this.filters.name = this.nameRoute;
			}
		},
	},
	setup() {
		const route = useRoute();
		const nameRoute = route.query.name;
		const addressRoute = route.query.address;
		const categoryRoute = route.query.category;

		return {
			nameRoute,
			addressRoute,
			categoryRoute,
		};
	},
	mounted() {
		axios({
			method: "post",
			url: `${this.$store.state.axios.urlApi}` + `get-prices-all`,
		})
			.then((response) => {
				if (response.data.status) {
					this.addresses = response.data.data.adresses;
					this.categories = response.data.data.categories;
					this.categoriesList = response.data.data.categoriesList;

					this.prices = response.data.data.prices;
				} else {
					let debbugStory = {
						title: "Ошибка.",
						body: response.data.message,
						type: "Error",
					};
					this.$store.commit("debuggerState", debbugStory);
				}
			})
			.catch((error) => {
				let debbugStory = {
					title: "Ошибка.",
					body: "Произошла ошибка при получении данных.",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
			})
			.finally(() => {
				this.getQuery();
				this.loading.loader.prices = false;
			});
	},
};
</script>

<style scoped>
.filter-blocks {
	display: flex;
	flex-direction: column;
	gap: 10px;

	width: 1350px;
	animation: show 0.5s ease-out;
}

.filter-blocks > .container-input {
	position: relative;
	display: grid;
	grid-template-columns: 1fr auto;
}

.filter-blocks > .container-input > input {
	box-sizing: border-box;

	padding: 15px;
	border-radius: 10px;
	border: 2px solid var(--input-border-color-inactive);
	outline: none;

	height: 58px;

	font-size: 1.125rem;

	transition: all 0.2s;
	caret-color: var(--input-border-color-active);
}

.filter-blocks > .container-input > input:focus {
	border: 2px solid var(--input-border-color-active);
}

.filter-blocks > .container-input > button {
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

.filter-blocks > .container-input > button > svg {
	fill: rgba(0, 0, 0, 1);
}

.filter-blocks > .container-input > button > svg:hover {
	fill: rgba(0, 0, 0, 1);
}

.filter-blocks > .container-filters {
	display: flex;
	flex-wrap: wrap;
	gap: 10px;

	border-radius: 10px;
}

.filter-blocks > .container-filters > .filter-subject {
	cursor: pointer;
	display: flex;
	align-items: center;
	gap: 5px;

	padding: 5px 10px;
	background-color: var(--button-default-color);
	border-radius: 10px;
	color: white;

	transition: all 0.2s;
}

.filter-blocks > .container-filters > .filter-subject:hover {
	background-color: var(--button-default-color-hover);
}

.filter-blocks > .container-filters > .filter-subject:last-child {
	color: black;
	background-color: rgb(240, 240, 240);
}

.filter-blocks > .container-filters > .filter-subject:last-child:hover {
	background-color: rgb(230, 230, 230);
}

.filter-blocks > .container-filters > .filter-subject > .title {
	display: flex;
	justify-content: center;
	align-items: center;
	gap: 5px;
}

.filter-blocks > .container-filters > .filter-subject:first-child {
	padding: 5px 10px;
	border-radius: 10px;
}

.filter-blocks > .container-filters > .filter-subject > .close {
	cursor: pointer;
	display: flex;
	align-items: center;
	justify-content: center;

	padding: 5px;
	border-radius: 50px;
}

.filter-blocks > .container-filters > .filter-subject.disabled {
	background-color: rgb(150, 150, 150);
	color: white;
}

/* Цены */
.prices {
	width: 1350px;
	display: grid;
	grid-template-columns: repeat(1, 1fr);
}

.prices > .container-address {
	display: flex;
	flex-direction: column;
	gap: 20px;
}

.prices > .container-address > .title {
	display: flex;
	justify-content: center;
	align-items: center;

	margin-top: 30px;

	font-size: 1.5rem;
	font-weight: 600;
	color: black;

	animation: show-bottom-to-top-15 0.5s ease-in-out;
}

.prices > .container-address:first-child > .title {
	margin-top: 10px;
}

.prices > .container-address > .title > svg {
	fill: var(--button-default-color);
}

.prices > .container-address > .container-category {
	display: flex;
	flex-direction: column;
	gap: 0px;

	border-radius: 20px;

	animation: show-bottom-to-top-15 0.5s ease-in-out;
}

.prices > .container-address > .container-category-none {
	display: flex;
	justify-content: center;

	padding: 20px;

	font-size: 1.125rem;
	color: rgb(150, 150, 150);

	animation: show-bottom-to-top-15 0.5s ease-in-out;
}

.prices > .container-address > .container-category > .title {
	display: flex;
	align-items: center;
	gap: 5px;

	font-size: 1.25rem;
	font-weight: 600;
	color: var(--button-default-color);
	padding: 10px 0px;

	border-radius: 20px 20px 0px 0px;

	border: 0px solid #2d9aa7;
}

.prices > .container-address > .container-category > .title > svg {
	fill: var(--button-default-color);
}

.prices > .container-address > .container-category.disabled > .title {
	color: rgb(150, 150, 150);
}

.prices > .container-address > .container-category.disabled > .title > svg {
	fill: rgb(150, 150, 150);
}

.prices > .container-address > .container-category > .container-price {
	padding: 0px 0px 0px 0px;
	margin: 0px;

	font-size: 1.125rem;
}

.prices > .container-address > .container-category > .container-price > li {
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

.prices > .container-address > .container-category > .container-price > li > .text {
	display: grid;
	grid-template-columns: 1fr 100px 50px;
	gap: 5px;
}

.prices > .container-address > .container-category > .container-price > li > .text > .price {
	font-family: "Roboto", sans-serif;
	text-align: right;
	color: var(--button-default-color);
}

@media screen and (width <= 1450px) {
	.prices {
		width: auto;
	}

	.filter-blocks {
		width: 100%;
	}
}

@media screen and (width <= 850px) {
	.prices > .container-address > .container-category > .container-price > li {
		margin: 0px 0px 0px 30px;
	}

	.prices > .container-address > .container-category > .container-price > li > .text {
		grid-template-columns: 1fr 50px 50px;
	}

	.prices > .container-address > .container-category > .container-price > li > .text > .name {
		text-overflow: ellipsis;
		word-break: break-all;
	}
}
</style>
