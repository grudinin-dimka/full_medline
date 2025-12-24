<template>
	<nav>
		<div class="nav__list">
			<a
				class="nav__list-item"
				:class="{ active: isActive('/specialists') }"
				href="/specialists"
				@click.prevent="insertPage({ name: `specialists-all` })"
			>
				Специалисты
			</a>

			<div class="nav__dropdown">
				<div class="nav__dropdown-title nav__list-item">
					Цены
					<VueIcon class="item-arrow" :name="'Arrow'" :fill="'white'" :width="'20px'" :height="'20px'" :rotate="180" />
				</div>
				<div class="nav__dropdown-body">
					<div class="nav__dropdown__body-list">
						<a
							class="nav__list-item"
							:class="{ active: isActive('/prices') }"
							href="/prices"
							@click.prevent="insertPage({ name: `prices-all` })"
						>
							Список цен
						</a>

						<a
							class="nav__list-item"
							:class="{ active: isActive('/clinics') }"
							href="/prices"
							@click.prevent="insertPage({ name: `prices-clinics` })"
						>
							Клиники
						</a>
					</div>
				</div>
			</div>

			<div class="nav__dropdown">
				<div class="nav__dropdown-title nav__list-item">
					Услуги
					<VueIcon class="item-arrow" :name="'Arrow'" :fill="'white'" :width="'20px'" :height="'20px'" :rotate="180" />
				</div>
				<div class="nav__dropdown-body">
					<div class="nav__dropdown__body-list">
						<a
							class="nav__list-item"
							:class="{ active: isActive('/plastic') }"
							href="/plastic"
							@click.prevent="insertPage({ name: `plastic` })"
						>
							Пластика
						</a>

						<a
							class="nav__list-item"
							:class="{ active: isActive('/travels') }"
							href="/schedule"
							@click.prevent="insertPage({ name: `travels-all` })"
						>
							Путевки
						</a>
					</div>
				</div>
			</div>

			<a
				class="nav__list-item"
				:class="{ active: isActive('/vacancies') }"
				href="/vacancies"
				@click.prevent="insertPage({ name: `vacancies` })"
			>
				Вакансии
			</a>

			<a
				class="nav__list-item"
				:class="{ active: isActive('/schedule') }"
				href="/schedule"
				@click.prevent="insertPage({ name: `schedule-all` })"
			>
				Расписание
			</a>

			<div class="nav__dropdown">
				<div class="nav__dropdown-title nav__list-item">
					Информация
					<VueIcon class="item-arrow" :name="'Arrow'" :fill="'white'" :width="'20px'" :height="'20px'" :rotate="180" />
				</div>
				<div class="nav__dropdown-body">
					<div class="nav__dropdown__body-list">
						<a
							class="nav__list-item"
							:class="{ active: isActive('/news') }"
							href="/news"
							@click.prevent="insertPage({ name: `news-all` })"
						>
							Новости
						</a>

						<a
							class="nav__list-item"
							:class="{ active: isActive('/videos') }"
							href="/videos"
							@click.prevent="insertPage({ name: `videos-all` })"
						>
							Видео
						</a>

						<a
							class="nav__list-item"
							:class="{ active: isActive('/contacts') }"
							href="/contacts"
							@click.prevent="insertPage({ name: `contacts` })"
						>
							Контакты
						</a>

						<a
							class="nav__list-item"
							:class="{ active: isActive('/about') }"
							href="/contacts"
							@click.prevent="insertPage({ name: `about` })"
						>
							О нас
						</a>
					</div>
				</div>
			</div>
		</div>
	</nav>
</template>

<script>
export default {
	methods: {
		/* Активная ссылка */
		isActive(route) {
			return this.$route.path === route;
		},

		insertPage(page) {
			this.$router.push(page);

			this.$store.commit("closeBurgerMain");
		},
	},
};
</script>

<style scoped>
nav {
	display: flex;
	flex-direction: column;
	justify-content: space-between;
	gap: 10px;

	padding: 10px;
	margin: 0px 30px;

	background-color: var(--primary-color);
	color: white;
	border-radius: var(--default-border-radius);

	transition: all 0.35s ease-out;
}

.nav__list {
	display: grid;
	grid-template-columns: repeat(6, auto);
	gap: 10px;

	background-color: var(--primary-color);
	color: white;
	border-radius: var(--default-border-radius);

	transition: all 0.5s ease-out;
}

.nav__list a {
	padding: 18px 0px;
	border-radius: calc(var(--default-border-radius) / 1.5);

	text-align: center;
	font-size: 1.25rem;
	color: white;
	text-decoration: none;

	transition: all 0.15s ease-in-out;
}

.nav__list a:is(.active, :hover) {
	background-color: rgba(255, 255, 255, 0.15);
}

.nav__list-item {
	text-transform: uppercase;

	cursor: pointer;
}

/* Дропдаун */
.nav__list > .nav__dropdown {
	position: relative;
}

.nav__dropdown-title {
	position: relative;
	padding: 20px 0px;
	border-radius: calc(var(--default-border-radius) / 1.5);

	text-align: center;
	font-size: 1.25rem;
	color: white;
	text-decoration: none;

	transition: all 0.15s ease-in-out;
}

.nav__dropdown-title:hover {
	cursor: pointer;
	background-color: rgba(255, 255, 255, 0.15);
}

.nav__dropdown-body {
	visibility: hidden;
	opacity: 0;

	position: absolute;
	width: 100%;
	top: calc(100% - 15px);

	z-index: 10;

	transition: all 0.2s ease-in-out;
}

.nav__dropdown:hover .nav__dropdown-body {
	visibility: visible;
	opacity: 1;
	top: 100%;
}

.nav__dropdown__body-list {
	display: flex;
	flex-direction: column;
	gap: 10px;
	min-width: 100%;

	margin-top: 20px;

	background-color: var(--primary-color);
	padding: 10px;
	border-radius: var(--default-border-radius);

	box-shadow: 0px 0px 15px 0px rgba(0, 0, 0, 0.1);
}

.item-arrow {
	position: absolute;
	right: 10px;
	top: 20px;
}

.nav__buttons {
	display: none;
	gap: 10px;
	flex-direction: column;
}

.nav__buttons > button {
	padding: 18px 0px;
	border-radius: calc(var(--default-border-radius) / 1.5);
	border: 0px;

	text-align: center;
	font-size: 1.25rem;
	color: white;
	text-decoration: none;
	background-color: rgba(0, 0, 0, 0.15);

	transition: all 0.15s ease-in-out;
}

.nav__buttons > button:hover {
	background-color: rgba(0, 0, 0, 0.3);
}

@media screen and (max-width: 1450px) {
	.nav__list {
		display: grid;
		grid-template-columns: repeat(3, 1fr);
	}

	.nav__list a {
		width: auto;
	}	
}

@media screen and (width <= 850px) {
	nav {
		display: none;
	}
}
</style>
