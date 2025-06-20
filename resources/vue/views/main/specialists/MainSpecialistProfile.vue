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

	<block :minHeight="500">
		<div class="container-specialist-profile" v-if="loading.sections.profile">
			<div class="img" :style="{ backgroundImage: `url(${specialist.profile.path})` }"></div>
			<div class="specialist-profile">
				<div class="title">
					{{
						specialist.profile.family +
						" " +
						specialist.profile.name +
						" " +
						specialist.profile.surname
					}}
				</div>
				<div class="item info">
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
				</div>
				<div class="item priem">
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
				</div>
				<div class="item certificates" v-if="specialist.certificates.length">
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
				</div>
				<div class="item educations" v-if="specialist.educations.length">
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
				</div>
				<div class="item links" v-if="specialist.profile.link !== '#'">
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
				</div>

				<VueTiptap :editable="false" :limit="10_000" v-model="specialist.profile.description" />
			</div>
		</div>

		<loader-child
			:isLoading="loading.loader.profile"
			@loaderChildAfterLeave="loaderChildAfterLeave"
		/>
	</block>
</template>

<script>
import InfoBar from "../../../components/ui/main/InfoBar.vue";
import Block from "../../../components/ui/main/Block.vue";
import VueTiptap from "../../../components/modules/VueTiptap.vue";

import LoaderChild from "../../../components/modules/LoaderChild.vue";
import LoadText from "../../../components/ui/main/LoadText.vue";

import Icon from "../../../components/modules/icon/Icon.vue";

import api from "../../../services/api";
import sorted from "../../../services/sorted";

export default {
	components: {
		InfoBar,
		Block,
		VueTiptap,

		LoaderChild,
		LoadText,

		Icon,
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
				certificates: [],
				educations: [],
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
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                   ЗАГРУЗЧИК                       |*/
		/* |___________________________________________________|*/
		/* После скрытия элементы */
		loaderChildAfterLeave() {
			this.loading.sections.profile = true;
		},

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
		// Получение массива слайдов с сервера
		api({
			method: "post",
			url: this.$store.getters.urlApi + "get-specialist-profile",
			data: {
				url: this.$route.params.name,
			},
		})
			.then((response) => {
				if (!response) return;

				this.specialist.profile = response.data.result.profile;
				this.specialist.specializations = response.data.result.specializations;
				this.specialist.educations = response.data.result.educations;
				this.specialist.certificates = response.data.result.certificates;
			})
			.catch((error) => {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
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
	width: 1350px;

	animation: show-bottom-to-top-15 0.5s ease-in-out;
}

.container-specialist-profile > .img {
	align-self: self-start;
	justify-self: end;

	width: 350px;
	height: 350px;
	border-radius: 100%;
	border: var(--input-border);
	background-size: contain;
	background-position: center center;
	background-repeat: no-repeat;
}

.specialist-profile {
	display: flex;
	flex-direction: column;
	gap: 20px;

	font-size: 1.125rem;
}

.specialist-profile > .title {
	font-size: 1.5rem;
}

.specialist-profile > .item {
	display: flex;
	flex-direction: column;
	gap: 10px;

	border-radius: 15px;
	border: 0px;
	padding: 0px 0px 0px 30px;
}

.specialist-profile > .item > .label {
	color: var(--primary-color);
	font-size: 1.25rem;
}

.specialist-profile > .item > ul {
	margin: 0px;
}

.specialist-profile > .item > ul > li > article {
	display: grid;
	grid-template-columns: 200px 1fr;

	border-radius: 15px;
	border: 0px;
	padding: 10px 0px;
	transition: all 0.2s;
}

.specialist-profile > .item:is(.educations, .certificates) > ul > li > article {
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

	.specialist-profile > .item {
		padding: 0px 0px;
	}

	.specialist-profile > .item > ul {
		padding: 0px 0px 0px 20px;
	}

	.specialist-profile > .item > ul > li > article {
		grid-template-columns: 175px 1fr;
	}

	.container-specialist-profile > .img {
		margin: 0px auto;
	}
}

@media screen and (width <= 420px) {
	.container-specialist-profile > .img {
		width: 100%;
		height: 100%;
		aspect-ratio: 1 / 1;
	}
}
</style>
