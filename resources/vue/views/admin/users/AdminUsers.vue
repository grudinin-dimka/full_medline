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
					<div
						v-if="currentUser.data.path.body"
						:style="{ backgroundImage: `url(${currentUser.data.path.body})` }"
					></div>
					<div
						v-if="!currentUser.data.path.body"
						:style="{ backgroundImage: `url(/storage/default/avatar.png)` }"
					></div>
				</div>
				<!-- Фамилия, имя, отчество -->
				<container-input-three
					:fieldset="true"
					:type="modal.type == 'create' ? 'create' : 'edit'"
				>
					<template #legend> Ф.И.О. </template>
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
			<container-input-two :fieldset="true" :type="modal.type == 'create' ? 'create' : 'edit'">
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
						:class="{ error: currentUser.errors.password.status }"
						v-model="currentUser.data.password.body"
						@input="currentUser.data.password.edited = true"
						@blur="checkModalInput('currentUser', 'password', 'text')"
					/>
				</template>
				<template #error-one>
					<span class="error" v-if="currentUser.errors.password.status">
						{{ currentUser.errors.password.body }}
					</span>
				</template>
				<!-- Второй -->
				<template #title-two>ГЕНЕРАТОР</template>
				<template #input-two>
					<PasswordGenerator />
				</template>
				<template #error-two>
					<span class="error" v-if="false"> Ошибка </span>
				</template>
			</container-input-two>
			<!-- Аватар и дата рождения -->
			<container-input-two :fieldset="true" :type="modal.type == 'create' ? 'create' : 'edit'">
				<template #legend>
					<span>АВАТАР И ДАТА РОЖДЕНИЯ</span>
				</template>
				<!-- Первый -->
				<template #title-one> АВАТАР <span v-if="false">(ИЗМЕНЕНО)</span> </template>
				<template #input-one>
					<input
						type="file"
						autocomplete="off"
						ref="fileUpload"
						:class="{ error: currentUser.errors.file.status }"
					/>
				</template>
				<template #error-one>
					<span class="error" v-if="currentUser.errors.file.status">
						{{ currentUser.errors.file.body }}
					</span>
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
			<container-input-two :fieldset="true" :type="modal.type == 'create' ? 'create' : 'edit'">
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
						placeholder="Введите почту"
						autocomplete="off"
						:class="{ error: currentUser.errors.email.status }"
						v-model="currentUser.data.email.body"
						@input="currentUser.data.email.edited = true"
						@blur="checkModalInput('currentUser', 'email', 'email')"
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
						placeholder="Введите псевдоним"
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
			<container-input-two :fieldset="true" :type="modal.type == 'create' ? 'create' : 'edit'">
				<template #legend>
					<span>СТАТУС И ПРАВА</span>
				</template>
				<!-- Первый -->
				<template #title-one> СТАТУС <span v-if="false">(ИЗМЕНЕНО)</span> </template>
				<template #input-one>
					<select
						v-model="currentUser.data.statusId.body"
						:class="{ error: currentUser.errors.statusId.status }"
						@blur="checkModalInput('currentUser', 'statusId', 'select')"
					>
						<option :value="null" disabled>Выберите статус</option>
						<option :value="status.id" v-for="status in statuses">{{ status.name }}</option>
					</select>
				</template>
				<template #error-one>
					<span class="error" v-if="currentUser.errors.statusId.status">
						{{ currentUser.errors.statusId.body }}
					</span>
				</template>
				<!-- Второй -->
				<template #title-two> ПРАВА <span v-if="false">(ИЗМЕНЕНО)</span> </template>
				<template #input-two>
					<select
						v-model="currentUser.data.rightsId.body"
						:class="{ error: currentUser.errors.rightsId.status }"
						@blur="checkModalInput('currentUser', 'rightsId', 'select')"
					>
						<option :value="null" disabled>Выберите права</option>
						<option :value="right.id" v-for="right in rights">{{ right.name }}</option>
					</select>
				</template>
				<template #error-two>
					<span class="error" v-if="currentUser.errors.rightsId.status">
						{{ currentUser.errors.rightsId.body }}
					</span>
				</template>
			</container-input-two>
		</template>
		<template #footer>
			<BlockButtons>
				<ButtonDefault
					@click="saveUser"
					:disabled="disabled.users.save"
					v-if="modal.type === 'edit'"
				>
					Сохранить
				</ButtonDefault>
				<ButtonClaim
					@click="addUser"
					:disabled="disabled.users.create"
					v-if="modal.type === 'create'"
				>
					Создать
				</ButtonClaim>
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
		<template #body>
			<container-input>
				<PasswordGenerator />
				<container-input-once :type="'password'">
					<template #title></template>
					<template #input>
						<input
							type="text"
							placeholder="Введите пароль"
							:class="{ error: userPassword.errors.password.status }"
							v-model="userPassword.data.password.body"
							@blur="checkModalInput('userPassword', 'password', 'text')"
						/>
					</template>
					<template #error>
						<span class="error" v-if="userPassword.errors.password.status">
							{{ userPassword.errors.password.body }}
						</span>
					</template>
				</container-input-once>
			</container-input>
		</template>
		<template #footer>
			<block-buttons-wide>
				<button-password-wide
					:type="'default'"
					@click="saveUserPassword"
					:disabled="disabled.userPassword.save"
				>
					Сбросить
				</button-password-wide>
				<button-password-wide :type="'other'" @click="closeModal('subModalPassword')">
					Закрыть
				</button-password-wide>
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
		<template #title>УДАЛЕНИЕ</template>
		<template #body>
			<div class="sub-modal-text">
				Вы действительно хотите <span class="delete">удалить</span> пользователя
				<span class="delete">
					{{
						currentUser.data.family.body +
						" " +
						currentUser.data.name.body +
						(currentUser.data.surname.body !== null
							? " " + currentUser.data.surname.body
							: "")
					}} </span
				>?
			</div>
		</template>
		<template #footer>
			<block-buttons-wide>
				<button-remove-wide
					:type="'default'"
					@click="deleteUser"
					:disabled="disabled.users.delete"
				>
					Да
				</button-remove-wide>
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
				Вы действительно хотите
				<span class="block">
					{{ currentUser.data.statusId.body === 1 ? "заблокировать" : "разблокировать" }}
				</span>
				пользователя
				<span class="block">
					{{
						currentUser.data.family.body +
						" " +
						currentUser.data.name.body +
						(currentUser.data.surname.body !== null
							? " " + currentUser.data.surname.body
							: "")
					}} </span
				>?
			</div>
		</template>
		<template #footer>
			<block-buttons-wide>
				<ButtonDefaultWide
					:type="'default'"
					@click="saveUserBlock"
					:disabled="disabled.userBlock.save"
				>
					Да
				</ButtonDefaultWide>
				<ButtonDefaultWide :type="'other'" @click="closeModal('subModalBlock')">
					Нет
				</ButtonDefaultWide>
			</block-buttons-wide>
		</template>
	</admin-sub-modal>

	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                   БЛОК ПРОФИЛЯ                    |-->
	<!--|___________________________________________________|-->
	<block-once :minHeight="100">
		<template #title>
			ПОЛЬЗОВАТЕЛИ
			<span v-if="loading.sections.users">{{ " (" + users.length + ")" }}</span>
		</template>

		<template #body>
			<div class="users" v-if="loading.sections.users">
				<div class="item" v-for="user in users">
					<div class="user-container">
						<div class="id">#{{ user.id }}</div>
						<div class="img" :style="{ backgroundImage: `url(${user.path}` }"></div>
						<div class="info">
							<div class="fio">
								{{ user.family + " " + user.name + " " }}
							</div>
							<div class="nickname">{{ user.nickname }}</div>
						</div>
					</div>
					<div class="buttons">
						<div class="icon edit" @click="editUser(user)">
							<IconEdit :width="24" :height="24" :type="'edit'"></IconEdit>
							<div class="label">Редактирование</div>
						</div>
						<div class="icon admin" @click="editUserPassword(user)">
							<IconPassword :width="22" :height="22" :type="'admin'"></IconPassword>
							<div class="label">Смена пароля</div>
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
								:type="'delete'"
								v-if="user.statusId === 2"
							></IconLockClose>
							<div class="label">Блокировка</div>
						</div>
						<div class="icon delete" @click="editUserDelete(user)">
							<IconRemove :width="24" :height="24" :type="'delete'"></IconRemove>
							<div class="label">Удаление</div>
						</div>
					</div>
				</div>
			</div>

			<LoaderChild
				:isLoading="loading.loader.users"
				:minHeight="300"
				@loaderChildAfterLeave="loaderChildAfterLeave"
			></LoaderChild>
		</template>

		<template #buttons>
			<ButtonDefault @click="createUser"> Добавить </ButtonDefault>
		</template>
	</block-once>
