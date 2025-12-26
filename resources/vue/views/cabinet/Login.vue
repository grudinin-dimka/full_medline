<template>
	<VueDebugger />
	<VueLoader :isLoading="loader.loading" />

	<div class="container-login">
		<div class="login">
			<div class="login__info">
				<div class="login__info-text">Личный кабинет</div>
				<div class="login__info-text">v0.5</div>
			</div>

			<form @submit.prevent class="login__form">
				<VueFieldset :count="1" :gap="'20px'" :clear="true">
					<template #inputs>
						<VueMask
							v-model.trim="currentLogin.data.snils.value"
							:mask="'###-###-### ##'"
							:placeholder="'000-000-000 00'"
							:error="currentLogin.errors.snils.status"
						>
							<template #label>
								<VueIcon :name="'Id Card'" :fill="'var(--primary-color)'" :width="'20px'" :height="'20px'" />
								СНИЛС
							</template>
							<template #error>
								{{ currentLogin.errors.snils.message }}
							</template>
						</VueMask>

						<VuePassword
							v-model.trim="currentLogin.data.password.value"
							:placeholder="'Введите пароль'"
							:error="currentLogin.errors.password.status"
						>
							<template #label>
								<VueIcon :name="'Key'" :fill="'var(--primary-color)'" :width="'20px'" :height="'20px'" />
								ПАРОЛЬ
							</template>
							<template #error>
								{{ currentLogin.errors.password.message }}
							</template>
						</VuePassword>
					</template>
				</VueFieldset>

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

				<VueSwitch v-model="currentLogin.data.approval.value" :error="currentLogin.errors.approval.status">
					<template #label> Я согласен с <a class="link" href="#">политикой конфиденциальности</a> кабинета </template>
				</VueSwitch>

				<div class="form__login-buttons">
					<VueButton :disabled="disabled.login" :type="'submit'" @click="login">
						<VueIcon :name="'Login'" :fill="'white'" :width="'28px'" :height="'28px'" />
						Войти
					</VueButton>
				</div>
			</form>

			<div class="login__footer">
				<div class="login__footer-text">ООО "МЕДЛАЙН"</div>
			</div>
		</div>

		<div class="info">
			<div class="info__logo">
				<div class="aside__logo-icon">
					<img :src="`/storage/img/logo.svg`" alt="Логотип" />
				</div>

				<div class="aside__logo-text">МЕДЛАЙН</div>
			</div>
		</div>
	</div>
</template>

<script>
import capi from "../../mixin/capi";
import axios from "axios";
import validate from "../../services/validate";

import VueCaptcha from "../../components/modules/VueCaptcha.vue";

export default {
	name: "CabinetLogin",
	components: {
		VueCaptcha,
	},
	data() {
		return {
			loader: {
				loading: true,
				other: false,
			},

			disabled: {
				login: false,
			},

			currentLogin: {
				errors: {
					password: {
						message: "",
						status: false,
					},
					snils: {
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
					approval: {
						message: "",
						status: false,
					},
				},
				data: {
					password: {
						value: "",
						edited: false,
					},
					snils: {
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
					approval: {
						value: false,
						edited: false,
					},
				},
			},
		};
	},
	methods: {
		/* Авторизация */
		async login() {
			// проверка полей ввода
			if (
				validate.checkInputsAll(this.currentLogin, [
					{
						key: "snils",
						type: "mask",
						mask: /\d{3}-\d{3}-\d{3} \d{2}/,
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
					{
						key: "approval",
						type: "boolean",
						reference: true,
					},
				])
			) {
				this.$store.commit("addDebugger", {
					title: "Ошибка",
					body: "Заполните важные поля",
					type: "error",
				});

				return;
			}

			this.disabled.login = true;

			await capi({
				method: "post",
				url: `login`,
				headers: {
					"Content-Type": "application/json",
				},
				data: {
					snils: this.currentLogin.data.snils.value,
					password: this.currentLogin.data.password.value,
				},
			})
				.then((response) => {
					if (!response) return;

					// Запись токена в глобальную переменную
					this.$store.commit("setValueToLocal", {
						name: "ctoken",
						value: response.data.result,
					});

					// Перевод на страницу админки
					this.$router.push({ name: "cabinet" });
				})
				.finally(() => {
					this.disabled.login = false;
				});
		},
	},
	mounted() {
		this.$store.commit("clearDebugger");

		if (localStorage.getItem("ctoken")) {
			this.$router.push({ name: "cabinet" });
		} else {
			this.loader.loading = false;
			this.loader.other = true;
		}
	},
};
</script>

<style scoped>
.container-login {
	display: grid;
	grid-template-columns: repeat(2, 1fr);

	height: 100dvh;
	width: 100dvw;

	background-color: var(--content-background-color);
}

.login,
.info {
	display: flex;
	flex-direction: column;
	gap: var(--default-gap);

	border-top: 0px;
	border-right: var(--default-border-width);
	border-bottom: 0px;
	border-left: 0px;
	border-style: var(--default-border-style);
	border-color: var(--default-border-color);

	padding: var(--default-padding);

	height: 100%;
	width: 100%;

	box-shadow: var(--default-box-shadow);
}

.login__info {
	display: flex;
	justify-content: space-between;
	align-items: center;

	width: 100%;
}

.login {
	display: flex;
	justify-content: space-between;
	align-items: center;

	background-color: white;
}

.login__form {
	display: flex;
	flex-direction: column;

	gap: var(--default-gap);

	width: min(100%, 500px);
}

.form__login-buttons {
	display: flex;
	justify-content: flex-end;
	align-items: center;
	gap: 10px;
}

.form__login-buttons-item {
	display: flex;
	justify-content: space-between;
	align-items: center;
	gap: 10px;
}

.info {
	display: flex;
	justify-content: center;
	align-items: center;
}

.info__logo {
	user-select: none;

	display: flex;
	justify-content: center;
	align-items: center;

	flex-direction: column;
}

.login__footer-text {
	user-select: none;

	text-align: center;
	color: var(--primary-color);
}

.aside__logo-icon img {
	width: 400px;
}

.aside__logo-text {
	font-size: 5rem;

	color: var(--primary-color);
}

@media screen and (width <= 900px) {
	.container-login {
		grid-template-columns: 1fr;
	}

	.info {
		display: none;
	}
}
</style>
