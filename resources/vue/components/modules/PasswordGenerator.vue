<template>
	<div class="password-generator">
		<div class="password">{{ password }}</div>
		<div class="copy" @click="copyRandomPassword(password)">
			<svg
				xmlns="http://www.w3.org/2000/svg"
				height="24px"
				viewBox="0 -960 960 960"
				width="24px"
			>
				<path
					d="M360-240q-33 0-56.5-23.5T280-320v-480q0-33 23.5-56.5T360-880h360q33 0 56.5 23.5T800-800v480q0 33-23.5 56.5T720-240H360Zm0-80h360v-480H360v480ZM200-80q-33 0-56.5-23.5T120-160v-560h80v560h440v80H200Zm160-240v-480 480Z"
				/>
			</svg>
		</div>
		<div class="generator" @click="setRandomPassword(password)">
			<svg
				xmlns="http://www.w3.org/2000/svg"
				height="24px"
				viewBox="0 -960 960 960"
				width="24px"
			>
				<path
					d="M480-160q-134 0-227-93t-93-227q0-134 93-227t227-93q69 0 132 28.5T720-690v-110h80v280H520v-80h168q-32-56-87.5-88T480-720q-100 0-170 70t-70 170q0 100 70 170t170 70q77 0 139-44t87-116h84q-28 106-114 173t-196 67Z"
				/>
			</svg>
		</div>
	</div>
</template>

<script>
import shared from "../../services/shared";

export default {
	data() {
		return {
			password: shared.generateRandomString(16, true, true, true),
		};
	},
	methods: {
		/* Копирование пароля */
		copyRandomPassword(captcha) {
			if (navigator.clipboard.writeText(captcha)) {
				let debbugStory = {
					title: "Успешно!",
					body: "Пароль скопирован в буфер обмена.",
					type: "Completed",
				};
				this.$store.commit("debuggerState", debbugStory);
			} else {
				let debbugStory = {
					title: "Ошибка.",
					body: "Не удалось скопировать пароль...",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
			}
		},
		/* Установка нового пароля */
		setRandomPassword() {
			this.password = shared.generateRandomString(16, true, true, true);
		},
	},
};
</script>

<style scoped>
.password-generator {
	position: relative;
	display: flex;
	justify-content: center;
	align-items: center;

	border: var(--input-border);
	border-radius: var(--input-border-radius);

	font-size: 18px;
	height: 56px;

	background-color: #f5f5f5;
}

.password-generator > .copy {
	position: absolute;
	cursor: pointer;
	top: 16px;
	right: 5px;

	fill: rgb(150, 150, 150);
}

.password-generator > .generator {
	position: absolute;
	cursor: pointer;
	top: 16px;
	right: 34px;

	fill: rgb(150, 150, 150);
}
</style>
