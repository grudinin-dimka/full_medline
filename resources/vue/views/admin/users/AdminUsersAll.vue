<template>
	<!-- Модальное окно: Пользователи -->
	<VueModal ref="modal" :settings="modal">
		<template #title>
			{{ modal.values.title }}
		</template>

		<template #body>
			<div class="img-fio">
				<div class="users__item-img">
					<div
						v-if="currentUser.data.path.value"
						:style="{ backgroundImage: `url(${currentUser.data.path.value})` }"
					></div>
					<div
						v-if="!currentUser.data.path.value"
						:style="{ backgroundImage: `url(/storage/default/avatar.png)` }"
					></div>
				</div>

				<VueFieldset :count="1" :gap="'20px'">
					<template #legend> ФИО </template>
					<template #inputs>
						<VueValues
							v-model.trim="currentUser.data.family.value"
							:type="'text'"
							:placeholder="'Введите фамилию'"
							:error="currentUser.errors.family.status"
						>
							<template #label>
								<VueIcon :name="'Id Card'" :fill="'var(--primary-color)'" :width="'20px'" :height="'20px'" />
								ФАМИЛИЯ
							</template>
							<template #error>
								{{ currentUser.errors.family.message }}
							</template>
						</VueValues>

						<VueValues
							v-model.trim="currentUser.data.name.value"
							:type="'text'"
							:placeholder="'Введите имя'"
							:error="currentUser.errors.name.status"
						>
							<template #label>
								<VueIcon :name="'Id Card'" :fill="'var(--primary-color)'" :width="'20px'" :height="'20px'" />
								ИМЯ
							</template>
							<template #error>
								{{ currentUser.errors.name.message }}
							</template>
						</VueValues>

						<VueValues
							v-model.trim="currentUser.data.surname.value"
							:type="'text'"
							:placeholder="'Введите отчество'"
							:error="currentUser.errors.surname.status"
						>
							<template #label>
								<VueIcon :name="'Id Card'" :fill="'var(--primary-color)'" :width="'20px'" :height="'20px'" />
								ОТЧЕСТВО
							</template>
							<template #error>
								{{ currentUser.errors.surname.message }}
							</template>
						</VueValues>
					</template>
				</VueFieldset>
			</div>

			<VueFieldset :count="1" :gap="'15px'">
				<template #legend> ПАРОЛЬ И ГЕНЕРАТОР </template>
				<template #inputs>
					<VueValues
						v-model="currentUser.data.password.value"
						:type="'text'"
						:placeholder="'Введите пароль'"
						:error="currentUser.errors.password.status"
					>
						<template #label>
							<VueIcon :name="'Key'" :fill="'var(--primary-color)'" :width="'20px'" :height="'20px'" />
							ПАРОЛЬ
						</template>
						<template #error>
							{{ currentUser.errors.password.message }}
						</template>
					</VueValues>

					<PasswordGenerator />
				</template>
			</VueFieldset>

			<VueFieldset :count="2" :gap="'10px'">
				<template #legend> АВАТАР И ДАТА РОЖДЕНИЯ </template>
				<template #inputs>
					<VueFile
						v-model="currentUser.data.file.value"
						ref="fileImage"
						:type="'image'"
						:placeholder="'Загрузите картинку'"
						:error="currentUser.errors.file.status"
					>
						<template #label>
							<VueIcon :name="'Attach File'" :fill="'var(--primary-color)'" :width="'20px'" :height="'20px'" />
							ИЗОБРАЖЕНИЕ
						</template>
						<template #error>
							{{ currentUser.errors.file.message }}
						</template>
					</VueFile>

					<VueDate
						v-model.trim="currentUser.data.dateOfBirth.value"
						:type="'date'"
						:error="currentUser.errors.dateOfBirth.status"
					>
						<template #label>
							<VueIcon :name="'Id Card'" :fill="'var(--primary-color)'" :width="'20px'" :height="'20px'" />
							ДАТА
						</template>
						<template #error>
							{{ currentUser.errors.dateOfBirth.message }}
						</template>
					</VueDate>
				</template>
			</VueFieldset>

			<VueFieldset :count="2" :gap="'10px'">
				<template #legend> ПОЧТА И ПСЕВДОНИМ </template>
				<template #inputs>
					<VueValues
						v-model.trim="currentUser.data.email.value"
						:type="'mail'"
						:placeholder="'test@mail.ru'"
						:error="currentUser.errors.email.status"
					>
						<template #label> ПОЧТА </template>
						<template #error>
							{{ currentUser.errors.email.message }}
						</template>
					</VueValues>

					<VueValues
						v-model.trim="currentUser.data.nickname.value"
						:type="'text'"
						:placeholder="'Введите псевдоним'"
						:error="currentUser.errors.nickname.status"
					>
						<template #label> ПСЕВДОНИМ </template>
						<template #error>
							{{ currentUser.errors.nickname.message }}
						</template>
					</VueValues>
				</template>
			</VueFieldset>

			<VueFieldset :count="2" :gap="'10px'">
				<template #legend> ПОЧТА И ПСЕВДОНИМ </template>
				<template #inputs>
					<VueSelector
						v-model="currentUser.data.statusId.value"
						:is-clear="false"
						:is-search="false"
						:list="filteredStatuses"
						:error="currentUser.errors.statusId.status"
					>
						<template #label> СТАТУС </template>
						<template #error>
							{{ currentUser.errors.statusId.message }}
						</template>
					</VueSelector>

					<VueSelector
						v-model="currentUser.data.rightsId.value"
						:is-clear="false"
						:is-search="false"
						:list="filteredRights"
						:error="currentUser.errors.rightsId.status"
					>
						<template #label> ПРАВА </template>
						<template #error>
							{{ currentUser.errors.rightsId.message }}
						</template>
					</VueSelector>
				</template>
			</VueFieldset>
		</template>

		<template #footer>
			<VueButton @click="saveUser" :disabled="disabled.users.save" v-if="modal.values.look == 'default'">
				<VueIcon :name="'Edit'" :fill="'white'" :width="'28px'" :height="'28px'" />
				Обновить
			</VueButton>

			<VueButton @click="addUser" :disabled="disabled.users.create" v-if="modal.values.look == 'create'">
				<VueIcon :name="'Add'" :fill="'white'" :width="'26px'" :height="'26px'" />
				Добавить
			</VueButton>
		</template>
	</VueModal>

	<!-- Модальное окно: Пользователи -> Пароль -->
	<VueModal ref="modalPassword" :settings="modalPassword">
		<template #title>
			{{ modalPassword.values.title }}
		</template>

		<template #body>
			<VueValues
				v-model.trim="currentPassword.data.password.value"
				:type="'text'"
				:placeholder="'Введите пароль'"
				:error="currentPassword.errors.password.status"
			>
				<template #error>
					{{ currentPassword.errors.password.message }}
				</template>
			</VueValues>

			<PasswordGenerator />
		</template>

		<template #footer>
			<VueButton :wide="true" @click="saveUserPassword" :disabled="disabled.userPassword.save"> Сбросить </VueButton>

			<VueButton :wide="true" :look="'edit-secondary'" @click="$refs.modalPassword.close()"> Закрыть </VueButton>
		</template>
	</VueModal>

	<!-- Модальное окно: Пользователи -> Блокировка -->
	<VueModal ref="modalBlock" :settings="modalBlock">
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
						(currentUser.data.surname.value !== null ? " " + currentUser.data.surname.value : "")
					}} </span
				>?
			</div>
		</template>

		<template #footer>
			<VueButton :wide="true" @click="saveUserBlock" :disabled="disabled.userBlock.save"> Да </VueButton>
			<VueButton :wide="true" :look="'edit-secondary'" @click="$refs.modalBlock.close()"> Нет </VueButton>
		</template>
	</VueModal>

	<!-- Модальное окно: Пользователи -> Удаление -->
	<VueModal ref="modalDelete" :settings="modalDelete">
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
						(currentUser.data.surname.value !== null ? " " + currentUser.data.surname.value : "")
					}} </span
				>?
			</div>
		</template>

		<template #footer>
			<VueButton :look="'delete'" :wide="true" @click="deleteUser" :disabled="disabled.users.delete"> Да </VueButton>

			<VueButton :wide="true" :look="'delete-secondary'" @click="$refs.modalDelete.close()"> Нет </VueButton>
		</template>
	</VueModal>

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
				<div class="users__item" v-for="user in users">
					<div class="users__item-info">
						<div class="users__item-id">#{{ user.id }}</div>
						<div class="users__item-img" :style="{ backgroundImage: `url(${user.path}` }"></div>
						<div class="users__item-fio">
							<div class="users__item-name">
								{{ user.family + " " + user.name + " " }}
							</div>
							<div class="users__item-nick">{{ user.nickname }}</div>
						</div>
					</div>

					<div class="buttons">
						<div class="icon edit" @click="openModalEdite(user)">
							<VueIcon :name="'Edit'" :fill="'var(--icon-edit-fill)'" :width="'28px'" :height="'28px'" />
							<div class="label">Редактирование</div>
						</div>
						<div class="icon admin" @click="openModalPassword(user)">
							<VueIcon :name="'Key'" :fill="'var(--icon-admin-fill)'" :width="'28px'" :height="'28px'" />
							<div class="label">Смена пароля</div>
						</div>
						<div
							class="icon"
							:class="{ default: user.statusId === 1, delete: user.statusId === 2 }"
							@click="openModalBlock(user)"
						>
							<VueIcon
								:name="'Lock Open'"
								:fill="'var(--icon-fill)'"
								:width="'24px'"
								:height="'24px'"
								v-if="user.statusId === 1"
							/>
							<VueIcon
								:name="'Lock'"
								:fill="'var(--delete-secondary-color)'"
								:width="'24px'"
								:height="'24px'"
								v-if="user.statusId === 2"
							/>
							<div class="label">Блокировка</div>
						</div>
						<div class="icon delete" @click="openModalDelete(user)">
							<VueIcon :name="'Delete'" :fill="'var(--delete-secondary-color)'" :width="'28px'" :height="'28px'" />
							<div class="label">Удаление</div>
						</div>
					</div>
				</div>
			</div>

			<VueLoader :isLoading="loading.loader.users" :isChild="true" :minHeight="300" @afterLeave="loaderChildAfterLeave" />
		</template>

		<template #buttons>
			<VueButton @click="openModalСreate()">
				<VueIcon :name="'Add'" :fill="'white'" :width="'26px'" :height="'26px'" />
				Добавить
			</VueButton>
		</template>
	</block-once>
