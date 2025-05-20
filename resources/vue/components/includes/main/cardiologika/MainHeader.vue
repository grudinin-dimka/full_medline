<template>
	<!-- Шапка страницы -->
	<header :class="{ slide: isShadow }">
		<div class="header__item">
			<a href="/" class="header-logo-avatar" @click.prevent="$router.push({ name: 'home' })">
				<img src="../../../../assets/svg/cardiologika/logo.svg" width="100" alt="логотип" />
			</a>
		</div>

		<div class="header__logo">
			<div class="header__logo-name">
				<p>Многопрофильный</p>
				<h2>Медицинский Центр</h2>
				<p class="colorblue">В Тобольске и Тюменской области</p>
			</div>
		</div>
		<div class="header__item">
			<div class="header__item-icon">
				<img src="../../../../assets/svg/1.svg" width="30" alt="адреса" />
			</div>

			<div class="header__item-list">
				<a href="https://yandex.ru/maps/-/CHvFBP65" target="_blank">8-й микрорайон, 26</a>
				<a href="https://yandex.ru/maps/-/CHvFBX8Q" target="_blank">10-й микрорайон, 15А</a>
			</div>
		</div>
		<div class="header__item">
			<div class="header__item-icon">
				<img src="../../../../assets/svg/2.svg" width="30" alt="адреса" />
			</div>

			<div class="header__item-list">
				<a href="tel:+73525390009" target="_blank">+7 (3456) 26-26-24</a>
				<a href="tel:+79961462624" target="_blank">+7 (996) 146-26-24</a>
			</div>
		</div>
	</header>
	<!-- Кнопка "бургер" -->
	<div
		class="burger"
		@click="$store.commit('setBurgerMain')"
		:class="{ active: $store.getters.burgerMainStatus }"
	>
		<div></div>
		<div></div>
		<div></div>
	</div>
</template>

<script>
import Captcha from "../../../modules/Captcha.vue";

import ContainerInput from "../../../ui/admin/containers/ContainerInput.vue";
import ContainerInputOnce from "../../../ui/admin/containers/input/ContainerInputOnce.vue";
import ContainerSelectOnce from "../../../ui/admin/containers/select/ContainerSelectOnce.vue";

import BlockButtons from "../../../ui/admin/blocks/BlockButtons.vue";
import ButtonDefault from "../../../ui/admin/buttons/ButtonDefault.vue";

import axios from "axios";
import validate from "../../../../services/validate";
import shared from "../../../../services/shared";

export default {
	components: {
		Captcha,
		ContainerInput,
		ContainerInputOnce,
		ContainerSelectOnce,
		BlockButtons,
		ButtonDefault,
		axios,
		validate,
		shared,
	},
	data() {
		return {
			isShadow: false,

			disabled: {
				modalForm: {
					request: false,
				},
			},
			captcha: "",
			rotate: 0,
		};
	},
	computed: {
		/* Разделение капчи на отдельные символы */
		getCaptchaSplited() {
			let slplited = [];

			for (let i = 0; i < this.captcha.length; i++) {
				slplited.push(this.captcha[i]);
			}

			return slplited;
		},
	},
	methods: {
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

.header-buttons {
	display: flex;
	flex-wrap: wrap;
	gap: 10px;
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

.burger {
	cursor: pointer;
	display: none;
	position: fixed;
	z-index: 100;

	top: 30px;
	right: 30px;
	height: 30px;
}

.burger div:nth-child(1),
.burger div:nth-child(2),
.burger div:nth-child(3) {
	position: relative;

	width: 30px;
	height: 3px;
	background-color: var(--primary-color);

	transition: all 0.5s;
}

.burger div:nth-child(2) {
	top: 7px;
}

.burger div:nth-child(3) {
	top: 15px;
}

.burger.active div:nth-child(1) {
	top: 10px;
	position: absolute;

	background-color: white;
	transform: rotate(-45deg);
}

.burger.active div:nth-child(2) {
	position: absolute;
}

.burger.active div:nth-child(2),
.burger.active div:nth-child(3) {
	top: 10px;

	background-color: white;
	transform: rotate(45deg);
}

.block-shadow {
	box-shadow: -5px 0px 20px rgba(0, 0, 0, 0.3);
}

.checkbox > .captcha__content {
	display: flex;
	justify-content: center;
	font-size: 18px;
}

.checkbox.error > .captcha__content > label {
	color: var(--span-color-error);
}

.checkbox > .captcha__content > input {
	width: 17.5px;
	height: 17.5px;
}

.captcha__content > input:checked {
	accent-color: #8fe5ee;
}

.captcha {
	display: flex;
	flex-direction: column;
	justify-content: center;
	align-items: center;
	gap: 10px;
}

.captcha__input > input {
	user-select: none;
	box-sizing: border-box;
	outline: none;
	text-align: center;

	padding: 10px;
	border: var(--input-border);
	border-radius: var(--input-border-radius);

	width: 300px;
	height: 58px;

	font-size: 20px;
	caret-color: var(--input-caret-color);
	background-color: white;

	transition: all 0.2s;
}

.captcha__input > input.error {
	background-color: var(--input-error-background-color);
	border: var(--input-error-border);

	caret-color: var(--input-error-color);
}

.captcha__input > input:focus {
	border: var(--input-border-focus);
}

.captcha__input > input.error:focus {
	border: var(--input-error-border);
}

.captcha__input > .input-error {
	margin-top: 5px;
	color: var(--input-error-color);
}

.captcha__content {
	position: relative;
}

.captcha__content-text {
	box-sizing: border-box;
	overflow: hidden;
	display: flex;
	justify-content: center;
	align-items: center;
	position: relative;
	user-select: none;
	border: var(--input-border);

	border-radius: var(--input-border-radius);
	color: rgb(0, 0, 0);

	width: 300px;
	height: 58px;

	font-family: "Henny Penny";
	font-weight: lighter;
	font-size: 2rem;
	letter-spacing: 10px;
}

.captcha__content-text > span {
	font-family: "Henny Penny";
	font-weight: lighter;
	font-size: 2rem;
}

.content__text-line {
	position: absolute;
	z-index: 0;
	width: 350px;
	height: 1px;

	background-color: rgb(140, 140, 140);
}

.content__text-trash {
	position: absolute;
	z-index: 0;
	width: 100%;
	height: 100%;

	background-image: url("../../../../assets/img/trash.webp");
	background-position: center center;
	background-size: cover;
	opacity: 0.7;
}

.captcha__content-update {
	position: absolute;
	cursor: pointer;
	display: flex;
	justify-content: center;
	align-items: center;

	background-color: white;
	padding: 5px;
	border-radius: 100px;
	border: var(--default-border);

	top: 11px;
	right: 5px;
}

.captcha__content-update > svg {
	fill: rgb(0, 0, 0);
}

@media screen and (max-width: 1650px) {
	.header-buttons {
		display: none;
	}
}

@media screen and (max-width: 1150px) {
	.header__item:nth-child(3) {
		display: none;
	}
}

@media screen and (max-width: 900px) {
	header {
		padding: 10px 50px 10px 50px;
		justify-content: space-between;
	}

	.header__logo {
		display: none;
	}
}

@media screen and (max-width: 600px) {
	.header__item:nth-child(4) {
		display: none;
	}
}

@media screen and (max-width: 500px) {
	.burger {
		display: block;
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

	.captcha > .captcha__content,
	.captcha > .captcha__input,
	.captcha > .captcha__input > input,
	.captcha > .captcha__content > .captcha__content-text {
		width: 100%;
	}
}
</style>
