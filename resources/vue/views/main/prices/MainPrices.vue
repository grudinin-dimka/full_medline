<template>
	<info-bar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link to="/prices">Цены</router-link>
	</info-bar>

	<block :minHeight="400">
		<template v-if="loading.sections.prices">
			<div class="filter-blocks">
				<div class="container-filters">
					<div class="filter-subject">Адрес: г. Шадринск, ул. Спартака, д. 8</div>
					<div class="filter-subject">Категория: Прием врача11231</div>
				</div>
				<div class="container-filters">
					<Filter
						:filter="filters.address"
						:list="addresses"
						@changeFilterStatus="changeFilterStatus"
						@touchItem="addSelectedItem"
						@saveSelectedItems="changeSelectedItems"
						@clearSelectedItems="clearSelectedItems"
					>
						<template #title>Адрес клиники</template>
					</Filter>
					<Filter
						:filter="filters.category"
						:list="categories"
						@changeFilterStatus="changeFilterStatus"
						@touchItem="addSelectedItem"
						@saveSelectedItems="changeSelectedItems"
					>
						<template #title>Категории</template>
					</Filter>
				</div>
			</div>

			<div class="prices" v-if="getCurrentCategories.length > 0">
				<div class="prices-item" v-for="category in getCurrentCategories" :key="category.id">
					<div class="title">{{ category.name }}</div>
					<ol>
						<li v-for="price in getCurrentPrices(category.id)" :key="price.id">
							<div class="name">{{ price.name }}</div>
							<div class="price">{{ price.price }}</div>
							<div class="valute">руб.</div>
						</li>
					</ol>
				</div>
			</div>
			<Empty :minHeight="300" v-else />
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

import axios from "axios";

export default {
	components: {
		InfoBar,
		LoaderChild,
		Block,
		Empty,
		Filter,
		ContainerInputOnce,
		axios,
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
					name: "address",
					selected: [],
					data: {
						body: "",
						edited: false,
					},
				},
				category: {
					status: false,
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
	computed: {
		getCurrentCategories() {
			return this.categories.filter((category) => {
				if (
					category.addressId === this.filters.address.data.body &&
					category.categoryId !== null
				) {
					return category;
				}
			});
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
		/* После скрытия элементы */
		changeFilterStatus(status, name) {
			this.filters[name].status = status;
		},
		addSelectedItem(id, name) {
			this.filters[name].selected.push(id);
		},
		changeSelectedItems(name, array) {
			this.filters[name].selected = array;
			this.filters[name].status = false;
		},
		clearSelectedItems(name) {
			this.filters[name].selected = [];
			this.filters[name].status = false;
		},
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
			.finally(() => (this.loading.loader.schedule = false));
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
	border: 0px solid white;
	outline: none;

	font-size: 1.125rem;

	background-color: rgba(0, 0, 0, 0.05);
}

.filter-blocks > .container-input > button {
	cursor: pointer;
	display: flex;
	align-items: center;
	gap: 5px;

	padding: 10px;
	border-radius: 10px;
	border: 0px solid white;

	font-size: 1.125rem;
	color: white;
	background-color: var(--button-default-color);
}

.filter-blocks > .container-filters {
	display: flex;
	gap: 10px;
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
	gap: 40px;

	animation: show-bottom-to-top-15 0.5s ease-in-out;
}

.prices-item > .title {
	font-size: 1.25rem;
	font-weight: 600;
	color: #00abbd;
}

.prices-item > ol {
	list-style: none;

	padding: 0px;
	margin: 0px;

	column-gap: 40px;
}

.prices-item > ol > li {
	display: grid;
	grid-template-columns: 1fr 50px 40px;
	gap: 5px;

	border-top: 0px;
	border-right: 0px;
	border-bottom: 1px;
	border-left: 0px;
	border-style: solid;
	border-color: #d2d2d2;

	padding: 15px 10px;

	font-size: 1.125rem;
}

.prices-item > ol > li > .price {
	text-align: right;
}
</style>
