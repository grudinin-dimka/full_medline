<template>
	<!-- Модальное окно: Видео -->
	<Modal ref="modal" :settings="modal">
		<template #title>
			{{ modal.values.title }}
		</template>

		<template #body>
			<VueInput
				v-model="modalForm.data.name.value"
				:type="'text'"
				:placeholder="'Введите Ф.И.О.'"
				:error="modalForm.errors.name.status"
			>
				<template #label> Ф.И.О. </template>
				<template #error>
					{{ modalForm.errors.name.message }}
				</template>
			</VueInput>

			<VueInput
				v-model="modalForm.data.phone.value"
				:type="'phone'"
				:placeholder="'+7 (___) ___-__-__'"
				:error="modalForm.errors.phone.status"
			>
				<template #label> НОМЕР ТЕЛЕФОНА </template>
				<template #error>
					{{ modalForm.errors.phone.message }}
				</template>
			</VueInput>

			<VueInput
				v-model="modalForm.data.email.value"
				:type="'email'"
				:placeholder="'test@mail.ru'"
				:error="modalForm.errors.email.status"
			>
				<template #label> ПОЧТА </template>
				<template #error>
					{{ modalForm.errors.email.message }}
				</template>
			</VueInput>

			<VueInput
				v-model="modalForm.data.date.value"
				:type="'date'"
				:placeholder="'Введите дату'"
				:error="modalForm.errors.date.status"
			>
				<template #label> ДАТА РОЖДЕНИЯ </template>
				<template #error>
					{{ modalForm.errors.date.message }}
				</template>
			</VueInput>

			<VueInput
				v-model="modalForm.data.description.value"
				:type="'textarea'"
				:placeholder="'Введите описание'"
				:error="modalForm.errors.description.status"
			>
				<template #label> ОПИСАНИЕ </template>
				<template #error>
					{{ modalForm.errors.description.message }}
				</template>
			</VueInput>

			<div class="captcha">
				<div class="captcha__content">
					<div class="captcha__content-text">
						<span v-for="letter in getCaptchaSplited" :style="getLetterStyle()">
							{{ letter }}
						</span>

						<div class="content__text-line" ref="line"></div>
						<div class="content__text-trash"></div>
					</div>
					<div class="captcha__content-update" @click="reloadCaptcha">
						<svg
							xmlns="http://www.w3.org/2000/svg"
							height="26px"
							viewBox="0 -960 960 960"
							width="26px"
						>
							<path
								d="M480-160q-134 0-227-93t-93-227q0-134 93-227t227-93q69 0 132 28.5T720-690v-110h80v280H520v-80h168q-32-56-87.5-88T480-720q-100 0-170 70t-70 170q0 100 70 170t170 70q77 0 139-44t87-116h84q-28 106-114 173t-196 67Z"
							/>
						</svg>
					</div>
				</div>

				<VueInput
					v-model="modalForm.data.captcha.value"
					:type="'text'"
					:placeholder="'Введите текст'"
					:error="modalForm.errors.captcha.status"
				>
					<template #label> КОД С КАРТИНКИ </template>
					<template #error>
						{{ modalForm.errors.captcha.message }}
					</template>
				</VueInput>
			</div>

			<VueInput
				v-model="modalForm.data.checkbox.value"
				:type="'checkbox'"
				:error="modalForm.errors.checkbox.status"
			>
				<template #label
					>Согласие на обработку указанных персональных данных в соответствии с
					законодательством Российской Федерации.</template
				>
			</VueInput>
		</template>

		<template #footer>
			<ButtonDefault @click="sendRequest" :disabled="disabled.modalForm.request">
				Отправить
			</ButtonDefault>
		</template>
	</Modal>

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
		<div class="header-buttons">
			<ButtonDefault :look="'white'" @click="openModalEdite('ЗАПИСЬ НА ПРИЕМ')">
				Записаться на прием
			</ButtonDefault>

			<ButtonDefault :look="'white'" @click="openModalEdite('ВЫЗОВ ВРАЧА НА ДОМ')">
				Вызов врача на дом
			</ButtonDefault>
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
import Modal from "../../../modules/modal/Modal.vue";
import Captcha from "../../../modules/Captcha.vue";

