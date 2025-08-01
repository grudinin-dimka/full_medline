<template>
	<info-bar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link to="/prices">Цены</router-link>
		<span class="link-arrow"> / </span>
		<router-link :to="`/prices/${$route.params.group}`">
			<span v-if="!loading.loader.title">{{ title }}</span>
		</router-link>
	</info-bar>

	<block :minHeight="100">
		<div class="price__group">
			<div class="price__group__search">
				<VueInput v-model="search" :type="'search'" :placeholder="'Введите услугу'" />
			</div>

			<div class="price__group__list">
				<template v-if="loading.loader.group">
					<div class="price__group__address" v-for="item in 3">
						<div class="price__group__address-title">
							<div :class="{ skeleton: loading.loader.group }">&nbsp;</div>
						</div>

						<div class="price__group__category">
							<div class="price__group__category-title">
								<div
									class="category__title-icon"
									:class="{ skeleton: loading.loader.group }"
								></div>
								<div
									class="category__title-title-name"
									:class="{ skeleton: loading.loader.group }"
								>
									&nbsp;
								</div>
							</div>
							<ol class="price__group__prices">
								<li v-for="price in 5" :key="price" :class="{ load: true }">
									<div class="price__group__prices-item">
										<div
											class="price__group__prices-name"
											:class="{ skeleton: loading.loader.group }"
										>
											&nbsp;
										</div>
										<div
											class="price__group__prices-value"
											:class="{ skeleton: loading.loader.group }"
										>
											&nbsp;
										</div>
										<div
											class="price__group__prices-valute"
											:class="{ skeleton: loading.loader.group }"
										>
											&nbsp;
										</div>
									</div>
								</li>
							</ol>
						</div>
					</div>
				</template>

				<template v-else>
					<div class="price__group__address" v-for="item in group">
						<div class="price__group__address-title">
							<div>{{ item.name }}</div>
						</div>

						<template v-if="isPricesGroupEmpty(item)" v-for="category in item.categories">
							<div
								class="price__group__category"
								v-if="getCurrentPrices(category.prices).length > 0"
							>
								<div class="price__group__category-title">
									<div class="category__title-icon">
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
									<div class="category__title-title-name">
										{{ category.name }}
									</div>
								</div>
								<ol class="price__group__prices">
									<li v-for="price in getCurrentPrices(category.prices)" :key="price.id">
										<div class="price__group__prices-item">
											<div class="price__group__prices-name">{{ price.name }}</div>
											<div class="price__group__prices-value">
												{{ formatPrice(price.price) }}
											</div>
											<div class="price__group__prices-valute">руб.</div>
										</div>
									</li>
								</ol>
							</div>
						</template>
						<div class="price__group__prices--none" v-else>Ничего нет...</div>

						<Empty :minHeight="300" v-else />
					</div>
				</template>
			</div>
		</div>
	</block>
</template>

<script>
import VueInput from "../../../components/modules/input/VueInput.vue";
import VueLoader from "../../../components/modules/VueLoader.vue";

import InfoBar from "../../../components/ui/main/InfoBar.vue";
import Block from "../../../components/ui/main/Block.vue";
import Empty from "../../../components/modules/Empty.vue";
import LoadText from "../../../components/ui/main/LoadText.vue";

import api from "../../../mixin/api";

import fakeDelay from "../../../mixin/fake-delay";

