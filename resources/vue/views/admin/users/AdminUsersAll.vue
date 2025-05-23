<template>
	<!-- Модальное окно: Пользователи -->
	<Modal ref="modal" :settings="modal">
		<template #title>
			{{ modal.values.title }}
		</template>

		<template #body>
			<div class="img-fio">
				<div class="img">
					<div
						v-if="currentUser.data.path.value"
						:style="{ backgroundImage: `url(${currentUser.data.path.value})` }"
					></div>
					<div
						v-if="!currentUser.data.path.value"
						:style="{ backgroundImage: `url(/storage/default/avatar.png)` }"
					></div>
				</div>
				<!-- Фамилия, имя, отчество -->
				<container-input-three :fieldset="true">
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
							v-model="currentUser.data.family.value"
							@input="currentUser.data.family.edited = true"
						/>
					</template>
					<template #error-one>
						<span class="error" v-if="currentUser.errors.family.status">
							{{ currentUser.errors.family.message }}
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
							v-model="currentUser.data.name.value"
							@input="currentUser.data.name.edited = true"
						/>
					</template>
					<template #error-two>
						<span class="error" v-if="currentUser.errors.name.status">
							{{ currentUser.errors.name.message }}
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
							v-model="currentUser.data.surname.value"
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
			<container-input-two :fieldset="true">
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
						v-model="currentUser.data.password.value"
						@input="currentUser.data.password.edited = true"
					/>
				</template>
				<template #error-one>
					<span class="error" v-if="currentUser.errors.password.status">
						{{ currentUser.errors.password.message }}
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
			<container-input-two :fieldset="true">
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
						{{ currentUser.errors.file.message }}
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
						v-model="currentUser.data.dateOfBirth.value"
						@input="currentUser.data.dateOfBirth.edited = true"
					/>
				</template>
				<template #error-two>
					<span class="error" v-if="currentUser.errors.dateOfBirth.status">
						{{ currentUser.errors.dateOfBirth.message }}
					</span>
				</template>
			</container-input-two>

			<!-- Почта и псевдоним -->
			<container-input-two :fieldset="true">
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
						v-model="currentUser.data.email.value"
						@input="currentUser.data.email.edited = true"
					/>
				</template>
				<template #error-one>
					<span class="error" v-if="currentUser.errors.email.status">
						{{ currentUser.errors.email.message }}
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
						v-model="currentUser.data.nickname.value"
						@input="currentUser.data.nickname.edited = true"
					/>
				</template>
				<template #error-two>
					<span class="error" v-if="currentUser.errors.nickname.status">
						{{ currentUser.errors.nickname.message }}
					</span>
				</template>
			</container-input-two>

			<!-- Статус и права -->
			<container-input-two :fieldset="true">
				<template #legend>
					<span>СТАТУС И ПРАВА</span>
				</template>
				<!-- Первый -->
				<template #title-one> СТАТУС <span v-if="false">(ИЗМЕНЕНО)</span> </template>
				<template #input-one>
					<select
						v-model="currentUser.data.statusId.value"
						:class="{ error: currentUser.errors.statusId.status }"
					>
						<template
							v-if="
								currentUser.data.statusId.value === '' ||
								currentUser.data.statusId.value === null
							"
						>
							<option :value="''" disabled>Выберите статус</option>
						</template>

						<option :value="status.id" v-for="status in statuses">{{ status.name }}</option>
					</select>
				</template>
				<template #error-one>
					<span class="error" v-if="currentUser.errors.statusId.status">
						{{ currentUser.errors.statusId.message }}
					</span>
				</template>
				<!-- Второй -->
				<template #title-two> ПРАВА <span v-if="false">(ИЗМЕНЕНО)</span> </template>
				<template #input-two>
					<select
						v-model="currentUser.data.rightsId.value"
						:class="{ error: currentUser.errors.rightsId.status }"
					>
						<template
							v-if="
								currentUser.data.rightsId.value === '' ||
								currentUser.data.rightsId.value === null
							"
						>
							<option :value="''" disabled>Выберите права</option>
						</template>

						<option :value="right.id" v-for="right in rights">{{ right.name }}</option>
					</select>
				</template>
				<template #error-two>
					<span class="error" v-if="currentUser.errors.rightsId.status">
						{{ currentUser.errors.rightsId.message }}
					</span>
				</template>
			</container-input-two>
		</template>

		<template #footer>
			<ButtonDefault
				@click="saveUser"
				:disabled="disabled.users.save"
				v-if="modal.values.look == 'default'"
			>
				<Icon :name="'edit'" :fill="'white'" :width="'28px'" :height="'28px'" />
				Обновить
			</ButtonDefault>
			<ButtonDefault
				@click="addUser"
				:disabled="disabled.users.create"
				v-if="modal.values.look == 'create'"
			>
				<Icon :name="'add'" :fill="'white'" :width="'23px'" :height="'23px'" />
				Добавить
			</ButtonDefault>
		</template>
	</Modal>

	<!-- Модальное окно: Пользователи -> Пароль -->
	<Modal ref="modalPassword" :settings="modalPassword">
		<template #title>
			{{ modalPassword.values.title }}
		</template>

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
							v-model="userPassword.data.password.value"
						/>
					</template>
					<template #error>
						<span class="error" v-if="userPassword.errors.password.status">
							{{ userPassword.errors.password.message }}
						</span>
					</template>
				</container-input-once>
			</container-input>
		</template>

		<template #footer>
			<button-password-wide
				:type="'default'"
				@click="saveUserPassword"
				:disabled="disabled.userPassword.save"
			>
				Сбросить
			</button-password-wide>
			<button-password-wide :type="'other'" @click="$refs.modalPassword.close()">
				Закрыть
			</button-password-wide>
		</template>
	</Modal>

	<!-- Модальное окно: Пользователи -> Блокировка -->
	<Modal ref="modalBlock" :settings="modalBlock">
		<template #title>
			{{ modalBlock.values.title }}
		</template>

		<template #body>
			<div class="sub-modal-text">
				Вы действительно хотите
				<span class="block">
					{{ currentUser.data.statusId.value === 1 ? "заблокировать" : "разблокировать" }}
				</span>
				пользователя
				<span class="block">
					{{
						currentUser.data.family.value +
						" " +
						currentUser.data.name.value +
						(currentUser.data.surname.value !== null
							? " " + currentUser.data.surname.value
							: "")
					}} </span
				>?
			</div>
		</template>

		<template #footer>
			<ButtonDefaultWide
				:type="'default'"
				@click="saveUserBlock"
				:disabled="disabled.userBlock.save"
			>
				Да
			</ButtonDefaultWide>
			<ButtonDefaultWide :type="'other'" @click="$refs.modalBlock.close()">
				Нет
			</ButtonDefaultWide>
		</template>
	</Modal>

	<!-- Модальное окно: Пользователи -> Удаление -->
	<Modal ref="modalDelete" :settings="modalDelete">
		<template #title>
			{{ modalDelete.values.title }}
		</template>

		<template #body>
			<div class="sub-modal-text">
				Вы действительно хотите <span class="delete">удалить</span> пользователя
				<span class="delete">
					{{
						currentUser.data.family.value +
						" " +
						currentUser.data.name.value +
						(currentUser.data.surname.value !== null
							? " " + currentUser.data.surname.value
							: "")
					}} </span
				>?
			</div>
		</template>

		<template #footer>
			<button-remove-wide
				:type="'default'"
				@click="deleteUser"
				:disabled="disabled.users.delete"
			>
				Да
			</button-remove-wide>
			<button-remove-wide :type="'other'" @click="$refs.modalDelete.close()">
				Нет
			</button-remove-wide>
		</template>
	</Modal>

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
						<div class="icon edit" @click="openModalEdite(user)">
							<Icon
								:name="'edit'"
								:fill="'var(--icon-edit-fill)'"
								:width="'24px'"
								:height="'24px'"
							/>
							<div class="label">Редактирование</div>
						</div>
						<div class="icon admin" @click="openModalPassword(user)">
							<Icon
								:name="'password'"
								:fill="'var(--icon-admin-fill)'"
								:width="'22px'"
								:height="'22px'"
							/>
							<div class="label">Смена пароля</div>
						</div>
						<div
							class="icon"
							:class="{ default: user.statusId === 1, delete: user.statusId === 2 }"
							@click="openModalBlock(user)"
						>
							<Icon
								:name="'lock-open'"
								:fill="'var(--icon-fill)'"
								:width="'24px'"
								:height="'24px'"
								v-if="user.statusId === 1"
							/>
							<Icon
								:name="'lock-close'"
								:fill="'var(--delete-secondary-color)'"
								:width="'24px'"
								:height="'24px'"
								v-if="user.statusId === 2"
							/>
							<div class="label">Блокировка</div>
						</div>
						<div class="icon delete" @click="openModalDelete(user)">
							<Icon
								:name="'delete'"
								:fill="'var(--delete-secondary-color)'"
								:width="'24px'"
								:height="'22px'"
							/>
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
			<ButtonDefault @click="openModalСreate">
				<Icon :name="'add'" :fill="'white'" :width="'23px'" :height="'23px'" />
				Добавить
			</ButtonDefault>
		</template>
	</block-once>
