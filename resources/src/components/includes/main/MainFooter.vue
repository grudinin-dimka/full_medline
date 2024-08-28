<template>
	<footer>
		<!-- Верхняя секция -->
		<section class="up">
			<!-- Блок с платёжными системами -->
			<div class="pay">
				<a href="/">
					<img
						src="../../../assets/svg/logo.svg"
						width="250"
						height="150"
					/>
				</a>
				<p>Мы принимаем к оплате</p>
				<div class="pay-cards">
					<!-- Карта visa -->
					<div class="card">
						<icon-visa :width="60" :height="60" />
					</div>
					<!-- Карта mastercard -->
					<div class="card">
						<icon-master-card :width="50" :height="40" />
					</div>
					<!-- Карта mir -->
					<div class="card">
						<icon-mir :width="60" :height="40" />
					</div>
				</div>
			</div>
			<!-- Блок с кнопками -->
			<div class="buttons">
				<div>Обратная связь</div>
				<div>Контакты</div>
				<div>Вакансии</div>
			</div>
			<!-- Блок доп. информации -->
			<div class="more">
				<div
					class="button"
					@click="this.$store.commit('changeModal', 'Записаться на прием')"
				>
					Записаться на прием
				</div>
				<p>Присоединяйтесь к нам</p>
				<div class="links">
					<a
						href="https://ok.ru/profile/586067527945"
						style="cursor: pointer"
					>
						<img src="../../../assets/svg/odnoklasniki.svg" width="100" />
					</a>
					<a href="https://vk.com/med_line45" style="cursor: pointer">
						<img src="../../../assets/svg/vk.svg" width="100" />
					</a>
					<a href="https://t.me/medlinegroup_ru" style="cursor: pointer">
						<img src="../../../assets/svg/telegram.svg" width="100" />
					</a>
				</div>
			</div>
		</section>

		<!-- Нижняя секция -->
		<section class="bottom">
			<p>{{ footer.title }}</p>
			<p>{{ footer.titleDesc }}</p>
			<p>{{ footer.license }}</p>
			<p>{{ footer.licenseDesc }}</p>
			<p>{{ footer.footer }}</p>
		</section>
		<article>
			Имеются противопоказания Необходима консультация специалиста
		</article>
	</footer>
</template>

<script>
import axios from "axios";

import IconVisa from "../../icons/IconVisa.vue";
import IconMasterCard from "../../icons/IconMasterCard.vue";
import IconMir from "../../icons/IconMir.vue";

export default {
	mounted() {
		axios({
			method: "post",
			url: `${this.$store.state.axios.urlApi}` + `get-footer`,
		})
			.then((response) => {
				for (let key in response.data) {
					this.footer[key] = response.data[key];
				}
			})
			.catch((error) => {
				let debbugStory = {
					title: "Ошибка.",
					body: "Произошла ошибка при получении данных о футере.",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
			});
	},
	data() {
		return {
			nameTitle: "",
			footer: {
				title: "",
				titleDesc: "",
				license: "",
				licenseDesc: "",
				footer: "",
			},
		};
	},
	components: {
		axios,
		IconVisa,
		IconMasterCard,
		IconMir,
	},
};
</script>

<style scoped>
footer {
	width: 100%;
	display: flex;
	flex-direction: column;
}

section.up {
	display: flex;
	flex-direction: row;
	justify-content: space-around;
	flex-wrap: wrap;
	gap: 30px;

	text-transform: uppercase;

	margin: 30px;
}

section.up .pay {
	display: flex;
	flex-direction: column;
	text-transform: uppercase;

	margin: 30px;
}

section.up .pay p {
	margin: 10px;
	font-weight: bold;
	text-align: center;
	font-size: 12px;
}

.pay-cards {
	display: flex;
	gap: 10px;
}

.card {
	display: flex;
	justify-content: center;
	align-items: center;

	border-radius: 10px;

	width: 80px;
	height: 50px;
}

.card:nth-child(1) {
	background-color: #cbe3f3;
}

.card:nth-child(2) {
	background-color: #f5dccd;
}

.card:nth-child(3) {
	background-color: #c5f5e3;
}

section.up .buttons {
	display: flex;
	flex-direction: column;
	justify-content: center;
	gap: 20px;
}

section.up .buttons div {
	align-self: center;
	cursor: pointer;
	text-align: center;

	border: none;
	border-radius: 5px;
	margin: 5px;

	text-transform: uppercase;
	font-size: 20px;

	color: white;
	background-color: var(--button-default-color);
	padding: 15px 30px;
	min-width: 200px;

	transition: background-color 0.15s;
}

section.up .buttons div:hover {
	background-color: var(--button-default-color-hover);
}

section.up .more {
	display: flex;
	flex-direction: column;
	justify-content: center;
}

section.up .more .button {
	align-self: center;
	cursor: pointer;
	text-align: center;

	border: none;
	border-radius: 5px;
	margin: 5px;

	text-transform: uppercase;
	font-size: 34px;

	color: white;
	background-color: var(--button-default-color);
	padding: 15px 30px;
	min-width: 200px;

	transition: background-color 0.15s;
}

section.up .more .button:hover {
	background-color: var(--button-default-color-hover);
}

section.up .more p {
	margin: 10px;
	text-align: center;
	font-weight: bold;
	font-size: 22px;
}

section.up .more .links {
	text-align: center;
	font-weight: bold;
}

section.bottom {
	padding: 30px;
	margin: 30px;
	border-radius: 10px;

	font-size: 20px;

	background-color: rgb(240, 240, 240);
}

footer article {
	text-align: center;

	border-radius: 10px;
	margin-bottom: 10px;

	text-transform: uppercase;
	font-size: 2.4vw;

	color: rgb(158, 130, 130);
}

@media screen and (max-width: 500px) {
	footer a[href="/"] {
		display: none;
	}
}
</style>
