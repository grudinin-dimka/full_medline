<template>
	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                  МОДАЛЬНОЕ ОКНО                   |-->
	<!--|___________________________________________________|-->
	<!-- TODO: Сделать проверку полей ввода -->
	<admin-modal ref="modal" @touchCloseModal="closeModal" :modal="modal">
		<template #title>
			<span>{{ modal.title }}</span>
		</template>
		<template #body>
			<!-- Ф.И.О. Пациента -->
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
							v-model="modalForm.data.name.body"
							:class="{ error: modalForm.errors.name.status }"
							@input="modalForm.data.name.edited = true"
							@blur="checkModalInput('modalForm', 'name', 'text')"
						/>
					</template>
					<template #error>
						<span class="error" v-if="modalForm.errors.name.status">
							{{ modalForm.errors.name.body }}
						</span>
					</template>
				</container-input-once>
				<!-- Номер телефона -->
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
							v-model="modalForm.data.phone.body"
							:class="{ error: modalForm.errors.phone.status }"
							@input="modalForm.data.phone.edited = true"
							@blur="checkModalInput('modalForm', 'phone', 'phone')"
						/>
					</template>
					<template #error>
						<span class="error" v-if="modalForm.errors.phone.status">
							{{ modalForm.errors.phone.body }}
						</span>
					</template>
				</container-input-once>
				<!-- Дата рождения -->
				<container-input-once type="edit">
					<template #title>
						<span>ДАТА РОЖДЕНИЯ</span>
						<span v-if="modalForm.data.date.edited"> (ИЗМЕНЕНО)</span>
					</template>
					<template #input>
						<input
							type="date"
							placeholder="Введите дату"
							v-model="modalForm.data.date.body"
							@input="modalForm.data.date.edited = true"
						/>
					</template>
					<template #error></template>
				</container-input-once>
				<!-- Специальность врача -->
				<container-input-once type="edit">
					<template #title>
						<span>СПЕЦИАЛИЗАЦИЯ ВРАЧА</span>
						<span v-if="modalForm.data.specialization.edited"> (ИЗМЕНЕНО)</span>
					</template>
					<template #input>
						<input
							type="text"
							placeholder="Введите специализацию"
							v-model="modalForm.data.specialization.body"
							:class="{ error: modalForm.errors.specialization.status }"
							@input="modalForm.data.specialization.edited = true"
						/>
					</template>
					<template #error></template>
				</container-input-once>
			</container-input>
			<div class="checkbox" :class="{ error: modalForm.errors.checkbox.status }">
				<div class="content">
					<input type="checkbox" id="checkboxApprove" v-model="modalForm.data.checkbox.body" />
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
						v-model="modalForm.data.captcha.body"
						:class="{ error: modalForm.errors.captcha.status }"
						@blur="checkModalInput('modalForm', 'captcha', 'captcha')"
					/>
					<div class="input-error">{{ modalForm.errors.captcha.body }}</div>
				</div>
			</div>
		</template>
		<template #footer>
			<BlockButtons>
				<ButtonDefault @click="sendRequest" :disabled="disabled.modalForm.request">
					Отправить
				</ButtonDefault>
			</BlockButtons>
		</template>
	</admin-modal>

	<!-- Шапка страницы -->
	<header>
		<div class="header-block">
			<a href="/" class="header-logo-avatar" @click.prevent="$router.push({ name: 'home' })">
				<img src="../../../assets/svg/logo.svg" width="100" />
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
			<img src="../../../assets/svg/home.svg" width="50" />
			<div class="header-block-list">
				<a href="https://yandex.ru/maps/-/CDtEvOl2" target="_blank">ул. Комсомольская, 16</a>
				<a href="https://yandex.ru/maps/-/CDtEvSjk" target="_blank">ул. Карла Либкнехта, 10</a>
				<a href="https://yandex.ru/maps/-/CDtEvS~W" target="_blank">ул. Октябрьская, 3</a>
			</div>
		</div>
		<div class="header-block">
			<img src="../../../assets/svg/phone.svg" width="50" />
			<div class="header-block-list">
				<a href="tel:+73525390009" target="_blank">+7 (35253) 9-000-9</a>
				<a href="tel:+73525332936" target="_blank">+7 (35253) 3-29-36</a>
				<a href="tel:+79630070006" target="_blank">+7 (963) 007-00-06</a>
			</div>
		</div>
		<div class="header-buttons">
			<button class="button" @click="openModal('edit', 'ЗАПИСЬ НА ПРИЁМ')">
				Записаться на прием
			</button>
			<button class="button" @click="openModal('edit', 'ВЫЗОВ ВРАЧА НА ДОМ')">
				Вызов врача на дом
			</button>
		</div>
	</header>
	<!-- Кнопка "бургер" -->
	<div
		class="burger"
		@click="$store.commit('setBurgerMain')"
		:class="{ active: $store.state.burger.main.status }"
	>
		<div></div>
		<div></div>
		<div></div>
	</div>
</template>

