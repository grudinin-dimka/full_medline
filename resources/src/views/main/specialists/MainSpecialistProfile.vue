<template>
	<info-bar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link to="/specialists">Специалисты</router-link>
		<span class="link-arrow"> / </span>
		<router-link :to="`/specialists/${$route.params.name}`">
			<load-text :isLoading="isLoading">
				Загрузка...
			</load-text>
			<span class="doctor-name" v-if="isDoctor">{{ doctor.name }}</span>
		</router-link>
	</info-bar>

	<loader-child :isLoading="isLoading" />

	<block v-if="isDoctor">
		<div class="container-doctor-profile">
			<img :src="`/storage/HKK2mLj6iM8WK2z9hkBMCI7YQ1IzBxDI8MYTp8dk.png`" />
			<div class="doctor-profile">
				<div class="doctor-profile-head">
					<div class="doctor-profile-head-title">
						<span>{{ doctor.name }}</span>
					</div>
					<div class="doctor-profile-head-item">
						<span>Образование:</span>
						<span>{{ doctor.education }}</span>
					</div>
					<div class="doctor-profile-head-item">
						<span>Специальность:</span>
						<span>{{ doctor.specialization }}</span>
					</div>
					<div class="doctor-profile-head-item">
						<span>Сертификаты:</span>
						<span>{{ doctor.certificates }}</span>
					</div>
					<div class="doctor-profile-head-item">
						<span>Врачебный стаж:</span>
						<span>{{ doctor.startWorkAge }}</span>
					</div>
				</div>
				<div class="doctor-profile-body">
					<div class="doctor-profile-body-title">
						<span>Ключевые компетенции:</span>
					</div>
					<ul>
						<li>
							Дегенеративно-дистрофические поражения позвоночника
							(остеохондроз, сколиотические деформации позвоночника,
							стеноз позвоночного канала, спондилолистез, спондилоартроз,
							грыжи дисков, протрузии дисков, дискогенные болевые
							синдромы).
						</li>
						<li>
							Травмы позвоночника и спинного мозга и их последствия.
						</li>
						<li>
							Новообразования позвоночника, оболочек и корешков спинного
							мозга.
						</li>
						<li>
							Патология периферической нервной системы (радикулопатии,
							нейропатии, травмы нервов конечностей).
						</li>
						<li>Нейропатический болевой синдром различной этиологии.</li>
						<li>
							Последствия оперативных вмешательств на позвоночнике и
							спинном мозге (болезнь оперированного позвоночника).
						</li>
					</ul>
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
			isDoctor: false,
			doctor: {},
		};
	},
	mounted() {
		// Получение массива слайдов с сервера
		axios({
			method: "post",
			url: `${this.$store.state.axios.urlApi}` + `get-doctor-profile`,
			data: {
				url: this.$route.params.name,
			},
		})
			.then((response) => {
				this.doctor = response.data;

				this.isLoading = false;
				setTimeout(() => {
					this.isDoctor = true;
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
.doctor-name {
	animation: show 0.5s linear;
}

.container-doctor-profile {
	margin: 0px 30px;
	display: flex;
	gap: 20px;
	max-width: 900px;

	animation: transform 0.5s ease-out;
}

.container-doctor-profile > img {
	width: 350px;
	border-radius: 15px;
	background-size: contain;
	background-position: center center;
	background-repeat: no-repeat;
	height: 100%;
}

.doctor-profile {
	display: flex;
	flex-direction: column;
	gap: 20px;
}

.doctor-profile-head {
	display: flex;
	flex-direction: column;
	gap: 10px;
}

.doctor-profile-head-title {
	font-size: 24px;
}

.doctor-profile-head-item {
	display: grid;
	font-size: 16px;
	grid-template-columns: 200px 1fr;
}

.doctor-profile-body {
	display: flex;
	flex-direction: column;
	gap: 10px;
}

.doctor-profile-body-title {
	font-size: 20px;
}

.doctor-profile-body > ul {
	margin: 0px;
	padding-left: 20px;
}

.doctor-profile-body > ul > li:not(:last-child) {
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

@media screen and (max-width: 780px) {
	.container-doctor-profile {
		justify-content: center;
		align-items: center;
		flex-direction: column;
	}
}
</style>
