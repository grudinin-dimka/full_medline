<template>
	<info-bar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link to="/prices">Цены</router-link>
	</info-bar>

	<Block>
		<div class="filters">
			<form @submit.prevent class="filters__item">
				<VueInput v-model="filters.name" :type="'search'" :placeholder="'Введите услугу'" />

				<button class="filters__item-button" @click="getPrices">
					<VueIcon
						:name="'search'"
						:width="'24px'"
						:height="'24px'"
						:fill="'white'"
						:cursor="'pointer'"
					/>
					Поиск
				</button>
			</form>

			<div class="filters__item">
				<VueSelector
					v-model="filters.address"
					:placeholder="'Выберите адрес'"
					:cleared="false"
					:list="getAdressesList"
				/>

				<VueSelector
					v-model="filters.category"
					:placeholder="'Выберите категорию'"
					:cleared="true"
					:list="getCategoriesList"
				/>
			</div>
		</div>

		<!-- Цены -->
		<div class="prices">
			<div class="prices__address">
				<template v-if="loading.loader.prices">
					<div class="prices__category" v-for="category in 3" :key="category">
						<div class="prices__address-title">
							<div
								class="prices__address-title-icon"
								:class="{ skeleton: loading.loader.prices }"
							>
								&nbsp;
							</div>
							<div
								class="prices__address-title-name"
								:class="{ skeleton: loading.loader.prices }"
							>
								&nbsp;
							</div>
						</div>
						<ol class="prices__values">
							<li v-for="price in 5" :key="price">
								<div class="prices__values-item">
									<div
										class="prices__values-name"
										:class="{ skeleton: loading.loader.prices }"
									>
										&nbsp;
									</div>
									<div
										class="prices__values-price"
										:class="{ skeleton: loading.loader.prices }"
									>
										&nbsp;
									</div>
									<div
										class="prices__values-valute"
										:class="{ skeleton: loading.loader.prices }"
									>
										&nbsp;
									</div>
								</div>
							</li>
						</ol>
					</div>
				</template>

				<template v-else>
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
							<div class="prices__address-title-icon">
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
							</div>
							<div class="prices__address-title-name">{{ category.name }}</div>
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
				</template>
			</div>
		</div>
	</Block>
</template>

<script>
import VueLoader from "../../../components/modules/VueLoader.vue";
import VueSelector from "../../../components/modules/VueSelector.vue";
import VueInput from "../../../components/modules/input/VueInput.vue";

import InfoBar from "../../../components/ui/main/InfoBar.vue";
import Block from "../../../components/ui/main/Block.vue";
import LoadText from "../../../components/ui/main/LoadText.vue";
import Empty from "../../../components/modules/Empty.vue";

import VueIcon from "../../../components/modules/icon/VueIcon.vue";

import api from "../../../mixin/api.js";
import sorted from "../../../services/sorted.js";

import TimeManager from "../../../mixin/time-manager.js";

