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

	<block :minHeight="400">
		<div class="container-specialist-profile" v-if="loading.sections.profile">
			<img :src="specialist.profile.path" />
			<div class="specialist-profile">
				<table class="specialist-profile-head">
					<thead>
						<tr>
							<th colspan="2">
								{{
									specialist.profile.family +
									" " +
									specialist.profile.name +
									" " +
									specialist.profile.surname
								}}
							</th>
						</tr>
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
									<li>
										<IconSpecialistChild :width="18" :height="18" />
										У детей:
									</li>
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
									<li>
										<IconSpecialistAdult :width="18" :height="18" />
										У взрослых:
									</li>
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
						<tr v-if="specialist.profile.link !== '#'">
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

		<loader-child
			:isLoading="loading.loader.profile"
			:minHeight="400"
			@loaderChildAfterLeave="loaderChildAfterLeave"
		/>
	</block>
</template>

<script>
import InfoBar from "../../../components/ui/main/InfoBar.vue";
import Block from "../../../components/ui/main/blocks/Block.vue";

import LoaderChild from "../../../components/includes/LoaderChild.vue";
import LoadText from "../../../components/ui/main/LoadText.vue";

import IconSpecialistChild from "../../../components/icons/specialists/IconSpecialistChild.vue";
import IconSpecialistAdult from "../../../components/icons/specialists/IconSpecialistAdult.vue";

import axios from "axios";

export default {
	components: {
		InfoBar,
		Block,
		LoaderChild,
		LoadText,
		IconSpecialistChild,
		IconSpecialistAdult,
		axios,
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
	grid-template-columns: 500px 1fr;
	gap: 20px;

	min-height: 300px;
	width: 1250px;

	animation: show-bottom-to-top-15 0.5s ease-in-out;
}

.container-specialist-profile > img {
	align-self: center;
	justify-self: end;

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
	list-style: none;
	padding-left: 15px;
}

.specialist-profile-head td ul li {
	display: flex;
	gap: 5px;
	align-items: center;
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

@media screen and (width <= 1250px) {
	.container-specialist-profile {
		grid-template-columns: 1fr 1fr;

		width: 100%;
	}

	.container-specialist-profile > img {
		align-self: center;
		justify-self: center;
	}
}

@media screen and (width <= 900px) {
	.container-specialist-profile {
		grid-template-columns: 1fr;
	}
}

@media screen and (width <= 420px) {
	.container-specialist-profile > img {
		width: 100%;
	}
}
</style>
