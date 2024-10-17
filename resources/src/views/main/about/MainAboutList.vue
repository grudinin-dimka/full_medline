<template>
	<div class="about">
		<div class="title">
			* Общество с ограниченной ответственностью «Медицинская Линия» (ООО «МедЛайн») организовано
			в 2007 г. Первый филиал открылся в городе Шадринске Курганской области, на улице Карла
			Либкнехта, 10.
		</div>
		<div class="item">
			<div class="images">
				<div
					class="item"
					:style="{
						backgroundImage: `url(/storage/about/2.jpg)`,
					}"
				></div>
				<div
					class="item"
					:style="{
						backgroundImage: `url(/storage/about/3.jpg)`,
					}"
				></div>
			</div>
			<div class="description">
				В настоящее время «МедЛайн» работает в рамках лицензии на осуществление медицинской
				деятельности № Л041-01107-72/00572725 от 11.09.2020 г., выданной Департаментом
				здравоохранения Тюменской области. В г. Шадринске действуют два филиала (ул.
				Комсомольская, 16, ул. К. Либкнехта, 10).
			</div>
		</div>
		<div class="item">
			<div class="images">
				<div
					class="item"
					:style="{
						backgroundImage: `url(/storage/about/3.jpg)`,
					}"
				></div>
				<div
					class="item"
					:style="{
						backgroundImage: `url(/storage/about/4.jpg)`,
					}"
				></div>
				<div
					class="item"
					:style="{
						backgroundImage: `url(/storage/about/5.jpg)`,
					}"
				></div>
			</div>
			<div class="description">
				Под брендом «СанЛайн» в Шадринске действует одноименный санаторий на ул. Октябрьской, 3
				в г. Шадринске. Медицинскую деятельность «СанЛайн» ведёт на основании лицензии №
				Л041-01141-45/00573044 от 08.11.2021 г., выданной Департаментом здравоохранения
				Курганской области. Юридическое лицо – ООО «Медлайн-Проф».
			</div>
		</div>
		<div class="item">
			<div class="images">
				<div
					class="item"
					:style="{
						backgroundImage: `url(/storage/about/6.jpg)`,
					}"
				></div>
			</div>
			<div class="description">
				Под брендом «СанЛайн» в Шадринске действует одноименный санаторий на ул. Октябрьской, 3
				в г. Шадринске. Медицинскую деятельность «СанЛайн» ведёт на основании лицензии №
				Л041-01141-45/00573044 от 08.11.2021 г., выданной Департаментом здравоохранения
				Курганской области. Юридическое лицо – ООО «Медлайн-Проф».
			</div>
		</div>
	</div>
</template>

<script></script>

<style scoped>
.about {
	display: flex;
	flex-direction: column;
	gap: 20px;

	width: 1000px;
	font-size: 18px;
}

.about > .title {
	font-size: 20px;
	font-weight: bold;

	color: var(--primary-color);
}

.about > .item {
	display: flex;
	flex-direction: column;
	gap: 10px;
}

.images {
	display: flex;
	align-items: center;
	flex-wrap: wrap;
	gap: 10px;
}

.images > .item {
	flex: 1 0 300px;
	border-radius: 10px;
	width: 300px;
	height: 300px;
	background-position: center center;
	background-repeat: no-repeat;
	background-size: cover;
}

@media screen and (width <= 1080px) {
	.about {
		width: auto;
	}
}
</style>
