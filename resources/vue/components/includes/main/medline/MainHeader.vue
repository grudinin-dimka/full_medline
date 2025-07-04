<template>
	<!-- Модальное окно: Баллы -->
	<VueModal ref="modalPoints" :settings="modalPoints">
		<template #title>
			{{ modalPoints.values.title }}
		</template>

		<template #body>
			<VueInputContainer :direction="'column'" :gap="'10px'">
				<template #legend> КОНТАКТНЫЕ ДАННЫЕ </template>
				<template #inputs>
					<VueInput
						v-model="modalPointsForm.data.name.value"
						:type="'text'"
						:placeholder="'Введите ФИО'"
						:error="modalPointsForm.errors.name.status"
					>
						<template #label> ФИО </template>
						<template #error>
							{{ modalPointsForm.errors.name.message }}
						</template>
					</VueInput>

					<VueInput
						v-model="modalPointsForm.data.snils.value"
						:type="'mask'"
						:mask="'###-###-###-##'"
						:placeholder="'___-___-___-__'"
						:error="modalPointsForm.errors.snils.status"
					>
						<template #label> НОМЕР СНИЛСА </template>
						<template #error>
							{{ modalPointsForm.errors.snils.message }}
						</template>
					</VueInput>
				</template>
			</VueInputContainer>

			<VueInputContainer :direction="'column'" :gap="'10px'">
				<template #legend> ПРОВЕРОЧНЫЙ КОД </template>
				<template #inputs>
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
							v-model="modalPointsForm.data.captcha.value"
							:type="'text'"
							:placeholder="'Введите текст'"
							:error="modalPointsForm.errors.captcha.status"
						>
							<template #label> КОД С КАРТИНКИ </template>
							<template #error>
								{{ modalPointsForm.errors.captcha.message }}
							</template>
						</VueInput>
					</div>
				</template>
			</VueInputContainer>

			<VueInput
				v-model="modalPointsForm.data.checkbox.value"
				:type="'checkbox'"
				:error="modalPointsForm.errors.checkbox.status"
			>
				<template #label
					>Согласие на обработку указанных персональных данных в соответствии с
					законодательством Российской Федерации.</template
				>
			</VueInput>

			<div class="modal__result">
				<template v-if="barcodes.length > 0">
					<div
						class="modal__result-item"
						v-for="barcode in barcodes"
						:class="{ skeleton: disabled.modalPoints.request }"
					>
						<div class="result__item-header">
							<div class="result__header-title">{{ barcode.type }}</div>
							<div class="result__header-value">{{ barcode.value }}</div>
						</div>

						<div class="result__item-barcode">
							<vue-js-barcode
								:value="barcode.barcode_value"
								:width="'2rem'"
								:height="40"
								:format="barcode.barcode_type"
								line-color="#000000"
								background="white"
							></vue-js-barcode>
						</div>
					</div>
				</template>

				<template v-else> </template>
			</div>
		</template>

		<template #footer>
			<VueButton @click="sendClientPoints" :disabled="disabled.modalPoints.request">
				<Icon :name="'info'" :fill="'white'" :width="'26px'" :height="'26px'" />
				Узнать баланс
			</VueButton>
		</template>
	</VueModal>

	<!-- Модальное окно: Заявка -->
	<VueModal ref="modalRequest" :settings="modalRequest">
		<template #title>
			{{ modalRequest.values.title }}
		</template>

		<template #body>
			<VueInputContainer :direction="'column'" :gap="'10px'">
				<template #legend> КОНТАКТНЫЕ ДАННЫЕ </template>
				<template #inputs>
					<VueInput
						v-model="modalRequestForm.data.name.value"
						:type="'text'"
						:placeholder="'Введите ФИО'"
						:error="modalRequestForm.errors.name.status"
					>
						<template #label> ФИО </template>
						<template #error>
							{{ modalRequestForm.errors.name.message }}
						</template>
					</VueInput>

					<VueInput
						v-model="modalRequestForm.data.phone.value"
						:type="'phone'"
						:placeholder="'+7 (___) ___-__-__'"
						:error="modalRequestForm.errors.phone.status"
					>
						<template #label> НОМЕР ТЕЛЕФОНА </template>
						<template #error>
							{{ modalRequestForm.errors.phone.message }}
						</template>
					</VueInput>
				</template>
			</VueInputContainer>

			<VueInputContainer :direction="'column'" :gap="'10px'">
				<template #legend> ДРУГАЯ ИНФОРМАЦИЯ </template>
				<template #inputs>
					<VueInput
						v-model="modalRequestForm.data.description.value"
						:type="'textarea'"
						:placeholder="'Введите описание'"
						:error="modalRequestForm.errors.description.status"
					>
						<template #label> ОПИСАНИЕ </template>
						<template #error>
							{{ modalRequestForm.errors.description.message }}
						</template>
					</VueInput>

					<VueInput
						v-model="modalRequestForm.data.title.value"
						:type="'select'"
						:options="[
							{
								default: true,
								disabled: true,
								value: '',
								label: 'Выберите категорию',
							},
							{
								default: false,
								disabled: false,
								value: 'Запись к врачу',
								label: 'Запись к врачу',
							},
							{
								default: false,
								disabled: false,
								value: 'Вызов врач на дом',
								label: 'Вызов врач на дом',
							},
							{
								default: false,
								disabled: false,
								value: 'Другое',
								label: 'Другое',
							},
						]"
						:error="modalRequestForm.errors.title.status"
					>
						<template #label> ТИП ЗАЯВКИ </template>
						<template #error>
							{{ modalRequestForm.errors.title.message }}
						</template>
					</VueInput>
				</template>
			</VueInputContainer>

			<VueInputContainer :direction="'column'" :gap="'10px'">
				<template #legend> ПРОВЕРОЧНЫЙ КОД </template>
				<template #inputs>
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
							v-model="modalRequestForm.data.captcha.value"
							:type="'text'"
							:placeholder="'Введите текст'"
							:error="modalRequestForm.errors.captcha.status"
						>
							<template #label> КОД С КАРТИНКИ </template>
							<template #error>
								{{ modalRequestForm.errors.captcha.message }}
							</template>
						</VueInput>
					</div>
				</template>
			</VueInputContainer>

			<VueInput
				v-model="modalRequestForm.data.checkbox.value"
				:type="'checkbox'"
				:error="modalRequestForm.errors.checkbox.status"
			>
				<template #label
					>Согласие на обработку указанных персональных данных в соответствии с
					законодательством Российской Федерации.</template
				>
			</VueInput>
		</template>

		<template #footer>
			<VueButton @click="sendRequest" :disabled="disabled.modalForm.request">
				<Icon :name="'phone'" :fill="'white'" :width="'22px'" :height="'22px'" />
				Отправить заявку
			</VueButton>
		</template>
	</VueModal>

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
			<VueButton @click="openModalRequestEdite()">
				<Icon :name="'phone'" :fill="'white'" :width="'22px'" :height="'22px'" />
				Записаться на прием
			</VueButton>
			<VueButton @click="openModalPointsEdite()">
				<Icon :name="'info'" :fill="'white'" :width="'26px'" :height="'26px'" />
				Мой баланс
			</VueButton>
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
import VueModal from "../../../modules/modal/VueModal.vue";
import Captcha from "../../../modules/Captcha.vue";

