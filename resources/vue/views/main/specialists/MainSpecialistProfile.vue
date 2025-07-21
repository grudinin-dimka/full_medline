<template>
	<info-bar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link to="/specialists">Специалисты</router-link>
		<span class="link-arrow"> / </span>
		<router-link :to="`/specialists/${$route.params.name}`">
			<span class="specialist__name" :class="{ skeleton: loading.loader.profile }">
				{{
					(specialist.profile.family ?? "") +
					" " +
					(specialist.profile.name ?? "") +
					" " +
					(specialist.profile.surname ?? "")
				}}
			</span>
		</router-link>
	</info-bar>

	<block :minHeight="700">
		<div class="profile">
			<div class="profile__img" :class="{ skeleton: loading.loader.profile }">
				<img
					class="profile__img-img"
					v-if="specialist.profile.path"
					:src="specialist.profile.path"
					alt="Фото специалиста"
				/>
			</div>

			<div class="profile__info">
				<div class="profile__info-title" :class="{ skeleton: loading.loader.profile }">
					{{
						(specialist.profile.family ?? "") +
						" " +
						(specialist.profile.name ?? "") +
						" " +
						(specialist.profile.surname ?? "")
					}}
				</div>

				<div class="profile__info-info info" :class="{ skeleton: loading.loader.profile }">
					<template v-if="!loading.loader.profile">
						<div class="label">Основная информация</div>
						<ul>
							<li>
								<article>
									<div>Специализация:</div>
									<div>{{ getSpecializations }}.</div>
								</article>
							</li>
							<li>
								<article>
									<div>Категория:</div>
									<div>{{ specialist.profile.category }}.</div>
								</article>
							</li>
							<li>
								<article>
									<div>Стаж:</div>
									<div>{{ getWorkAges(specialist.profile.startWorkAge) }}.</div>
								</article>
							</li>
						</ul>
					</template>
				</div>
				<div class="profile__info-info priem" :class="{ skeleton: loading.loader.profile }">
					<template v-if="!loading.loader.profile">
						<div class="label">Прием пациентов</div>
						<ul>
							<li>
								<article>
									<div>У детей:</div>
									<div v-if="specialist.profile.childrenDoctor">
										{{ specialist.profile.childrenDoctorAge }}+.
									</div>
									<div v-else>Нет.</div>
								</article>
							</li>
							<li>
								<article>
									<div>У взрослых:</div>
									<div>{{ specialist.profile.adultDoctor ? "Да" : "Нет" }}.</div>
								</article>
							</li>
						</ul>
					</template>
				</div>

				<div
					class="profile__info-info certificates"
					:class="{ skeleton: loading.loader.profile }"
					v-if="specialist.certificates.length"
				>
					<template v-if="specialist.certificates.length && !loading.loader.profile">
						<div class="label">Сертификаты</div>
						<ul>
							<li v-for="certificate in specialist.certificates">
								<article>
									<div>
										{{
											`${formatDate(certificate.endEducation)} - ${certificate.name} (${
												certificate.organization
											}) `
										}}.
									</div>
								</article>
							</li>
						</ul>
					</template>
				</div>

				<div
					class="profile__info-info educations"
					:class="{ skeleton: loading.loader.profile }"
					v-if="specialist.educations.length"
				>
					<template v-if="specialist.educations.length && !loading.loader.profile">
						<div class="label">Образование</div>
						<ul>
							<li v-for="education in specialist.educations">
								<article>
									<div>
										{{
											`${formatDate(education.date)} - ${education.name} (${
												education.organization
											}).`
										}}
									</div>
								</article>
							</li>
						</ul>
					</template>
				</div>

				<div class="profile__info-info links" :class="{ skeleton: loading.loader.profile }">
					<template v-if="specialist.profile.link !== '#' && specialist.profile.link">
						<div class="label">Ссылки</div>
						<ul>
							<li>
								<article>
									<div>
										<a class="prodoctorov" :href="specialist.profile.link">
											<span class="red">ПРО</span>
											<span class="blue">ДОКТОРОВ</span>
										</a>
									</div>
								</article>
							</li>
						</ul>
					</template>
				</div>

				<VueTiptap
					v-if="specialist.profile.description"
					:editable="false"
					:limit="10_000"
					v-model="specialist.profile.description"
				/>
			</div>
		</div>
	</block>
</template>

<script>
import VueTiptap from "../../../components/modules/VueTiptap.vue";
import VueLoader from "../../../components/modules/VueLoader.vue";

import InfoBar from "../../../components/ui/main/InfoBar.vue";
import Block from "../../../components/ui/main/Block.vue";
import LoadText from "../../../components/ui/main/LoadText.vue";

import api from "../../../mixin/api";
import sorted from "../../../services/sorted";

import TimeManager from "../../../mixin/time-manager";