export default {
	components: {
		VueLoader,
		VueSelector,
		VueInput,

		InfoBar,
		Block,
		LoadText,
		Empty,

		VueIcon,
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

			/* Фильтры */
			filters: {
				name: "",
				address: "",
				category: "",
			},

			/* Данные */
			prices: [],
			addresses: [],
			categories: [],

			/* Вспомогательные */
			categoriesList: [],
		};
	},
	watch: {
		"filters.address": {
			handler(newValue, oldValue) {
				if (oldValue !== "") {
					this.getPrices();
				}
			},
		},
		"filters.category": {
			handler(newValue, oldValue) {
				this.getPrices();
			},
		},
		"filters.name": {
			handler(newValue, oldValue) {
				if (newValue === "" && oldValue !== "") {
					this.getPrices();
				}
			},
		},
	},
	computed: {
		getCurrentCategories() {
			let currentCategories = this.categoriesList;

			sorted.sortByName("up", currentCategories);

			return currentCategories;
		},

		getAdressesList() {
			let result = this.addresses.map((address) => {
				return {
					value: address.id,
					label: address.name,
				};
			});

			sorted.sortStringByKey("up", result, "label");

			return result;
		},

		getCategoriesList() {
			let result = this.categories.map((category) => {
				return {
					value: category.id,
					label: category.name,
				};
			});

			sorted.sortStringByKey("up", result, "label");

			return result;
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

		getRandomPricesCount() {
			return Math.floor(Math.random() * 10) + 1;
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

			sorted.sortStringByKey("up", currentPrices, "name");

			return currentPrices;
		},

		formatPrice(price) {
			return price.toLocaleString("ru-RU");
		},

		/* Получение текущих цен */
		getPrices() {
			this.loading.loader.prices = true;
			this.loading.sections.prices = false;

			api({
				method: "post",
				url: this.$store.getters.urlApi + `get-prices-manual`,
				headers: {
					Accept: "application/json",
				},
				data: {
					price: this.filters.name === "" ? null : this.filters.name,
					address:
						this.addresses.find((category) => category.id == this.filters.address)?.name ??
						null,
					category:
						this.categories.find((category) => category.id == this.filters.category)?.name ??
						null,
				},
			})
				.then((response) => {
					if (!response) return;
					``;
					this.filters.address = response.data.result.address.id;

					this.categoriesList = response.data.result.categories;
					sorted.sortByName("up", this.categoriesList);

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
	},
	mounted() {
		const timePrices = new TimeManager();
		timePrices.start();

		api({
			method: "post",
			url: `${this.$store.getters.urlApi}` + `get-prices-complecte`,
		})
			.then((response) => {
				if (!response) return;

				this.addresses = response.data.result.addresses;
				this.categories = response.data.result.uniqueCategories;

				this.filters.address = response.data.result.address.id;

				this.categoriesList = response.data.result.categories;
				sorted.sortByName("up", this.categoriesList);

				this.prices = response.data.result.prices;

				this.loading.sections.prices = true;
			})
			.catch((error) => {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			})
			.finally(() => {
				timePrices.end();

				timePrices.difference(this.$store.getters.timeout, () => {
					this.loading.loader.prices = false;
				});
			});
	},
};
</script>

<style scoped>
.filters {
	display: flex;
	flex-direction: column;
	gap: 10px;

	margin: 0 auto;

	width: 1350px;
}

.filters__item {
	display: flex;
	gap: 10px;
}

.filters__item-button {
	cursor: pointer;

	display: flex;
	justify-content: center;
	align-items: center;
	gap: 10px;

	border: 0px;
	border-radius: var(--input-border-radius);
	background-color: var(--primary-color);
	color: white;
	padding: 0px 20px;

	min-height: 58px;
	font-size: 1.125rem;
	transition: all 0.2s ease;
}

.filters__item-button:hover {
	background-color: var(--primary-color-hover);
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
}

.prices__address:first-child > .prices__address-title {
	margin-top: 10px;
}

.prices__address > .prices__address-title svg {
	fill: var(--primary-color);
}

.prices__category {
	display: flex;
	flex-direction: column;
	gap: 0px;

	border-radius: 20px;
}

.prices__categories--none {
	display: flex;
	justify-content: center;

	padding: 20px;

	font-size: 1.125rem;
	color: rgb(150, 150, 150);
}

.prices__category > .prices__address-title {
	display: grid;
	grid-template-columns: auto 1fr;
	align-items: center;
	gap: 5px;

	min-height: 30px;
	font-size: 1.25rem;
	font-weight: 600;
	color: var(--primary-color);
	padding: 10px 0px;

	border-radius: 20px;

	border: 0px solid #2d9aa7;
}

.prices__address-title-icon {
	display: flex;
	justify-content: center;
	align-items: center;

	width: 36px;
	height: 36px;
	border-radius: 50px;

	fill: var(--primary-color);
}

.prices__address-title-name {
	min-height: 36px;
	display: flex;
	align-items: center;

	border-radius: 50px;
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

.prices__values-name {
	border-radius: 50px;
}

.prices__values-price {
	border-radius: 50px;

	font-family: "Roboto", sans-serif;
	text-align: right;
	color: var(--primary-color);
}

.prices__values-valute {
	border-radius: 50px;
}

@media screen and (width <= 1450px) {
	.prices {
		width: 100%;
	}

	.filters {
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

	.filters__item {
		flex-direction: column;
	}
}

@media screen and (width <= 750px) {
	.prices__values > li {
		margin: 0px 0px 0px 35px;
	}
}
</style>