</template>

<script>
import BlockOnce from "../../../components/ui/admin/blocks/BlockOnce.vue";
import PasswordGenerator from "../../../components/modules/PasswordGenerator.vue";

import api from "../../../mixin/api";
import validate from "../../../services/validate";
import files from "../../../services/files";
import shared from "../../../services/shared";

export default {
	components: {
		PasswordGenerator,
		BlockOnce,
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
				touch: false,
				values: {
					title: "",
					look: "default",
				},
			},

			modalPassword: {
				thin: true,
				clamped: false,
				touch: false,
				values: {
					title: "",
					look: "default",
				},
			},

			modalBlock: {
				thin: true,
				clamped: false,
				touch: true,
				values: {
					title: "",
					look: "default",
				},
			},

			modalDelete: {
				thin: true,
				clamped: false,
				touch: true,
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
					file: {
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
					file: {
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

			currentPassword: {
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
	computed: {
		filteredStatuses() {
			let array = shared.getRecursiveCopy(this.statuses);

			array = array.map((status) => {
				return {
					default: false,
					disabled: false,
					value: status.id,
					label: status.name,
				};
			});

			array.unshift({
				default: true,
				disabled: true,
				value: "",
				label: "Выберите статус",
			});

			return array;
		},

		filteredRights() {
			let array = shared.getRecursiveCopy(this.rights);

			array = array.map((right) => {
				return {
					default: false,
					disabled: false,
					value: right.id,
					label: right.name,
				};
			});

			array.unshift({
				default: true,
				disabled: true,
				value: "",
				label: "Выберите права",
			});

			return array;
		},
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
			this.$refs.fileImage.clear();

			shared.clearObjectFull(this.currentUser);
			shared.setData(value, this.currentUser);

			this.openModal("modal", "ПОЛЬЗОВАТЕЛЬ", "default");
		},

		/* Открытие модального окна для добавления */
		openModalСreate() {
			this.$refs.fileImage.clear();

			shared.clearObjectFull(this.currentUser);

			this.openModal("modal", "ПОЛЬЗОВАТЕЛЬ", "create");
		},

		/* Открытие модального окна для смены пароля */
		openModalPassword(value) {
			shared.clearObjectFull(this.currentUser);
			shared.clearObjectFull(this.currentPassword);
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
		/* Сохранение блокировки пользователя */
		saveUserBlock() {
			this.disabled.userBlock.save = true;

			api({
				method: "post",
				url: this.$store.getters.urlApi + `set-user-status`,
				headers: {
					Accept: "application/json",
				},
				data: {
					id: this.currentUser.data.id.value,
				},
			})
				.then((response) => {
					if (!response) return;

					let currentUser = this.users.find((user) => {
						if (user.id === this.currentUser.data.id.value) {
							return user;
						}
					});

					currentUser.statusId = response.data.result;

					this.$refs.modalBlock.close();
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
				validate.checkInputsAll(this.currentPassword, [
					{
						key: "password",
						type: "text",
					},
				])
			)
				return;

			this.disabled.userPassword.save = true;

			api({
				method: "post",
				url: this.$store.getters.urlApi + `set-user-password`,
				headers: {
					Accept: "application/json",
				},
				data: {
					id: this.currentUser.data.id.value,
					password: this.currentPassword.data.password.value,
				},
			})
				.then((response) => {
					if (!response) return;

					this.$refs.modalPassword.close();
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

			if (this.$refs.fileImage.files().length > 0) {
				if (
					validate.checkInputsAll(this.currentUser, [
						{
							key: "file",
							type: "file",
							value: this.$refs.fileImage.files(),
							formats: ["jpg", "jpeg", "png", "webp"],
						},
					])
				)
					errors++;

				formData.append("image", this.$refs.fileImage.files()[0]);
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

			formData.append(
				"user",
				JSON.stringify({
					id: this.currentUser.data.id.value,
					family: this.currentUser.data.family.value,
					name: this.currentUser.data.name.value,
					surname: this.currentUser.data.surname.value,
					dateOfBirth: this.currentUser.data.dateOfBirth.value,
					email: this.currentUser.data.email.value,
					nickname: this.currentUser.data.nickname.value,
					password: this.currentUser.data.password.value,
					rightsId: this.currentUser.data.rightsId.value,
					statusId: this.currentUser.data.statusId.value,
				})
			);

			this.disabled.users.save = true;

			api({
				method: "post",
				url: this.$store.getters.urlApi + `save-user`,
				headers: {
					Accept: "multipart/form-data",
				},
				data: formData,
			})
				.then((response) => {
					if (!response) return;

					let currentUser = this.users.find((user) => {
						if (user.id === this.currentUser.data.id.value) {
							return user;
						}
					});

					for (let key in currentUser) {
						if (key === "created_at" || key === "updated_at") continue;
						currentUser[key] = this.currentUser.data[key].value;
					}

					if (response.data.result.path) {
						currentUser.path = response.data.result.path;
						currentUser.filename = files.basename(response.data.result.path);
					}

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
						value: this.$refs.fileImage.files(),
						formats: ["jpg", "jpeg", "png", "webp"],
					},
				])
			)
				return;

			let formData = new FormData();
			formData.append("image", this.$refs.fileImage.files()[0]);

			let user = {};
			for (let key in this.currentUser.data) {
				user[key] = this.currentUser.data[key].value;
			}

			formData.append("user", JSON.stringify(user));

			this.disabled.users.create = true;

			api({
				method: "post",
				url: this.$store.getters.urlApi + `create-user`,
				headers: {
					Accept: "multipart/form-data",
				},
				data: formData,
			})
				.then((response) => {
					if (!response) return;

					this.users.push({
						id: response.data.result.id,
						family: response.data.result.family,
						name: response.data.result.name,
						surname: response.data.result.surname,
						dateOfBirth: response.data.result.dateOfBirth,
						email: response.data.result.email,
						nickname: response.data.result.nickname,
						path: response.data.result.path,
						filename: response.data.result.filename,
						rightsId: response.data.result.rightsId,
						statusId: response.data.result.statusId,
					});

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
					this.disabled.users.create = false;
				});
		},

		/* Удаление пользователя */
		deleteUser() {
			this.disabled.users.delete = true;

			api({
				method: "post",
				url: this.$store.getters.urlApi + `delete-user`,
				headers: {
					Accept: "multipart/form-data",
				},
				data: {
					id: this.currentUser.data.id.value,
				},
			})
				.then((response) => {
					if (!response) return;

					this.users = this.users.filter((user) => user.id !== this.currentUser.data.id.value);

					this.$refs.modalDelete.close();
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
		api({
			method: "get",
			url: this.$store.getters.urlApi + `get-users-all`,
		})
			.then((response) => {
				if (!response) return;

				this.users = response.data.result.users;
				this.rights = response.data.result.rights;
				this.statuses = response.data.result.statuses;
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

.users__item {
	display: flex;
	gap: 20px;
	align-items: center;

	border: var(--input-border);
	border-radius: calc(var(--default-border-radius) / 1.5);
	padding: 10px 20px;
	transition: all 0.2s;
}

.users__item-info {
	display: flex;
	align-items: center;
	gap: 20px;
}

.users__item-id {
	font-size: 22px;
}

.users__item-img {
	min-width: 70px;
	min-height: 70px;
	border-radius: 50%;
	background-size: cover;
	background-repeat: no-repeat;
	background-position: center center;
}

.users__item-name {
	color: var(--primary-color);
}

.users > .users__item > .buttons {
	display: flex;
	gap: 10px;
	margin-left: auto;
}

.users > .users__item > .buttons > .icon {
	display: flex;
	justify-content: center;
	align-items: center;
	cursor: pointer;
	padding: 10px;
	border-radius: 100px;

	width: 60px;
	height: 60px;

	aspect-ratio: 1 / 1;
}

.users > .users__item > .buttons > .icon > .label {
	display: none;
}

.users > .users__item > .buttons > .icon.delete > .label {
	color: var(--icon-delete-font-color);
}

.users > .users__item > .buttons > .icon.default > .label {
	color: rgb(100, 100, 100);
}

.users > .users__item > .buttons > .icon.admin > .label {
	color: rgb(208, 145, 62);
}

.users > .users__item > .buttons > .icon.edit > .label {
	color: var(--primary-color);
}

.users > .users__item > .buttons > .icon.delete {
	background-color: var(--icon-delete-background-color);
}

.users > .users__item > .buttons > .icon.delete:hover {
	background-color: var(--icon-delete-background-color-hover);
}

.users > .users__item > .buttons > .icon.edit {
	background-color: var(--icon-edit-background-color);
}

.users > .users__item > .buttons > .icon.edit:hover {
	background-color: var(--icon-edit-background-color-hover);
}

.users > .users__item > .buttons > .icon.admin {
	background-color: rgb(255, 242, 222);
}

.users > .users__item > .buttons > .icon.admin:hover {
	background-color: rgb(248, 232, 208);
}

.users > .users__item > .buttons > .icon.default {
	background-color: var(--icon-background-color);
}

.users > .users__item > .buttons > .icon.default:hover {
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

.img-fio > .users__item-img {
	display: flex;
	justify-content: center;
	align-items: center;
	margin-top: 10px;
}

.img-fio > .users__item-img > div {
	margin: 0 auto;
	width: 250px;
	height: 100%;
	max-height: 300px;
	aspect-ratio: 1 / 1;
	border-radius: 300px;
	background-size: cover;
	background-repeat: no-repeat;
	background-position: center center;
}

@media screen and (width <= 860px) {
	.img-fio {
		grid-template-columns: 1fr;
	}

	.img-fio > .users__item-img {
		height: 300px;
	}
}

@media screen and (width <= 750px) {
	.users > .users__item {
		flex-direction: column;
		align-items: flex-start;
	}

	.users > .users__item > .buttons {
		flex-direction: column;
		margin-left: 0;
		width: 100%;
	}

	.users > .users__item > .buttons > .icon {
		flex-grow: 1;
		justify-content: flex-start;
		gap: 10px;
		width: auto;
	}

	.users > .users__item > .buttons > .icon > .label {
		display: block;
	}
}
</style>
