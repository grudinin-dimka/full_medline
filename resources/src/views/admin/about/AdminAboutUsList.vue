<template>
	<div class="about">
		<div class="item">
			<div class="body">
				<div class="images">
					<div
						class="item"
						:style="{
							backgroundImage: `url(/storage/about/1.jpg)`,
						}"
					></div>
					<div
						class="item"
						:style="{
							backgroundImage: `url(/storage/about/2.jpg)`,
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
		</div>
		<div class="item">
			<div class="body">
				<div class="images">
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
					<div
						class="item"
						:style="{
							backgroundImage: `url(/storage/about/6.jpg)`,
						}"
					></div>
				</div>
				<div class="description">
					Под брендом «СанЛайн» в Шадринске действует одноименный санаторий на ул. Октябрьской,
					3 в г. Шадринске. Медицинскую деятельность «СанЛайн» ведёт на основании лицензии №
					Л041-01141-45/00573044 от 08.11.2021 г., выданной Департаментом здравоохранения
					Курганской области. Юридическое лицо – ООО «Медлайн-Проф».
				</div>
			</div>
		</div>
		<div class="item">
			<div class="body">
				<div class="images">
					<div
						class="item"
						:style="{
							backgroundImage: `url(/storage/about/6.jpg)`,
						}"
					></div>
				</div>
				<div class="description">
					Под брендом «СанЛайн» в Шадринске действует одноименный санаторий на ул. Октябрьской,
					3 в г. Шадринске. Медицинскую деятельность «СанЛайн» ведёт на основании лицензии №
					Л041-01141-45/00573044 от 08.11.2021 г., выданной Департаментом здравоохранения
					Курганской области. Юридическое лицо – ООО «Медлайн-Проф».
				</div>
			</div>
		</div>
	</div>
</template>

<script>
export default {
	props: {},
	data() {
		return {};
	},
};
</script>

<style scoped>
.about {
	display: grid;
	grid-template-columns: repeat(3, 1fr);
	grid-auto-rows: 300px;
	gap: 20px;
}

.about > .item {
	cursor: pointer;
	display: flex;
	flex-direction: column;
	gap: 10px;

	border: 2px solid var(--input-border-color-inactive);
	border-radius: 10px;
	padding: 10px;

	font-size: 16px;

	transition: all 0.2s;
}

.about > .item > .body {
	display: flex;
	flex-direction: column;
	gap: 10px;
}

.about > .item:hover {
	border: 2px solid var(--input-border-color-active);
	background-color: #f2feff;
}

.images {
	display: flex;
	align-items: center;
	flex-wrap: wrap;
	gap: 10px;
}

.images > .item {
	flex: 1 0 100px;
	border-radius: 10px;
	min-width: 100px;
	min-height: 150px;
	background-position: center center;
	background-repeat: no-repeat;
	background-size: cover;
}

.description {
	height: 100px;
	overflow-y: scroll;
	border-radius: 10px;
}

.item:hover > .body > .description::-webkit-scrollbar-thumb {
	background-color: rgb(220, 220, 220);
	border: 6px solid #f2feff;
	border-radius: 10px;
}

.description::-webkit-scrollbar {
	width: 18px;
	transition: all 0.2s;
}

.description::-webkit-scrollbar-track {
	background-color: rgba(255, 255, 255, 0);
	border-radius: 10px;
}

.description::-webkit-scrollbar-thumb {
	background-color: rgb(220, 220, 220);
	border: 6px solid #ffffff;
	border-radius: 10px;
}

.description::-webkit-scrollbar-thumb:hover {
	background-color: rgb(210, 210, 210);
	cursor: all-scroll;
}
</style>
