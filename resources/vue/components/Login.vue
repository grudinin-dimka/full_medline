<template>
	<debugger />

	<Loader :isLoading="loader.loading" />

	<div class="container-login" v-if="loader.other">
		<div class="login">
			<div class="logo">
				<Logo />
			</div>
			<container-input>
				<container-input-once :type="'edit'">
					<template #title>
						<span>ЛОГИН</span>
					</template>
					<template #input>
						<input
							type="text"
							placeholder="Введите логин"
							v-model="currentLogin.data.name.body"
							:class="{ error: currentLogin.errors.name.status }"
							@blur="checkModalInput('currentLogin', 'name', 'text')"
						/>
					</template>
					<template #error>
						<span class="error" v-if="currentLogin.errors.name.status">
							{{ currentLogin.errors.name.body }}
						</span>
					</template>
				</container-input-once>
				<container-input-once :type="'edit'">
					<template #title>
						<span>ПАРОЛЬ</span>
					</template>
					<template #input>
						<input
							type="password"
							placeholder="Введите пароль"
							v-model="currentLogin.data.password.body"
							:class="{ error: currentLogin.errors.password.status }"
							@blur="checkModalInput('currentLogin', 'password', 'text')"
						/>
					</template>
					<template #error>
						<span class="error" v-if="currentLogin.errors.password.status">
							{{ currentLogin.errors.password.body }}
						</span>
					</template>
				</container-input-once>
				<div class="buttons">
					<ButtonDefault @click="loginUser" :disabled="disabled.login.update">
						Войти
					</ButtonDefault>
				</div>
			</container-input>
		</div>
	</div>
</template>

<script>
import Debugger from "./includes/Debugger.vue";
import Loader from "./includes/loader.vue";

import axios from "axios";

import Logo from "./icons/logo.vue";

import ContainerInput from "./ui/admin/containers/ContainerInput.vue";
import ContainerInputOnce from "./ui/admin/containers/input/ContainerInputOnce.vue";
import ButtonDefault from "./ui/admin/buttons/ButtonDefault.vue";

import validate from "../services/validate";

export default {
	components: {
		Debugger,
		Loader,
		axios,
		Logo,
		ContainerInput,
		ContainerInputOnce,
		ButtonDefault,
		validate,
	},
	data() {
		return {
			loader: {
				loading: true,
				other: false,
			},
			name: "",
			password: "",
			disabled: {
				login: {
					update: false,
				},
			},
			currentLogin: {
				errors: {
					name: {
						body: "",
						status: false,
					},
					password: {
						body: "",
						status: false,
					},
				},
				data: {
					name: {
						body: "",
						edited: false,
					},
					password: {
						body: "",
						edited: false,
					},
				},
			},
			errors: {
				name: {
					status: false,
					value: "",
				},
				password: {
					status: false,
					value: "",
				},
				server: false,
			},
		};
	},
	methods: {
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                     ПРОВЕРКИ                      |*/
		/* |___________________________________________________|*/
		/* Проверка поля имени */
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
		/* Проверка всех полей ввода модального окна */
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
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                   АВТОРИЗАЦИЯ                     |*/
		/* |___________________________________________________|*/
		/* Авторизация */
		async loginUser() {
			if (this.checkModalInputsAll("currentLogin", ["name", "password"])) return;

			this.disabled.login.update = true;

			axios({
				method: "post",
				url: `${this.$store.state.axios.urlApi}` + `login`,
				data: {
					name: this.currentLogin.data.name.body,
					password: this.currentLogin.data.password.body,
				},
			})
				.then((response) => {
					if (response.data.status) {
						// Запись токена в глобальную переменную
						localStorage.setItem("token", response.data.result.token);
						// Запись пользователя в глобальную переменную

						axios.defaults.headers.common["Accept"] = "application/json";
						axios.defaults.headers.common["Authorization"] = `Bearer ${localStorage.getItem(
							"token"
						)}`;

						// Перевод на страницу админки
						this.$router.push({ name: "ehome" });

						// Очистка ошибок
						this.errors.name.status = false;
						this.errors.name.value = "";
						this.errors.password.status = false;
						this.errors.password.value = "";

						this.disabled.login.update = false;

						return;
					} else {
						this.disabled.login.update = false;

						let debbugStory = {
							title: "Ошибка.",
							body: response.data.message,
							type: "Error",
						};
						this.$store.commit("debuggerState", debbugStory);
					}
				})
				.catch((error) => {
					this.errors.server = true;
					this.disabled.login.update = false;
					j;
					let debbugStory = {
						title: "Ошибка.",
						body: "Отсутствует соединение с сервером.",
						type: "Error",
					};
					this.$store.commit("debuggerState", debbugStory);
					return;
				})
				.finally(() => {
					this.loader.loading = false;
				});
		},
	},
	mounted() {
		this.$store.commit("setDebuggerClose");

		if (localStorage.getItem("token")) {
			// Проверка токена
			axios({
				method: "post",
				url: `${this.$store.state.axios.urlApi}` + `check-token`,
				headers: {
					Accept: "application/json",
					Authorization: `Bearer ${localStorage.getItem("token")}`,
				},
			})
				.then((response) => {
					// Перевод на страницу админки
					this.$router.push({ name: "ehome" });
				})
				.catch((error) => {
					localStorage.removeItem("token");

					this.loader.loading = false;
					this.loader.other = true;
				});
		} else {
			this.loader.loading = false;
			this.loader.other = true;
		}
	},
};
</script>

<style scoped>
.container-login {
	display: flex;
	justify-content: center;
	align-items: center;
	height: 100vh;
	background-color: #ececec;
}

.login {
	display: flex;
	flex-direction: column;

	border-radius: 20px;

	padding: 40px;
	margin: 20px;

	width: 500px;
	background: white;

	box-shadow: 0px 0px 20px 0px rgba(0, 0, 0, 0.1);
}

.logo {
	display: flex;
	justify-content: center;
	margin-bottom: 30px;
}

.block {
	display: flex;
	flex-direction: column;
}

.block label {
	margin: 10px 10px;

	font-size: 20px;
}

.buttons {
	display: flex;
	justify-content: center;

	margin-top: 30px;
}

@media screen and (max-width: 620px) {
	.login {
		padding: 40px 20px;
	}
}
</style>