</template>

<script>
import LoaderChild from "../../../components/modules/LoaderChild.vue";
import AdminModal from "../../../components/includes/admin/AdminModal.vue";
import AdminSubModal from "../../../components/includes/admin/AdminSubModal.vue";
import PasswordGenerator from "../../../components/modules/PasswordGenerator.vue";

import BlockOnce from "../../../components/ui/admin/blocks/BlockOnce.vue";

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
import ButtonClaim from "../../../components/ui/admin/buttons/ButtonClaim.vue";
import ButtonDefault from "../../../components/ui/admin/buttons/ButtonDefault.vue";
import ButtonRemoveWide from "../../../components/ui/admin/buttons/ButtonRemoveWide.vue";
import ButtonDefaultWide from "../../../components/ui/admin/buttons/ButtonDefaultWide.vue";
import ButtonPasswordWide from "../../../components/ui/admin/buttons/ButtonPasswordWide.vue";

import axios from "axios";
import validate from "../../../services/validate";

export default {
	components: {
		LoaderChild,
		AdminModal,
		AdminSubModal,
		PasswordGenerator,
		BlockOnce,
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
		ButtonClaim,
		ButtonDefault,
		ButtonRemoveWide,
		ButtonDefaultWide,
		ButtonPasswordWide,
		axios,
	},
	data() {
		return {
			disabled: {
				users: {
					save: false,
					delete: false,
					create: false,
				},
				userPassword: {
					save: false,
				},
				userBlock: {
					save: false,
				},
			},
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
					delete: true,
				},
				modules: {
					title: false,
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
				type: "delete",
				style: {
					create: false,
					delete: true,
				},
				modules: {
					title: false,
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
					title: false,
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
					password: {
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
					statusId: {
						body: "",
						status: false,
					},
					rightsId: {
						body: "",
						status: false,
					},
					filename: {
						body: "",
						status: false,
					},
					path: {
						body: "",
						status: false,
					},
					file: {
						body: "",
						status: false,
					},
				},
				data: {
					id: {
						body: "",
						edited: false,
					},
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
			userPassword: {
				errors: {
					password: {
						body: "",
						status: false,
					},
				},
				data: {
					password: {
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
			this.$refs.fileUpload.value = "";

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
						this.$store.commit("addDebugger", {
							title: "Ошибка.",
							body: "Низвестный тип открытия модального окна.",
							type: "error",
						});
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
		checkSelect(value) {
			if (validate.checkEmpty(value)) {
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
		chekInputFile() {
			let checkFile = validate.checkInputFile(this.$refs.fileUpload.files[0], [
				"image/jpeg",
				"image/png",
			]);

			if (checkFile.status) {
				this.currentUser.errors.file.status = true;
				this.currentUser.errors.file.body = checkFile.message;

				return checkFile;
			} else {
				this.currentUser.errors.file.status = false;

				return {
					status: false,
					message: "Ошибок нет.",
				};
			}
		},
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
				case "file":
					errorLog = this.chekInputFile();
					break;
				case "select":
					errorLog = this.checkSelect(this[currentName].data[dataKey].body);
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
					case "file":
						if (this.checkModalInput(currentName, inputKeys[i], "file")) {
							errorCount++;
						}
						break;
					case "email":
						if (this.checkModalInput(currentName, inputKeys[i], "email")) {
							errorCount++;
						}
						break;
					case "statusId":
					case "rightsId":
						if (this.checkModalInput(currentName, inputKeys[i], "select")) {
							errorCount++;
						}
						break;
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
		clearModalData(name = "currentUser") {
			for (let key in this[name].data) {
				if (typeof this[name].data[key].body === "array") {
					this[name].data[key].body = [];
					continue;
				}

				this[name].data[key].body = null;
			}
		},
		/* Очистка содержимого модального окна */
		clearModalEdited(name = "currentUser") {
			for (let key in this[name].data) {
				this[name].data[key].edited = false;
			}
		},
		/* Очистка ошибок */
		clearModalErrors(name = "currentUser") {
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
		createUser() {
			this.clearModalErrors("currentUser");
			this.clearModalData("currentUser");

			this.openModal("create", "modal");
		},
		editUserPassword(user) {
			for (let key in this.currentUser.data) {
				this.currentUser.data[key].body = user[key];
			}

			this.clearModalErrors("userPassword");
			this.clearModalData("userPassword");

			this.openModal("edit", "subModalPassword");
		},
		
		/* Редактирование блокировки пользователя */
		editUserBlock(user) {
			for (let key in this.currentUser.data) {
				this.currentUser.data[key].body = user[key];
			}

			this.openModal("edit", "subModalBlock");
		},

		/* Редактирование удаления пользователя */
		editUserDelete(user) {
			this.subModalDelete.style.delete = true;

			for (let key in this.currentUser.data) {
				this.currentUser.data[key].body = user[key];
			}

			this.openModal("edit", "subModalDelete");
		},
		
		/* Редактирование информации пользователя */
		editUser(user) {
			for (let key in this.currentUser.data) {
				this.currentUser.data[key].body = user[key];
			}

			this.clearModalErrors("currentUser");
			this.clearModalEdited("currentUser");

			this.openModal("edit", "modal");
		},

		/* Сохранение блокировки пользователя */
		saveUserBlock() {
			let formData = new FormData();
			formData.append("userId", JSON.stringify(this.currentUser.data.id.body));

			this.disabled.userBlock.save = true;

			axios({
				method: "post",
				url: `${this.$store.getters.urlApi}` + `set-user-status`,
				headers: {
					Accept: "multipart/form-data",
					Authorization: `Bearer ${localStorage.getItem("token")}`,
				},
				data: formData,
			})
				.then((response) => {
					if (response.data.status) {
						let currentUser = this.users.find((user) => {
							if (user.id === this.currentUser.data.id.body) {
								return user;
							}
						});
						currentUser.statusId = response.data.data;

						this.closeModal("subModalBlock");

						this.$store.commit("addDebugger", {
							title: "Успешно!",
							body: response.data.message,
							type: "completed",
						});
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
					this.disabled.userBlock.save = false;
				});
		},
		saveUserPassword() {
			if (this.checkModalInput("userPassword", "password", "text")) return;

			let formData = new FormData();
			formData.append("password", JSON.stringify(this.userPassword.data.password.body));
			formData.append("userId", JSON.stringify(this.currentUser.data.id.body));

			this.disabled.userPassword.save = true;

			axios({
				method: "post",
				url: `${this.$store.getters.urlApi}` + `set-user-password`,
				headers: {
					Accept: "multipart/form-data",
					Authorization: `Bearer ${localStorage.getItem("token")}`,
				},
				data: formData,
			})
				.then((response) => {
					if (response.data.status) {
						this.disabled.userPassword.save = false;
						this.closeModal("subModalPassword");

						this.$store.commit("addDebugger", {
							title: "Успешно!",
							body: response.data.message,
							type: "completed",
						});
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
					this.disabled.userPassword.save = false;
				});
		},

		/* Редактирование информации пользователя */
		saveUser() {
			let checkArray = ["family", "name", "dateOfBirth", "email", "nickname"];

			let formData = new FormData();
			if (this.$refs.fileUpload.files[0]) {
				checkArray.push("file");
				formData.append("image", this.$refs.fileUpload.files[0]);
				formData.append("formats", ["png", "jpg", "jpeg"]);
			}

			if (this.checkModalInputsAll("currentUser", checkArray)) return;

			let user = {};
			for (let key in this.currentUser.data) {
				user[key] = this.currentUser.data[key].body;
			}
			formData.append("user", JSON.stringify(user));

			this.disabled.users.save = true;

			axios({
				method: "post",
				url: `${this.$store.getters.urlApi}` + `save-user`,
				headers: {
					Accept: "multipart/form-data",
					Authorization: `Bearer ${localStorage.getItem("token")}`,
				},
				data: formData,
			})
				.then((response) => {
					if (response.data.status) {
						let currentUser = this.users.find((user) => {
							if (user.id === this.currentUser.data.id.body) {
								return user;
							}
						});

						for (let key in currentUser) {
							if (key === "created_at" || key === "updated_at") continue;
							currentUser[key] = this.currentUser.data[key].body;
						}

						if (response.data.data.path) {
							currentUser.path = response.data.data.path;
							currentUser.filename = response.data.data.path.replace("/storage/users/", "");
						}

						this.closeModal("modal");

						this.$store.commit("addDebugger", {
							title: "Успешно!",
							body: response.data.message,
							type: "completed",
						});
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
					this.disabled.users.save = false;
				});
		},

		/* Добавление пользователя */
		addUser() {
			let checkArray = [
				"family",
				"name",
				"dateOfBirth",
				"email",
				"nickname",
				"password",
				"file",
				"rightsId",
				"statusId",
			];

			if (this.checkModalInputsAll("currentUser", checkArray)) return;

			let formData = new FormData();
			formData.append("image", this.$refs.fileUpload.files[0]);
			formData.append("formats", ["png", "jpg", "jpeg"]);
			let user = {};
			for (let key in this.currentUser.data) {
				user[key] = this.currentUser.data[key].body;
			}
			formData.append("user", JSON.stringify(user));

			this.disabled.users.create = true;

			axios({
				method: "post",
				url: `${this.$store.getters.urlApi}` + `create-user`,
				headers: {
					Accept: "multipart/form-data",
					Authorization: `Bearer ${localStorage.getItem("token")}`,
				},
				data: formData,
			})
				.then((response) => {
					if (response.data.status) {
						this.users.push({
							id: response.data.data.id,
							family: response.data.data.family,
							name: response.data.data.name,
							surname: response.data.data.surname,
							dateOfBirth: response.data.data.dateOfBirth,
							email: response.data.data.email,
							nickname: response.data.data.nickname,
							path: response.data.data.path,
							filename: response.data.data.filename,
							rightsId: response.data.data.rightsId,
							statusId: response.data.data.statusId,
						});

						this.closeModal("modal");

						this.$store.commit("addDebugger", {
							title: "Успешно!",
							body: response.data.message,
							type: "completed",
						});
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
					this.disabled.users.create = false;
				});
		},

		/* Удаление пользователя */
		deleteUser() {
			this.disabled.users.delete = true;

			axios({
				method: "post",
				url: `${this.$store.getters.urlApi}` + `delete-user`,
				headers: {
					Accept: "multipart/form-data",
					Authorization: `Bearer ${localStorage.getItem("token")}`,
				},
				data: {
					id: this.currentUser.data.id.body,
				},
			})
				.then((response) => {
					if (response.data.status) {
						this.users = this.users.filter(
							(user) => user.id !== this.currentUser.data.id.body
						);

						this.closeModal("subModalDelete");

						this.$store.commit("addDebugger", {
							title: "Успешно!",
							body: response.data.message,
							type: "completed",
						});
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
					this.disabled.users.delete = false;
				});
		},
	},
	mounted() {
		axios({
			method: "get",
			headers: {
				Accept: "application/json",
				Authorization: `Bearer ${localStorage.getItem("token")}`,
			},
			url: `${this.$store.getters.urlApi}` + `get-users-all`,
		})
			.then((response) => {
				if (response.data.status) {
					this.users = response.data.data.users;
					this.rights = response.data.data.rights;
					this.statuses = response.data.data.statuses;
				} else {
					if (response.data.message === "Недостаточно прав.") {
						this.$router.push("/404");
						return;
					}

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
				this.loading.loader.users = false;
			});
	},
	beforeCreate() {
		if (this.$store.getters.userRights !== "creator") {
			this.$router.push("/404");
		}
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
	gap: 20px;
	align-items: center;

	border: 2px solid var(--input-border-color-inactive);
	border-radius: 15px;
	padding: 10px 20px;
	transition: all 0.2s;
}

.users > .item > .user-container {
	display: flex;
	align-items: center;
	gap: 20px;
}

.users > .item > .user-container > .id {
	font-size: 22px;
}

.users > .item > .user-container > .img {
	min-width: 70px;
	min-height: 70px;
	border-radius: 50%;
	background-size: cover;
	background-repeat: no-repeat;
	background-position: center center;
}

.users > .item > .user-container > .info > .fio {
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

.users > .item > .buttons > .icon > .label {
	display: none;
}

.users > .item > .buttons > .icon.delete > .label {
	color: #d74949;
}

.users > .item > .buttons > .icon.default > .label {
	color: rgb(100, 100, 100);
}

.users > .item > .buttons > .icon.admin > .label {
	color: rgb(208, 145, 62);
}

.users > .item > .buttons > .icon.edit > .label {
	color: var(--primary-color);
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

@media screen and (width <= 860px) {
	.img-fio {
		grid-template-columns: 1fr;
	}

	.img-fio > .img {
		height: 300px;
	}
}

@media screen and (width <= 750px) {
	.users > .item {
		flex-direction: column;
		align-items: flex-start;
	}

	.users > .item > .buttons {
		flex-direction: column;
		margin-left: 0;
		width: 100%;
	}

	.users > .item > .buttons > .icon {
		flex-grow: 1;
		justify-content: flex-start;
		gap: 10px;
		width: auto;
	}

	.users > .item > .buttons > .icon > .label {
		display: block;
	}
}
</style>