</template>

<script>
import LoaderChild from "../../../components/modules/LoaderChild.vue";
import Modal from "../../../components/modules/modal/Modal.vue";
import PasswordGenerator from "../../../components/modules/PasswordGenerator.vue";

import BlockOnce from "../../../components/ui/admin/blocks/BlockOnce.vue";

import ContainerInput from "../../../components/ui/admin/containers/ContainerInput.vue";
import ContainerInputOnce from "../../../components/ui/admin/containers/input/ContainerInputOnce.vue";
import ContainerInputTwo from "../../../components/ui/admin/containers/input/ContainerInputTwo.vue";
import ContainerInputThree from "../../../components/ui/admin/containers/input/ContainerInputThree.vue";

import Icon from "../../../components/modules/icon/Icon.vue";

import ButtonClaim from "../../../components/ui/admin/buttons/ButtonClaim.vue";
import ButtonDefault from "../../../components/ui/admin/buttons/ButtonDefault.vue";
import ButtonRemoveWide from "../../../components/ui/admin/buttons/ButtonRemoveWide.vue";
import ButtonDefaultWide from "../../../components/ui/admin/buttons/ButtonDefaultWide.vue";
import ButtonPasswordWide from "../../../components/ui/admin/buttons/ButtonPasswordWide.vue";

