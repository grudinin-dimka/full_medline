<template>
	<info-bar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link to="/prices">Цены</router-link>
	</info-bar>

	<Block :minHeight="500">
		<template v-if="loading.sections.addresses">
			<div class="prices__choice" v-if="getCities.length > 0">
				<div class="prices__item" v-for="city in getCities">
					<div class="prices__item-label">{{ city }}</div>
					<ul>
						<li v-for="street in getStreet(city)" @click="pushStreet(street)">
							<div class="address">{{ `ул. ${street.street}, д. ${street.house}` }}</div>
							<a
								@click.prevent="pushStreet(street)"
								:href="`/prices/${street.cityUrl}/${street.streetUrl}/${street.houseUrl}`"
								alt="смотреть"
							>
								Смотреть
							</a>
						</li>
					</ul>
				</div>
			</div>
			<Empty :minHeight="300" v-else />
		</template>

		<loader-child
			:isLoading="loading.loader.addresses"
			@loaderChildAfterLeave="loaderChildAfterLeave"
		/>
	</Block>
</template>

<script>
import Block from "../../../components/ui/main/Block.vue";
import InfoBar from "../../../components/ui/main/InfoBar.vue";
import LoaderChild from "../../../components/modules/LoaderChild.vue";
import Empty from "../../../components/modules/Empty.vue";

import api from "../../../services/api";
import shared from "../../../services/shared";
import sorted from "../../../services/sorted";

export default {
	components: {
		InfoBar,
		Block,
		LoaderChild,
		Empty,
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

			// Перемещение Шадринска наверх
			let cityShadrinsk = cities.find((item) => item === "Шадринск");

			if (cityShadrinsk) {
				cities.splice(cities.indexOf(cityShadrinsk), 1);
				cities.unshift(cityShadrinsk);
			}

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

		pushStreet(street) {
			this.$router.push({
				name: "prices-template",
				params: {
					city: street.cityUrl,
					street: street.streetUrl,
					house: street.houseUrl,
				},
			});
		},
	},
	mounted() {
		api({
			method: "get",
			url: this.$store.getters.urlApi + `get-prices-choice`,
		})
			.then((response) => {
				if (!response) return;

				this.addresses = response.data.result;
			})
			.catch((error) => {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			})
			.finally(() => {
				this.loading.loader.addresses = false;
			});
	},
};
</script>

<style scoped>
.prices__choice {
	display: grid;
	grid-template-columns: repeat(1, 1fr);
	gap: 20px;

	width: 1350px;
	font-size: 18px;

	animation: show-bottom-to-top-15 0.5s ease-in-out;
}

.prices__item {
	display: flex;
	flex-direction: column;
	gap: 20px;
}

.prices__item-label {
	font-weight: 600;
	font-size: 1.5rem;
	color: var(--primary-color);
}

.prices__item > ul {
	display: flex;
	flex-direction: column;
	gap: 20px;

	margin: 0px;
	padding: 0px;
}

.prices__item > ul > li {
	cursor: pointer;
	display: flex;
	justify-content: space-between;
	align-items: center;
	gap: 10px;

	border: var(--input-border);
	border-radius: var(--input-border-radius);
	padding: 10px;

	font-size: 1.125em;

	transition: all 0.2s;
}

.prices__item > ul > li > a {
	cursor: pointer;
	padding: 10px 20px;
	font-size: 1.125rem;

	text-decoration: none;

	border: 0px;
	border-radius: 10px;
	color: white;
	background-color: var(--button-background-color);

	transition: all 0.2s;
}

.prices__item > ul > li > a:hover {
	background-color: var(--button-background-color-hover);
	color: white;
}

@media screen and (width < 1450px) {
	.prices__choice {
		grid-template-columns: repeat(1, 1fr);
		width: 100%;
	}
}

@media screen and (width < 600px) {
	.prices__item > ul > li {
		flex-direction: column;
	}

	.prices__item > ul > li > a {
		text-align: center;
		box-sizing: border-box;
		width: 100%;
	}
}
</style>