import VueInput from "../../../modules/input/VueInput.vue";
import VueInputContainer from "../../../modules/input/VueInputContainer.vue";

import VueButton from "../../../ui/VueButton.vue";
import Icon from "../../../modules/icon/VueIcon.vue";
import ButtonDefault from "../../../ui/admin/buttons/ButtonDefault.vue";

import api from "../../../../services/api";
import validate from "../../../../services/validate";
import shared from "../../../../services/shared";

export default {
	components: {
		VueModal,
		Captcha,

		VueInput,
		VueInputContainer,

		VueButton,
		Icon,
		ButtonDefault,
	},
	data() {
		return {
			isShadow: false,

			/* Отключение */
			disabled: {
				modalForm: {
					request: false,
				},
				modalPoints: {
					request: false,
				},
			},

			/* Модальное окно */
			modalPoints: {
				thin: false,
				clamped: true,
				values: {
					title: "",
					look: "default",
				},
			},

			modalRequest: {
				thin: false,
				clamped: true,
				values: {
					title: "",
					look: "default",
				},
			},

			/* Форма */
			modalPointsForm: {
				errors: {
					name: {
						status: false,
						message: "",
					},
					snils: {
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
						value: "",
						edited: false,
					},
					snils: {
						value: "",
						edited: false,
					},
					checkbox: {
						value: false,
						edited: false,
					},
					captcha: {
						value: "",
						edited: false,
					},
					barcode_value: {
						value: "",
						edited: false,
					},
					barcode_type: {
						value: "",
						edited: false,
					},
					points: {
						value: "",
						edited: false,
					},
				},
			},

			modalRequestForm: {
				errors: {
					title: {
						status: false,
						message: "",
					},
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
					title: {
						value: "",
						edited: false,
					},
					name: {
						value: "",
						edited: false,
					},
					phone: {
						value: "",
						edited: false,
					},
					email: {
						value: "",
						edited: false,
					},
					description: {
						value: "",
						edited: false,
					},
					checkbox: {
						value: false,
						edited: false,
					},
					captcha: {
						value: "",
						edited: false,
					},
				},
			},

			/* Данные */
			barcodes: [],
			captcha: "",
		};
	},
	computed: {
		/* Разделение капчи на отдельные символы */
		getCaptchaSplited() {
			let array = [];

			for (let i = 0; i < this.captcha.length; i++) {
				array.push(this.captcha[i]);
			}

			return array;
		},

		/* Разделение капчи на отдельные символы */
		filteredBarcodes() {
			let array = [];

			array = this.barcodes.map((value, index) => {
				return {
					default: false,
					disabled: false,
					value: value.id,
					label: value.type,
				};
			});

			array.unshift({
				default: true,
				disabled: true,
				value: "",
				label: "Выберите карту",
			});

			return array;
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

		/* Открытие модального окна баллов */
		openModalPointsEdite() {
			shared.clearObjectFull(this.modalPointsForm);
			this.barcodes = [
				{
					id: 1,
					type: "Карта 1",
					value: "?",
				},
				{
					id: 2,
					type: "Карта 2",
					value: "?",
				},
			];
			this.reloadCaptcha();

			this.openModal("modalPoints", "БАЛЛЫ", "default");
		},

		/* Открытие модального окна заявки */
		openModalRequestEdite() {
			shared.clearObjectFull(this.modalRequestForm);
			this.reloadCaptcha();

			this.openModal("modalRequest", "ЗАЯВКА", "default");
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
		/* |                 ОТПРАВКА ДАННЫХ                   |*/
		/* |___________________________________________________|*/
		/* Отправка запроса */
		sendRequest() {
			let errors = false;

			if (
				validate.checkInputsAll(this.modalRequestForm, [
					{
						key: "name",
						type: "text",
					},
					{
						key: "phone",
						type: "phone",
					},
					{
						key: "title",
						type: "text",
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
				errors++;

			if (errors) {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: "Заполните важные поля.",
					type: "error",
				});

				return;
			}

			this.disabled.modalForm.request = true;

			let formData = new FormData();

			api({
				method: "post",
				url: this.$store.getters.urlApi + `request-telegram-bot`,
				headers: {
					ContentType: "multipart/form-data",
				},
				data: {
					title: this.modalRequestForm.data.title.value,
					name: this.modalRequestForm.data.name.value,
					phone: this.modalRequestForm.data.phone.value,
					description: this.modalRequestForm.data.description.value,
				},
			})
				.then((response) => {
					if (!response) return;

					this.$refs.modalRequest.close();
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

		/* Отправка запроса */
		sendClientPoints() {
			let errors = false;

			if (
				validate.checkInputsAll(this.modalPointsForm, [
					{
						key: "name",
						type: "text",
					},
					{
						key: "snils",
						type: "text",
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
				errors++;

			if (errors) {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: "Заполните важные поля.",
					type: "error",
				});

				return;
			}

			this.disabled.modalPoints.request = true;
			this.barcodes = [
				{
					id: 1,
					type: "Карта 1",
					value: "?",
				},
				{
					id: 2,
					type: "Карта 2",
					value: "?",
				},
			];

			api({
				method: "post",
				url: this.$store.getters.urlApi + `clieint-points`,
				headers: {
					ContentType: "multipart/form-data",
				},
				data: {
					name: this.modalPointsForm.data.name.value,
					snils: this.modalPointsForm.data.snils.value,
				},
			})
				.then((response) => {
					if (!response) return;

					if (!response.data.result.length) {
						this.$store.commit("addDebugger", {
							title: "Ошибка.",
							body: "К вам не привязана ни одна карта.",
							type: "error",
						});
					} else {
						for (let i = 0; i < response.data.result.length; i++) {
							this.barcodes[i] = response.data.result[i];
						}

						this.barcodes.splice(response.data.result.length, this.barcodes.length);
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
					this.disabled.modalPoints.request = false;
				});
		},
	},
	mounted() {
		window.addEventListener("scroll", this.setShadow);
	},
};
</script>

<style scoped>
/* Баллы */
.modal__result {
	display: grid;
	grid-template-columns: repeat(2, 1fr);
	gap: var(--default-gap);

	margin-top: 10px;

	border-radius: calc(var(--default-border-radius) / 1.5);
}

.modal__result-item {
	box-sizing: border-box;
	display: flex;
	flex-direction: column;
	justify-content: space-between;

	border-radius: calc(var(--default-border-radius) / 2);
	padding: var(--default-padding);

	min-height: 200px;
	background-color: var(--skeleton-background-color);
}

.result__item-header {
	display: flex;
	justify-content: space-between;

	font-size: 1.25rem;
	color: black;
}

.result__item-barcode {
	display: flex;
	flex-direction: column;
	justify-content: center;
	align-items: center;

	border-radius: calc(var(--default-border-radius) / 2);
	background-color: white;
	min-height: 100px;
}

@media screen and (width <= 800px) {
	.modal__result {
		grid-template-columns: repeat(1, 1fr);
	}
}

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

@media screen and (max-width: 900px) {
	header {
		padding: 10px 30px 10px 30px;
		justify-content: space-between;
	}

	.header__logo {
		display: none;
	}
}

@media screen and (max-width: 750px) {
	.header-buttons {
		display: none;
	}

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
