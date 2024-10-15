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
						<input type="text" placeholder="Введите Ф.И.О." v-model="modalForm.data.name.body" />
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
						/>
					</template>
					<template #error>
						<span class="error" v-if="modalForm.errors.name.status">
							{{ this.modalForm.errors.name.body }}
						</span>
					</template>
				</container-input-once>
				<!-- Дата рождения -->
				<container-input-once type="edit">
					<template #title>
						<span>ДАТА РОЖДЕНИЯ</span>
					</template>
					<template #input>
						<input type="date" placeholder="Введите дату" v-model="modalForm.data.date.body" />
					</template>
					<template #error>
						<span class="error" v-if="modalForm.errors.name.status">
							{{ this.modalForm.errors.name.body }}
						</span>
					</template>
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
					<template #error>
						<span class="error" v-if="modalForm.errors.name.status">
							{{ this.modalForm.errors.name.body }}
						</span>
					</template>
				</container-select-once>
			</container-input>
		</template>
		<template #footer>
			<BlockButtons>
				<ButtonDefault @click=""> Отправить </ButtonDefault>
			</BlockButtons>
		</template>
	</admin-modal>

	<!-- Шапка страницы -->
	<header>
		<div class="header-block">
			<a href="/" class="header-logo-avatar">
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
		@click="$store.commit('changeMainNav')"
		:class="{ active: $store.state.mainNav.status }"
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

export default {
	components: {
		AdminModal,
		ContainerInput,
		ContainerInputOnce,
		ContainerSelectOnce,
		BlockButtons,
		ButtonDefault,
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
			// this.clearModalErrors();
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