export default {
	components: {
		VueInput,
		VueLoader,

		InfoBar,
		Block,
		Empty,
		LoadText,
	},
	data() {
		return {
			loading: {
				loader: {
					title: true,
					group: true,
				},
				sections: {
					title: false,
					group: false,
				},
			},

			search: "",
			title: "",
			group: [],
		};
	},
	methods: {
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                   ЗАГРУЗЧИК                       |*/
		/* |___________________________________________________|*/
		/* После скрытия элементы */
		afterLeave() {
			for (let key in this.loading.loader) {
				if (!this.loading.loader[key]) {
					this.loading.sections[key] = true;
				}
			}
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                      ЦЕНЫ                        |*/
		/* |___________________________________________________|*/
		/* Получение цен с учётом значения поиска */
		getCurrentPrices(prices) {
			if (this.search !== "") {
				prices = prices.filter((price) => {
					if (price.name.toLowerCase().includes(this.search.toLowerCase())) {
						return price;
					}
				});
			}

			return prices;
		},

		/* Получение цены с учётом форматирования */
		formatPrice(price) {
			return price.toLocaleString("ru-RU");
		},

		isPricesGroupEmpty(item) {
			for (let i = 0; i < item.categories.length; i++) {
				if (this.getCurrentPrices(item.categories[i].prices).length > 0) {
					return true;
				}
			}

			return false;
		},
	},
	created() {
		fakeDelay(this.$store.getters.timeout, () =>
			api({
				method: "post",
				url: this.$store.getters.urlApi + `get-prices-group`,
				headers: {
					Accept: "application/json",
				},
				data: {
					group: this.$route.params.group,
				},
			})
		).then((response) => {
			if (!response) return;

			try {
				this.title = response.data.result.title;
				this.group = response.data.result.array;

				this.loading.loader.title = false;
				this.loading.loader.group = false;
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
.price__group {
	width: 1350px;
	display: flex;
	flex-direction: column;
	gap: 20px;
}

.price__group__list {
	width: 1350px;
	display: grid;
	grid-template-columns: repeat(1, 1fr);
}

.price__group__address {
	display: flex;
	flex-direction: column;
	gap: 20px;
}

.price__group__address-title {
	margin-top: 10px;
}

.price__group__address-title > div {
	margin-top: 10px;

	border-radius: 50px;
	min-height: 40px;

	width: 100%;

	font-size: 1.5rem;
	font-weight: 600;
	color: black;
}

.price__group__address-title > div.skeleton {
	max-width: 300px;
}

.price__group__category-title {
	display: grid;
	grid-template-columns: auto 1fr;
	align-items: center;
	gap: 5px;

	font-size: 1.25rem;
	font-weight: 600;
	color: var(--primary-color);
}

.price__group__category-title > svg {
	fill: var(--primary-color);
}

.price__group__category {
	display: flex;
	flex-direction: column;
	gap: 0px;

	border-radius: 20px;
}

.price__group__prices--none {
	display: flex;
	justify-content: center;

	padding: 20px;

	font-size: 1.125rem;
	color: rgb(150, 150, 150);
}

.price__group__prices {
	padding: 0px 0px 0px 0px;
	margin: 0px;

	font-size: 1.125rem;
}

.price__group__prices > li {
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

.price__group__prices > li.load {
	border-top: 0px;
	border-right: 0px;
	border-bottom: 1px;
	border-left: 0px;
	border-style: solid;
	border-color: white;
}

.price__group__prices-item {
	display: grid;
	grid-template-columns: 1fr 100px 50px;
	gap: 5px;
}

.price__group__prices-value {
	font-family: "Roboto", sans-serif;
	text-align: right;
	color: var(--button-default-color);
}

.category__title-icon {
	display: flex;
	justify-content: center;
	align-items: center;

	width: 36px;
	height: 36px;
	border-radius: 50px;

	fill: var(--primary-color);
}

.category__title-title-name {
	min-height: 36px;
	display: flex;
	align-items: center;

	border-radius: 50px;
}

.price__group__prices-name {
	border-radius: 50px;
}

.price__group__prices-value {
	border-radius: 50px;
}

.price__group__prices-valute {
	border-radius: 50px;
}

@media screen and (width <= 1450px) {
	.price__group,
	.price__group__list {
		width: 100%;
	}

	.filter_blocks {
		width: 100%;
	}
}

@media screen and (width <= 850px) {
	.price__group__prices > li {
		margin: 0px 0px 0px 30px;
	}

	.price__group__prices-item {
		grid-template-columns: 1fr 50px 50px;
	}

	.price__group__prices-name {
		text-overflow: ellipsis;
		word-break: break-all;
	}
}

@media screen and (width <= 750px) {
	.price__group__prices > li {
		margin: 0px 0px 0px 35px;
	}
}
</style>
