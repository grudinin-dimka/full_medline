<template>
	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                  МОДАЛЬНОЕ ОКНО                   |-->
	<!--|___________________________________________________|-->
	<!-- Модальное окно: Видео -->
	<Modal ref="modal" :settings="modal">
		<template #title>
			{{ modal.values.title }}
		</template>

		<template #body>
			<container-input>
				<container-input-once type="edit">
					<template #title>
						<span>Ф.И.О.*</span>
						<span v-if="modalForm.data.name.edited"> (ИЗМЕНЕНО)</span>
					</template>
					<template #input>
						<input
							type="text"
							placeholder="Введите Ф.И.О."
							v-model="modalForm.data.name.value"
							:class="{ error: modalForm.errors.name.status }"
							@input="modalForm.data.name.edited = true"
						/>
					</template>
					<template #error>
						<span class="error" v-if="modalForm.errors.name.status">
							{{ modalForm.errors.name.message }}
						</span>
					</template>
				</container-input-once>

				<container-input-once type="edit">
					<template #title>
						<span>НОМЕР ТЕЛЕФОНА*</span>
						<span v-if="modalForm.data.phone.edited"> (ИЗМЕНЕНО)</span>
					</template>
					<template #input>
						<input
							type="tel"
							v-mask="'+7(###)-###-##-##'"
							placeholder="+7(___)___-__-__"
							v-model="modalForm.data.phone.value"
							:class="{ error: modalForm.errors.phone.status }"
							@input="modalForm.data.phone.edited = true"
						/>
					</template>
					<template #error>
						<span class="error" v-if="modalForm.errors.phone.status">
							{{ modalForm.errors.phone.message }}
						</span>
					</template>
				</container-input-once>

				<container-input-once type="edit">
					<template #title>
						<span>ДАТА РОЖДЕНИЯ</span>
						<span v-if="modalForm.data.date.edited"> (ИЗМЕНЕНО)</span>
					</template>
					<template #input>
						<input
							type="date"
							placeholder="Введите дату"
							v-model="modalForm.data.date.value"
							@input="modalForm.data.date.edited = true"
						/>
					</template>
					<template #error></template>
				</container-input-once>

				<container-input-once type="edit">
					<template #title>
						<span>СПЕЦИАЛИЗАЦИЯ ВРАЧА</span>
						<span v-if="modalForm.data.specialization.edited"> (ИЗМЕНЕНО)</span>
					</template>
					<template #input>
						<input
							type="text"
							placeholder="Введите специализацию"
							v-model="modalForm.data.specialization.value"
							:class="{ error: modalForm.errors.specialization.status }"
							@input="modalForm.data.specialization.edited = true"
						/>
					</template>
					<template #error></template>
				</container-input-once>
			</container-input>

			<div class="checkbox" :class="{ error: modalForm.errors.checkbox.status }">
				<div class="content">
					<input
						type="checkbox"
						id="checkboxApprove"
						v-model="modalForm.data.checkbox.value"
					/>
					<label for="checkboxApprove">
						Согласие на обработку указанных персональных данных в соответствии с
						законодательством Российской Федерации.
					</label>
				</div>
			</div>

			<div class="captcha">
				<div class="content">
					<div class="text">
						{{ captcha }}
						<div class="line" ref="line"></div>
					</div>
					<div class="update" @click="reloadCaptcha">
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
				<div class="input">
					<input
						placeholder="Введите текст"
						autocomplete="off"
						v-model="modalForm.data.captcha.value"
						:class="{ error: modalForm.errors.captcha.status }"
					/>
					<div class="input-error">{{ modalForm.errors.captcha.message }}</div>
				</div>
			</div>
		</template>

		<template #footer>
			<ButtonDefault @click="sendRequest" :disabled="disabled.modalForm.request">
				Отправить
			</ButtonDefault>
		</template>
	</Modal>

	<!-- Шапка страницы -->
	<header :class="{ slide: isShadow }">
		<div class="header-block">
			<a href="/" class="header-logo-avatar" @click.prevent="$router.push({ name: 'home' })">
				<img src="../../../assets/svg/logo.svg" width="100" alt="логотип" />
			</a>
		</div>

		<div class="header-logo">
			<div class="header-logo-name">
				<p>Многопрофильный</p>
				<h2>Медицинский Центр</h2>
				<p class="colorblue">В Шадринске и Курганской области</p>
			</div>
		</div>
		<div class="header-block">
			<img src="../../../assets/svg/home.svg" width="50" alt="адреса" />
			<div class="header-block-list">
				<a href="https://yandex.ru/maps/-/CDtEvOl2" target="_blank">ул. Комсомольская, 16</a>
				<a href="https://yandex.ru/maps/-/CDtEvSjk" target="_blank">ул. Карла Либкнехта, 10</a>
				<a href="https://yandex.ru/maps/-/CDtEvS~W" target="_blank">ул. Октябрьская, 3</a>
			</div>
		</div>
		<div class="header-block">
			<img src="../../../assets/svg/phone.svg" width="50" alt="номера" />
			<div class="header-block-list">
				<a href="tel:+73525390009" target="_blank">+7 (35253) 9-000-9</a>
				<a href="tel:+79630070006" target="_blank">+7 (963) 007-00-06</a>
			</div>
		</div>
		<div class="header-buttons">
			<button class="button" @click="openModalEdite('ЗАПИСЬ НА ПРИЕМ')">
				Записаться на прием
			</button>
			<button class="button" @click="openModalEdite('ВЫЗОВ ВРАЧА НА ДОМ')">
				Вызов врача на дом
			</button>
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
import AdminModal from "../admin/AdminModal.vue";
import Modal from "../../modules/modal/Modal.vue";
import Captcha from "../../modules/Captcha.vue";

