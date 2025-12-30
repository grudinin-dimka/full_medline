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
			<div class="profile__doctor" :class="{ skeleton: loading.loader.profile }">
				<div class="profile__img">
					<img
						class="profile__img-img"
						v-if="specialist.profile.path"
						:src="specialist.profile.path"
						alt="Фото специалиста"
					/>
				</div>

				<div class="profile__buttons">
					<VueButton :wide="true" @click="$store.commit('incrementCallCount')"> Записаться </VueButton>

					<a :href="specialist.profile.link" target="_blank" class="profile__buttons-prodoctorov">
						<VueButton :wide="true" :look="'inverse'">
							<span>
								<span class="red">ПРО</span>
								<span class="blue">ДОКТОРОВ</span>
							</span>
						</VueButton>
					</a>
				</div>
			</div>

			<div class="profile__info">
				<div class="profile__info-block" :class="{ skeleton: loading.loader.profile }">
					<div class="profile__info-head">
						<div class="profile__info-specs">
							<div class="profile__info-name">
								{{
									(specialist.profile.family ?? "") +
									" " +
									(specialist.profile.name ?? "") +
									" " +
									(specialist.profile.surname ?? "")
								}}
							</div>

							<div class="profile__info-specializations">
								{{ getSpecializations }}
							</div>
						</div>

						<div class="profile__info-other">
							<div class="profile__info__button" v-if="!loading.loader.profile">
								Прием

								<div class="profile__info__button-radial" v-if="specialist.profile.childrenDoctorAge">
									{{ specialist.profile.childrenDoctorAge }}+
								</div>

								<div class="profile__info__button-radial" v-if="specialist.profile.adultDoctor">18+</div>
							</div>

							<div class="profile__info__button" v-if="!loading.loader.profile">
								Стаж

								<div class="profile__info__button-radial">
									{{ getWorkAges(specialist.profile.startWorkAge) }}
								</div>
							</div>
						</div>
					</div>

					<div class="profile__info-body">
						<div class="profile__info-line">
							<span>Категория:</span>
							<span>{{ specialist.profile.category }}</span>
						</div>
					</div>
				</div>

				<div class="profile__info-block" :class="{ skeleton: loading.loader.profile }">
					<div class="profile__info-accordeon">
						<VueAccordeon>
							<template #name> Сертификаты </template>
							<template #body>
								<VueAccordeonBlock>
									<ul class="profile__info-list">
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
								</VueAccordeonBlock>
							</template>
						</VueAccordeon>

						<VueAccordeon>
							<template #name> Образование </template>
							<template #body>
								<VueAccordeonBlock>
									<ul class="profile__info-list">
										<li v-for="education in specialist.educations">
											<article>
												<div>
													{{ `${formatDate(education.date)} - ${education.name} (${education.organization}).` }}
												</div>
											</article>
										</li>
									</ul>
								</VueAccordeonBlock>
							</template>
						</VueAccordeon>
					</div>
				</div>

				<div class="profile__description" v-if="specialist.profile.description">
					<VueTiptap :editable="false" :limit="10_000" v-model="specialist.profile.description" />
				</div>
			</div>
		</div>
	</block>
</template>

<script>
import InfoBar from "../../../components/ui/main/InfoBar.vue";
import Block from "../../../components/ui/main/Block.vue";
import LoadText from "../../../components/ui/main/LoadText.vue";

import VueAccordeon from "../../../components/modules/accordeon/VueAccordeon.vue";
import VueAccordeonBlock from "../../../components/modules/accordeon/VueAccordeonBlock.vue";

import api from "../../../mixin/api";
import sorted from "../../../services/sorted";

import fakeDelay from "../../../mixin/fake-delay";

