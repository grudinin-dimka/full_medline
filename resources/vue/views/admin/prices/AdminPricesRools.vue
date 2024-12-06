<template>
	<info-bar>
		<template v-slot:title>Цены</template>
		<template v-slot:addreas>prices</template>
	</info-bar>

	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                      ПРАВИЛА                      |-->
	<!--|___________________________________________________|-->
	<block-once :minHeight="200">
		<block-title>
			<template #title>1. Структура</template>
		</block-title>

		<details class="details">
			<summary class="details-title">1.1. Основная информация</summary>
			<div class="details-content">
				<p>
					На строках с 1-10 должны быть расположена следующая информация:
					<img :src="`/storage/other/prices-rools-1-10.jpg`" alt="" />
					В левой части расположен логотип мед. центра. В правой части информация о главном
					враче, дате и название мед. центра.
				</p>
			</div>
		</details>
		<details class="details">
			<summary class="details-title">1.2. Заголовок, адрес, телефон</summary>
			<div class="details-content">
				<p>
					На строках с 11-13 должны быть расположена следующая информация:
					<img :src="`/storage/other/prices-rools-11-13.jpg`" alt="" />
					11 строка - заголовок, 12 строка - адрес, 13 строка - телефон.
				</p>
			</div>
		</details>
		<details class="details">
			<summary class="details-title">1.3. Заголовки таблицы с услугами</summary>
			<div class="details-content">
				<p>
					На строках с 14-15 должны быть расположена следующая информация:
					<img :src="`/storage/other/prices-rools-14-15.jpg`" alt="" />
					14 строка - пустая, 15 строка - заголовки для таблицы (№, Наименование услуги, Цена).
				</p>
			</div>
		</details>
		<details class="details">
			<summary class="details-title">1.4. Категории</summary>
			<div class="details-content">
				<p>
					Начиная со строки 16 (включительно) можно начинать заполнять таблицу. Для того, чтобы
					указать категорию нужно объеденить все столбцы в данной строке, которые используются.
					<img :src="`/storage/other/prices-rools-category.jpg`" alt="" />
				</p>
			</div>
		</details>
		<details class="details">
			<summary class="details-title">1.5. Услуги</summary>
			<div class="details-content">
				<p>
					Услуги заполняются только На строках с 14-15 должны быть расположена следующая
					информация:
					<img :src="`/storage/other/prices-rools-14-15.jpg`" alt="" />
					14 строка - пустая, 15 строка - заголовки для таблицы (№, Наименование услуги, Цена).
				</p>
			</div>
		</details>

		<LoaderChild
			:isLoading="loading.loader.rools"
			:minHeight="300"
			@loaderChildAfterLeave=""
		></LoaderChild>
	</block-once>

	<block-once :minHeight="300">
		<block-title>
			<template #title>2. Оформление</template>
		</block-title>

		<details class="details">
			<summary class="details-title">2.1. Объеденение ячеек</summary>
			<div class="details-content">
				<p>
					Lorem ipsum dolor sit amet consectetur adipisicing elit. Earum omnis beatae itaque!
					Maxime consequuntur architecto, voluptatibus iste aliquid quaerat dolores, culpa
					repellendus optio omnis rem saepe quisquam incidunt, numquam molestias?
				</p>
				<p>
					Lorem ipsum dolor sit amet consectetur adipisicing elit. Earum omnis beatae itaque!
					Maxime consequuntur architecto, voluptatibus iste aliquid quaerat dolores, culpa
					repellendus optio omnis rem saepe quisquam incidunt, numquam molestias?
				</p>
			</div>
		</details>
		<details class="details">
			<summary class="details-title">2.2. Что-то</summary>
			<div class="details-content">
				<p>
					Lorem ipsum dolor sit amet consectetur adipisicing elit. Earum omnis beatae itaque!
					Maxime consequuntur architecto, voluptatibus iste aliquid quaerat dolores, culpa
					repellendus optio omnis rem saepe quisquam incidunt, numquam molestias?
				</p>
			</div>
		</details>
		<details class="details">
			<summary class="details-title">2.3. Что-то</summary>
			<div class="details-content">
				<p>
					Lorem ipsum dolor sit amet consectetur adipisicing elit. Earum omnis beatae itaque!
					Maxime consequuntur architecto, voluptatibus iste aliquid quaerat dolores, culpa
					repellendus optio omnis rem saepe quisquam incidunt, numquam molestias?
				</p>
			</div>
		</details>
		<details class="details">
			<summary class="details-title">2.4. Что-то</summary>
			<div class="details-content">
				<p>
					Lorem ipsum dolor sit amet consectetur adipisicing elit. Earum omnis beatae itaque!
					Maxime consequuntur architecto, voluptatibus iste aliquid quaerat dolores, culpa
					repellendus optio omnis rem saepe quisquam incidunt, numquam molestias?
				</p>
			</div>
		</details>

		<LoaderChild
			:isLoading="loading.loader.rools"
			:minHeight="300"
			@loaderChildAfterLeave=""
		></LoaderChild>
	</block-once>
</template>

<script>
import LoaderChild from "../../../components/includes/LoaderChild.vue";

import BlockOnce from "../../../components/ui/admin/blocks/BlockOnce.vue";
import BlockTitle from "../../../components/ui/admin/blocks/BlockTitle.vue";
import InfoBar from "../../../components/ui/admin/InfoBar.vue";

export default {
	components: {
		LoaderChild,
		BlockOnce,
		BlockTitle,
		InfoBar,
	},
	data() {
		return {
			loading: {
				loader: {
					rools: false,
				},
				sections: {
					rools: false,
				},
			},
		};
	},
};
</script>

<style scoped>
p {
	margin: 0;
	padding: 0;
}

body {
	background: #425995;
	padding-top: 50px;
}

.details {
	position: relative;
	display: block;
	overflow: hidden;

	border-radius: 5px;

	width: 100%;
	background: rgba(255, 255, 255, 0.5);

	transition: 0.3s;
	animation: show-bottom-to-top-15 0.5s ease-in-out;
}

.details::after {
	position: absolute;
	display: block;
	right: 24px;
	top: 10px;

	border-right: 3px solid var(--primary-color);
	border-bottom: 3px solid var(--primary-color);

	content: "";
	width: 14px;
	height: 14px;

	transform: rotate(-45deg);
	transform-origin: center;
	transition: 0.3s;
}

.details[open]::after {
	transform: rotate(45deg);
}

.details[open] {
	padding-bottom: 30px;
}

.details[open] .details-title {
	margin-bottom: 10px;
}

.details-title {
	cursor: pointer;
	list-style: none;

	border-radius: 5px;
	padding: 7px 0px 7px 24px;

	font-size: 20px;
	font-weight: 500;
	background: #fff;
	color: var(--primary-color);

	transition: 0.3s;
}

.details-title::-webkit-details-marker {
	display: none;
}

.details-content {
	padding: 0 24px;
	font-size: 18px;
	margin-bottom: -30px;
}

.details-content > p {
	display: flex;
	flex-direction: column;
	gap: 10px;

	padding-left: 24px;
}

.details-content > p > img {
	margin: auto;
}

.details-content p:last-child {
	margin-bottom: 0;
}

.details-content p:first-child {
	margin-top: 0;
}
</style>
