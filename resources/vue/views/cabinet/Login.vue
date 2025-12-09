<template>
	<div class="container-login">
		<div class="login">
			<div class="login__info">
				<div class="login__info-text">Личный кабинет</div>
				<div class="login__info-text">v0.0</div>
			</div>

			<form @click.prevent class="login__form">
				<template v-if="section === 'login'">
					<VueMask
						v-model.trim="currentLogin.data.snils.body"
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
						v-model.trim="currentLogin.data.password.body"
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

					<div class="form__login-buttons">
						<div class="form__login-buttons-item">
							<VueButton :look="'inverse'" @click="changeSection('register')"> Регистрация </VueButton>
							<VueButton :look="'inverse'" :figure="'circle'" @click="changeSection('recover')">
								<VueIcon :name="'Key'" :fill="'var(--primary-color)'" :width="'20px'" :height="'20px'" />
							</VueButton>
						</div>

						<div class="form__login-buttons-item">
							<VueButton>
								<VueIcon :name="'Login'" :fill="'white'" :width="'28px'" :height="'28px'" />
								Войти
							</VueButton>
						</div>
					</div>
				</template>

				<template v-if="section === 'register'">
					<VueMask
						v-model.trim="currentLogin.data.snils.body"
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

					<VueValues
						v-model.trim="currentLogin.data.surname.body"
						:placeholder="'Введите фамилию'"
						:error="currentLogin.errors.surname.status"
					>
						<template #label>
							<VueIcon :name="'Id Card'" :fill="'var(--primary-color)'" :width="'20px'" :height="'20px'" />
							ФАМИЛИЯ
						</template>
						<template #error>
							{{ currentLogin.errors.surname.message }}
						</template>
					</VueValues>

					<VuePassword
						v-model.trim="currentLogin.data.password.body"
						:placeholder="'Введите фамилию'"
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

					<VuePassword
						v-model.trim="currentLogin.data.password.body"
						:placeholder="'Введите фамилию'"
						:error="currentLogin.errors.surname.status"
					>
						<template #label>
							<VueIcon :name="'Key'" :fill="'var(--primary-color)'" :width="'20px'" :height="'20px'" />
							ПОДТВЕРЖДЕНИЕ ПАРОЛЯ
						</template>
						<template #error>
							{{ currentLogin.errors.surname.message }}
						</template>
					</VuePassword>

					<div class="form__login-buttons">
						<VueButton :look="'inverse'" @click="changeSection('login')"> Вернуться </VueButton>
						<VueButton> Подтвердить </VueButton>
					</div>
				</template>

				<template v-if="section === 'recover'">
					<VueMask
						v-model.trim="currentLogin.data.snils.body"
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

					<div class="form__login-buttons">
						<VueButton :look="'inverse'" @click="changeSection('login')"> Вернуться </VueButton>
						<VueButton> Восстановить </VueButton>
					</div>
				</template>
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
import axios from "axios";
import validate from "../../services/validate";

export default {
	data() {
		return {
			loader: {
				loading: true,
				other: false,
			},

			disabled: {
				login: {
					update: false,
				},
			},

			currentLogin: {
				errors: {
					password: {
						body: "",
						status: false,
					},
					snils: {
						body: "",
						status: false,
					},
					surname: {
						body: "",
						status: false,
					},
				},
				data: {
					password: {
						body: "",
						edited: false,
					},
					snils: {
						body: "",
						edited: false,
					},
					surname: {
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

			section: "login",
		};
	},
	methods: {
		changeSection(name) {
			this.section = name;
		},
	},
	mounted() {},
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

	box-shadow: var(--default-shadow);
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
	justify-content: space-between;
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
	display: flex;
	justify-content: center;
	align-items: center;

	flex-direction: column;
}

.login__footer-text {
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
</style>