import ContainerInput from "../../ui/admin/containers/ContainerInput.vue";
import ContainerInputOnce from "../../ui/admin/containers/input/ContainerInputOnce.vue";
import ContainerSelectOnce from "../../ui/admin/containers/select/ContainerSelectOnce.vue";

import BlockButtons from "../../ui/admin/blocks/BlockButtons.vue";
import ButtonDefault from "../../ui/admin/buttons/ButtonDefault.vue";

import axios from "axios";
import validate from "../../../services/validate";
import shared from "../../../services/shared";

export default {
	components: {
		AdminModal,
		Modal,
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

			/* Модальное окно */
			modal: {
				thin: false,
				clamped: true,
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
					date: {
						status: false,
						message: "",
					},
					specialization: {
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
					date: {
						value: null,
						edited: false,
					},
					specialization: {
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
			captcha: null,
			rotate: 0,
		};
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

			formData.append(
				"formData",
				JSON.stringify({
					title: this.modal.values.title,
					name: this.modalForm.data.name.value,
					phone: this.modalForm.data.phone.value,
					date: this.modalForm.data.date.value,
					specialization: this.modalForm.data.specialization.value,
				})
			);

			axios({
				method: "post",
				url: `${this.$store.getters.urlApi}` + `request-telegram-bot`,
				headers: {
					ContentType: "multipart/form-data",
				},
				data: formData,
			})
				.then((response) => {
					if (response.data.status) {
						this.$store.commit("addDebugger", {
							title: "Успешно!",
							body: response.data.message,
							type: "completed",
						});

						this.$refs.modal.close();
					} else {
						this.$store.commit("addDebugger", {
							title: "Ошибка.",
							body: response.data.message,
							type: "error",
						});
					}
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
	justify-content: space-evenly;
	align-items: center;
	flex-wrap: wrap;
	padding: 10px;
	margin: 0px 0px;

	transition: box-shadow 0.5s;
}

header.slide {
	background-color: white;
	box-shadow: 0px 0px 20px 0px rgba(0, 0, 0, 0.2);
}

.header-logo {
	display: flex;
	flex-direction: row;
	text-transform: uppercase;
}

.header-logo p,
.header-logo h2 {
	text-align: center;
	text-transform: uppercase;
	margin: 0px;
	padding: 0px;
}

.header-block {
	display: flex;
	flex-direction: row;
	justify-content: center;
	align-items: center;
}

.header-block .header-block-list {
	display: flex;
	flex-direction: column;

	margin-left: 20px;
}

.header-block .header-block-list p,
.header-block .header-block-list a {
	margin: 0px;
	padding: 0px;

	text-transform: uppercase;
	font-weight: 500;
}

.header-block a {
	text-decoration: none;
	color: black;

	transition: all 0.15s;
}

.header-block a:hover {
	color: var(--button-default-color);
}

.header-buttons {
	display: flex;
	flex-wrap: wrap;
}

.button {
	align-self: center;
	cursor: pointer;

	border: none;
	border-radius: 10px;
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
	background-color: var(--button-default-color);

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

.checkbox > .content {
	display: flex;
	justify-content: center;
	font-size: 18px;
}

.checkbox.error > .content > label {
	color: var(--span-color-error);
}

.checkbox > .content > input {
	width: 17.5px;
	height: 17.5px;
}

.checkbox > .content > input:checked {
	accent-color: #8fe5ee;
}

.captcha {
	display: flex;
	flex-direction: column;
	justify-content: center;
	align-items: center;
	gap: 10px;
}

.captcha > .input > input {
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

.captcha > .input > input.error {
	background-color: var(--input-error-background-color);
	border: var(--input-error-border);

	caret-color: var(--input-error-color);
}

.captcha > .input > input:focus {
	border: var(--input-border-focus);
}

.captcha > .input > input.error:focus {
	border: var(--input-error-border);
}

.captcha > .input > .input-error {
	margin-top: 5px;
	color: var(--input-error-color);
}

.captcha > .content {
	position: relative;
}

.captcha > .content > .text {
	box-sizing: border-box;
	overflow: hidden;
	display: flex;
	justify-content: center;
	align-items: center;
	position: relative;
	user-select: none;
	border: var(--input-border);

	border-radius: var(--input-border-radius);
	background-color: rgb(245, 245, 245);

	width: 300px;
	height: 58px;

	font-family: "Azeret Mono";
	font-style: italic;
	font-weight: 100;
	font-size: 22px;
}

.captcha > .content > .text > .line {
	position: absolute;
	width: 100px;
	height: 1px;

	background-color: rgb(0, 0, 0);
}

.captcha > .content > .update {
	position: absolute;
	cursor: pointer;

	top: 16px;
	right: 5px;
}

.captcha > .content > .update > svg {
	fill: rgb(150, 150, 150);
}

@media screen and (max-width: 1650px) {
	.header-buttons {
		display: none;
	}
}

@media screen and (max-width: 1150px) {
	.header-block:nth-child(3) {
		display: none;
	}
}

@media screen and (max-width: 900px) {
	header {
		padding: 10px 50px 10px 50px;
		justify-content: space-between;
	}

	.header-logo {
		display: none;
	}
}

@media screen and (max-width: 600px) {
	.header-block:nth-child(4) {
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

		padding: 10px;

		width: 100%;
		margin: 0px;
		background-color: white;
	}

	.captcha > .content,
	.captcha > .input,
	.captcha > .input > input,
	.captcha > .content > .text {
		width: 100%;
	}
}
</style>
