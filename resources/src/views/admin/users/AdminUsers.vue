<template>
	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                  МОДАЛЬНОЕ ОКНО                   |-->
	<!--|___________________________________________________|-->
	<admin-modal ref="modal" @touchCloseModal="closeModal('modal')" :modal="modal">
		<template #title>
			<span v-if="modal.type == 'create'">БЛОК (СОЗДАНИЕ)</span>
			<span v-if="modal.type == 'edit'">БЛОК (РЕДАКТИРОВАНИЕ)</span>
		</template>
		<template #body>
			<div class="img-fio">
				<div class="img" :style="{ backgroundImage: `url(/storage/users/avatar.png)` }"></div>
				<container-input-three :fieldset="true" :type="'edit'">
					<template #legend>
						<span>Ф.И.О.</span>
					</template>
					<template #title-one>
						<span>ТЕСТ*</span>
						<span v-if="false"> (ИЗМЕНЕНО)</span>
					</template>
					<template #input-one>
						<input type="text" placeholder="Введите фамилию" autocomplete="off" />
					</template>
					<template #error-one>
						<span class="error" v-if="false"> Ошибка </span>
					</template>
					<template #title-two>
						<span>ИМЯ*</span>
						<span v-if="false"> (ИЗМЕНЕНО)</span>
					</template>
					<template #input-two>
						<input type="text" placeholder="Введите фамилию" autocomplete="off" />
					</template>
					<template #error-two>
						<span class="error" v-if="false"> Ошибка </span>
					</template>
					<template #title-three>
						<span>ОТЧЕСТВО</span>
						<span v-if="false"> (ИЗМЕНЕНО)</span>
					</template>
					<template #input-three>
						<input type="text" placeholder="Введите фамилию" autocomplete="off" />
					</template>
					<template #error-three>
						<span class="error" v-if="false"> Ошибка </span>
					</template>
				</container-input-three>
			</div>
		</template>
		<template #footer>
			<BlockButtons>
				<ButtonDefault @click=""> Сохранить </ButtonDefault>
			</BlockButtons>
		</template>
	</admin-modal>

	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|      МОДАЛЬНОЕ ОКНО (УДАЛЕНИЕ ПОЛЬЗОВАТЕЛЯ)       |-->
	<!--|___________________________________________________|-->
	<admin-sub-modal
		ref="sub-modal-delete"
		@touchCloseModal="closeModal('subModalDelete')"
		:modal="subModalDelete"
	>
		<template #title></template>
		<template #body>
			<div class="sub-modal-text">
				Вы действительно хотите удалить пользователя "Иванов Иван Иванович?"
			</div>
		</template>
		<template #footer>
			<block-buttons-wide>
				<button-remove-wide :type="'default'">Да</button-remove-wide>
				<button-remove-wide :type="'other'" @click="closeModal('subModalDelete')">
					Нет
				</button-remove-wide>
			</block-buttons-wide>
		</template>
	</admin-sub-modal>
	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|     МОДАЛЬНОЕ ОКНО (БЛОКИРОВКА ПОЛЬЗОВАТЕЛЯ)      |-->
	<!--|___________________________________________________|-->
	<admin-sub-modal
		ref="sub-modal-delete"
		@touchCloseModal="closeModal('subModalBlock')"
		:modal="subModalBlock"
	>
		<template #title></template>
		<template #body>
			<div class="sub-modal-text">
				Вы действительно хотите заблокировать пользователя "Иванов Иван Иванович"?
			</div>
		</template>
		<template #footer>
			<block-buttons-wide>
				<ButtonDefaultWide :type="'default'">Да</ButtonDefaultWide>
				<ButtonDefaultWide :type="'other'" @click="closeModal('subModalBlock')">
					Нет
				</ButtonDefaultWide>
			</block-buttons-wide>
		</template>
	</admin-sub-modal>

	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                   БЛОК ПРОФИЛЯ                    |-->
	<!--|___________________________________________________|-->
	<block-once :minHeight="400">
		<block-title>
			<template #title>
				ПОЛЬЗОВАТЕЛИ
				<span v-if="loading.sections.users">{{ " (" + users.length + ")" }}</span>
			</template>
		</block-title>

		<div class="users" v-if="loading.sections.users">
			<div class="item" v-for="user in users">
				<div class="id">#{{ user.id }}</div>
				<div class="img" :style="{ backgroundImage: `url(${user.path}` }"></div>
				<div class="info">
					<div class="fio">{{ user.family + " " + user.name + " " + user.surname }}</div>
					<div class="nickname">{{ user.nickname }}</div>
				</div>
				<div class="buttons">
					<div class="icon edit" @click="openModal('edit', 'modal')">
						<IconEdit :width="24" :height="24" :type="'edit'"></IconEdit>
					</div>
					<div
						class="icon"
						:class="{ default: user.status === 'active', delete: user.status === 'passive' }"
						@click="openModal('edit', 'subModalBlock')"
					>
						<IconLockOpen
							:width="22"
							:height="22"
							:type="'default'"
							v-if="user.status === 'active'"
						></IconLockOpen>
						<IconLockClose
							:width="22"
							:height="22"
							:type="'default'"
							v-if="user.status === 'passive'"
						></IconLockClose>
					</div>
					<div class="icon delete" @click="openModal('edit', 'subModalDelete')">
						<IconRemove :width="24" :height="24" :type="'delete'"></IconRemove>
					</div>
				</div>
			</div>
		</div>

		<LoaderChild
			:isLoading="loading.loader.users"
			:minHeight="300"
			@loaderChildAfterLeave="loaderChildAfterLeave"
		></LoaderChild>

		<BlockButtons>
			<ButtonDefault @click=""> Добавить </ButtonDefault>
		</BlockButtons>
	</block-once>
