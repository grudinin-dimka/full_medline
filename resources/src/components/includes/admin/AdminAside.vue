<template>
	<aside :class="{ active: $store.state.burger.status }">
		<div class="aside-body">
			<div class="item">
				<a
					:class="{ active: links.home.status }"
					class="item-title"
					href="#"
					@click.prevent="insertPage('ehome')"
				>
					Главная
				</a>
			</div>
			<div class="item">
				<a class="item-title" href="#" @click.prevent="insertPage('eabout')"> О нас </a>
			</div>
			<div class="item">
				<a
					class="item-title"
					:class="{ active: links.specialists.status }"
					href="#"
					@click.prevent="openListEspecialists"
				>
					<IconArrowWhite :width="20" :height="20" :rotate="180" class="item-arrow" />
					Специалисты
				</a>
				<div class="item-list" ref="especialists" :class="{ active: links.specialists.status }">
					<a href="#" @click.prevent="insertSubPage('especialists-all')"> Список </a>
					<a href="#" @click.prevent="insertSubPage('especialists-specializations')">
						Специализации
					</a>
					<a href="#" @click.prevent="insertSubPage('specialists-clinics')"> Клиники </a>
					<a href="#" @click.prevent="insertSubPage('specialists-educations')"> Образование </a>
					<a href="#" @click.prevent="insertSubPage('specialists-works')"> Места работы </a>
					<a href="#" @click.prevent="insertSubPage('specialists-certificates')"> Сертификаты </a>
				</div>
			</div>
			<a class="item-title" href="#" @click.prevent="insertPage('eprice')"> Прайс </a>
		</div>
		<div class="aside-footer">
			<button class="btn-close" @click="logoutUser">Выйти</button>
		</div>
	</aside>
</template>

<script>
import axios from "axios";

import IconArrowWhite from "../../icons/IconArrowWhite.vue";

export default {
	components: {
		axios,
		IconArrowWhite,
	},
	data() {
		return {
			links: {
				home: {
					name: "ehome",
					status: false,
					list: null,
				},
				about: {
					name: "about-us",
					status: false,
					list: null,
				},
				specialists: {
					name: "specialists",
					status: false,
					list: {
						all: false,
						specialization: false,
						clinic: false,
						education: false,
						works: false,
						certificates: false,
					},
				},
				price: {
					name: "eprice",
					status: false,
					list: null,
				},
			},
		};
	},
	methods: {
		// Открыть список специалистов
		openListEspecialists() {
			this.links.specialists.status = !this.links.specialists.status;

			for (let key in this.links) {
				if (key != "specialists") {
					this.links[key].status = false;
				}
			}
		},
		insertPage(page) {
			for (let key in this.links) {
				this.links[key].status = false;
			}

			this.$router.push({ name: `${page}` });

			// смена статуса закрытия бургера
			this.$store.state.burger.status = false;

			// скролл к верху
			window.scrollTo({
				top: 0,
				left: 0,
				behavior: "instant",
			});
		},
		insertSubPage(subPage) {
			this.$router.push({ name: `${subPage}` });

			// смена статуса закрытия бургера
			this.$store.state.burger.status = false;

			// скролл к верху
			window.scrollTo({
				top: 0,
				left: 0,
				behavior: "instant",
			});
		},
		// Выход из аккаунта
		logoutUser() {
			axios({
				method: "post",
				url: `${this.$store.state.axios.urlApi}` + `logout`,
				headers: {
					Accept: "application/json",
					Authorization: `Bearer ${localStorage.getItem("token")}`,
				},
				data: {
					token: localStorage.getItem("token"),
				},
			});
			this.$store.state.burger.status = false;
			localStorage.removeItem("token");
			this.$router.push({ name: "login" });
			return;
		},
	},
};
</script>

<style scoped>
aside {
	position: fixed;
	z-index: 100;
	display: flex;
	flex-direction: column;
	justify-content: space-between;

	width: 350px;
	bottom: 0px;
	top: 80px;

	background-color: var(--primary-color);
	box-shadow: 0px 0px 20px -5px rgba(0, 0, 0, 0.3);

	transition: all 0.5s;
}

aside.active {
	padding-top: 100px;
	top: 0px;
	left: 0px;
	right: 0px;
	bottom: 0;
}

.aside-body {
	padding: 20px;
	display: flex;
	flex-direction: column;
	gap: 5px;
}

.aside-body > .item {
	position: relative;
	box-sizing: border-box;
	display: flex;
	flex-direction: column;
}

.item-title.active:hover {
	background-color: rgba(255, 255, 255, 0.3);
}

.item-title.active {
	background-color: rgba(255, 255, 255, 0.15);
	border-radius: 10px 10px 0px 0px;
}

.item-arrow {
	position: absolute;
	right: 15px;
	top: 23px;
}

.item-arrow:hover + .item-list > a {
	border-radius: 0px;
	background-color: rgba(255, 255, 255, 0.15);
}

.item-list {
	display: none;
}

.item-list.active {
	display: block;
	display: flex;
	flex-direction: column;
}

.item-list > a {
	border-radius: 0px;
	background-color: rgba(255, 255, 255, 0.15);
}

.item-list > a:last-child {
	border-radius: 0px 0px 10px 10px;
}

.item-list > a:hover {
	background-color: rgba(255, 255, 255, 0.3);
}

.aside-footer {
	padding: 20px;
	box-sizing: border-box;
	display: flex;
	flex-direction: column;
	gap: 5px;
}

.aside-footer button {
	cursor: pointer;
}

.btn-close {
	padding: 18px 0px;
	border: 0px;
	border-radius: 10px;

	text-align: center;
	font-size: 22px;
	text-decoration: none;

	color: white;
	background-color: rgba(0, 0, 0, 0.15);

	transition: all 0.15s ease-in-out;
}

.btn-close:hover {
	background-color: rgba(0, 0, 0, 0.3);
}

a {
	padding: 18px 0px;
	border-radius: 10px;
	outline: white;

	text-align: center;
	font-size: 22px;
	text-decoration: none;

	color: white;

	transition: hover 0.15s ease-in-out;
}

a:active {
	background-color: rgba(255, 255, 255, 0.15);
}

aside a:hover {
	background-color: rgba(255, 255, 255, 0.15);
}
@media screen and (max-width: 1000px) {
	aside {
		z-index: 300;
		top: -100vh;
		bottom: 100vh;
		left: 0px;
		right: 0px;

		width: 100%;
	}
}
</style>
