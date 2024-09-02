<template>
	<info-bar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link to="/specialists">Специалисты</router-link>
	</info-bar>
	<block>
		<div class="doctor" v-for="doctor in doctors" :key="doctor.id">
			<img :src="doctor.img" />
			<div class="doctor-body">
				<div class="doctor-body-options">
					<div class="doctor-body-options-title">{{ doctor.name }}</div>
					<div class="doctor-body-options-other">
						<div class="doctor-body-options-other-block">
							<div>Специальность:</div>
							<div>{{ doctor.specialization }}</div>
						</div>
						<div class="doctor-body-options-other-block">
							<div>Стаж:</div>
							<div>{{ doctor.workage }}</div>
						</div>
						<div class="doctor-body-options-other-block">
							<div>Образование:</div>
							<div>
								{{ doctor.education }}
							</div>
						</div>
						<div class="doctor-body-options-other-block">
							<div>Сертификаты:</div>
							<div>
								{{ doctor.certificates }}
							</div>
						</div>
					</div>
				</div>
				<div class="doctor-body-price">
					Стоимость приёма: {{ doctor.price }} ₽
				</div>
			</div>
		</div>
	</block>
</template>

<script>
import Block from "../../components/ui/main/Block.vue";
import InfoBar from "../../components/ui/main/InfoBar.vue";
import { RouterLink } from "vue-router";

export default {
	components: {
		Block,
		InfoBar,
		RouterLink,
	},
	data() {
		return {
			doctors: [
				{
					id: 1,
					name: "Иванов Иван Иванович",
					specialization: "Хирург.",
					workage: "19 лет.",
					education:
						"Тюменский государственный медицинский университет, 2004 г.",
					certificates:
						"Тюменский государственный медицинский университет, 2004 г.",
					price: "1 400",
					img: "/storage/HKK2mLj6iM8WK2z9hkBMCI7YQ1IzBxDI8MYTp8dk.png",
				},
				{
					id: 2,
					name: "Ёжиков Ёжик Ёжикович",
					specialization: "Эндокринолог.",
					workage: "20 лет.",
					education:
						"Тюменский государственный медицинский университет, 2004 г.",
					certificates:
						"Тюменский государственный медицинский университет, 2004 г.",
					price: "1 400",
					img: "/storage/HKK2mLj6iM8WK2z9hkBMCI7YQ1IzBxDI8MYTp8dk.png",
				},
				{
					id: 3,
					name: "Васьков Василий Васильевич",
					specialization: "Эндокринолог.",
					workage: "20 лет.",
					education:
						"Тюменский государственный медицинский университет, 2004 г.",
					certificates:
						"Тюменский государственный медицинский университет, 2004 г.",
					price: "1 400",
					img: "/storage/HKK2mLj6iM8WK2z9hkBMCI7YQ1IzBxDI8MYTp8dk.png",
				},
			],
		};
	},
};
</script>

<style scoped>
.doctor {
	box-sizing: border-box;
	display: flex;
	justify-content: center;
	gap: 20px;

	border: 2px solid var(--input-border-color-inactive);
	border-radius: 20px;
	padding: 20px;

	width: 900px;

	transition: all 0.2s;
}

.doctor > img {
	width: 230px;
	border-radius: 15px;
	background-size: contain;
	background-position: center center;
	background-repeat: no-repeat;
	height: 100%;
}

.doctor-body {
	flex: 1 0 500px;
	display: flex;
	flex-direction: column;
	justify-content: space-between;
}

.doctor-body-options {
	display: flex;
	flex-direction: column;
	gap: 7px;
}

.doctor-body-options-title {
	font-size: 24px;
}

.doctor-body-options-other {
	display: flex;
	flex-direction: column;
	gap: 10px;
}

.doctor-body-options-other-block {
	display: grid;
	grid-template-columns: 150px 1fr;
}

.doctor-body-options-other-block > div {
	font-size: 16px;
}

.doctor-body-price {
	font-size: 20px;
	text-align: right;
}
</style>
