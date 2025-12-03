<template>
	<info-bar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link :to="`/travels`">Путевки</router-link>
	</info-bar>

	<block :minHeight="100">
		<div class="travels" v-if="travels.length > 0">
			<div
				class="travels__item"
				v-for="travel in travels"
				:class="{ 'skeleton': loading.loader.travels }"
				@click="pushTravel(travel)"
			>
				<div class="travels__item__hit" v-if="travel.hit">
					Хит продаж!
				</div>

				<div class="travels__item__header">
					<div class="travels__item-title">
						{{ travel.title }}
					</div>
				</div>
				<div class="travels__item__body">
					<div class="travels__item-image">
						<img v-if="travel.path" :src="travel.path" loading="lazy" alt="Картинка" />
					</div>
					<div class="travels__item-other">
						<div class="travels__item-other-info">
							<div class="travels__item-info">
								<div class="travels__item-range">
									<template v-if="travel.duration">
										<VueIcon
											:name="'Calendar Month'"
											:fill="'var(--primary-color)'"
											:width="'26px'"
											:height="'26px'"
										/>
										{{ travel.duration }}
									</template>
								</div>
							</div>

							<div class="travels__item-description">
								<VueTiptap :editable="false" :limit="1_000" v-model="travel.description" />
							</div>
						</div>

						<div class="travels__item-price">
							<div
								class="travels__price-food"
								v-for="(prices, key) in travel.food"
								:class="{ 'travels__price-food--disabled': false }"
							>
								<div class="travels__price-food-line">
									{{ key }}
								</div>

								<div class="travels__price-food-name">
									<VueIcon
										v-if="key === 'С питанием'"
										:name="'Restaurant'"
										:fill="'var(--primary-color)'"
										:width="'26px'"
										:height="'26px'"
									/>
									<VueIcon
										v-if="key === 'Без питания'"
										:name="'No Meals'"
										:fill="'var(--primary-color)'"
										:width="'26px'"
										:height="'26px'"
									/>
								</div>
								<div class="travels__price-food-value">
									<div
										class="travels__food-value"
										v-for="price in sortPrices(prices)"
										:class="{ 'travels__food-value--before': price.subtype === 'До' }"
									>
										{{ formatPrice(price.price) }} ₽
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<Empty :minHeight="300" v-else />
	</block>
</template>

<script>
import InfoBar from "../../../components/ui/main/InfoBar.vue";
import Block from "../../../components/ui/main/Block.vue";
import Empty from "../../../components/modules/Empty.vue";
import LoadText from "../../../components/ui/main/LoadText.vue";

import api from "../../../mixin/api";
import fakeDelay from "../../../mixin/fake-delay";

