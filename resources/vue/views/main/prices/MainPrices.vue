<template>
	<info-bar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link to="/prices">Цены</router-link>
	</info-bar>

	<block :minHeight="400">
		<template v-if="loading.sections.prices">
			<div class="filter-blocks">
				<!-- Поле ввода -->
				<div class="container-input">
					<input type="text" placeholder="Введите услугу" />
				</div>
				<!-- Фильтры -->
				<div class="container-filters">
					<Filter
						:filter="filters.address"
						:list="addresses"
						@changeFilterStatus="changeFilterStatus"
						@selectItem="changeSelectedItem"
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
						@selectItem="changeSelectedItem"
						@saveSelectedItems="changeSelectedItems"
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
					v-if="filters.address.selected.length > 0 || filters.category.selected.length > 0"
				>
					<div
						class="filter-subject"
						:class="{
							disabled: filters.address.all,
						}"
						v-for="filter in getAddressSelected"
						:key="filter.id"
						@click="changeSelectedItem(filter, 'address')"
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
						@click="changeSelectedItem(filter, 'category')"
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

			<div class="prices">
				<div
					class="container-address"
					v-if="getCurrentAddresses.length > 0 && getCurrentCategories.length > 0"
					v-for="address in getCurrentAddresses"
					:key="address.id"
				>
					<div class="title">
						{{ address.name }}
					</div>
					<div
						class="container-category"
						v-if="getCurrentCategories(address.id).length > 0"
						v-for="category in getCurrentCategories(address.id)"
						:key="category.id"
					>
						<div class="title">
							<svg
								xmlns="http://www.w3.org/2000/svg"
								height="30px"
								viewBox="0 -960 960 960"
								width="30px"
								fill="#00abbd"
							>
								<path
									d="m260-520 220-360 220 360H260ZM700-80q-75 0-127.5-52.5T520-260q0-75 52.5-127.5T700-440q75 0 127.5 52.5T880-260q0 75-52.5 127.5T700-80Zm-580-20v-320h320v320H120Zm580-60q42 0 71-29t29-71q0-42-29-71t-71-29q-42 0-71 29t-29 71q0 42 29 71t71 29Zm-500-20h160v-160H200v160Zm202-420h156l-78-126-78 126Zm78 0ZM360-340Zm340 80Z"
								/>
							</svg>
							{{ category.name }}
						</div>
						<ol class="container-price">
							<li v-for="price in getCurrentPrices(category.id)" :key="price.id">
								<div class="text">
									<div class="name">{{ price.name }}</div>
									<div class="price">{{ price.price }}</div>
									<div class="valute">руб.</div>
								</div>
							</li>
						</ol>
					</div>
					<div class="container-category" v-else v-for="category in getCategorySelected">
						<div class="title">
							<svg
								xmlns="http://www.w3.org/2000/svg"
								height="30px"
								viewBox="0 -960 960 960"
								width="30px"
								fill="#00abbd"
							>
								<path
									d="m260-520 220-360 220 360H260ZM700-80q-75 0-127.5-52.5T520-260q0-75 52.5-127.5T700-440q75 0 127.5 52.5T880-260q0 75-52.5 127.5T700-80Zm-580-20v-320h320v320H120Zm580-60q42 0 71-29t29-71q0-42-29-71t-71-29q-42 0-71 29t-29 71q0 42 29 71t71 29Zm-500-20h160v-160H200v160Zm202-420h156l-78-126-78 126Zm78 0ZM360-340Zm340 80Z"
								/>
							</svg>
							{{ category.name }}
						</div>
						<ol class="container-price">
							<li>
								<div class="text">
									<div class="name">Отсутствует...</div>
									<div class="price"></div>
									<div class="valute"></div>
								</div>
							</li>
						</ol>
					</div>
				</div>
				<Empty :minHeight="300" v-else />
			</div>
		</template>

		<loader-child
			:isLoading="loading.loader.prices"
			:minHeight="397"
			@loaderChildAfterLeave="loaderChildAfterLeave"
		/>
	</block>
</template>

<script>
import InfoBar from "../../../components/ui/main/InfoBar.vue";
import LoaderChild from "../../../components/includes/LoaderChild.vue";
import Block from "../../../components/ui/main/blocks/Block.vue";
import Empty from "../../../components/includes/Empty.vue";
import Filter from "../../../components/includes/Filter.vue";

import ContainerInputOnce from "../../../components/ui/admin/containers/input/ContainerInputOnce.vue";

import IconContactHome from "../../../components/icons/contacts/IconContactHome.vue";

import axios from "axios";
import { useRoute } from "vue-router";
import sorted from "../../../services/sorted.js";