</template>

<script>
import LoaderChild from "../../../components/includes/LoaderChild.vue";
import AdminModal from "../../../components/includes/admin/AdminModal.vue";
import AdminSubModal from "../../../components/includes/admin/AdminSubModal.vue";

import AdminUsersTable from "./AdminUsersTable.vue";

import BlockOnce from "../../../components/ui/admin/blocks/BlockOnce.vue";
import BlockTitle from "../../../components/ui/admin/blocks/BlockTitle.vue";

import IconLoad from "../../../components/icons/IconLoad.vue";
import IconSave from "../../../components/icons/IconSave.vue";

import ContainerInput from "../../../components/ui/admin/containers/ContainerInput.vue";
import ContainerInputOnce from "../../../components/ui/admin/containers/input/ContainerInputOnce.vue";
import ContainerInputTwo from "../../../components/ui/admin/containers/input/ContainerInputTwo.vue";
import ContainerInputThree from "../../../components/ui/admin/containers/input/ContainerInputThree.vue";

import IconEdit from "../../../components/icons/IconEdit.vue";
import IconRemove from "../../../components/icons/IconRemove.vue";
import IconLockClose from "../../../components/icons/users/IconLockClose.vue";
import IconLockOpen from "../../../components/icons/users/IconLockOpen.vue";

import BlockButtons from "../../../components/ui/admin/blocks/BlockButtons.vue";
import BlockButtonsWide from "../../../components/ui/admin/blocks/BlockButtonsWide.vue";
import ButtonDefault from "../../../components/ui/admin/buttons/ButtonDefault.vue";
import ButtonRemoveWide from "../../../components/ui/admin/buttons/ButtonRemoveWide.vue";
import ButtonDefaultWide from "../../../components/ui/admin/buttons/ButtonDefaultWide.vue";

import axios from "axios";