export default {
	components: {
		InfoBar,
		Block,
		Empty,
		LoadText,
	},
	data() {
		return {
			loading: {
				loader: {
					travels: true,
				},
			},

			/* Данные */
			travels: [
				{
					id: 1,
					title: "",
					duration: "",
					description: "",
					order: "",
					image: "",
					path: "",
					prices: "",
					services: "",
				},
				{
					id: 2,
					title: "",
					duration: "",
					description: "",
					order: "",
					image: "",
					path: "",
					prices: "",
					services: "",
				},
				{
					id: 3,
					title: "",
					duration: "",
					description: "",
					order: "",
					image: "",
					path: "",
					prices: "",
					services: "",
				},
				{
					id: 4,
					title: "",
					duration: "",
					description: "",
					order: "",
					image: "",
					path: "",
					prices: "",
					services: "",
				},
			],
		};
	},
	methods: {
		formatPrice(price) {
			return price.toLocaleString("ru-RU");
		},

		sortPrices(food) {
			return food.sort((a, b) => {
				if (a.subtype === "До") return 1;
				if (a.subtype === "После") return -1;
				return 0;
			});
		},

		pushTravel(travel) {
			if (!travel.url) return;

			this.$router.push({
				name: "travels-once",
				params: { name: travel.url },
			});
		},
	},
	created() {
		fakeDelay(this.$store.getters.timeout, () =>
			api({
				method: "get",
				url: this.$store.getters.urlApi + `get-travels`,
			})
		).then((response) => {
			if (!response) return;

			try {
				for (let i = 0; i < response.data.result.length; i++) {
					this.travels[i] = response.data.result[i];
				}

				this.travels.splice(response.data.result.length, this.travels.length);

				this.loading.loader.travels = false;
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
.travels {
	display: grid;
	grid-template-columns: repeat(2, 1fr);
	gap: var(--default-gap);

	margin: 0 auto;

	width: 1350px;
}

.travels__item {
	position: relative;
	cursor: pointer;
	display: flex;
	flex-direction: column;
	gap: calc(var(--default-gap) / 2);

	border-radius: var(--default-border-radius);
	padding: var(--default-padding);

	background-color: var(--skeleton-background-color);
}

.travels__item:hover {
	background-image: linear-gradient(120deg, #ececec 50%, #fafafa 60%, #fafafa 61%, #ececec 70%);
	background-size: 200%;
	background-position: 100% 0;

	animation: waves 2s linear infinite;
}

.travels__item__hit {
	position: absolute;
	top: -15px;
	right: 0px;
	padding: 5px 10px;
	border-radius: 50px;

	color: white;
	background-color: var(--primary-color);
	font-size: 1.2rem;
	font-weight: 500;
}

.travels__item__header {
	display: flex;
	flex-direction: column;
	gap: calc(var(--default-gap) / 2);
}

.travels__item__body {
	position: relative;
	display: grid;
	grid-template-columns: 300px 1fr;
	gap: 10px;
}

.travels__item-title {
	font-size: 1.5rem;
	font-weight: 500;

	color: var(--primary-color);

	min-height: 28px;
}

:is(.travels__item-range, .travels__item-food) {
	display: flex;
	align-items: center;
	gap: 5px;
}

.travels__item-services {
	display: inline-block;

	text-align: justify;
	height: 100%;
	max-height: 310px;

	overflow-y: auto;
}

.travels__item-services-list {
	margin: 10px 0px 10px 0px;
}

/* Скролбар блока с контентом. */
.travels__item-services::-webkit-scrollbar {
	width: 20px;
}

.travels__item-services::-webkit-scrollbar-track {
	background-color: rgb(255, 255, 255);
	border-radius: 10px;
	margin: 2px;
}

.travels__item-services::-webkit-scrollbar-thumb {
	background-color: rgb(220, 220, 220);
	border: 6px solid rgb(255, 255, 255);
	border-radius: 10px;
}

.travels__item-services::-webkit-scrollbar-thumb:hover {
	background-color: rgb(230, 230, 230);
	cursor: all-scroll;
}
/* Конец. */

.travels__item-services-icon {
	display: flex;
	align-items: center;
	justify-content: center;

	float: left;
	margin: 0px 5px 0px 0px;
}

:is(
		.travels__item-range,
		.travels__item-food,
		.travels__item-description,
		.travels__item-services
	) {
	padding: 5px 10px;
	text-align: justify;

	font-size: 1.125rem;
}

.travels__item-other {
	display: flex;
	flex-direction: column;
	gap: calc(var(--default-gap) / 2);
	justify-content: space-between;

	width: 100%;
}

.travels__item-other-info {
	display: flex;
	flex-direction: column;
}

.travels__item-info {
	display: flex;
	gap: 10px;
	justify-content: space-between;

	min-height: 30px;
}

.travels__item-image {
	border-radius: calc(var(--default-border-radius) / 1.5);
	object-fit: contain;

	aspect-ratio: 1 / 1.2;
}

.travels__item-image > img {
	width: 100%;
	height: 100%;
	border-radius: calc(var(--default-border-radius) / 1.5);
	object-fit: cover;

	animation: show 0.5s ease-in-out;
}

.travels__item-description {
	font-size: 1.125rem;
	overflow: hidden;

	max-height: 190px;
}

.travels__item-price {
	display: flex;
	flex-direction: column;
	gap: 10px;

	color: var(--primary-color);
	font-size: 1.5rem;
	font-weight: 500;
}

.travels__food-value--before {
	color: rgba(0, 0, 0, 0.3);
	text-decoration: line-through;
}

.travels__item-price-title {
	display: flex;
	align-items: center;
	font-weight: 400;
	color: black;

	font-size: 1.125rem;
}

.travels__price-food {
	position: relative;
	display: flex;
	justify-content: space-between;
	gap: 5px;

	border-radius: calc(var(--default-border-radius) / 1.5);
	padding: 5px 10px;

	transition: all 0.2s ease-in-out;
}

.travels__price-food-line {
	overflow: hidden;
	opacity: 0;
	position: absolute;
	top: -21px;
	left: 0px;
	right: 0px;
	width: calc();
	text-align: left;
	
	padding: 1px 7px;
	border-radius: calc(var(--default-border-radius) / 1.5) calc(var(--default-border-radius) / 1.5) 0px 0px;
	font-size: 1rem;

	background-color: rgba(0, 0, 0, 0.3);
	color: white;

	transition: all 0.2s ease-in-out;
}

.travels__price-food:hover {
	background-color: rgba(0, 0, 0, 0.1);
	border-radius: 0px 0px calc(var(--default-border-radius) / 1.5) calc(var(--default-border-radius) / 1.5);
}

.travels__price-food:hover .travels__price-food-line {
	overflow: visible;
	opacity: 1;
}

.travels__price-food--disabled {
	color: rgba(0, 0, 0, 0.3);
}

.travels__price-food-value {
	display: flex;
	justify-content: flex-end;
	gap: 5px;

	min-height: 30px;
}

.travels__price-food-name {
	font-size: 1.125rem;
	color: black;
}

@media screen and (max-width: 1450px) {
	.travels {
		width: 100%;
	}
}

@media screen and (max-width: 1250px) {
	.travels {
		grid-template-columns: 1fr;
	}
}

@media screen and (max-width: 700px) {
	.travels__item__body {
		grid-template-columns: 1fr;
	}
}
</style>