<script>
import AdminModal from "../admin/AdminModal.vue";
import Captcha from "../Captcha.vue";

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
			nameTitle: "",
			modal: {
				title: "",
				status: false,
				type: null,
				style: {
					create: false,
					delete: false,
				},
				modules: {
					title: true,
					buttons: {
						hide: false,
						close: true,
					},
					images: false,
					body: true,
					footer: true,
				},
			},
			modalForm: {
				data: {
					name: {
						body: null,
						edited: false,
					},
					phone: {
						body: null,
						edited: false,
					},
					date: {
						body: null,
						edited: false,
					},
					specialization: {
						body: null,
						edited: false,
					},
					checkbox: {
						body: null,
						edited: false,
					},
					captcha: {
						body: null,
						edited: false,
					},
				},
				errors: {
					name: {
						body: "",
						status: false,
					},
					phone: {
						body: "",
						status: false,
					},
					date: {
						body: "",
						status: false,
					},
					specialization: {
						body: "",
						status: false,
					},
					checkbox: {
						body: "",
						status: false,
					},
					captcha: {
						body: "",
						status: false,
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
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                 Модальное окно                    |*/
		/* |___________________________________________________|*/
		/* _____________________________________________________*/
		/* 1. Основные действия                                 */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		/* Открытие */
		openModal(type, title) {
			this.clearModalData("modalForm");
			this.clearModalErrors("modalForm");
			this.reloadCaptcha();
			this.modal.title = title;

			switch (type) {
				case "create":
					{
						this.modal.type = "create";
						this.modal.status = true;
						this.modal.style.create = true;
						this.modal.style.delete = false;
						// this.clearModalData();
					}
					document.body.classList.add("modal-open");
					break;
				case "edit":
					{
						this.modal.type = "edit";
						this.modal.status = true;
						this.modal.style.create = false;
						this.modal.style.delete = false;
					}
					document.body.classList.add("modal-open");
					break;
				default:
					{
						let debbugStory = {
							title: "Ошибка.",
							body: "Низвестный тип открытия модального окна.",
							type: "Error",
						};
						this.$store.commit("debuggerState", debbugStory);
					}
					break;
			}
		},
		/* Закрытие */
		closeModal() {
			this.modal.status = false;
			document.body.classList.remove("modal-open");
		},

		changeModal(title) {
			if (title == "priem") {
				this.$store.commit("changeModal", "Записаться на прием");
			} else if (title == "vizov") {
				this.$store.commit("changeModal", "Вызов врача на дом");
			}
		},
		/* _____________________________________________________*/
		/* 2. Работа с полями ввода модального окна             */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		// Проверка введенного текстового значения
		checkInputText(value) {
			/* Проверка на пустую строку */
			if (value === "" || value === null) {
				return {
					status: true,
					message: "Поле не может быть пустым.",
				};
			}

			/* Проверка на соответствие типу string */
			if (typeof value != "string") {
				return {
					status: true,
					message: "Тип данных не совпадает.",
				};
			}

			return {
				status: false,
				message: "Ошибок нет.",
			};
		},
		// Проверка введенного текстового значения
		checkInputNumber(value) {
			/* Проверка на пустую строку */
			if (value === "" || value === null) {
				return {
					status: true,
					message: "Поле не может быть пустым.",
				};
			}

			/* Проверка на соответствие типу Number */
			if (Number.isNaN(Number(value))) {
				return {
					status: true,
					message: "Тип данных не совпадает.",
				};
			}

			return {
				status: false,
				message: "Ошибок нет.",
			};
		},
		/* Проверка введенного текстового значения */
		checkInputPhone(value) {
			/* Проверка на пустую строку */
			if (value === "" || value === null) {
				return {
					status: true,
					message: "Поле не может быть пустым.",
				};
			}

			/* Проверка на соответствие типу string */
			if (typeof value != "string") {
				return {
					status: true,
					message: "Тип данных не совпадает.",
				};
			}

			if (!validate.checkPhone(value)) {
				return {
					status: true,
					message: "Телефон не прошел проверку.",
				};
			}

			return {
				status: false,
				message: "Ошибок нет.",
			};
		},
		checkInputCaptcha(value) {
			/* Проверка на пустую строку */
			if (value === "" || value === null) {
				return {
					status: true,
					message: "Поле не может быть пустым.",
				};
			}

			/* Проверка на соответствие типу string */
			if (typeof value != "string") {
				return {
					status: true,
					message: "Тип данных не совпадает.",
				};
			}

			if (value != this.captcha) {
				return {
					status: true,
					message: "Значение не совпадает.",
				};
			}

			return {
				status: false,
				message: "Ошибок нет.",
			};
		},
		checkInputCheckbox(value) {
			/* Проверка на пустую строку */
			if (!value) {
				return {
					status: true,
					message: "Поле не может быть пустым.",
				};
			}

			return {
				status: false,
				message: "Ошибок нет.",
			};
		},
		// Проверка поля имени
		checkModalInput(currentName, dataKey, inputType) {
			let errorLog = {};
			switch (inputType) {
				case "text":
					errorLog = this.checkInputText(this[currentName].data[dataKey].body);
					break;
				case "number":
					errorLog = this.checkInputNumber(this[currentName].data[dataKey].body);
					break;
				case "phone":
					errorLog = this.checkInputPhone(this[currentName].data[dataKey].body);
					break;
				case "captcha":
					errorLog = this.checkInputCaptcha(this[currentName].data[dataKey].body);
					break;
				case "checkbox":
					errorLog = this.checkInputCheckbox(this[currentName].data[dataKey].body);
					break;
				default:
					break;
			}

			if (errorLog.status) {
				this[currentName].errors[dataKey].body = errorLog.message;
				this[currentName].errors[dataKey].status = true;

				return true;
			} else {
				this[currentName].errors[dataKey].body = "";
				this[currentName].errors[dataKey].status = false;

				return false;
			}
		},
		// Проверка всех полей ввода модального окна
		checkModalInputsAll(inputKeys) {
			let errorCount = 0;
			for (let i = 0; i < inputKeys.length; i++) {
				switch (inputKeys[i]) {
					case "phone":
						if (this.checkModalInput("modalForm", inputKeys[i], "phone")) {
							errorCount++;
						}
						break;
					case "captcha":
						if (this.checkModalInput("modalForm", inputKeys[i], "captcha")) {
							errorCount++;
						}
						break;
					case "checkbox":
						if (this.checkModalInput("modalForm", inputKeys[i], "checkbox")) {
							errorCount++;
						}
						break;
					default:
						if (this.checkModalInput("modalForm", inputKeys[i], "text")) {
							errorCount++;
						}
						break;
				}
			}

			if (errorCount > 0) {
				return true;
			} else {
				return false;
			}
		},
		/* Очистка содержимого модального окна */
		clearModalData(currentName) {
			for (let key in this[currentName].data) {
				this[currentName].data[key].body = "";
				this[currentName].data[key].edited = false;
			}
		},
		/* Очистка ошибок */
		clearModalErrors(currentName) {
			for (let key in this[currentName].errors) {
				this[currentName].errors[key].body = "";
				this[currentName].errors[key].status = false;
			}
		},
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                     КАПТЧА                        |*/
		/* |___________________________________________________|*/
		/* _____________________________________________________*/
		/* 1. Основные действия                                 */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
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
			if (this.checkModalInputsAll(["name", "phone", "captcha", "checkbox"])) return;

			this.disabled.modalForm.request = true;

			let formData = new FormData();
			formData.append(
				"formData",
				JSON.stringify({
					title: this.modal.title,
					name: this.modalForm.data.name.body,
					phone: this.modalForm.data.phone.body,
					date: this.modalForm.data.date.body,
					specialization: this.modalForm.data.specialization.body,
				})
			);

			axios({
				method: "post",
				url: `${this.$store.state.axios.urlApi}` + `request-telegram-bot`,
				headers: {
					"Content-Type": "multipart/form-data",
				},
				data: formData,
			})
				.then((response) => {
					if (response.data.status) {
						try {
							this.disabled.modalForm.request = false;
							this.closeModal();
						} catch (error) {
							this.disabled.modalForm.request = false;

							let debbugStory = {
								title: "Ошибка.",
								body: "После отправки запроса произошла ошибка.",
								type: "Error",
							};
							this.$store.commit("debuggerState", debbugStory);
						}
					} else {
						this.disabled.modalForm.request = false;

						let debbugStory = {
							title: "Ошибка.",
							body: response.data.message,
							type: "Error",
						};
						this.$store.commit("debuggerState", debbugStory);
					}
				})
				.catch((error) => {
					this.disabled.modalForm.request = false;

					let debbugStory = {
						title: "Ошибка.",
						body: "Не удалось запросить данные у сервера.",
						type: "Error",
					};
					this.$store.commit("debuggerState", debbugStory);
				});
		},
	},
	mounted() {
		window.addEventListener("resize", this.setShadow);
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
	margin: 0px 50px;

	transition: box-shadow 0.5s;
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
	border-radius: 5px;
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
	background-color: var(--button-default-color-hover);
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
	box-sizing: border-box;
	outline: none;
	text-align: center;

	padding: 10px;
	border: 2px solid var(--input-border-color-inactive);
	border-radius: 10px;

	width: 300px;
	height: 58px;

	font-size: 20px;
	caret-color: var(--input-border-color-active);
	background-color: white;

	transition: all 0.2s;
}

.captcha > .input > input.error {
	background-color: var(--input-background-color-error);
	border: 2px solid var(--input-border-color-error);

	caret-color: red;
}

.captcha > .input > input:focus {
	border: 2px solid var(--input-border-color-active);
}

.captcha > .input > input.error:focus {
	border: 2px solid var(--input-border-color-error);
}

.captcha > .input > .input-error {
	margin-top: 5px;
	color: var(--span-color-error);
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
	border: 2px solid var(--input-border-color-inactive);

	border-radius: 10px;
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
