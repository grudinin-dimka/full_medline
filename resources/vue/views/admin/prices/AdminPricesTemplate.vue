<template>
	<info-bar>
		<template v-slot:title>Цены</template>
		<template v-slot:addreas>prices</template>
	</info-bar>

	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                      ПРАВИЛА                      |-->
	<!--|___________________________________________________|-->
	<block-once :minHeight="200">
		<template #title>1. Структура</template>

		<template #body>
			<details class="details">
				<summary class="details-title">1.0. Общий шаблон.</summary>
				<div class="details-content">
					<div class="details-content-img">
						<img :src="`/storage/other/prices-rools.png`" alt="" />
						<span>рис. 1</span>
					</div>
				</div>
			</details>
			<details class="details">
				<summary class="details-title">1.1. Основная информация</summary>
				<div class="details-content">
					<p>На строках с 1-10 должны быть расположена следующая информация (рис. 2):</p>
					<div class="details-content-img">
						<img :src="`/storage/other/prices-rools-1-10.jpg`" alt="" />
						<span>рис. 2</span>
					</div>
					<p>
						В левой части расположен логотип мед. центра. В правой части информация о главном
						враче, дате и название мед. центра.
					</p>
				</div>
			</details>
			<details class="details">
				<summary class="details-title">1.2. Заголовок, адрес, телефон</summary>
				<div class="details-content">
					<p>На строках с 11-13 должны быть расположена следующая информация (рис. 3):</p>
					<div class="details-content-img">
						<img :src="`/storage/other/prices-rools-11-13.jpg`" alt="" />
						<span>рис. 3</span>
					</div>
					<p>11 строка - заголовок, 12 строка - адрес, 13 строка - телефон.</p>
				</div>
			</details>
			<details class="details">
				<summary class="details-title">1.3. Заголовки таблицы с категориями и услугами</summary>
				<div class="details-content">
					<p>
						На строках с 14-15 должны быть расположена следующая информация (рис. 4): 14
						строка - пустая, 15 строка - заголовки для таблицы (№, Наименование услуги, Цена).
					</p>
					<div class="details-content-img">
						<img :src="`/storage/other/prices-rools-14-15.jpg`" alt="" />
						<span>рис. 4</span>
					</div>
					<p>
						14 строка - пустая, 15 строка - заголовки для таблицы (№, Наименование услуги,
						Цена).
					</p>
				</div>
			</details>
			<details class="details">
				<summary class="details-title">1.4. Категории</summary>
				<div class="details-content">
					<p>
						Начиная со строки 16 (включительно) можно начинать заполнять таблицу. Для того,
						чтобы указать категорию нужно объеденить все столбцы в данной строке, которые
						используются (кроме столбца с порядковым номером).
					</p>
					<div class="details-content-img">
						<img :src="`/storage/other/prices-rools-16.jpg`" alt="" />
						<span>рис. 5</span>
					</div>
					<p>
						Для обозначение категории нужно в столбце с порядковым номером вписать # (рис. 5).
						Бывают случаи, когда нужно сделать одну общую категорию для других категорий, для
						этого достаточно добавить # к исходному значению в ячейке с порядковым номер для
						каждой новой вложенный категории (рис. 6).
					</p>
					<div class="details-content-img">
						<img :src="`/storage/other/prices-rools-16-categories.jpg`" alt="" />
						<span>рис. 6</span>
					</div>
				</div>
			</details>
			<details class="details">
				<summary class="details-title">1.5. Услуги</summary>
				<div class="details-content">
					<p>
						Начиная со строки 16 (включительно) можно начинать заполнять таблицу услугами.
						Однако, важно учитывать главное условие: "Услуга всегда идёт только после указания
						выше где-либо категории услуг" (рис. 7-8).
					</p>
					<div class="details-content-img">
						<img :src="`/storage/other/prices-rools-usligi-1.png`" alt="" />
						<span>рис. 7</span>
					</div>
					<p>
						Очень важно, чтобы ваши услуши соответствовали формату заполнения (Номер,
						Наименование, Цена).
					</p>
					<div class="details-content-img">
						<img :src="`/storage/other/prices-rools-usligi-2.png`" alt="" />
						<span>рис. 8</span>
					</div>
				</div>
			</details>
		</template>

		<template #buttons>
			<a :href="`/storage/other/shablon.ods`" download>
				<VueButton>Скачать шаблон </VueButton>
			</a>
		</template>
	</block-once>
</template>

<script>
import BlockOnce from "../../../components/ui/admin/blocks/BlockOnce.vue";
import InfoBar from "../../../components/ui/admin/InfoBar.vue";

import VueButton from "../../../components/ui/VueButton.vue";

export default {
	components: {
		InfoBar,
		BlockOnce,

		VueButton,
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
	display: flex;
	flex-direction: column;
	gap: 10px;

	padding: 0 24px;
	margin-bottom: -30px;

	font-size: 18px;
}

.details-content > p {
	text-indent: 48px;
	line-height: 1.5;
	padding-left: 24px;
}

.details-content > img {
	margin: auto;
}

.details-content p:last-child {
	margin-bottom: 0;
}

.details-content p:first-child {
	margin-top: 0;
}

.details-content-img {
	display: flex;
	flex-direction: column;
	justify-content: center;
	align-items: center;

	margin: 10px;
}

.details-content-img > span {
	margin-top: 10px;
	color: rgb(170, 170, 170);
}

a {
	text-decoration: none;
}
</style>