import axios from "axios";
import validate from "../../../services/validate";
import files from "../../../services/files";
import shared from "../../../services/shared";

export default {
	components: {
		LoaderChild,
		Modal,
		PasswordGenerator,
		BlockOnce,

		ContainerInput,
		ContainerInputOnce,
		ContainerInputTwo,
		ContainerInputThree,

		Icon,

		ButtonClaim,
		ButtonDefault,
		ButtonRemoveWide,
		ButtonDefaultWide,
		ButtonPasswordWide,

		axios,
	},
	data() {
		return {
			/* Кнопки */
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

			/* Загрузчик */
			loading: {
				loader: {
					users: true,
				},
				sections: {
					users: false,
				},
			},

			/* Модальное окно */
			modal: {
				thin: false,
				clamped: false,
				values: {
					title: "",
					look: "default",
				},
			},

			modalPassword: {
				thin: true,
				clamped: false,
				values: {
					title: "",
					look: "default",
				},
			},

			modalBlock: {
				thin: true,
				clamped: false,
				values: {
					title: "",
					look: "default",
				},
			},

			modalDelete: {
				thin: true,
				clamped: false,
				values: {
					title: "",
					look: "default",
				},
			},

			/* Форма */
			currentUser: {
				errors: {
					family: {
						message: "",
						status: false,
					},
					name: {
						message: "",
						status: false,
					},
					surname: {
						message: "",
						status: false,
					},
					password: {
						message: "",
						status: false,
					},
					dateOfBirth: {
						message: "",
						status: false,
					},
					nickname: {
						message: "",
						status: false,
					},
					email: {
						message: "",
						status: false,
					},
					statusId: {
						message: "",
						status: false,
					},
					rightsId: {
						message: "",
						status: false,
					},
					filename: {
						message: "",
						status: false,
					},
					path: {
						message: "",
						status: false,
					},
					file: {
						message: "",
						status: false,
					},
				},
				data: {
					id: {
						value: "",
						edited: false,
					},
					family: {
						value: "",
						edited: false,
					},
					name: {
						value: "",
						edited: false,
					},
					surname: {
						value: "",
						edited: false,
					},
					password: {
						value: "",
						edited: false,
					},
					dateOfBirth: {
						value: "",
						edited: false,
					},
					nickname: {
						value: "",
						edited: false,
					},
					email: {
						value: "",
						edited: false,
					},
					statusId: {
						value: "",
						edited: false,
					},
					rightsId: {
						value: "",
						edited: false,
					},
					filename: {
						value: "",
						edited: false,
					},
					path: {
						value: "",
						edited: false,
					},
				},
			},

			userPassword: {
				errors: {
					password: {
						message: "",
						status: false,
					},
				},
				data: {
					password: {
						value: "",
						edited: false,
					},
				},
			},

			/* Данные */
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
		/* Открытие модального окна */
		openModal(name, title, look) {
			this[name].values.title = title;
			this[name].values.look = look;

			this.$refs[name].open();
		},

		/* Открытие модального окна для добавления */
		openModalEdite(value) {
			this.$refs.fileUpload.value = "";

			shared.clearObjectFull(this.currentUser);
			shared.setData(value, this.currentUser);

			this.openModal("modal", "ПОЛЬЗОВАТЕЛЬ", "default");
		},

		/* Открытие модального окна для добавления */
		openModalСreate() {
			this.$refs.fileUpload.value = "";

			shared.clearObjectFull(this.currentUser);

			this.openModal("modal", "ПОЛЬЗОВАТЕЛЬ", "create");
		},

		/* Открытие модального окна для смены пароля */
		openModalPassword(value) {
			shared.clearObjectFull(this.currentUser);
			shared.setData(value, this.currentUser);

			this.openModal("modalPassword", "", "default");
		},

		/* Открытие модального окна для блокировки */
		openModalBlock(value) {
			shared.clearObjectFull(this.currentUser);
			shared.setData(value, this.currentUser);

			this.openModal("modalBlock", "", "default");
		},

		/* Открытие модального окна для удаления */
		openModalDelete(value) {
			shared.clearObjectFull(this.currentUser);
			shared.setData(value, this.currentUser);

			this.openModal("modalDelete", "", "default");
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                  ПОЛЬЗОВАТЕЛИ                     |*/
		/* |___________________________________________________|*/
		/* Редактирование пароля пользователя */
		editUserPassword(user) {
			shared.clearObjectFull(this.currentUser);
			shared.clearObjectFull(this.userPassword);
			shared.setData(user, this.currentUser);

			this.openModal("edit", "subModalPassword");
		},

		/* Редактирование блокировки пользователя */
		editUserBlock(user) {
			shared.clearObjectFull(this.currentUser);
			shared.setData(user, this.currentUser);

			this.openModal("edit", "subModalBlock");
		},

		/* Редактирование удаления пользователя */
		editUserDelete(user) {
			shared.clearObjectFull(this.currentUser);
			shared.setData(user, this.currentUser);

			this.openModal("edit", "subModalDelete");
		},

		/* Сохранение блокировки пользователя */
		saveUserBlock() {
			let formData = new FormData();
			formData.append("userId", JSON.stringify(this.currentUser.data.id.value));

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
							if (user.id === this.currentUser.data.id.value) {
								return user;
							}
						});
						currentUser.statusId = response.data.data;

						this.$refs.modalBlock.close();

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

		/* Сохранение пароля пользователя */
		saveUserPassword() {
			if (
				validate.checkInputsAll(this.userPassword, [
					{
						key: "password",
						type: "text",
					},
				])
			)
				return;

			let formData = new FormData();
			formData.append("password", JSON.stringify(this.userPassword.data.password.value));
			formData.append("userId", JSON.stringify(this.currentUser.data.id.value));

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
						this.$refs.modalPassword.close();

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
			let errors = 0;

			let formData = new FormData();

			if (this.$refs.fileUpload.files[0]) {
				if (
					validate.checkInputsAll(this.currentUser, [
						{
							key: "file",
							type: "file",
							value: this.$refs.fileUpload,
							formats: ["jpg", "jpeg", "png", "webp"],
						},
					])
				)
					errors++;

				formData.append("image", this.$refs.fileUpload.files[0]);
			}

			if (
				validate.checkInputsAll(this.currentUser, [
					{
						key: "family",
						type: "text",
					},
					{
						key: "name",
						type: "text",
					},
					{
						key: "dateOfBirth",
						type: "text",
					},
					{
						key: "email",
						type: "email",
					},
					{
						key: "nickname",
						type: "text",
					},
				])
			)
				errors++;

			if (errors) return;

			let user = {};
			for (let key in this.currentUser.data) {
				user[key] = this.currentUser.data[key].value;
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
							if (user.id === this.currentUser.data.id.value) {
								return user;
							}
						});

						for (let key in currentUser) {
							if (key === "created_at" || key === "updated_at") continue;
							currentUser[key] = this.currentUser.data[key].value;
						}

						if (response.data.data.path) {
							currentUser.path = response.data.data.path;
							currentUser.filename = files.basename(response.data.data.path);
						}

						this.$refs.modal.close();

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
			if (
				validate.checkInputsAll(this.currentUser, [
					{
						key: "family",
						type: "text",
					},
					{
						key: "name",
						type: "text",
					},
					{
						key: "dateOfBirth",
						type: "text",
					},
					{
						key: "email",
						type: "email",
					},
					{
						key: "nickname",
						type: "text",
					},
					{
						key: "password",
						type: "text",
					},
					{
						key: "rightsId",
						type: "number",
					},
					{
						key: "statusId",
						type: "number",
					},
					{
						key: "file",
						type: "file",
						value: this.$refs.fileUpload,
						formats: ["jpg", "jpeg", "png", "webp"],
					},
				])
			)
				return;

			let formData = new FormData();
			formData.append("image", this.$refs.fileUpload.files[0]);

			let user = {};
			for (let key in this.currentUser.data) {
				user[key] = this.currentUser.data[key].value;
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

						this.$refs.modal.close();

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
					id: this.currentUser.data.id.value,
				},
			})
				.then((response) => {
					if (response.data.status) {
						this.users = this.users.filter(
							(user) => user.id !== this.currentUser.data.id.value
						);

						this.$refs.modalBlock.close();

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

	border: var(--input-border);
	border-radius: calc(var(--default-border-radius) / 2);
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
	color: var(--icon-delete-font-color);
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
	background-color: var(--icon-delete-background-color);
}

.users > .item > .buttons > .icon.delete:hover {
	background-color: var(--icon-delete-background-color-hover);
}

.users > .item > .buttons > .icon.edit {
	background-color: var(--icon-edit-background-color);
}

.users > .item > .buttons > .icon.edit:hover {
	background-color: var(--icon-edit-background-color-hover);
}

.users > .item > .buttons > .icon.admin {
	background-color: rgb(255, 242, 222);
}

.users > .item > .buttons > .icon.admin:hover {
	background-color: rgb(248, 232, 208);
}

.users > .item > .buttons > .icon.default {
	background-color: var(--icon-background-color);
}

.users > .item > .buttons > .icon.default:hover {
	background-color: var(--icon-background-color-hover);
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
