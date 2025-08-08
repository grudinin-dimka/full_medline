<template>
	<info-bar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link :to="`/travels`">Путевки</router-link>
	</info-bar>

	<block :minHeight="100">
		<div class="filters">
			<div class="filters__item">
				<VueInput v-model="filters.name" :type="'search'" :placeholder="'Введите услугу'" />
			</div>

			<div class="filters__item">
				<VueSelector
					v-model="filters.address"
					:placeholder="'Выберите клинику'"
					:cleared="false"
					:list="[]"
				/>

				<VueSelector
					v-model="filters.category"
					:placeholder="'Питание'"
					:cleared="true"
					:list="[
						{ label: 'Включено', value: true },
						{ label: 'Не включено', value: false },
					]"
				/>
			</div>
		</div>

		<div class="travels">
			<div class="travels__item">
				<div class="travels__item__header">
					<div class="travels__item-title">ПОДАРИ ЗДОРОВЬЕ РОДИТЕЛЯМ</div>
				</div>
				<div class="travels__item__body">
					<div class="travels__item-image">
						<img :src="`/storage/img/Vjwl6ugvdfQ.webp`" loading="lazy" alt="" />
					</div>
					<div class="travels__item-other">
						<div class="travels__item-info">
							<div class="travels__item-range">
								<VueIcon
									:name="'Calendar Month'"
									:fill="'var(--primary-color)'"
									:width="'26px'"
									:height="'26px'"
								/>
								5 дней
							</div>
						</div>
						<div class="travels__item-description">
							Наша программа «Подари здоровье родителям» является хитом продаж среди других
							программ!
						</div>
						<div class="travels__item-price">
							<div
								class="travels__price-food"
								:class="{ 'travels__price-food--disabled': false }"
							>
								<div class="travels__price-food-name">
									<VueIcon
										:name="'Fastfood'"
										:fill="'var(--primary-color)'"
										:width="'26px'"
										:height="'26px'"
									/>
								</div>
								<div class="travels__price-food-value">
									<div class="travels__food-value-after">{{ formatPrice(9000) }} ₽</div>
									<div class="travels__food-value-before">{{ formatPrice(11000) }} ₽</div>
								</div>
							</div>
							<div
								class="travels__price-food"
								:class="{ 'travels__price-food--disabled': false }"
							>
								<div class="travels__price-food-name">
									<VueIcon
										:name="'No Food'"
										:fill="'var(--primary-color)'"
										:width="'26px'"
										:height="'26px'"
									/>
								</div>
								<div class="travels__price-food-value">
									<div class="travels__food-value-after">{{ formatPrice(7500) }} ₽</div>
									<div class="travels__food-value-before">{{ formatPrice(9000) }} ₽</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="travels__item">
				<div class="travels__item__hit">Хит продаж!</div>

				<div class="travels__item__header">
					<div class="travels__item-title">ВСЁ ВКЛЮЧЕНО!</div>
				</div>
				<div class="travels__item__body">
					<div class="travels__item-image">
						<img :src="`/storage/img/Vjwl6ugvdfQ.webp`" loading="lazy" alt="" />
					</div>
					<div class="travels__item-other">
						<div class="travels__item-info">
							<div class="travels__item-range">
								<VueIcon
									:name="'Calendar Month'"
									:fill="'var(--primary-color)'"
									:width="'26px'"
									:height="'26px'"
								/>
								10 дней
							</div>
						</div>
						<div class="travels__item-description">
							Наша программа «Подари здоровье родителям» является хитом продаж среди других
							программ!
						</div>
						<div class="travels__item-price">
							<div class="travels__price-food">
								<div class="travels__price-food-name">
									<VueIcon
										:name="'Fastfood'"
										:fill="'var(--primary-color)'"
										:width="'26px'"
										:height="'26px'"
									/>
								</div>
								<div class="travels__price-food-value">
									<div class="travels__food-value-after">{{ formatPrice(30000) }} ₽</div>
									<div class="travels__food-value-before">{{ formatPrice(40000) }} ₽</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</block>
</template>

<script>
import VueInput from "../../../components/modules/input/VueInput.vue";
import VueLoader from "../../../components/modules/VueLoader.vue";
import VueIcon from "../../../components/modules/icon/VueIcon.vue";
import VueSelector from "../../../components/modules/VueSelector.vue";

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
		VueIcon,
		VueSelector,

		InfoBar,
		Block,
		Empty,
		LoadText,
	},
	data() {
		return {
			/* Фильтры */
			filters: {
				name: "",
				address: "",
				category: "",
			},
		};
	},
	methods: {
		formatPrice(price) {
			return price.toLocaleString("ru-RU");
		},
	},
	created() {},
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

	width: 100%;
}

.travels__item-info {
	display: flex;
	gap: 10px;
	justify-content: space-between;
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
}

.travels__item-description {
	font-size: 1.125rem;
	overflow: hidden;
	display: -webkit-box;
	-webkit-line-clamp: 13;
	line-clamp: 13;
	-webkit-box-orient: vertical;

	height: 100%;
}

.travels__item-price {
	display: flex;
	flex-direction: column;
	gap: 10px;

	color: var(--primary-color);
	font-size: 1.5rem;
	font-weight: 500;
}

.travels__food-value-before {
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
	display: flex;
	justify-content: space-between;
	gap: 5px;

	border-radius: calc(var(--default-border-radius) / 1.5);
	padding: 5px 10px;
}

.travels__price-food--disabled {
	color: rgba(0, 0, 0, 0.3);
}

.travels__price-food-value {
	display: flex;
	justify-content: flex-end;
	gap: 5px;
}

.travels__price-food-name {
	font-size: 1.125rem;
	color: black;
}
</style>
