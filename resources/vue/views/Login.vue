<template>
	<debugger />

	<Loader :isLoading="loader.loading" />

	<div class="container-login" v-if="loader.other">
		<form @submit.prevent class="login">
			<div class="logo">
				<!-- NOTE: Логотип в логине -->
				<img src="../assets/svg/medline/logo.svg" alt="логотип" width="130" />
			</div>

			<VueInput
				:placeholder="'Введите логин'"
				:type="'text'"
				v-model="currentLogin.data.name.body"
				:error="currentLogin.errors.name.status"
			>
				<template #label> ЛОГИН </template>
				<template #error>
					{{ currentLogin.errors.name.message }}
				</template>
			</VueInput>

			<VueInput
				:placeholder="'Введите пароль'"
				:type="'password'"
				v-model="currentLogin.data.password.body"
				:error="currentLogin.errors.password.status"
			>
				<template #label> ПАРОЛЬ </template>
				<template #error>
					{{ currentLogin.errors.password.message }}
				</template>
			</VueInput>

			<div class="buttons">
				<ButtonDefault @click="loginUser" :disabled="disabled.login.update">
					Войти
				</ButtonDefault>
			</div>
		</form>
	</div>
</template>

<script>
import Debugger from "../components/modules/Debugger.vue";
import Loader from "../components/modules/loader.vue";
import VueInput from "../components/modules/input/VueInput.vue";

import axios from "axios";

import ButtonDefault from "../components/ui/admin/buttons/ButtonDefault.vue";

import validate from "../services/validate";

export default {
	components: {
		Debugger,
		Loader,
		VueInput,

		ButtonDefault,

		axios,
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
				url: `${this.$store.getters.urlApi}` + `login`,
				data: {
					name: this.currentLogin.data.name.body,
					password: this.currentLogin.data.password.body,
				},
			})
				.then((response) => {
					if (response.data.status) {
						// Запись токена в глобальную переменную
						this.$store.commit("setTokenToLocal", response.data.data.token);

						// Перевод на страницу админки
						this.$router.push({ name: "ehome" });

						// Очистка ошибок
						this.errors.name.status = false;
						this.errors.name.value = "";
						this.errors.password.status = false;
						this.errors.password.value = "";
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
					this.loader.loading = false;
					this.disabled.login.update = false;
				});
		},
	},
	mounted() {
		this.$store.commit("clearDebugger");

		if (localStorage.getItem("token")) {
			this.$router.push({ name: "ehome" });
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

form {
	display: flex;
	flex-direction: column;
	gap: calc(var(--default-gap) / 2);
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
	justify-content: flex-end;

	margin-top: 20px;
}

@media screen and (max-width: 620px) {
	.login {
		padding: 40px 20px;
	}
}
</style>
