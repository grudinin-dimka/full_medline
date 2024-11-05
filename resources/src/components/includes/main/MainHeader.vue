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
							{{ this.modalForm.errors.name.body }}
						</span>
					</template>
				</container-input-once>
				<!-- Номер телефона -->
				<container-input-once type="edit">
					<template #title>
						<span>НОМЕР ТЕЛЕФОНА*</span>
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
							{{ this.modalForm.errors.phone.body }}
						</span>
					</template>
				</container-input-once>
				<!-- Дата рождения -->
				<container-input-once type="edit">
					<template #title>
						<span>ДАТА РОЖДЕНИЯ</span>
					</template>
					<template #input>
						<input
							type="date"
							placeholder="Введите дату"
							v-model="modalForm.data.date.body"
						/>
					</template>
					<template #error></template>
				</container-input-once>
				<!-- Специальность врача -->
				<container-select-once type="edit">
					<template #title>
						<span>СПЕЦИАЛИЗАЦИЯ ВРАЧА</span>
					</template>
					<template #select>
						<!-- TODO: Сделать подгрузку специализаций специаилстов -->
						<select name="spec" v-model="modalForm.data.specialization.body">
							<option value="" disabled selected>Выберите специализацию</option>
							<option value="Терапевт">Терапевт</option>
							<option value="Невролог">Невролог</option>
							<option value="Уролог">Уролог</option>
							<option value="Хирург">Хирург</option>
						</select>
					</template>
					<template #error></template>
				</container-select-once>
			</container-input>
		</template>
		<template #footer>
			<BlockButtons>
				<ButtonDefault @click="sendRequest"> Отправить </ButtonDefault>
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
				<p>ул. Комсомольская, 16</p>
				<p>ул. Карла Либкнехта, 10</p>
				<p>ул. Октябрьская, 3</p>
			</div>
		</div>
		<div class="header-block">
			<img src="../../../assets/svg/phone.svg" width="50" />
			<div class="header-block-list">
				<a href="tel:+73525390009">+7 (35253) 9-000-9</a>
				<a href="tel:+73525332936">+7 (35253) 3-29-36</a>
				<a href="tel:+79630070006">+7 (963) 007-00-06</a>
			</div>
		</div>
		<div class="buttons">
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

import ContainerInput from "../../ui/admin/containers/ContainerInput.vue";
import ContainerInputOnce from "../../ui/admin/containers/input/ContainerInputOnce.vue";
import ContainerSelectOnce from "../../ui/admin/containers/select/ContainerSelectOnce.vue";

import BlockButtons from "../../ui/admin/blocks/BlockButtons.vue";
import ButtonDefault from "../../ui/admin/buttons/ButtonDefault.vue";

import validate from "../../../services/validate";

export default {
	components: {
		AdminModal,
		ContainerInput,
		ContainerInputOnce,
		ContainerSelectOnce,
		BlockButtons,
		ButtonDefault,
		validate,
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
						body: "",
						edited: false,
					},
					phone: {
						body: "",
						edited: false,
					},
					date: {
						body: "",
						edited: false,
					},
					specialization: {
						body: "",
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
				},
			},
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
				console.log(value, typeof value);
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
		/* |                 ОТПРАВКА ЗАПРОСА                  |*/
		/* |___________________________________________________|*/
		/* Отправка запроса */
		sendRequest() {
			if (this.checkModalInputsAll(["name", "phone"])) return;

			console.log("Проверка пройдена.");
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

.buttons {
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

@media screen and (max-width: 1600px) {
	.buttons {
		flex-direction: column;
	}
}

@media screen and (max-width: 1400px) {
	.buttons {
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
}
</style>
