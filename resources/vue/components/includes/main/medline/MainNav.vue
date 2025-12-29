<template>
	<nav>
		<div class="nav__list">
			<a
				class="nav__list-item"
				:class="{ active: isActive('/specialists') }"
				href="/specialists"
				@click.prevent="insertPage({ name: `specialists-all` })"
			>
				<VueIcon :name="'Ecg Heart'" :fill="'white'" :width="'28px'" :height="'28px'" />
				Специалисты
			</a>

			<div class="nav__dropdown">
				<div class="nav__dropdown-title nav__list-item">
					<VueIcon :name="'Payments'" :fill="'white'" :width="'28px'" :height="'28px'" />
					Цены

					<div class="nav__item-arrow">
						<VueIcon :name="'Arrow'" :fill="'white'" :width="'20px'" :height="'20px'" :rotate="180" />
					</div>
				</div>
				<div class="nav__dropdown-body">
					<div class="nav__dropdown__body-list">
						<a
							class="nav__list-item"
							:class="{ active: isActive('/prices') }"
							href="/prices"
							@click.prevent="insertPage({ name: `prices-all` })"
						>
							<VueIcon :name="'List'" :fill="'white'" :width="'28px'" :height="'28px'" />
							Список цен
						</a>

						<a
							class="nav__list-item"
							:class="{ active: isActive('/clinics') }"
							href="/prices"
							@click.prevent="insertPage({ name: `prices-clinics` })"
						>
							<VueIcon :name="'Home'" :fill="'white'" :width="'28px'" :height="'28px'" />
							Клиники
						</a>
					</div>
				</div>
			</div>

			<div class="nav__dropdown">
				<div class="nav__dropdown-title nav__list-item">
					<VueIcon :name="'Design Services'" :fill="'white'" :width="'28px'" :height="'28px'" />
					Услуги

					<div class="nav__item-arrow">
						<VueIcon :name="'Arrow'" :fill="'white'" :width="'20px'" :height="'20px'" :rotate="180" />
					</div>
				</div>
				<div class="nav__dropdown-body">
					<div class="nav__dropdown__body-list">
						<a
							class="nav__list-item"
							:class="{ active: isActive('/plastic') }"
							href="/plastic"
							@click.prevent="insertPage({ name: `plastic` })"
						>
							<VueIcon :name="'Surgical'" :fill="'white'" :width="'28px'" :height="'28px'" />
							Пластика
						</a>

						<a
							class="nav__list-item"
							:class="{ active: isActive('/travels') }"
							href="/schedule"
							@click.prevent="insertPage({ name: `travels-all` })"
						>
							<VueIcon :name="'Travel'" :fill="'white'" :width="'28px'" :height="'28px'" />
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
				<VueIcon :name="'Badge'" :fill="'white'" :width="'28px'" :height="'28px'" />
				Вакансии
			</a>

			<a
				class="nav__list-item"
				:class="{ active: isActive('/schedule') }"
				href="/schedule"
				@click.prevent="insertPage({ name: `schedule-all` })"
			>
				<VueIcon :name="'Calendar Month'" :fill="'white'" :width="'28px'" :height="'28px'" />
				Расписание
			</a>

			<div class="nav__dropdown">
				<div class="nav__dropdown-title nav__list-item">
					<VueIcon :name="'Info'" :fill="'white'" :width="'28px'" :height="'28px'" />
					Информация

					<div class="nav__item-arrow">
						<VueIcon :name="'Arrow'" :fill="'white'" :width="'20px'" :height="'20px'" :rotate="180" />
					</div>
				</div>
				<div class="nav__dropdown-body">
					<div class="nav__dropdown__body-list">
						<a
							class="nav__list-item"
							:class="{ active: isActive('/news') }"
							href="/news"
							@click.prevent="insertPage({ name: `news-all` })"
						>
							<VueIcon :name="'News'" :fill="'white'" :width="'28px'" :height="'28px'" />
							Новости
						</a>

						<a
							class="nav__list-item"
							:class="{ active: isActive('/videos') }"
							href="/videos"
							@click.prevent="insertPage({ name: `videos-all` })"
						>
							<VueIcon :name="'Videocam'" :fill="'white'" :width="'28px'" :height="'28px'" />
							Видео
						</a>

						<a
							class="nav__list-item"
							:class="{ active: isActive('/contacts') }"
							href="/contacts"
							@click.prevent="insertPage({ name: `contacts` })"
						>
							<VueIcon :name="'Groups'" :fill="'white'" :width="'28px'" :height="'28px'" />
							Контакты
						</a>

						<a
							class="nav__list-item"
							:class="{ active: isActive('/about') }"
							href="/contacts"
							@click.prevent="insertPage({ name: `about` })"
						>
							<VueIcon :name="'Info I'" :fill="'white'" :width="'28px'" :height="'28px'" />
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
	gap: 10px;

	padding: 10px;
	margin: 0px 30px;

	background-color: var(--primary-color);
	color: white;
	border-radius: 100px;

	transition: all 0.35s ease-out;
}

.nav__list {
	display: grid;
	grid-template-columns: repeat(6, 1fr);
	gap: 10px;

	background-color: var(--primary-color);
	color: white;
	border-radius: 100px;

	transition: all 0.5s ease-out;
}

.nav__list-item,
.nav__dropdown-title {
	text-transform: uppercase;
	cursor: pointer;

	flex: 1 0 auto;
	display: flex;
	align-items: center;
	gap: calc(var(--default-gap) / 2);

	padding: 20px 20px;
	height: 65px;
	border-radius: 100px;

	font-size: 1.25rem;
	color: white;
	text-decoration: none;

	transition: all 0.15s ease-in-out;
}

.nav__list-item:is(.active, :hover) {
	background-color: rgba(255, 255, 255, 0.15);
}

/* Дропдаун */
.nav__list > .nav__dropdown {
	position: relative;
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

.nav__item-arrow {
	position: absolute;
	right: 20px;
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
	nav {
		border-radius: var(--default-border-radius);
	}

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