export default {
	components: {
		LoaderChild,
		AdminModal,
		AdminSubModal,
		AdminUsersTable,
		BlockOnce,
		BlockTitle,
		IconLoad,
		IconSave,
		ContainerInput,
		ContainerInputOnce,
		ContainerInputTwo,
		ContainerInputThree,
		IconEdit,
		IconRemove,
		IconLockClose,
		IconLockOpen,
		BlockButtons,
		BlockButtonsWide,
		ButtonDefault,
		ButtonRemoveWide,
		ButtonDefaultWide,
		axios,
	},
	data() {
		return {
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
			subModalDelete: {
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
			subModalBlock: {
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
			loading: {
				loader: {
					users: true,
				},
				sections: {
					users: false,
				},
			},
			currentUser: {
				errors: {
					family: {
						body: "",
						status: false,
					},
					name: {
						body: "",
						status: false,
					},
					surname: {
						body: "",
						status: false,
					},
					dateOfBirth: {
						body: "",
						status: false,
					},
					nickname: {
						body: "",
						status: false,
					},
					email: {
						body: "",
						status: false,
					},
					status: {
						body: "",
						status: false,
					},
					rights: {
						body: "",
						status: false,
					},
					path: {
						body: "",
						status: false,
					},
				},
				data: {
					family: {
						body: "",
						edited: false,
					},
					name: {
						body: "",
						edited: false,
					},
					surname: {
						body: "",
						edited: false,
					},
					dateOfBirth: {
						body: "",
						edited: false,
					},
					nickname: {
						body: "",
						edited: false,
					},
					email: {
						body: "",
						edited: false,
					},
					status: {
						body: "",
						edited: false,
					},
					rights: {
						body: "",
						edited: false,
					},
					path: {
						body: "",
						edited: false,
					},
					created_at: {
						body: "",
						edited: false,
					},
					updated_at: {
						body: "",
						edited: false,
					},
				},
			},
			users: [],
			rights: [],
			statuses: [],
		};
	},
	methods: {
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                   Загрузчик                       |*/
		/* |___________________________________________________|*/
		/* После скрытия элементы */
		loaderChildAfterLeave() {
			this.loading.sections.users = true;
		},
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                 Модальное окно                    |*/
		/* |___________________________________________________|*/
		/* _____________________________________________________*/
		/* 1. Основные действия                                 */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		/* Открытие */
		openModal(type = "edit", name = "modal") {
			switch (type) {
				case "create":
					{
						this[name].type = "create";
						this[name].status = true;
						this[name].style.create = true;
						this[name].style.delete = false;
					}
					document.body.classList.add("modal-open");
					break;
				case "edit":
					{
						this[name].type = "edit";
						this[name].status = true;
						this[name].style.delete = false;
						this[name].style.create = false;
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
		closeModal(name = "modal") {
			this[name].status = false;
			document.body.classList.remove("modal-open");
		},
		/* _____________________________________________________*/
		/* 2. Работа с полями ввода модального окна             */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		/* Проверка введенного значения почты */
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
		/* Проверка введенного текстового значения */
		checkInputEmail(value) {
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

			if (!validate.checkMail(value)) {
				return {
					status: true,
					message: "Почта не прошла проверку.",
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
				case "email":
					errorLog = this.checkInputEmail(this[currentName].data[dataKey].body);
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
		/* Очистка содержимого модального окна */
		clearModalData(name = "currentInfoBlock") {
			for (let key in this[name].data) {
				if (key === "phones" || key === "mails") {
					this[name].data[key].body = [];
					continue;
				}

				this[name].data[key].body = null;
			}
		},
		/* Очистка содержимого модального окна */
		clearModalEdited(name = "currentInfoBlock") {
			for (let key in this[name].data) {
				this[name].data[key].edited = false;
			}
		},
		/* Очистка ошибок */
		clearModalErrors(name = "currentInfoBlock") {
			for (let key in this[name].errors) {
				this[name].errors[key].body = null;
				this[name].errors[key].status = false;
			}
		},
	},
	mounted() {
		axios({
			method: "post",
			headers: {
				Accept: "application/json",
				Authorization: `Bearer ${localStorage.getItem("token")}`,
			},
			url: `${this.$store.state.axios.urlApi}` + `get-users-all`,
		})
			.then((response) => {
				this.users = response.data.data.users;
				this.rights = response.data.data.rights;
				this.statuses = response.data.data.statuses;
			})
			.catch((error) => {
				let debbugStory = {
					title: "Ошибка.",
					body: "Произошла ошибка при загрузке пользователей.",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
			})
			.finally(() => {
				this.loading.loader.users = false;
			});
	},
};
</script>

<style scoped>
.users {
	display: flex;
	flex-direction: column;
	gap: 10px;

	font-size: 18px;

	animation: show-bottom-to-top-15 0.5s ease-in-out;
}

.users > .item {
	display: flex;
	align-items: center;
	gap: 20px;

	border: 2px solid var(--input-border-color-inactive);
	border-radius: 30px;
	padding: 10px 20px;
	transition: all 0.2s;
}

.users > .item > .id {
	font-size: 22px;
}

.users > .item > .img {
	width: 70px;
	height: 70px;
	border-radius: 50%;
	background-size: cover;
	background-repeat: no-repeat;
	background-position: center center;
}

.users > .item > .info > .fio {
	color: var(--primary-color);
}

.users > .item > .buttons {
	display: flex;
	gap: 10px;
	margin-left: auto;
}

.users > .item > .buttons > .icon {
	cursor: pointer;
	padding: 10px;
	border-radius: 100px;

	width: 25px;
	height: 25px;
}

.users > .item > .buttons > .icon.delete {
	background-color: rgb(255, 237, 237);
}

.users > .item > .buttons > .icon.delete:hover {
	background-color: rgb(245, 227, 227);
}

.users > .item > .buttons > .icon.edit {
	background-color: rgb(234, 253, 255);
}

.users > .item > .buttons > .icon.edit:hover {
	background-color: rgb(224, 243, 245);
}

.users > .item > .buttons > .icon.default {
	background-color: rgb(240, 240, 240);
}

.users > .item > .buttons > .icon.default:hover {
	background-color: rgb(230, 230, 230);
}

.sub-modal-text {
	font-size: 18px;
	min-height: 50px;
	max-width: 600px;
	text-align: center;
	margin: auto;
}

.img-fio {
   display: grid;
   grid-template-columns: repeat(2, 1fr);
}

.img-fio > .img {
   background-size: contain;
   background-repeat: no-repeat;
   background-position: center center;
}
</style>
