<template>
	<VueDebugger />
	<VueLoader :isLoading="loader.loading" />

	<div class="container-login">
		<div class="login">
			<div class="login__info">
				<div class="login__info-text">Личный кабинет</div>
				<div class="login__info-text">v0.0</div>
			</div>

			<form @submit.prevent class="login__form">
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

				<div class="form__login-buttons">
					<VueButton :disabled="disabled.login" @click="login">
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

export default {
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
				},
			},
		};
	},
	methods: {
		changeSection(name) {
			this.section = name;
		},

		login() {
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
				])
			)
				return;

			this.disabled.login = true;

			capi({
				method: "post",
				url: `${this.$store.getters.urlCabinet}` + `login`,
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
</style>
