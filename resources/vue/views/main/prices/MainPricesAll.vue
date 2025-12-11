<template>
	<info-bar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link to="/prices">Цены</router-link>
	</info-bar>

	<Block>
		<div class="filters">
			<form @submit.prevent class="filters__item">
				<VueValues v-model.trim="filters.name" :type="'search'" :placeholder="'Введите услугу'">
					<template #label>
						<VueIcon :name="'Design Services'" :fill="'var(--primary-color)'" :width="'20px'" :height="'20px'" />
						УСЛУГА
					</template>
				</VueValues>

				<button class="filters__item-button" @click="getPrices" :disabled="disabled.prices.get">
					<span class="loader" v-if="disabled.prices.get"></span>
					<div class="content" :class="{ disabled: disabled.prices.get }">
						<VueIcon :name="'Search'" :width="'30px'" :height="'30px'" :fill="'white'" :cursor="'pointer'" />
						Поиск
					</div>
				</button>
			</form>

			<div class="filters__item">
				<VueSelector
					v-model.trim="filters.address"
					:list="getAdressesList"
					:size="6"
					:isSearch="true"
					:isClear="false"
				>
					<template #label>
						<VueIcon :name="'Location On'" :fill="'var(--primary-color)'" :width="'20px'" :height="'20px'" />
						АДРЕС
					</template>
				</VueSelector>

				<VueSelector
					v-model.trim="filters.category"
					:list="getCategoriesList"
					:size="6"
					:isSearch="true"
					:isClear="true"
				>
					<template #label>
						<VueIcon :name="'Category'" :fill="'var(--primary-color)'" :width="'20px'" :height="'20px'" />
						КАТЕГОРИЯ
					</template>
				</VueSelector>
			</div>
		</div>

		<!-- Цены -->
		<div class="prices">
			<div class="prices__address">
				<template v-if="loading.loader.prices">
					<div class="prices__category" v-for="category in 3" :key="category">
						<div class="prices__address-title">
							<div class="prices__address-title-icon" :class="{ skeleton: loading.loader.prices }">&nbsp;</div>
							<div class="prices__address-title-name" :class="{ skeleton: loading.loader.prices }">&nbsp;</div>
						</div>
						<ol class="prices__values">
							<li v-for="price in 5" :key="price" :class="{ load: loading.loader.prices }">
								<div class="prices__values-item">
									<div class="prices__values-name" :class="{ skeleton: loading.loader.prices }">&nbsp;</div>
									<div class="prices__values-price" :class="{ skeleton: loading.loader.prices }">&nbsp;</div>
									<div class="prices__values-valute" :class="{ skeleton: loading.loader.prices }">&nbsp;</div>
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
								<svg xmlns="http://www.w3.org/2000/svg" height="30px" viewBox="0 -960 960 960" width="30px">
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
import InfoBar from "../../../components/ui/main/InfoBar.vue";
import Block from "../../../components/ui/main/Block.vue";
import LoadText from "../../../components/ui/main/LoadText.vue";
import Empty from "../../../components/modules/Empty.vue";

import api from "../../../mixin/api.js";
import sorted from "../../../services/sorted.js";

import fakeDelay from "../../../mixin/fake-delay.js";

export default {
	components: {
		InfoBar,
		Block,
		LoadText,
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

			disabled: {
				prices: {
					get: false,
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
					this.getPrices(true, false);
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

			sorted.sortStringByKey("up", currentCategories, "name");

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
		getPrices(isAddress = true, isCategory = true) {
			this.disabled.prices.get = true;

			let values = {
				price: this.filters.name === "" ? null : this.filters.name,
			};

			if (isAddress) {
				values.address = this.addresses.find((address) => address.id == this.filters.address)?.name;
			} else {
				values.address = null;
			}

			if (isCategory) {
				values.category = this.categories.find((category) => category.id == this.filters.category)?.name;
			} else {
				values.category = null;
				this.filters.category = "";
			}

			api({
				method: "post",
				url: this.$store.getters.urlApi + `get-prices-manual`,
				headers: {
					Accept: "application/json",
				},
				data: values,
			})
				.then((response) => {
					if (!response) return;

					this.filters.address = response.data.result.address.id;
					this.categories = response.data.result.categories;
					this.categoriesList = response.data.result.categoriesList;
					this.prices = response.data.result.prices;

					sorted.sortNumberByKey("up", this.categoriesList, "name");
				})
				.catch((error) => {
					this.$store.commit("addDebugger", {
						title: "Ошибка.",
						body: error,
						type: "error",
					});
				})
				.finally(() => {
					this.disabled.prices.get = false;
				});
		},
	},
	mounted() {
		fakeDelay(this.$store.getters.timeout, () =>
			api({
				method: "post",
				url: `${this.$store.getters.urlApi}` + `get-prices-complecte`,
			})
		).then((response) => {
			if (!response) return;

			try {
				this.addresses = response.data.result.addresses;
				this.categories = response.data.result.categories;

				this.filters.address = response.data.result.address.id;

				this.categoriesList = response.data.result.categories;
				sorted.sortStringByKey("up", this.categoriesList, "name");

				this.prices = response.data.result.prices;

				this.loading.loader.prices = false;
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			}
		});
	},
};
</script>

<style scoped>
.filters {
	display: flex;
	flex-direction: column;
	gap: var(--default-gap);

	margin: 0 auto;

	width: 1350px;
}

.filters__item {
	display: flex;
	gap: var(--default-gap);
}

.filters__item-button {
	cursor: pointer;
	display: flex;
	justify-content: center;
	align-items: center;
	gap: var(--button-gap);

	padding: var(--button-padding);
	border: var(--button-border);
	border-radius: var(--input-border-radius);

	font-size: var(--button-font-size);
	color: var(--button-font-color);
	background-color: var(--button-background-color);

	transition: all 0.2s;
}

.filters__item-button:hover {
	color: var(--button-font-color-hover);
	background-color: var(--button-background-color-hover);
}

button > .content {
	cursor: pointer;
	display: flex;
	justify-content: center;
	align-items: center;
	gap: var(--button-gap);
}

button > .content.disabled {
	visibility: hidden;
	opacity: 0;
}

.loader {
	position: absolute;

	width: 26px;
	height: 26px;
	border: var(--button-loader-border);
	border-bottom-color: transparent;
	border-radius: 50%;
	display: inline-block;
	box-sizing: border-box;
	animation: rotation 1s linear infinite;
}

@keyframes rotation {
	0% {
		transform: rotate(0deg);
	}
	100% {
		transform: rotate(360deg);
	}
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

.prices__values > li.load {
	border-top: 0px;
	border-right: 0px;
	border-bottom: 1px;
	border-left: 0px;
	border-style: solid;
	border-color: #ffffff;

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
