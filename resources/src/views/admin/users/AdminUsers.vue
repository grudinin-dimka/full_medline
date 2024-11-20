<template>
	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                  МОДАЛЬНОЕ ОКНО                   |-->
	<!--|___________________________________________________|-->
	<admin-modal ref="modal" @touchCloseModal="closeModal('modal')" :modal="modal">
		<template #title>
			<span v-if="modal.type == 'create'">ПОЛЬЗОВАТЕЛЬ (СОЗДАНИЕ)</span>
			<span v-if="modal.type == 'edit'">ПОЛЬЗОВАТЕЛЬ (РЕДАКТИРОВАНИЕ)</span>
		</template>
		<template #body>
			<div class="img-fio">
				<div class="img">
					<div :style="{ backgroundImage: `url(${currentUser.data.path.body})` }"></div>
				</div>
				<!-- Фамилия, имя, отчество -->
				<container-input-three :fieldset="true" :type="'edit'">
					<template #legend>
						<span>Ф.И.О.</span>
					</template>
					<!-- Первая -->
					<template #title-one>
						<span>ФАМИЛИЯ*</span>
						<span v-if="currentUser.data.family.edited"> (ИЗМЕНЕНО)</span>
					</template>
					<template #input-one>
						<input
							type="text"
							placeholder="Введите фамилию"
							autocomplete="off"
							:class="{ error: currentUser.errors.family.status }"
							v-model="currentUser.data.family.body"
							@input="currentUser.data.family.edited = true"
							@blur="checkModalInput('currentUser', 'family', 'text')"
						/>
					</template>
					<template #error-one>
						<span class="error" v-if="currentUser.errors.family.status">
							{{ currentUser.errors.family.body }}
						</span>
					</template>
					<!-- Вторник -->
					<template #title-two>
						<span>ИМЯ*</span>
						<span v-if="currentUser.data.name.edited"> (ИЗМЕНЕНО)</span>
					</template>
					<template #input-two>
						<input
							type="text"
							placeholder="Введите имя"
							autocomplete="off"
							:class="{ error: currentUser.errors.name.status }"
							v-model="currentUser.data.name.body"
							@input="currentUser.data.name.edited = true"
							@blur="checkModalInput('currentUser', 'name', 'text')"
						/>
					</template>
					<template #error-two>
						<span class="error" v-if="currentUser.errors.name.status">
							{{ currentUser.errors.name.body }}
						</span>
					</template>
					<!-- Среда -->
					<template #title-three>
						<span>ОТЧЕСТВО</span>
						<span v-if="currentUser.data.surname.edited"> (ИЗМЕНЕНО)</span>
					</template>
					<template #input-three>
						<input
							type="text"
							placeholder="Введите отчество"
							autocomplete="off"
							v-model="currentUser.data.surname.body"
							@input="currentUser.data.surname.edited = true"
						/>
					</template>
					<template #error-three>
						<span class="error" v-if="currentUser.errors.surname.status">
							{{ currentUser.errors.surname.status }}
						</span>
					</template>
				</container-input-three>
			</div>
			<!-- Пароль и генератор пароля -->
			<container-input-two :fieldset="true" :type="'default'">
				<template #legend>
					<span>ПАРОЛЬ И ГЕНЕРАТОР</span>
				</template>
				<!-- Первый -->
				<template #title-one>
					ПАРОЛЬ <span v-if="currentUser.data.password.edited">(ИЗМЕНЕНО)</span>
				</template>
				<template #input-one>
					<input
						type="text"
						placeholder="Введите пароль"
						autocomplete="off"
						v-model="currentUser.data.password.body"
						@input="currentUser.data.password.edited = true"
					/>
				</template>
				<template #error-one>
					<span class="error" v-if="false"> Ошибка </span>
				</template>
				<!-- Второй -->
				<template #title-two>ГЕНЕРАТОР</template>
				<template #input-two>
					<div class="password-generator">
						<div class="password">{{ captcha }}</div>
						<div class="copy" @click="copyCaptcha(captcha)">
							<svg
								xmlns="http://www.w3.org/2000/svg"
								height="24px"
								viewBox="0 -960 960 960"
								width="24px"
							>
								<path
									d="M360-240q-33 0-56.5-23.5T280-320v-480q0-33 23.5-56.5T360-880h360q33 0 56.5 23.5T800-800v480q0 33-23.5 56.5T720-240H360Zm0-80h360v-480H360v480ZM200-80q-33 0-56.5-23.5T120-160v-560h80v560h440v80H200Zm160-240v-480 480Z"
								/>
							</svg>
						</div>
						<div class="generator" @click="setCaptcha(captcha)">
							<svg
								xmlns="http://www.w3.org/2000/svg"
								height="24px"
								viewBox="0 -960 960 960"
								width="24px"
							>
								<path
									d="M480-160q-134 0-227-93t-93-227q0-134 93-227t227-93q69 0 132 28.5T720-690v-110h80v280H520v-80h168q-32-56-87.5-88T480-720q-100 0-170 70t-70 170q0 100 70 170t170 70q77 0 139-44t87-116h84q-28 106-114 173t-196 67Z"
								/>
							</svg>
						</div>
					</div>
				</template>
				<template #error-two>
					<span class="error" v-if="false"> Ошибка </span>
				</template>
			</container-input-two>
			<!-- Аватар и дата рождения -->
			<container-input-two :fieldset="true" :type="'default'">
				<template #legend>
					<span>АВАТАР И ДАТА РОЖДЕНИЯ</span>
				</template>
				<!-- Первый -->
				<template #title-one> АВАТАР <span v-if="false">(ИЗМЕНЕНО)</span> </template>
				<template #input-one>
					<input type="file" autocomplete="off" />
				</template>
				<template #error-one>
					<span class="error" v-if="false"> Ошибка </span>
				</template>
				<!-- Второй -->
				<template #title-two>
					ДАТА РОЖДЕНИЯ* <span v-if="currentUser.data.dateOfBirth.edited">(ИЗМЕНЕНО)</span>
				</template>
				<template #input-two>
					<input
						type="date"
						placeholder="Введите дату"
						autocomplete="off"
						:class="{ error: currentUser.errors.dateOfBirth.status }"
						v-model="currentUser.data.dateOfBirth.body"
						@input="currentUser.data.dateOfBirth.edited = true"
						@blur="checkModalInput('currentUser', 'dateOfBirth', 'text')"
					/>
				</template>
				<template #error-two>
					<span class="error" v-if="currentUser.errors.dateOfBirth.status">
						{{ currentUser.errors.dateOfBirth.body }}
					</span>
				</template>
			</container-input-two>
			<!-- Почта и псевдоним -->
			<container-input-two :fieldset="true" :type="'default'">
				<template #legend>
					<span>ПОЧТА И ПСЕВДОНИМ</span>
				</template>
				<!-- Первый -->
				<template #title-one>
					ПОЧТА* <span v-if="currentUser.data.email.edited">(ИЗМЕНЕНО)</span>
				</template>
				<template #input-one>
					<input
						type="text"
						placeholder="Введите отчество"
						autocomplete="off"
						:class="{ error: currentUser.errors.email.status }"
						v-model="currentUser.data.email.body"
						@input="currentUser.data.email.edited = true"
						@blur="checkModalInput('currentUser', 'email', 'text')"
					/>
				</template>
				<template #error-one>
					<span class="error" v-if="currentUser.errors.email.status">
						{{ currentUser.errors.email.body }}
					</span>
				</template>
				<!-- Второй -->
				<template #title-two>
					ПСЕВДОНИМ* <span v-if="currentUser.data.nickname.edited">(ИЗМЕНЕНО)</span>
				</template>
				<template #input-two>
					<input
						type="text"
						placeholder="Введите отчество"
						autocomplete="off"
						:class="{ error: currentUser.errors.nickname.status }"
						v-model="currentUser.data.nickname.body"
						@input="currentUser.data.nickname.edited = true"
						@blur="checkModalInput('currentUser', 'nickname', 'text')"
					/>
				</template>
				<template #error-two>
					<span class="error" v-if="currentUser.errors.nickname.status">
						{{ currentUser.errors.nickname.body }}
					</span>
				</template>
			</container-input-two>
			<!-- Статус и права -->
			<container-input-two :fieldset="true" :type="'default'">
				<template #legend>
					<span>СТАТУС И ПРАВА</span>
				</template>
				<!-- Первый -->
				<template #title-one> СТАТУС <span v-if="false">(ИЗМЕНЕНО)</span> </template>
				<template #input-one>
					<select v-model="currentUser.data.statusId.body">
						<option :value="status.id" v-for="status in statuses">{{ status.name }}</option>
					</select>
				</template>
				<template #error-one>
					<span class="error" v-if="false"> Ошибка </span>
				</template>
				<!-- Второй -->
				<template #title-two> ПРАВА <span v-if="false">(ИЗМЕНЕНО)</span> </template>
				<template #input-two>
					<select v-model="currentUser.data.rightsId.body">
						<option :value="right.id" v-for="right in rights">{{ right.name }}</option>
					</select>
				</template>
				<template #error-two>
					<span class="error" v-if="false"> Ошибка </span>
				</template>
			</container-input-two>
		</template>
		<template #footer>
			<BlockButtons>
				<ButtonDefault @click="saveUser"> Сохранить </ButtonDefault>
			</BlockButtons>
		</template>
	</admin-modal>

	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|           МОДАЛЬНОЕ ОКНО (СМЕНА ПАРОЛЯ)           |-->
	<!--|___________________________________________________|-->
	<admin-sub-modal
		ref="sub-modal-password"
		@touchCloseModal="closeModal('subModalPassword')"
		:modal="subModalPassword"
	>
		<template #title></template>
		<template #body> </template>
		<template #footer>
			<block-buttons-wide>
				<button-remove-wide :type="'default'">Обновить пароль</button-remove-wide>
				<button-remove-wide :type="'other'" @click="closeModal('subModalDelete')">
					Отменить
				</button-remove-wide>
			</block-buttons-wide>
		</template>
	</admin-sub-modal>
	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|      МОДАЛЬНОЕ ОКНО (УДАЛЕНИЕ ПОЛЬЗОВАТЕЛЯ)       |-->
	<!--|___________________________________________________|-->
	<admin-sub-modal
		ref="sub-modal-delete"
		@touchCloseModal="closeModal('subModalDelete')"
		:modal="subModalDelete"
	>
		<template #title> <span>УДАЛЕНИЕ</span> </template>
		<template #body>
			<div class="sub-modal-text">
				Вы действительно хотите удалить пользователя "
				<span class="delete">
					{{
						currentUser.data.family.body +
						" " +
						currentUser.data.name.body +
						" " +
						currentUser.data.surname.body
					}} </span
				>?"
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
		<template #title>БЛОКИРОВКА</template>
		<template #body>
			<div class="sub-modal-text">
				Вы действительно хотите заблокировать пользователя "
				<span class="block">
					{{
						currentUser.data.family.body +
						" " +
						currentUser.data.name.body +
						" " +
						currentUser.data.surname.body
					}} </span
				>?"
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
					<div class="fio">
						{{ user.family + " " + user.name + " " }}
					</div>
					<div class="nickname">{{ user.nickname }}</div>
				</div>
				<div class="buttons">
					<div class="icon edit" @click="editUser(user)">
						<IconEdit :width="24" :height="24" :type="'edit'"></IconEdit>
					</div>
					<div class="icon admin" @click="openModal('edit', 'subModalPassword')">
						<IconPassword :width="22" :height="22" :type="'admin'"></IconPassword>
					</div>
					<div
						class="icon"
						:class="{ default: user.statusId === 1, delete: user.statusId === 2 }"
						@click="editUserBlock(user)"
					>
						<IconLockOpen
							:width="22"
							:height="22"
							:type="'default'"
							v-if="user.statusId === 1"
						></IconLockOpen>
						<IconLockClose
							:width="22"
							:height="22"
							:type="'default'"
							v-if="user.statusId === 2"
						></IconLockClose>
					</div>
					<div class="icon delete" @click="editUserDelete(user)">
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
import IconPassword from "../../../components/icons/users/IconPassword.vue";