export default {
	components: {
		VueTiptap,
		VueLoader,

		InfoBar,
		Block,
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
				profile: {
					path: null,
					family: null,
					name: null,
					surname: null,
					category: null,
					startWorkAge: null,
					childrenDoctor: null,
					childrenDoctorAge: null,
					adultDoctor: null,
					link: null,
					description: null,
				},

				specializations: [],
				certificates: [
					{
						id: null,
						name: null,
						organization: null,
						endEducation: null,
					},
				],
				educations: [
					{
						id: null,
						name: null,
						organization: null,
						date: null,
					},
				],
			},
		};
	},
	computed: {
		getSpecializations() {
			let result = "";

			sorted.sortStringByKey("up", this.specialist.specializations, "name");

			this.specialist.specializations.forEach((specialization) => {
				result += specialization.name + ", ";
			});

			return result.slice(0, -2);
		},
	},
	methods: {
		formatDate(date) {
			let options = {
				month: "numeric",
				day: "numeric",
				year: "numeric",
			};

			return new Date(date).toLocaleString("ru", options);
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
		const timeProfile = new TimeManager();
		timeProfile.start();

		api({
			method: "post",
			url: this.$store.getters.urlApi + "get-specialist-profile",
			data: {
				url: this.$route.params.name,
			},
		})
			.then((response) => {
				if (!response) return;

				timeProfile.end();

				timeProfile.difference(this.$store.getters.timeout, () => {
					this.specialist.profile = response.data.result.profile;
					this.specialist.specializations = response.data.result.specializations;
					this.specialist.educations = response.data.result.educations;
					this.specialist.certificates = response.data.result.certificates;

					this.loading.loader.profile = false;
				});
			})
			.catch((error) => {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			});
	},
};
</script>

<style scoped>
.specialist__name {
	display: inline-block;

	border-radius: var(--default-border-radius);
}

.profile {
	display: grid;
	grid-template-columns: 500px 1fr;
	gap: 20px;

	min-height: 300px;
	width: 1350px;
}

.profile__img {
	align-self: self-start;
	justify-self: end;

	width: 350px;
	height: 350px;
	border-radius: 100%;

	background-color: var(--skeleton-background-color);
}

.profile__img-img {
	width: 100%;
	border-radius: 100%;
	height: 100%;
	object-fit: contain;

	animation: show 0.5s ease-out;
}

.profile__info {
	display: flex;
	flex-direction: column;
	gap: 10px;

	font-size: 1.125rem;
}

.profile__info-title {
	font-size: 1.5rem;

	border-radius: 15px;
	border: 0px;
	padding: 10px 30px;

	min-height: 30px;
}

.profile__info-info {
	display: flex;
	flex-direction: column;
	gap: 10px;

	border-radius: 15px;
	border: 0px;
	padding: 10px 30px;
}

.profile__info-info.info {
	min-height: 160px;
}

.profile__info-info.priem {
	min-height: 110px;
}

.profile__info-info.certificates {
	min-height: 70px;
}

.profile__info-info.educations {
	min-height: 70px;
}

.profile__info-info.links {
	min-height: 70px;
}

.profile__info-info > .label {
	color: var(--primary-color);
	font-size: 1.25rem;
}

.profile__info-info > ul {
	margin: 0px;
}

.profile__info-info > ul > li > article {
	display: grid;
	grid-template-columns: 200px 1fr;

	border-radius: 15px;
	border: 0px;
	padding: 10px 0px;
	transition: all 0.2s;
}

.profile__info-info:is(.educations, .certificates) > ul > li > article {
	grid-template-columns: 1fr;
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

	border-radius: 50px;

	font-size: 18px;
	text-decoration: none;
	text-align: center;

	transition: all 0.2s;
}

a.prodoctorov:hover {
	text-decoration: underline;
	text-decoration-thickness: 2px;
	text-decoration-color: #117cc0;
}

:is(a.prodoctorov, li > article > div) .red {
	color: #ec2227;
}

:is(a.prodoctorov, li > article > div) .blue {
	color: #117cc0;
}

@media screen and (width <= 1250px) {
	.profile {
		grid-template-columns: 1fr 1fr;

		width: 100%;
	}

	.profile > img {
		align-self: center;
		justify-self: center;
	}
}

@media screen and (width <= 900px) {
	.profile {
		grid-template-columns: 1fr;
	}

	.profile__info-title {
		padding: 10px 0px;
	}

	.profile__info > .profile__info-info {
		padding: 0px 0px;
	}

	.profile__info > .profile__info-info > ul {
		padding: 0px 0px 0px 20px;
	}

	.profile__info > .profile__info-info > ul > li > article {
		grid-template-columns: 175px 1fr;
	}

	.profile > .profile__img {
		margin: 0px auto;
	}
}

@media screen and (width <= 420px) {
	.profile > .profile__img {
		width: 100%;
		height: 100%;
		aspect-ratio: 1 / 1;
	}
}
</style>
