<template>
	<info-bar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link to="/specialists">Специалисты</router-link>
		<span class="link-arrow"> / </span>
		<router-link :to="`/specialists/${$route.params.name}`">
			<load-text :isLoading="loading.loader.profile"> Загрузка... </load-text>
			<span class="specialist-name" v-if="loading.sections.profile">{{
				specialist.profile.family +
				" " +
				specialist.profile.name +
				" " +
				specialist.profile.surname
			}}</span>
		</router-link>
	</info-bar>

	<block>
		<div class="container-specialist-profile" v-if="loading.sections.profile">
			<img :src="specialist.profile.path" />
			<div class="specialist-profile">
				<table class="specialist-profile-head">
					<thead>
						<th colspan="2">
							{{
								specialist.profile.family +
								" " +
								specialist.profile.name +
								" " +
								specialist.profile.surname
							}}
						</th>
					</thead>
					<tbody>
						<tr v-if="specialist.specializations.length">
							<td width="250px">Специализации:</td>
							<td>{{ getSpecializations }}</td>
						</tr>
						<tr>
							<td width="250px">Категория:</td>
							<td>{{ specialist.profile.category }}.</td>
						</tr>
						<tr>
							<td>Стаж:</td>
							<td>{{ getWorkAges(specialist.profile.startWorkAge) }}.</td>
						</tr>
						<tr>
							<td>Приём:</td>
							<td></td>
						</tr>
						<tr>
							<td>
								<ul>
									<li>У детей:</li>
								</ul>
							</td>
							<td v-if="specialist.profile.childrenDoctor">
								{{ specialist.profile.childrenDoctorAge }}+.
							</td>
							<td v-else>Нет.</td>
						</tr>
						<tr>
							<td>
								<ul>
									<li>У взрослых:</li>
								</ul>
							</td>
							<td>{{ specialist.profile.adultDoctor ? "Да." : "Нет." }}</td>
						</tr>
						<tr v-if="specialist.educations.length">
							<td>Образования:</td>
							<td></td>
						</tr>
						<tr
							v-if="specialist.educations.length"
							v-for="education in specialist.educations"
						>
							<td colspan="2">
								<ul>
									<li>{{ education.organization }}.</li>
								</ul>
							</td>
						</tr>
						<tr>
							<td colspan="2" height="50px">
								<a class="prodoctorov" :href="specialist.profile.link">
									<span class="red">ПРО</span>
									<span class="blue">ДОКТОРОВ</span>
								</a>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</block>

	<loader-child
		:isLoading="loading.loader.profile"
		:minHeight="400"
		@loaderChildAfterLeave="loaderChildAfterLeave"
	/>
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
			loading: {
				loader: {
					profile: true,
				},
				sections: {
					profile: false,
				},
			},
			specialist: {
				profile: {},
				specializations: [],
				education: [],
			},
		};
	},
	computed: {
		getSpecializations() {
			let result = "";

			this.specialist.specializations.forEach((specialization) => {
				result += specialization.name + ", ";
			});

			return result.slice(0, -2) + ".";
		},
	},
	methods: {
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                   ЗАГРУЗЧИК                       |*/
		/* |___________________________________________________|*/
		/* После скрытия элементы */
		loaderChildAfterLeave() {
			this.loading.sections.profile = true;
		},
		getWorkAges(date) {
			let startDate = new Date(date);
			let currentDate = new Date();
			let result = currentDate.getFullYear() - startDate.getFullYear();
			if (result == 0) return "Менее года";
			switch (result) {
				case 1:
					return result + " " + "год";
				case 2:
				case 3:
				case 4:
					return result + " " + "года";
				default:
					return result + " " + "лет";
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
				if (response.data.status) {
					this.specialist.profile = response.data.data.profile;
					this.specialist.specializations = response.data.data.specializations;
					this.specialist.educations = response.data.data.educations;
				} else {
					this.specialist.profile = null;

					let debbugStory = {
						title: "Ошибка.",
						body: response.data.message,
						type: "Error",
					};
					this.$store.commit("debuggerState", debbugStory);
				}
			})
			.catch((error) => {
				let debbugStory = {
					title: "Ошибка.",
					body: "Произошла ошибка при получении данных о враче.",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
			})
			.finally(() => {
				if (this.specialist.profile != null) {
					this.loading.loader.profile = false;
				}
			});
	},
};
</script>

<style scoped>
.specialist-name {
	animation: show 0.5s linear;
}

.container-specialist-profile {
	display: grid;
	grid-template-columns: 350px 1fr;
	gap: 20px;

	margin: 0px 30px;

	min-height: 300px;
	width: 1000px;

	animation: show 0.5s ease-out;
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

.specialist-profile-head th {
	font-size: 24px;
	text-align: left;
	font-weight: normal;
}

.specialist-profile-head td {
	font-size: 18px;
	padding: 5px 0px;
}

.specialist-profile-head td ul {
	margin: 0px;
}

a.prodoctorov {
	box-sizing: border-box;

	border: 2px solid var(--input-border-color-inactive);
	border-radius: 50px;
	padding: 10px;
	width: 200px;

	font-size: 18px;
	text-decoration: none;
	text-align: center;

	transition: all 0.2s;
}

a.prodoctorov:hover {
	border: 2px solid #ec9d9e;
	background-color: #fff0f0;
}

a.prodoctorov .red {
	color: #ec2227;
}

a.prodoctorov .blue {
	color: #117cc0;
}

@media screen and (width <= 1000px) {
	.container-specialist-profile {
		display: flex;
		flex-wrap: wrap;
		justify-content: center;

		width: calc(100% - 60px);
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

	.specialist-profile-head > .item {
		font-size: 16px;
		grid-template-columns: 1fr 1fr;
	}
}
</style>
