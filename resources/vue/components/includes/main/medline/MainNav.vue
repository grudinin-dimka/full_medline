<template>
	<nav :class="{ active: $store.getters.burgerMainStatus }">
		<div class="nav__list">
			<a
				class="element"
				:class="{ active: isActive('/specialists') }"
				href="/specialists"
				@click.prevent="$router.push({ name: `specialists-all` })"
			>
				Специалисты
			</a>

			<div class="dropdown">
				<div class="dropdown-title element">
					Цены
					<VueIcon
						class="item-arrow"
						:name="'arrow'"
						:fill="'white'"
						:width="'20px'"
						:height="'20px'"
						:rotate="180"
					/>
				</div>
				<div class="dropdown-body">
					<div class="dropdown-body__list">
						<a
							class="element"
							:class="{ active: isActive('/prices') }"
							href="/prices"
							@click.prevent="$router.push({ name: `prices-all` })"
						>
							Список
						</a>

						<a
							class="element"
							:class="{ active: isActive('/clinics') }"
							href="/prices"
							@click.prevent="$router.push({ name: `prices-clinics` })"
						>
							Клиники
						</a>

						<a
							class="element"
							:class="{ active: isActive('/prices/travels') }"
							href="/prices/travels"
							@click.prevent="
								$router.push({ name: `prices-group`, params: { group: 'travels' } })
							"
						>
							Путевки
						</a>
					</div>
				</div>
			</div>

			<a
				class="element"
				:class="{ active: isActive('/plastic') }"
				href="/plastic"
				@click.prevent="$router.push({ name: `plastic` })"
			>
				Пластическая хирургия
			</a>

			<a
				class="element"
				:class="{ active: isActive('/schedule') }"
				href="/schedule"
				@click.prevent="$router.push({ name: `schedule-all` })"
			>
				Расписание
			</a>

			<div class="dropdown">
				<div class="dropdown-title element">
					Информация
					<VueIcon
						class="item-arrow"
						:name="'arrow'"
						:fill="'white'"
						:width="'20px'"
						:height="'20px'"
						:rotate="180"
					/>
				</div>
				<div class="dropdown-body">
					<div class="dropdown-body__list">
						<a
							class="element"
							:class="{ active: isActive('/news') }"
							href="/news"
							@click.prevent="$router.push({ name: `news-all` })"
						>
							Новости
						</a>

						<a
							class="element"
							:class="{ active: isActive('/videos') }"
							href="/videos"
							@click.prevent="$router.push({ name: `videos-all` })"
						>
							Видео
						</a>

						<a
							class="element"
							:class="{ active: isActive('/contacts') }"
							href="/contacts"
							@click.prevent="$router.push({ name: `contacts` })"
						>
							Контакты
						</a>

						<a
							class="element"
							:class="{ active: isActive('/about') }"
							href="/contacts"
							@click.prevent="$router.push({ name: `about` })"
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
import VueIcon from "../../../modules/icon/VueIcon.vue";

export default {
	components: {
		VueIcon,
	},
	data() {
		return {};
	},
	watch: {
		$route() {
			this.$store.commit("closeBurgerMain");
		},
	},
	methods: {
		/* Активная ссылка */
		isActive(route) {
			return this.$route.path === route;
		},

		insertPage(page) {
			if (page.params !== null) {
				this.$router.push({ name: `${page.name}`, params: page.params });
			} else {
				this.$router.push({ name: `${page.name}` });
			}

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

	transition: all 0.5s ease-out;
}

.nav__list {
	display: grid;
	grid-template-columns: repeat(5, auto);
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

.element {
	text-transform: uppercase;

	cursor: pointer;
}

/* Дропдаун */
.nav__list > .dropdown {
	position: relative;
}

.dropdown-title {
	position: relative;
	padding: 18px 0px;
	border-radius: calc(var(--default-border-radius) / 1.5);

	text-align: center;
	font-size: 1.25rem;
	color: white;
	text-decoration: none;

	transition: all 0.15s ease-in-out;
}

.dropdown-title:hover {
	cursor: pointer;
	background-color: rgba(255, 255, 255, 0.15);
}

.dropdown-body {
	visibility: hidden;
	opacity: 0;

	position: absolute;
	width: 100%;
	top: calc(100% - 15px);

	z-index: 10;
	left: -10px;

	transition: all 0.2s ease-in-out;
}

.dropdown:hover .dropdown-body {
	visibility: visible;
	opacity: 1;
	top: 100%;
}

.dropdown-body__list {
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

@media screen and (max-width: 800px) {
	.nav__list {
		display: grid;
		grid-template-columns: repeat(2, 1fr);
	}

	.nav__list a {
		width: auto;
	}
}

@media screen and (max-width: 750px) {
	nav {
		z-index: 5;
		position: fixed;
		top: -100vh;
		bottom: 100vh;
		left: 0px;
		right: 0px;

		border-radius: 0px;

		display: flex;
		flex-direction: column;
		margin: 0px;

		overflow-y: auto;
	}

	.nav__list {
		display: grid;
		grid-template-columns: repeat(1, 1fr);
	}

	nav.active {
		padding-top: 100px;
		top: 0px;
		left: 0px;
		right: 0px;
		bottom: 0;
	}

	.dropdown-body {
		position: static;
		top: 0px;

		visibility: visible;
		opacity: 1;

		display: none;
	}

	.dropdown:hover .dropdown-body {
		display: block;
	}

	.dropdown-body__list {
		min-width: calc(100% - 20px);

		margin-top: 10px;

		background-color: rgba(255, 255, 255, 0.1);
		padding: 10px;
		border-radius: 10px;

		box-shadow: 0px 0px 15px 0px rgba(0, 0, 0, 0);
	}

	.nav__buttons {
		display: flex;
	}
}
</style>