export default {
	components: {
		InfoBar,
		Block,
		LoadText,

		VueAccordeon,
		VueAccordeonBlock,
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
		fakeDelay(this.$store.getters.timeout, () =>
			api({
				method: "post",
				url: "get-specialist-profile",
				data: {
					url: this.$route.params.name,
				},
			})
		).then((response) => {
			if (!response) return;

			try {
				this.specialist.profile = response.data.result.profile;
				this.specialist.specializations = response.data.result.specializations;
				this.specialist.educations = response.data.result.educations;
				this.specialist.certificates = response.data.result.certificates;

				if (this.specialist.profile.family && this.specialist.profile.name) {
					let title = this.specialist.profile.family + " " + this.specialist.profile.name;

					if (this.specialist.profile.surname) {
						title += " " + this.specialist.profile.surname;
					}

					title += " | Специалисты";

					// Изменение заголовка
					this.$store.commit("setDocumentTitle", title);

					// Изменение ключевых слов
					this.$store.commit("setDocumentMeta", {
						name: "keywords",
						value:
							this.getSpecializations +
							", " +
							this.specialist.profile.family +
							" " +
							this.specialist.profile.name +
							" " +
							this.specialist.profile.surname +
							" " +
							"| Специалисты",
					});
				}

				this.loading.loader.profile = false;
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			}
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
	grid-template-columns: auto 1fr;
	gap: var(--default-gap);

	min-height: 300px;
	width: 1350px;
}

.profile__doctor {
	position: sticky;
	top: var(--default-margin);
	display: flex;
	flex-direction: column;
	gap: var(--default-gap);

	padding: var(--default-padding);
	border: var(--default-border);
	border-radius: var(--default-border-radius);

	height: fit-content;

	background-color: var(--skeleton-background-color);
}

.profile__img {
	align-self: self-start;
	justify-self: end;

	height: 250px;
	width: 250px;

	border-radius: 100%;
	border: var(--default-border);

	background-color: white;
}

.profile__img-img {
	width: 100%;
	border-radius: 100%;
	height: 100%;
	aspect-ratio: 1 / 1;
	object-fit: contain;

	animation: show 0.5s ease-out;
}

.profile__buttons {
	display: flex;
	flex-direction: column;
	gap: calc(var(--default-gap) / 2);
}

.profile__buttons-prodoctorov {
	text-decoration: none;
}

.red {
	color: #ec2227;
}

.blue {
	color: #117cc0;
}

.profile__info {
	display: flex;
	flex-direction: column;
	gap: var(--default-gap);

	font-size: 1.125rem;
}

.profile__info-block {
	display: flex;
	flex-direction: column;
	justify-content: flex-start;
	gap: var(--default-gap);

	padding: var(--default-padding);
	border: var(--default-border);
	border-radius: var(--default-border-radius);

	background-color: var(--skeleton-background-color);
}

.profile__info-head {
	display: flex;
	flex-wrap: wrap;
	justify-content: space-between;
	align-items: center;

	gap: var(--default-gap);
}

.profile__info-accordeon {
	display: flex;
	flex-direction: column;
	gap: calc(var(--default-gap) / 2);
}

.profile__info-specs {
	display: flex;
	flex-direction: column;
	gap: calc(var(--default-gap) / 2);
}

.profile__info-name {
	font-size: 1.5rem;

	border-radius: 15px;
	border: 0px;
	min-height: 30px;

	color: var(--primary-color);
}

.profile__info-specializations {
	font-size: 1rem;
}

.profile__info-other {
	display: flex;
	flex-wrap: wrap;
	justify-content: flex-end;
	gap: calc(var(--default-gap) / 2);
}

.profile__info__button {
	user-select: none;
	display: flex;
	align-items: center;
	gap: calc(var(--default-gap) / 3);

	text-align: center;
	border-radius: var(--button-border-radius);
	border: 0px;

	padding: 10px 20px;
	color: var(--primary-color);

	background-color: var(--transparent-color);
}

.profile__info__button-radial {
	display: flex;
	justify-content: center;
	align-items: center;

	padding: 10px;
	border-radius: var(--default-border-radius);

	background-color: white;
	height: 45px;
	min-width: 45px;
}

.profile__info-line {
	display: flex;
	flex-wrap: wrap;
	justify-content: space-between;
	align-items: center;
	gap: calc(var(--default-gap) / 2);

	background-color: white;

	min-height: 62px;
	padding: calc(var(--default-padding) / 2) var(--default-padding);
	border: var(--default-border);
	border-radius: var(--button-border-radius);

	width: 100%;
}

.profile__description {
	padding: var(--default-padding);
	border: var(--default-border);
	border-radius: var(--default-border-radius);
}

.profile__info-info {
	display: flex;
	flex-direction: column;
	gap: 10px;

	border-radius: 15px;
	border: 0px;
	padding: 10px 30px;
}

.profile__info-list {
	margin: 0px;
}

.profile__info-list > li {
	display: grid;
	grid-template-columns: 200px 1fr;

	border-radius: 15px;
	border: 0px;
	padding: 10px 0px;
	transition: all 0.2s;
}

.profile__info-list > li {
	grid-template-columns: 1fr;
}

@media screen and (width <= 1450px) {
	.profile {
		width: 100%;
	}
}

@media screen and (width <= 850px) {
	.profile {
		grid-template-columns: 1fr;
	}

	.profile__doctor {
		position: static;
	}

	.profile__img {
		margin: auto;

		height: 350px;
		width: 350px;
	}

	.profile__img-img {
		width: 100%;
		border-radius: 100%;
		height: 100%;
		aspect-ratio: 1 / 1;
		object-fit: contain;

		animation: show 0.5s ease-out;
	}
}

@media screen and (width <= 450px) {
	.profile__img {
		height: 250px;
		width: 250px;
	}
}
</style>