export default {
	components: {
		InfoBar,
		LoaderChild,
		Block,
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
				address: {
					status: false,
					all: false,
					name: "address",
					selected: [],
					data: {
						body: "",
						edited: false,
					},
				},
				category: {
					status: false,
					all: false,
					name: "category",
					selected: [],
					data: {
						body: "",
						edited: false,
					},
				},
			},
			prices: [],
			addresses: [],
			categories: [],
		};
	},
	watch: {
		"$route.query": {
			handler(newQuery) {
				// ...
			},
			immediate: true,
		},
		"filters.address.all"(newValue) {
			this.changeQuery();
		},
		"filters.category.all"(newValue) {
			this.changeQuery();
		},
	},
	computed: {
		getAddressSelected() {
			let selected = this.filters.address.selected;
			sorted.sortByName("up", this.filters.address.selected);

			return selected;
		},
		getCategorySelected() {
			let selected = this.filters.category.selected;
			sorted.sortByName("up", this.filters.category.selected);

			return selected;
		},
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
			return this.prices.filter((price) => {
				if (price.categoryId == categoryId) {
					return price;
				}
			});
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

			currentCategories = this.categories.filter((category) => {
				if (
					category.addressId == addressId &&
					this.prices.find((price) => price.categoryId == category.id)
				) {
					return category;
				}
			});

			if (!this.filters.category.all) {
				currentCategories = currentCategories.filter((category) => {
					let bool = false;

					this.filters.category.selected.forEach((item) => {
						if (item.name == category.name) {
							bool = true;
						}
					});

					return bool;
				});
			}

			return currentCategories;
		},
		/* После скрытия элементы */
		changeFilterStatus(status, name) {
			this.filters[name].status = status;
		},
		changeSelectedItem(selectedItem, name) {
			if (this.filters[name].selected.includes(selectedItem)) {
				this.filters[name].selected = this.filters[name].selected.filter(
					(item) => item !== selectedItem
				);
			} else {
				this.filters[name].selected.push(selectedItem);
			}

			this.changeQuery();
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
		},
		/* _____________________________________________________*/
		/* 2. Изменение query в строке                          */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		changeQuery() {
			let urlAddress = "none";
			let urlCategory = "none";

			if (this.filters.address.all) {
				urlAddress = "all";
			} else {
				urlAddress = this.calculateFilterAddress();
			}

			if (this.filters.category.all) {
				urlCategory = "all";
			} else {
				urlCategory = this.calculateFilterCategory();
			}

			this.$router.push({
				query: {
					address: urlAddress,
					category: urlCategory,
				},
			});
		},
		calculateFilterAddress() {
			let activeAddresses = [];

			if (this.filters.address.selected.length > 0) {
				this.filters.address.selected.forEach((address) => {
					if (this.addresses.includes(address)) {
						activeAddresses.push(address.name);
					}
				});

				return activeAddresses.join(", ");
			} else {
				return "none";
			}
		},
		calculateFilterCategory() {
			let activeCategories = [];

			if (this.filters.category.selected.length > 0) {
				this.filters.category.selected.forEach((category) => {
					if (this.categories.includes(category)) {
						activeCategories.push(category.name);
					}
				});

				return activeCategories.join(", ");
			} else {
				return "none";
			}
		},
	},
	setup() {
		const route = useRoute();
		const addressRoute = route.query.address;
		const categoryRoute = route.query.category;

		return {
			addressRoute,
			categoryRoute,
		};
	},
	mounted() {
		this.loading.loader.prices = false;

		axios({
			method: "post",
			url: `${this.$store.state.axios.urlApi}` + `get-prices-all`,
		})
			.then((response) => {
				if (response.data.status) {
					this.addresses = response.data.data.adresses;
					this.addresses.forEach((address) => {
						address.status = false;
					});

					this.categories = response.data.data.categories;
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
				this.loading.loader.schedule = false;

				if (this.$route.query.address == "all") {
					this.filters.address.all = true;
				}

				if (this.$route.query.category == "all") {
					this.filters.category.all = true;
				}
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
	display: grid;
	grid-template-columns: 1fr auto;
	gap: 10px;
}

.filter-blocks > .container-input > input {
	box-sizing: border-box;

	padding: 15px;
	border-radius: 10px;
	border: 2px solid var(--input-border-color-inactive);
	outline: none;

	font-size: 1.125rem;

	transition: all 0.2s;
	caret-color: var(--input-border-color-active);
}

.filter-blocks > .container-input > input:focus {
	border: 2px solid var(--input-border-color-active);
}

.filter-blocks > .container-input > button {
	cursor: pointer;
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 5px;

	padding: 10px 15px;
	width: 125px;
	border-radius: 10px;
	border: 0px solid white;

	font-size: 1.125rem;
	color: white;
	background-color: var(--button-default-color);
}

.filter-blocks > .container-input > button:hover {
	background-color: var(--button-default-color-hover);
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

.adresses {
	display: flex;
	overflow-x: auto;

	width: 1350px;
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

.prices > .container-address > .container-category > .title {
	display: flex;
	align-items: center;
	gap: 5px;

	font-size: 1.25rem;
	font-weight: 600;
	color: #2d9aa7;
	padding: 10px 0px;

	border-radius: 20px 20px 0px 0px;
	background-color: white;

	border: 0px solid #2d9aa7;
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
	text-align: right;
}
</style>
