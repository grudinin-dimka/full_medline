<template>
	<info-bar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link to="/specialists">Специалисты</router-link>
		<span class="link-arrow"> / </span>
		<router-link :to="`/specialists/${$route.params.name}`">
			<load-text :isLoading="isLoading"> Загрузка... </load-text>
			<span class="specialist-name" v-if="isSpecialist">{{ specialist.name }}</span>
		</router-link>
	</info-bar>

	<loader-child :isLoading="isLoading" />

	<block v-if="isSpecialist">
		<div class="container-specialist-profile">
			<img :src="specialist.path" />
			<div class="specialist-profile">
				<div class="specialist-profile-head">
					<div class="specialist-profile-head-title">
						<div>
							{{ specialist.family + " " + specialist.name + " " + specialist.surname }}
						</div>
					</div>
					<div class="specialist-profile-head-item">
						<div>Категория:</div>
						<div>{{ specialist.category }}</div>
					</div>
					<div class="specialist-profile-head-item">
						<div>Звание:</div>
						<div>{{ specialist.rank }}.</div>
					</div>
					<div class="specialist-profile-head-item">
						<div>Степень:</div>
						<div>{{ specialist.degree }}.</div>
					</div>
					<div class="specialist-profile-head-item">
						<div>Стаж:</div>
						<div>{{ getWorkAges(specialist.startWorkAge) }}</div>
					</div>
					<div class="specialist-profile-head-item">
						<div>Приём у детей:</div>
						<div>{{ specialist.childrenDoctorAge }}+.</div>
					</div>
					<div class="specialist-profile-head-item">
						<div>Приём у детей:</div>
						<div>Нет.</div>
					</div>
					<div class="specialist-profile-head-item">
						<div>Приём у взрослых:</div>
						<div>{{ specialist.childrenDoctor ? "Да." : "Нет." }}</div>
					</div>
					<div class="specialist-profile-head-item">
						<div>Врачебный стаж:</div>
						<div>{{ specialist.startWorkAge }}</div>
					</div>
				</div>
			</div>
			<!-- User {{ $route.params.id }} -->
		</div>
	</block>
</template>

<script>
import InfoBar from "../../../components/ui/main/InfoBar.vue";
import Block from "../../../components/ui/main/Block.vue";
import axios from "axios";
import LoaderChild from "../../../components/includes/LoaderChild.vue";
import LoadText from "../../../components/ui/main/LoadText.vue";

export default {
	components: {
		InfoBar,
		Block,
		axios,
		LoaderChild,
		LoadText,
	},
	data() {
		return {
			isLoading: true,
			isSpecialist: false,
			specialist: {},
		};
	},
	methods: {
		getWorkAges(date) {
			let startDate = new Date(date);
			let currentDate = new Date();
			let result = currentDate.getFullYear() - startDate.getFullYear();
			if (result == 0) return "Менее года.";
			switch (result) {
				case 1:
					return result + " " + "год.";
				case 2:
				case 3:
				case 4:
					return result + " " + "года.";
				default:
					return result + " " + "лет.";
			}
		},
	},
	mounted() {
		// Получение массива слайдов с сервера
		axios({
			method: "post",
			url: `${this.$store.state.axios.urlApi}` + `get-specialist-profile`,
			data: {
				url: this.$route.params.name,
			},
		})
			.then((response) => {
				this.specialist = response.data;

				this.isLoading = false;
				setTimeout(() => {
					this.isSpecialist = true;
				}, 500);
			})
			.catch((error) => {
				let debbugStory = {
					title: "Ошибка.",
					body: "Произошла ошибка при получении данных о враче.",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
			});
	},
};
</script>

<style scoped>
.specialist-name {
	animation: show 0.5s linear;
}

.container-specialist-profile {
	margin: 0px 30px;
	display: flex;
	gap: 20px;
	max-width: 1000px;

	animation: transform 0.5s ease-out;
}

.container-specialist-profile > img {
	width: 350px;
	border-radius: 15px;
	background-size: contain;
	background-position: center center;
	background-repeat: no-repeat;
	height: 100%;
}

.specialist-profile {
	display: flex;
	flex-direction: column;
	gap: 20px;
}

.specialist-profile-head {
	display: flex;
	flex-direction: column;
	gap: 10px;
}

.specialist-profile-head-title {
	font-size: 24px;
}

.specialist-profile-head-item {
	display: grid;
	font-size: 16px;
	grid-template-columns: 250px 1fr;
}

.specialist-profile-body {
	display: flex;
	flex-direction: column;
	gap: 10px;
}

.specialist-profile-body-title {
	font-size: 20px;
}

.specialist-profile-body > ul {
	margin: 0px;
	padding-left: 20px;
}

.specialist-profile-body > ul > li:not(:last-child) {
	margin-bottom: 10px;
}

@keyframes show {
	0% {
		opacity: 0;
	}
	100% {
		opacity: 1;
	}
}

@keyframes transform {
	0% {
		opacity: 0;
		transform: translateY(30px);
	}
	100% {
		opacity: 1;
		transform: translateY(0);
	}
}

@media screen and (max-width: 990px) {
	.container-specialist-profile {
		justify-content: center;
		align-items: center;
		flex-direction: column;
	}
}

@media screen and (max-width: 460px) {
	.container-specialist-profile {
		margin: 0px 0px;
		display: flex;
		gap: 20px;
		max-width: 1000px;

		animation: transform 0.5s ease-out;
	}

	.container-specialist-profile > img {
		width: 100%;
		max-width: 300px;
	}

	.specialist-profile-head-item {
		font-size: 16px;
		grid-template-columns: 1fr 1fr;
	}
}
</style>
