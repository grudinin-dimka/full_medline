<template>
	<info-bar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link to="/specialists">Специалисты</router-link>
		<span class="link-arrow"> / </span>
		<router-link :to="`/specialists/${$route.params.name}`">
			<load-text :isLoading="isLoading"> Загрузка... </load-text>
			<span class="specialist-name" v-if="isSpecialist">{{
				specialist.name
			}}</span>
		</router-link>
	</info-bar>

	<loader-child :isLoading="isLoading" />

	<block v-if="isSpecialist">
		<div class="container-specialist-profile">
			<img :src="specialist.path" />
			<div class="specialist-profile">
				<div class="specialist-profile-head">
					<div class="specialist-profile-head-title">
						<span>{{ specialist.name }}</span>
					</div>
					<div class="specialist-profile-head-item">
						<span>Специальность:</span>
						<span>{{ specialist.specialization }}</span>
					</div>
					<div class="specialist-profile-head-item">
						<span>Образование:</span>
						<span>{{ specialist.education }}</span>
					</div>
					<div class="specialist-profile-head-item">
						<span>Повышение квалификации:</span>
						<span>{{ specialist.advancedTraining }}</span>
					</div>
					<div class="specialist-profile-head-item">
						<span>Сертификаты:</span>
						<span>{{ specialist.certificates }}</span>
					</div>
					<div class="specialist-profile-head-item">
						<span>Врачебный стаж:</span>
						<span>{{ specialist.startWorkAge }}</span>
					</div>
				</div>
				<div class="specialist-profile-body">
					<div class="specialist-profile-body-title">
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
			isSpecialist: false,
			specialist: {},
		};
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
