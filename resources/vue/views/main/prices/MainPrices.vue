<template>
	<info-bar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link to="/prices">Цены</router-link>
	</info-bar>

	<block :minHeight="400">
		<template v-if="loading.sections.prices">
			<div class="filter-blocks">
				<div class="filter" :class="{ active: true }">
					<div class="filter-title">
						<div class="name">Адрес клиники</div>
						<div class="arrow">></div>
					</div>
					<div class="filter-body">
						<input type="text" placeholder="Введите адрес" />
						<ol>
							<li>г. Тест, ул. Тестовая, д. Тестовый</li>
							<li>г. Тест, ул. Тестовая, д. Тестовый</li>
							<li>г. Тест, ул. Тестовая, д. Тестовый</li>
							<li>г. Тест, ул. Тестовая, д. Тестовый</li>
							<li>г. Тест, ул. Тестовая, д. Тестовый</li>
						</ol>
					</div>
				</div>

				<!-- <div class="item">
					<div class="clear-filter" v-if="false">Очистить</div>
					<container-input-once :type="'default'">
						<template #title>
							<span>АДРЕС КЛИНИКИ</span>
						</template>
						<template #input>
							<select v-model="filters.address.data.body">
								<option value="" disabled selected>Ничего не выбрано</option>
								<option :value="address.id" v-for="address in addresses">
									{{ address.name }}
								</option>
							</select>
						</template>
					</container-input-once>
				</div>
				<div class="item">
					<div class="clear-filter" v-if="false">Очистить</div>
					<container-input-once :type="'default'">
						<template #title>
							<span>КАТЕГОРИЯ</span>
						</template>
						<template #input>
							<select v-model="filters.category.data.body">
								<option value="" disabled selected>Ничего не выбрано</option>
								<option :value="category.id" v-for="category in getCurrentCategories">
									{{ category.name }}
								</option>
							</select>
						</template>
					</container-input-once>
				</div>
				<div class="item search">
					<div class="clear-filter" v-if="false">Очистить</div>
					<container-input-once :type="'default'">
						<template #title>
							<span>НАЗВАНИЕ УСЛУГИ</span>
						</template>
						<template #input>
							<input type="text" placeholder="Введите услугу" />
						</template>
					</container-input-once>
				</div> -->
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

import ContainerInputOnce from "../../../components/ui/admin/containers/input/ContainerInputOnce.vue";

import axios from "axios";

export default {
	components: {
		InfoBar,
		LoaderChild,
		Block,
		Empty,
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
					data: {
						body: "",
						edited: false,
					},
				},
				category: {
					status: false,
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
				if (category.addressId === 1) {
					return category;
				}
			});

			// return this.categories.filter((category) => {
			// 	if (
			// 		category.addressId === this.filters.address.data.body &&
			// 		category.categoryId !== null
			// 	) {
			// 		return category;
			// 	}
			// });
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
					this.categories = response.data.data.categories;
					this.prices = response.data.data.prices;

					console.log(response.data);
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
/* Блоки фильтров */
.filter-blocks {
	display: grid;
	grid-template-columns: 1fr 1fr;
	grid-row: repeat(2, 1fr);
	gap: 10px;

	width: 1350px;
	animation: show 0.5s ease-out;
}

.filter-blocks > .filter {
	position: relative;

	display: flex;
	flex-direction: column;

	padding: 20px 0px;
}

.filter-blocks > .filter > .filter-title {
	display: flex;
	justify-content: space-between;
	gap: 10px;

	padding: 10px;
	border-radius: 10px;

	font-size: 1.125rem;
	width: 200px;
}

.filter-blocks > .filter.active > .filter-title {
	background-color: rgba(0, 0, 0, 0.1);
}

.filter-blocks > .filter > .filter-body {
	position: absolute;
	top: 75px;
	left: 0px;
	display: flex;
	flex-direction: column;

	padding: 10px;
	border-radius: 10px;

	background-color: white;
	box-shadow: 0px 0px 15px 5px rgba(0, 0, 0, 0.1);
}

.filter-blocks > .filter > .filter-body > input {
	box-sizing: border-box;

	outline: none;
	padding: 10px;
	border: 0px solid white;
	border-radius: 10px;

	font-size: 1.125rem;

	background-color: rgba(0, 0, 0, 0.05);
}

.filter-blocks > .filter > .filter-body > ol {
	list-style: none;
	display: flex;
	flex-direction: column;
	gap: 5px;

	font-size: 1.125rem;

	padding: 0px;
	margin: 10px 0px 0px 0px;
}

.filter-blocks > .filter > .filter-body > ol > li {
	cursor: pointer;
	padding: 10px;
}

.filter-blocks > .filter > .filter-body > ol > li:hover {
	padding: 10px;
	border-radius: 10px;
	background-color: rgba(0, 0, 0, 0.05);
}

.filter-blocks > .search {
	grid-column-start: 1;
	grid-column-end: 3;
}

.filter-blocks > .item {
	position: relative;
	flex: 1 0 350px;

	transition: all 0.2s;
}

.filter-blocks > .item.active {
	border: 2px solid #44a533;
}

.filter-button.active {
	background-color: #f2feff;
	border: 2px solid var(--input-border-color-active);
}

.clear-filter {
	position: absolute;
	cursor: pointer;

	top: 12px;
	right: 20px;
	color: var(--button-remove-color);
}

.clear-filter:hover {
	text-decoration: underline;
}

.clear-filter > .point {
	position: absolute;
	top: 2px;

	border-radius: 30px;

	width: 16px;
	height: 16px;
}

.clear-filter > .point.default {
	left: 2px;
	background-color: #b6b6b6;
}

.clear-filter > .point.active {
	right: 2px;
	background-color: var(--primary-color);
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