import ContainerInput from "../../../ui/admin/containers/ContainerInput.vue";
import ContainerInputOnce from "../../../ui/admin/containers/input/ContainerInputOnce.vue";
import ContainerSelectOnce from "../../../ui/admin/containers/select/ContainerSelectOnce.vue";

import VueInput from "../../../modules/VueInput.vue";

import ButtonDefault from "../../../ui/admin/buttons/ButtonDefault.vue";

import api from "../../../../services/api";
import validate from "../../../../services/validate";
import shared from "../../../../services/shared";

export default {
	components: {
		Modal,

		Captcha,

		ContainerInput,
		ContainerInputOnce,
		ContainerSelectOnce,

		VueInput,

		ButtonDefault,
	},
	data() {
		return {
			isShadow: false,

			/* Модальное окно */
			modal: {
				thin: false,
				clamped: false,
				values: {
					title: "",
					look: "default",
				},
			},

			/* Форма */
			modalForm: {
				errors: {
					name: {
						status: false,
						message: "",
					},
					phone: {
						status: false,
						message: "",
					},
					email: {
						status: false,
						message: "",
					},
					date: {
						status: false,
						message: "",
					},
					description: {
						status: false,
						message: "",
					},
					checkbox: {
						status: false,
						message: "",
					},
					captcha: {
						status: false,
						message: "",
					},
				},
				data: {
					name: {
						value: null,
						edited: false,
					},
					phone: {
						value: null,
						edited: false,
					},
					email: {
						value: null,
						edited: false,
					},
					date: {
						value: null,
						edited: false,
					},
					description: {
						value: null,
						edited: false,
					},
					checkbox: {
						value: false,
						edited: false,
					},
					captcha: {
						value: null,
						edited: false,
					},
				},
			},

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

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                Модальное окно                     |*/
		/* |___________________________________________________|*/
		/* Открытие модального окна */
		openModal(name, title, look) {
			this[name].values.title = title;
			this[name].values.look = look;

			this.$refs[name].open();
		},

		/* Открытие модального окна для добавления */
		openModalEdite(type) {
			shared.clearObjectFull(this.modalForm);
			this.reloadCaptcha();

			this.openModal("modal", type, "default");
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                     КАПТЧА                        |*/
		/* |___________________________________________________|*/
		/* Обновление */
		reloadCaptcha() {
			this.captcha = shared.generateRandomString(5);
			this.$refs.line.style.transform = `rotate(${shared.generateRandomAngle(25)}deg)`;
		},

		/* Стилизация */
		getLetterStyle() {
			return {
				transform: `translateY(${shared.generateRandomAngle(25)}deg)`,
				transform: `rotate(${shared.generateRandomAngle(25)}deg)`,
			};
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                 ОТПРАВКА ЗАПРОСА                  |*/
		/* |___________________________________________________|*/
		/* Отправка запроса */
		sendRequest() {
			if (
				validate.checkInputsAll(this.modalForm, [
					{
						key: "name",
						type: "text",
					},
					{
						key: "phone",
						type: "phone",
					},
					{
						key: "checkbox",
						type: "boolean",
						reference: true,
					},
					{
						key: "captcha",
						type: "twice",
						reference: this.captcha,
					},
				])
			)
				return;

			this.disabled.modalForm.request = true;

			let formData = new FormData();

			api({
				method: "post",
				url: this.$store.getters.urlApi + `request-telegram-bot`,
				headers: {
					ContentType: "multipart/form-data",
				},
				data: {
					title: this.modal.values.title,
					name: this.modalForm.data.name.value,
					phone: this.modalForm.data.phone.value,
					email: this.modalForm.data.email.value,
					date: this.modalForm.data.date.value,
					specialization: this.modalForm.data.description.value,
				},
			})
				.then((response) => {
					if (!response) return;

					this.$refs.modal.close();
				})
				.catch((error) => {
					this.$store.commit("addDebugger", {
						title: "Ошибка.",
						body: error,
						type: "error",
					});
				})
				.finally(() => {
					this.disabled.modalForm.request = false;
				});
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
	align-items: center;
	gap: 10px;

	width: 100%;
}

.captcha__content {
	width: 100%;
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
	background-size: contain;
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
}
</style>
