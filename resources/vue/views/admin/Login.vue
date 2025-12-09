<template>
	<VueDebugger />

	<VueLoader :isLoading="loader.loading" />

	<div class="container-login" v-if="loader.other">
		<form @submit.prevent class="login">
			<div class="logo">
				<!-- NOTE: Логотип в логине -->
				<img src="../../assets/svg/medline/logo.svg" alt="логотип" width="130" />
			</div>

			<VueValues
				v-model.trim="currentLogin.data.name.body"
				:type="'text'"
				:placeholder="'Введите логин'"
				:error="currentLogin.errors.name.status"
			>
				<template #label>
					<VueIcon :name="'Alternate Email'" :fill="'var(--primary-color)'" :width="'20px'" :height="'20px'" />
					ЛОГИН
				</template>
				<template #error>
					{{ currentLogin.errors.name.message }}
				</template>
			</VueValues>

			<VuePassword
				v-model.trim="currentLogin.data.password.body"
				:placeholder="'Введите пароль'"
				:error="currentLogin.errors.password.status"
			>
				<template #label>
					<VueIcon :name="'Passkey'" :fill="'var(--primary-color)'" :width="'20px'" :height="'20px'" />
					ПАРОЛЬ
				</template>
				<template #error>
					{{ currentLogin.errors.password.message }}
				</template>
			</VuePassword>

			<div class="buttons">
				<VueButton @click="loginUser" :disabled="disabled.login.update"> Войти </VueButton>
			</div>
		</form>
	</div>
</template>

<script>
import axios from "axios";
import validate from "../../services/validate";

export default {
	components: {
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
					errorLog = validate.checkInputMask(this[currentName].data[dataKey].body);
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
	background-color: var(--content-background-color);
}

.login {
	display: flex;
	flex-direction: column;
	gap: var(--default-gap);

	border: var(--default-border);
	border-radius: 20px;
	padding: 40px;
	margin: 20px;

	width: min(100%, 600px);
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
}

@media screen and (max-width: 620px) {
	.login {
		padding: 40px 20px;
	}
}
</style>
