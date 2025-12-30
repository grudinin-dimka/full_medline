<template>
	<!-- Шапка страницы -->
	<header :class="{ slide: isShadow }">
		<div class="header__item">
			<a href="/" class="header-logo-avatar" @click.prevent="$router.push({ name: 'home' })">
				<img src="../../../../assets/svg/medline/logo.svg" width="100" alt="логотип" />
			</a>
		</div>

		<div class="header__logo">
			<div class="header__logo-name">
				<p>Многопрофильный</p>
				<h2>Медицинский Центр</h2>
				<p class="colorblue">В Шадринске и Курганской области</p>
			</div>
		</div>
		<div class="header__item">
			<div class="header__item-icon">
				<img src="../../../../assets/svg/1.svg" width="30" alt="адреса" />
			</div>

			<div class="header__item-list">
				<a href="https://yandex.ru/maps/-/CDtEvOl2" target="_blank">ул. Комсомольская, 16</a>
				<a href="https://yandex.ru/maps/-/CDtEvSjk" target="_blank">ул. Карла Либкнехта, 10</a>
				<a href="https://yandex.ru/maps/-/CDtEvS~W" target="_blank">ул. Октябрьская, 3</a>
			</div>
		</div>
		<div class="header__item">
			<div class="header__item-icon">
				<img src="../../../../assets/svg/2.svg" width="30" alt="адреса" />
			</div>

			<div class="header__item-list">
				<a href="tel:+73525390009" target="_blank">+7 (35253) 9-000-9</a>
				<a href="tel:+79630070006" target="_blank">+7 (963) 007-00-06</a>
			</div>
		</div>

		<div class="header__buttons">
			<a href="https://lk.medlinegroup.ru/" target="_blank" class="header__buttons-button">
				<VueIcon :name="'Account Circle'" :fill="'black'" :width="'28px'" :height="'28px'" />
				<div class="header__buttons-button-text">Личный кабинет</div>
			</a>
		</div>
	</header>

	<!-- Кнопка "бургер" -->
	<VueBurger :is-active="!$store.getters.getAsideHide" @click="$store.commit('toggleAsideHide')" />
</template>

<script>
import VueBurger from "../../../ui/VueBurger.vue";

export default {
	components: { VueBurger },
	data() {
		return {
			countPoints: 0,

			isShadow: false,

			/* Данные */
			barcodes: [],
			captcha: "",
		};
	},
	methods: {
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾| */
		/* |                      Шапка                        | */
		/* |___________________________________________________| */
		/* Подсветка шапки */
		setShadow() {
			if (window.scrollY > 0 && window.document.documentElement.clientWidth <= 500) {
				this.isShadow = true;
			} else {
				this.isShadow = false;
			}
		},
	},
	mounted() {
		window.addEventListener("scroll", this.setShadow);
	},
};
</script>

<style scoped>
/* Шапка */
header {
	display: flex;
	justify-content: space-between;
	align-items: center;
	flex-wrap: wrap;
	padding: 10px 30px;
	margin: 0px 0px;

	transition: box-shadow 0.5s;
}

header.slide {
	background-color: white;
	box-shadow: 0px 0px 20px 0px rgba(0, 0, 0, 0.2);
}

.header-logo-avatar {
	display: flex;
	justify-content: center;
	width: 150px;
}

.header__logo {
	display: flex;
	flex-direction: row;
	text-transform: uppercase;
}

.header__logo p,
.header__logo h2 {
	text-align: center;
	text-transform: uppercase;
	margin: 0px;
	padding: 0px;
}

.header__item {
	display: flex;
	flex-direction: row;
	justify-content: center;
	align-items: center;
}

.header__item-list {
	display: flex;
	flex-direction: column;

	margin-left: 20px;
}

.header__item-list :is(p, a) {
	margin: 0px;
	padding: 0px;

	text-transform: uppercase;
	font-weight: 500;
}

.header__item a {
	text-decoration: none;
	color: black;

	transition: all 0.15s;
}

.header__item a:hover {
	color: var(--primary-color);
}

.header__item-icon {
	display: flex;
	justify-content: center;
	align-items: center;

	width: 50px;
	height: 50px;
	border-radius: 100px;

	background-color: var(--primary-color-hover);
}

.header__buttons {
	display: flex;
	flex-wrap: wrap;
	gap: 10px;
}

.header__buttons-button {
	cursor: pointer;
	display: flex;
	justify-content: center;
	align-items: center;
	gap: var(--button-gap);

	padding: var(--button-padding);
	border: var(--button-inverse-border);
	border-radius: var(--button-border-radius);

	text-decoration: none;
	font-size: 1.125rem;
	color: black;

	transition: all 0.2s ease-in-out;
}

.header__buttons-button:hover {
	border: var(--button-inverse-border-hover);
	background-color: var(--button-inverse-background-color-hover);
}

.button {
	align-self: center;
	cursor: pointer;

	border: none;
	border-radius: var(--button-border-radius);
	margin: 5px;

	text-transform: uppercase;
	font-size: 14px;

	color: white;
	background-color: var(--button-default-color);
	width: 200px;
	height: 50px;

	transition: background-color 0.15s;
}

.button:hover {
	background-color: var(--button-background-color-hover);
}

.block-shadow {
	box-shadow: -5px 0px 20px rgba(0, 0, 0, 0.3);
}

@media screen and (max-width: 1600px) {
	.header__item:nth-child(3) {
		display: none;
	}
}

@media screen and (max-width: 1400px) {
	.header__logo-name {
		display: none;
	}
}

@media screen and (max-width: 1000px) {
	.header__item:nth-child(4) {
		display: none;
	}
}

@media screen and (width <= 850px) {
	header {
		padding: 10px 30px 10px 30px;
		justify-content: space-between;
	}

	.header__buttons {
		position: absolute;

		left: 20px;
	}

	.header__buttons-button {
		padding: 0px;
		border: 0px;
	}

	.header__buttons-button:hover {
		border: 0px;
		background-color: transparent;
	}

	.header__buttons-button svg {
		width: 45px;
		height: 45px;
	}

	.header__buttons-button-text {
		display: none;
	}

	header {
		z-index: 3;
		position: fixed;
		justify-content: center;
		top: 0px;

		padding: 10px 0px;

		width: 100%;
		margin: 0px;
		background-color: white;
	}
}
</style>
