<template>
	<info-bar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link to="/specialists">Специалисты</router-link>
	</info-bar>

	<loader-child :isLoading="isLoading" />

	<filters v-if="isDoctor"></filters>
	<block v-if="isDoctor">
		<doctors-list :doctors="doctors"/>
	</block>
</template>

<script>
import Block from "../../../components/ui/main/Block.vue";
import InfoBar from "../../../components/ui/main/InfoBar.vue";
import Filters from "../../../components/ui/main/Filters.vue";
import { RouterLink } from "vue-router";
import axios from "axios";
import LoaderChild from "../../../components/includes/LoaderChild.vue";
import DoctorsList from "./MainSpecialistsAllList.vue";

export default {
	components: {
		Block,
		InfoBar,
		Filters,
		RouterLink,
		axios,
		LoaderChild,
		DoctorsList,
	},
	data() {
		return {
			doctors: [],
			isLoading: true,
			isDoctor: false,
		};
	},
	methods: {
		openDoctorProfile(id) {
			window.scrollTo({
				top: 0,
				left: 0,
				behavior: "smooth",
			});
			this.$router.push("/specialists/" + id);
		},
	},
	mounted() {
		// Получение массива слайдов с сервера
		axios({
			method: "post",
			url: `${this.$store.state.axios.urlApi}` + `get-doctors`,
		})
			.then((response) => {
				this.isLoading = false;
				setTimeout(() => {
					this.isDoctor = true;					
				}, 500)
				this.doctors = response.data;
			})
			.catch((error) => {
				let debbugStory = {
					title: "Ошибка.",
					body: "Произошла ошибка при получении данных о слайдере.",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
			});
	},
};
</script>

<style scoped>
.list-enter-active,
.list-leave-active {
	transition: all 0.5s ease;
}
.list-enter-from,
.list-leave-to {
	opacity: 0;
	transform: translateX(30px);
}

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
	flex: 1 0 0px;
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

.doctor-body-advanced {
	display: flex;
	justify-content: space-between;
}

.doctor-body-link {
	margin-top: 10px;
	font-size: 16px;
	color: var(--primary-color);
	cursor: pointer;
	text-align: right;
}

@media screen and (max-width: 980px) {
	.doctor {
		width: 100%;
	}
}

@media screen and (max-width: 775px) {
	.doctor {
		flex-direction: column;
		justify-content: center;
		align-items: center;
	}
}
</style>
