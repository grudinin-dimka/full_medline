<template>
	<VueDebugger />
	<VueLoader :isLoading="loader.loading" />

	<div class="container-login" v-if="!loader.loading">
		<form @submit.prevent class="login">
			<div class="logo">
				<!-- NOTE: Логотип в логине -->
				<img src="../../assets/svg/medline/logo.svg" alt="логотип" width="130" />
			</div>

			<VueValues
				v-model.trim="currentLogin.data.name.value"
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
				v-model.trim="currentLogin.data.password.value"
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

			<VueFieldset :count="1" :gap="'20px'">
				<template #legend> ПРОВЕРОЧНЫЙ КОД </template>

				<template #inputs>
					<VueCaptcha
						v-model="currentLogin.data.captcha.value"
						:width="250"
						:height="60"
						:length="5"
						:dots="1000"
						:font="{
							min: 30,
							max: 30,
							baseline: 'middle',
						}"
					/>

					<VueValues
						v-model.trim="currentLogin.data.captchaText.value"
						:type="'text'"
						:placeholder="'Введите текст'"
						:error="currentLogin.errors.captchaText.status"
					>
						<template #label>
							<VueIcon
								:name="'Data Loss Prevention'"
								:fill="'var(--primary-color)'"
								:width="'20px'"
								:height="'20px'"
							/>
							КОД С КАРТИНКИ
						</template>
						<template #error>
							{{ currentLogin.errors.captchaText.message }}
						</template>
					</VueValues>
				</template>
			</VueFieldset>

			<div class="buttons">
				<VueButton @click="login" :disabled="disabled.login">
					<VueIcon :name="'Login'" :fill="'white'" :width="'28px'" :height="'28px'" />
					Войти
				</VueButton>
			</div>
		</form>
	</div>
</template>

<script>
import VueCaptcha from "../../components/modules/VueCaptcha.vue";

import axios from "axios";
import validate from "../../services/validate";

export default {
	components: {
		VueCaptcha,
	},
	data() {
		return {
			loader: {
				loading: true,
			},

			disabled: {
				login: false,
			},

			currentLogin: {
				errors: {
					name: {
						message: "",
						status: false,
					},
					password: {
						message: "",
						status: false,
					},
					captcha: {
						message: "",
						status: false,
					},
					captchaText: {
						message: "",
						status: false,
					},
				},
				data: {
					name: {
						value: "",
						edited: false,
					},
					password: {
						value: "",
						edited: false,
					},
					captcha: {
						value: "",
						edited: false,
					},
					captchaText: {
						value: "",
						edited: false,
					},
				},
			},
		};
	},
	methods: {
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                   АВТОРИЗАЦИЯ                     |*/
		/* |___________________________________________________|*/
		/* Авторизация */
		async login() {
			if (
				validate.checkInputsAll(this.currentLogin, [
					{
						key: "name",
						type: "text",
					},
					{
						key: "password",
						type: "text",
					},
					{
						key: "captchaText",
						type: "twice",
						reference: this.currentLogin.data.captcha.value,
					},
				])
			)
				return;

			this.disabled.login = true;

			axios({
				method: "post",
				url: `/api/login`,
				data: {
					name: this.currentLogin.data.name.value,
					password: this.currentLogin.data.password.value,
				},
			})
				.then((response) => {
					if (response.data.success) {
						// Запись токена в глобальную переменную
						this.$store.commit("setValueToLocal", {
							name: "atoken",
							value: response.data.result,
						});

						// Перевод на страницу админки
						this.$router.push({ name: "ehome" });
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
					this.disabled.login = false;
				});
		},
	},
	mounted() {
		this.$store.commit("clearDebugger");

		if (localStorage.getItem("atoken")) {
			this.$router.push({ name: "ehome" });
		} else {
			this.loader.loading = false;
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
