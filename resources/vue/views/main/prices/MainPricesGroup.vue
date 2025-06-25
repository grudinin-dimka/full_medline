<template>
	<info-bar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link to="/prices">Цены</router-link>
		<span class="link-arrow"> / </span>
		<router-link :to="`/prices/${$route.params.group}`">
			<load-text :isLoading="loading.loader.title" @afterLeave="afterLeave">
				Загрузка...
			</load-text>
			<span v-if="loading.sections.title">{{ title }}</span>
		</router-link>
	</info-bar>

	<block :minHeight="100">
		<template v-if="loading.sections.group">
			<div class="price__group" v-if="group.length > 0">
				<div class="price__group__search">
					<VueInput v-model="search" :type="'search'" :placeholder="'Введите услугу'" />
				</div>

				<div class="price__group__list">
					<div class="price__group__address" v-for="item in group">
						<div class="price__group__address-title">{{ item.name }}</div>
						<template v-if="isPricesGroupEmpty(item)" v-for="category in item.categories">
							<div
								class="price__group__category"
								v-if="getCurrentPrices(category.prices).length > 0"
							>
								<div class="price__group__category-title">
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
					</div>
				</div>
			</div>
			<Empty :minHeight="300" v-else />
		</template>

		<loader-child :isLoading="loading.loader.group" @loaderChildAfterLeave="afterLeave" />
	</block>
</template>

<script>
import InfoBar from "../../../components/ui/main/InfoBar.vue";
import Block from "../../../components/ui/main/Block.vue";
import LoaderChild from "../../../components/modules/LoaderChild.vue";
import Empty from "../../../components/modules/Empty.vue";
import LoadText from "../../../components/ui/main/LoadText.vue";

import VueInput from "../../../components/modules/VueInput.vue";

import api from "../../../services/api";

export default {
	components: {
		InfoBar,
		Block,
		LoaderChild,
		Empty,
		LoadText,

		VueInput,
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
		// Получение массива докторов с сервера
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
			.then((response) => {
				if (!response) return;

				this.title = response.data.result.title;
				this.group = response.data.result.array;
			})
			.catch((error) => {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			})
			.finally(() => {
				this.loading.loader.title = false;
				this.loading.loader.group = false;
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

.price__group__search {
	animation: show 0.5s ease-in-out;
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
	display: flex;
	justify-content: center;
	align-items: center;

	margin-top: 10px;

	font-size: 1.5rem;
	font-weight: 600;
	color: black;

	animation: show-bottom-to-top-15 0.5s ease-in-out;
}

.price__group__category-title {
	display: flex;
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

	animation: show-bottom-to-top-15 0.5s ease-in-out;
}

.price__group__prices--none {
	display: flex;
	justify-content: center;

	padding: 20px;

	font-size: 1.125rem;
	color: rgb(150, 150, 150);

	animation: show-bottom-to-top-15 0.5s ease-in-out;
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

@media screen and (width <= 1450px) {
	.price__group,
	.price__group__list {
		width: auto;
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
</style>
