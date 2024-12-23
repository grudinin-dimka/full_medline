<template>
	<nav :class="{ active: $store.state.burger.main.status }">
		<a
			v-for="element in menu"
			:key="element.id"
			class="element"
			:class="{ hiden: isActiv }"
			@click="insertPage(element.name)"
		>
			{{ element.title }}
		</a>
	</nav>
</template>

<script>
export default {
	data() {
		return {
			isActiv: true,
			menu: [
				{ id: "1", title: "Главная", name: "home", href: "home" },
				{ id: "2", title: "О Нас", name: "about", href: "about" },
				{ id: "3", title: "Специалисты", name: "all", href: "/specialists" },
				{ id: "4", title: "Цены", name: "prices", href: "/prices" },
				{ id: "5", title: "Расписание", name: "schedule-all", href: "/schedule" },
				{ id: "6", title: "Контакты", name: "contacts", href: "/contacts" },
			],
		};
	},
	methods: {
		insertPage(page) {
			if (page === "prices") {
				this.$router.push({
					name: `${page}`,
					query: {
						address: "all",
						category: "all",
					},
				});
			} else {
				this.$router.push({ name: `${page}` });
			}

			// смена статуса закрытия бургера
			this.$store.state.burger.main.status = false;
		},
	},
};
</script>

<style scoped>
nav {
	display: grid;
	grid-template-columns: repeat(6, 1fr);

	padding: 10px;
	margin: 0px 30px;
	gap: 10px;

	background-color: #00abbd;
	color: white;
	border-radius: 15px;

	transition: all 0.5s ease-out;
}

nav a {
	padding: 18px 0px;
	border-radius: 10px;

	text-align: center;
	font-size: 20px;

	transition: all 0.15s ease-in-out;
}

nav a:hover {
	background-color: rgba(255, 255, 255, 0.15);
}

.element {
	text-transform: uppercase;

	cursor: pointer;
}

@media screen and (max-width: 1550px) {
	nav {
		display: grid;
		grid-template-columns: repeat(4, 1fr);
	}

	nav a {
		width: auto;
	}
}

@media screen and (max-width: 800px) {
	nav {
		display: grid;
		grid-template-columns: repeat(2, 1fr);
	}

	nav a {
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
	}

	nav.active {
		padding-top: 100px;
		top: 0px;
		left: 0px;
		right: 0px;
		bottom: 0;
	}
}
</style>
