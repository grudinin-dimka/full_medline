<template>
	<info-bar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link to="/prices">Цены</router-link>
	</info-bar>

	<Block :minHeight="500">
		<div class="prices-choice" v-if="loading.sections.addresses">
			<div class="item" v-for="city in getCities">
				<div class="label">{{ city }}</div>
				<ul>
					<li v-for="street in getStreet(city)">
						<div class="address">{{ `ул. ${street.street}, д. ${street.house}` }}</div>
						<button
							@click="
								$router.push({
									name: 'prices-template',
									params: {
										city: street.cityUrl,
										street: street.streetUrl,
										house: street.houseUrl,
									},
								})
							"
						>
							Смотреть
						</button>
					</li>
				</ul>
			</div>
		</div>

		<loader-child
			:isLoading="loading.loader.addresses"
			@loaderChildAfterLeave="loaderChildAfterLeave"
		/>
	</Block>
</template>

<script>
import Block from "../../../components/ui/main/blocks/Block.vue";
import InfoBar from "../../../components/ui/main/InfoBar.vue";
import LoaderChild from "../../../components/includes/LoaderChild.vue";

import shared from "../../../services/shared";
import sorted from "../../../services/sorted";
import axios from "axios";

export default {
	components: {
		InfoBar,
		Block,
		LoaderChild,
		shared,
		axios,
	},
	computed: {
		getCities() {
			let cities = [];

			this.addresses.forEach((address) => {
				if (!cities.includes(address.city)) {
					cities.push(address.city);
				}
			});

			sorted.sortString("up", cities);

			return cities;
		},
	},
	data() {
		return {
			loading: {
				loader: {
					addresses: true,
				},
				sections: {
					addresses: false,
				},
			},
			addresses: [],
		};
	},
	methods: {
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                   Загрузчик                       |*/
		/* |___________________________________________________|*/
		/* После скрытия элементы */
		loaderChildAfterLeave() {
			this.loading.sections.addresses = true;
		},
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                    Геттеры                        |*/
		/* |___________________________________________________|*/
		getStreet(city) {
			let filteredAddresses = this.addresses.filter((address) => {
				return address.city == city;
			});

			sorted.sortStringByKey("up", filteredAddresses, "street");

			return filteredAddresses;
		},
	},
	mounted() {
		axios({
			method: "post",
			url: `${this.$store.state.axios.urlApi}` + `get-prices-choice`,
		})
			.then((response) => {
				if (response.data.status) {
					this.addresses = response.data.data;
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
				this.loading.loader.addresses = false;
			});
	},
};
</script>

<style scoped>
.prices-choice {
	display: grid;
	grid-template-columns: repeat(1, 1fr);
	gap: 20px;

	width: 1350px;
	font-size: 18px;

	animation: show-bottom-to-top-15 0.5s ease-in-out;
}

.prices-choice > .item {
	display: flex;
	flex-direction: column;
	gap: 20px;
}

.prices-choice > .item > .label {
	font-weight: 600;
	font-size: 1.5rem;
	color: var(--primary-color);
}

.prices-choice > .item > ul {
	display: flex;
	flex-direction: column;
	gap: 20px;

	margin: 0px;
	padding: 0px;
}

.prices-choice > .item > ul > li {
	display: flex;
	justify-content: space-between;
	align-items: center;
	gap: 10px;

	border: 2px solid var(--input-border-color-inactive);
	border-radius: 10px;
	padding: 10px;

	font-size: 1.125em;

	transition: all 0.2s;
}

.prices-choice > .item > ul > li > button {
	cursor: pointer;
	padding: 10px 20px;
	font-size: 1.125rem;

	color: white;
	border: 0px;
	border-radius: 10px;
	background-color: var(--primary-color);

	transition: all 0.2s;
}

.prices-choice > .item > ul > li > button:hover {
	background-color: var(--primary-color);
	color: white;
}

@media screen and (width < 1450px) {
	.prices-choice {
		grid-template-columns: repeat(1, 1fr);
		width: 100%;
	}
}
</style>