import BlockButtons from "../../../components/ui/admin/blocks/BlockButtons.vue";
import BlockButtonsWide from "../../../components/ui/admin/blocks/BlockButtonsWide.vue";
import ButtonDefault from "../../../components/ui/admin/buttons/ButtonDefault.vue";
import ButtonRemoveWide from "../../../components/ui/admin/buttons/ButtonRemoveWide.vue";
import ButtonDefaultWide from "../../../components/ui/admin/buttons/ButtonDefaultWide.vue";

import axios from "axios";
import shared from "../../../services/shared";
import validate from "../../../services/validate";

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
		IconPassword,
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
			subModalPassword: {
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
					delete: true,
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
					password: {
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
					statusId: {
						body: "",
						edited: false,
					},
					rightsId: {
						body: "",
						edited: false,
					},
					filename: {
						body: "",
						edited: false,
					},
					path: {
						body: "",
						edited: false,
					},
				},
			},
			captcha: null,
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
		/* Копирование капчи */
		copyCaptcha(captcha) {
			if (navigator.clipboard.writeText(captcha)) {
				let debbugStory = {
					title: "Успешно!",
					body: "Пароль скопирован в буфер обмена.",
					type: "Completed",
				};
				this.$store.commit("debuggerState", debbugStory);
			} else {
				let debbugStory = {
					title: "Ошибка.",
					body: "Не удалось скопировать пароль...",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
			}
		},
		/* Установка новой капчи */
		setCaptcha() {
			this.captcha = shared.generateRandomString(16, true, true, true);
		},
		/* _____________________________________________________*/
		/* 2. Работа с полями ввода модального окна             */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		// Проверка поля имени
		checkModalInput(currentName, dataKey, inputType) {
			let errorLog = {};
			switch (inputType) {
				case "text":
					errorLog = validate.checkInputText(this[currentName].data[dataKey].body);
					break;
				case "email":
					errorLog = validate.checkInputEmail(this[currentName].data[dataKey].body);
					break;
				case "phone":
					errorLog = validate.checkInputPhone(this[currentName].data[dataKey].body);
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
		checkModalInputsAll(currentName, inputKeys) {
			let errorCount = 0;
			for (let i = 0; i < inputKeys.length; i++) {
				switch (inputKeys[i]) {
					// Для всех остальных полей
					default:
						if (this.checkModalInput(currentName, inputKeys[i], "text")) {
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
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                  ПОЛЬЗОВАТЕЛИ                     |*/
		/* |___________________________________________________|*/
		/* _____________________________________________________*/
		/* 1. Основные действия                                 */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		editUserBlock(user) {
			for (let key in this.currentUser.data) {
				this.currentUser.data[key].body = user[key];
			}

			this.openModal("edit", "subModalBlock");
		},
		editUserDelete(user) {
			this.subModalDelete.style.delete = true;

			for (let key in this.currentUser.data) {
				this.currentUser.data[key].body = user[key];
			}

			this.openModal("edit", "subModalDelete");
		},
		editUser(user) {
			this.captcha = shared.generateRandomString(16, true, true, true);

			for (let key in this.currentUser.data) {
				this.currentUser.data[key].body = user[key];
			}

			this.clearModalErrors("currentUser");
			this.clearModalEdited("currentUser");

			this.openModal("edit", "modal");
		},
		saveUser() {
			if (
				this.checkModalInputsAll("currentUser", [
					"family",
					"name",
					"dateOfBirth",
					"email",
					"nickname",
				])
			) {
				return;
			}

			let user = {};

			for (let key in this.currentUser.data) {
				user[key] = this.currentUser.data[key].body;
			}

			let formData = new FormData();
			formData.append("user", JSON.stringify(user));

			this.closeModal("modal");
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
	display: flex;
	justify-content: center;
	align-items: center;
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

.users > .item > .buttons > .icon.admin {
	background-color: rgb(255, 242, 222);
}

.users > .item > .buttons > .icon.admin:hover {
	background-color: rgb(248, 232, 208);
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

	hyphens: auto;
	word-break: keep-all;
}

.sub-modal-text > span.block {
	color: var(--primary-color);
}

.sub-modal-text > span.delete {
	color: #cd3f3e;
}

.img-fio {
	display: grid;
	grid-template-columns: 1fr 1fr;
	gap: 20px;
}

.img-fio > .img {
	display: flex;
	justify-content: center;
	align-items: center;
	margin-top: 10px;
}

.img-fio > .img > div {
	margin: 0 auto;
	width: 300px;
	height: 100%;
	max-height: 300px;
	border-radius: 300px;
	background-size: cover;
	background-repeat: no-repeat;
	background-position: center center;
}

.password-generator {
	position: relative;
	display: flex;
	justify-content: center;
	align-items: center;

	border: 2px solid var(--input-border-color-inactive);
	border-radius: 10px;

	font-size: 18px;
	height: 54px;

	background-color: #f5f5f5;
}

.password-generator > .copy {
	position: absolute;
	cursor: pointer;
	top: 16px;
	right: 5px;

	fill: rgb(150, 150, 150);
}

.password-generator > .generator {
	position: absolute;
	cursor: pointer;
	top: 16px;
	right: 34px;

	fill: rgb(150, 150, 150);
}

@media screen and (width <= 860px) {
	.img-fio {
		grid-template-columns: 1fr;
	}

	.img-fio > .img {
		height: 300px;
	}
}
</style>
