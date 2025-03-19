<template>
	<info-bar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link to="/prices">Цены</router-link>
		<span class="link-arrow"> / </span>
		<router-link to="/prices/travels">Путевки</router-link>
	</info-bar>

	<block :minHeight="100">
		<div class="travels" v-if="loading.sections.travels">
			<div class="travels__search">
				<ContainerInputSearch v-model="search" :placeholder="'Введите услугу'" />
			</div>

			<div class="travels__list">
				<div class="travels__address" v-for="travel in travels">
					<div class="travels__address-title">{{ travel.name }}</div>
					<template v-if="isPricesTravelEmpty(travel)" v-for="category in travel.categories">
						<div class="travels__category" v-if="getCurrentPrices(category.prices).length > 0">
							<div class="travels__category-title">
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
							<ol class="travels__prices">
								<li v-for="price in getCurrentPrices(category.prices)" :key="price.id">
									<div class="travels__prices-item">
										<div class="travels__prices-name">{{ price.name }}</div>
										<div class="travels__prices-value">
											{{ formatPrice(price.price) }}
										</div>
										<div class="travels__prices-valute">руб.</div>
									</div>
								</li>
							</ol>
						</div>
					</template>
					<div class="prices__categories--none" v-else>Ничего нет...</div>
				</div>
			</div>
		</div>

		<loader-child
			:isLoading="loading.loader.travels"
			@loaderChildAfterLeave="loaderChildAfterLeave"
		/>
	</block>
</template>

<script>
import InfoBar from "../../../components/ui/main/InfoBar.vue";
import Block from "../../../components/ui/main/blocks/Block.vue";
import LoaderChild from "../../../components/includes/LoaderChild.vue";

import ContainerInputSearch from "../../../components/ui/admin/containers/input/ContainerInputSearch.vue";

import axios from "axios";

export default {
	components: {
		InfoBar,
		Block,

		LoaderChild,

		ContainerInputSearch,
	},
	data() {
		return {
			loading: {
				loader: {
					travels: true,
				},
				sections: {
					travels: false,
				},
			},

			search: "",
			travels: [],
		};
	},
	methods: {
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                   ЗАГРУЗЧИК                       |*/
		/* |___________________________________________________|*/
		/* После скрытия элементы */
		loaderChildAfterLeave() {
			this.loading.sections.travels = true;
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

		isPricesTravelEmpty(travel) {
			for (let i = 0; i < travel.categories.length; i++) {
				if (this.getCurrentPrices(travel.categories[i].prices).length > 0) {
					return true;
				}
			}

			return false;
		},
	},
	created() {
		// Получение массива докторов с сервера
		axios({
			method: "post",
			url: `${this.$store.state.axios.urlApi}` + `get-prices-group`,
			data: this.$route.params.group,
		})
			.then((response) => {
				if (response.data.status) {
					this.travels = response.data.data;
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
					body: "Произошла ошибка при получении данных о путевках.",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
			})
			.finally(() => {
				this.loading.loader.travels = false;
			});
	},
};
</script>

<style scoped>
.travels {
	width: 1350px;
	display: flex;
	flex-direction: column;
	gap: 20px;
}

.travels__search {
	animation: show 0.5s ease-in-out;
}

.travels__list {
	width: 1350px;
	display: grid;
	grid-template-columns: repeat(1, 1fr);
}

.travels__address {
	display: flex;
	flex-direction: column;
	gap: 20px;
}

.travels__address-title {
	display: flex;
	justify-content: center;
	align-items: center;

	margin-top: 10px;

	font-size: 1.5rem;
	font-weight: 600;
	color: black;

	animation: show-bottom-to-top-15 0.5s ease-in-out;
}

.travels__category-title {
	display: flex;
	align-items: center;
	gap: 5px;

	font-size: 1.25rem;
	font-weight: 600;
	color: var(--primary-color);
}

.travels__category-title > svg {
	fill: var(--primary-color);
}

.travels__category {
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

.travels__prices {
	padding: 0px 0px 0px 0px;
	margin: 0px;

	font-size: 1.125rem;
}

.travels__prices > li {
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

.travels__prices-item {
	display: grid;
	grid-template-columns: 1fr 100px 50px;
	gap: 5px;
}

.travels__prices-value {
	font-family: "Roboto", sans-serif;
	text-align: right;
	color: var(--button-default-color);
}

@media screen and (width <= 1450px) {
	.travels,
	.travels__list {
		width: auto;
	}

	.filter_blocks {
		width: 100%;
	}
}

@media screen and (width <= 850px) {
	.travels__prices > li {
		margin: 0px 0px 0px 30px;
	}

	.travels__prices-item {
		grid-template-columns: 1fr 50px 50px;
	}

	.travels__prices-name {
		text-overflow: ellipsis;
		word-break: break-all;
	}
}
</style>
