<template>
	<nav :class="{ active: $store.getters.burgerMainStatus }">
		<div class="nav__list">
			<a
				class="element"
				:class="{ active: isActive('/specialists') }"
				href="/specialists"
				@click.prevent="insertPage({ name: `specialists-all` })"
			>
				Специалисты
			</a>

			<a
				class="element"
				:class="{ active: isActive('/prices') }"
				href="/prices"
				@click.prevent="insertPage({ name: `prices-all` })"
			>
				Цены
			</a>

			<a
				class="element"
				:class="{ active: isActive('/schedule') }"
				href="/schedule"
				@click.prevent="insertPage({ name: `schedule-all` })"
			>
				Расписание
			</a>

			<a
				class="element"
				:class="{ active: isActive('/contacts') }"
				href="/contacts"
				@click.prevent="insertPage({ name: `contacts` })"
			>
				Контакты
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
							@click.prevent="insertPage({ name: `news-all` })"
						>
							Новости
						</a>

						<a
							class="element"
							:class="{ active: isActive('/videos') }"
							href="/videos"
							@click.prevent="insertPage({ name: `videos-all` })"
						>
							Видео
						</a>

						<a
							class="element"
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
import VueIcon from "../../../modules/icon/VueIcon.vue";

export default {
	components: {
		VueIcon,
	},
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

	transition: all 0.5s ease-out;
}

/* Скролбар блока с контентом. */
nav::-webkit-scrollbar {
	width: 20px;
}

nav::-webkit-scrollbar-track {
	background-color: rgba(255, 255, 255, 0);
	border-radius: 10px;
	margin: 2px;
}

nav::-webkit-scrollbar-thumb {
	background-color: rgba(255, 255, 255, 0.3);
	border: 6px solid var(--primary-color);
	border-radius: 10px;
}

nav::-webkit-scrollbar-thumb:hover {
	background-color: rgba(255, 255, 255, 0.5);
	cursor: all-scroll;
}
/* Конец. */

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

@media screen and (max-width: 500px) {
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
