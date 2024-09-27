<template>
	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|            МОДАЛЬНОЕ ОКНО (СЕРТИФИКАТЫ)           |-->
	<!--|___________________________________________________|-->
	<admin-modal ref="modal" @touchCloseModal="closeModal" :modal="modal">
		<template #title>
			<span class="create" v-if="modal.type == 'create'"> СПЕЦИАЛИЗАЦИЯ (СОЗДАНИЕ) </span>
			<span v-if="modal.type == 'edit'">СЕРТИФИКАТЫ</span>
		</template>
		<template #body>
			<div class="certificates-list">
				<div class="item">
					<div>Название</div>
					<div>Организация</div>
				</div>
			</div>
			<div class="certificates-list">
				<div class="item" v-for="certificate in sections.certificates" :key="certificate.id">
					<input type="checkbox" :id="certificate.id" :value="certificate.id" />
					<div>{{ certificate.name }}</div>
					<div>{{ certificate.organization }}</div>
				</div>
			</div>
		</template>
		<template #footer>
			<block-buttons>
				<button-claim @click="" v-if="modal.type == 'create'"> Создать </button-claim>
				<button-default @click="" v-if="modal.type == 'edit'"> Обновить </button-default>
			</block-buttons>
		</template>
	</admin-modal>

	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                   СПЕЦИАЛИСТЫ                     |-->
	<!--|___________________________________________________|-->
	<info-bar>
		<template v-slot:title>Специалисты</template>
		<template v-slot:addreas>specialists/{{ $route.params.id }}</template>
	</info-bar>

	<!-- Основные данные врача -->
	<block-once>
		<block-title>
			<template #title>Профиль</template>
			<template #buttons>
				<icon-save :width="28" :height="28" />
			</template>
		</block-title>

		<div class="container-profile" v-show="loading.sections.profile">
			<img :src="`${spesialist.profile.path.body}`" class="profile-image" alt="" />
			<div class="profile-info">
				<container-input-two :fieldset="true">
					<template #legend>АВАТАР И ССЫЛКА</template>
					<template #title-one>ФОТО ВРАЧА</template>
					<template #input-one>
						<input class="profile-file" type="file" autocomplete="off" />
					</template>
					<template #title-two>ССЫЛКА НА ПРОДОКТОРОВ</template>
					<template #input-two>
						<input
							type="text"
							placeholder="Введите ссылку"
							autocomplete="off"
							v-model="spesialist.profile.link.body"
						/>
					</template>
				</container-input-two>
				<container-input-three :fieldset="true">
					<template #legend>Ф.И.О.</template>
					<template #title-one>ФАМИЛИЯ</template>
					<template #input-one>
						<input
							type="text"
							placeholder="Введите фамилию"
							autocomplete="off"
							v-model="spesialist.profile.family.body"
						/>
					</template>
					<template #title-two>ИМЯ</template>
					<template #input-two>
						<input
							type="text"
							placeholder="Введите имя"
							autocomplete="off"
							v-model="spesialist.profile.name.body"
						/>
					</template>
					<template #title-three>ОТЧЕСТВО</template>
					<template #input-three>
						<input
							type="text"
							placeholder="Введите отчество"
							autocomplete="off"
							v-model="spesialist.profile.surname.body"
						/>
					</template>
				</container-input-three>
			</div>
		</div>
		<!-- Первая работа и статус приёма -->
		<div class="container-profile-other" v-show="loading.sections.profile">
			<container-input>
				<container-input-two :fieldset="true">
					<template #legend>ПЕРВАЯ РАБОТА</template>
					<template #title-one>НАЧАЛО ПЕРВОЙ РАБОТЫ</template>
					<template #input-one>
						<input type="date" v-model="spesialist.profile.startWorkAge.body" />
					</template>
					<template #title-two>ГОРОД ПЕРВОЙ РАБОТЫ</template>
					<template #input-two>
						<input
							type="text"
							placeholder="Введите название города"
							autocomplete="off"
							v-model="spesialist.profile.startWorkCity.body"
						/>
					</template>
				</container-input-two>
				<container-input-two :fieldset="true">
					<template #legend>ПРИЁМ ВРАЧА</template>
					<template #title-one>У ВЗРОСЛЫХ</template>
					<template #input-one>
						<select v-model="spesialist.profile.adultDoctor.body">
							<option value="0">Нет</option>
							<option value="1">Да</option>
						</select>
					</template>
					<template #title-two>У ДЕТЕЙ</template>
					<template #input-two>
						<select v-model="spesialist.profile.childrenDoctor.body" autocomplete="off">
							<option value="0">Нет</option>
							<option value="1">Да</option>
						</select>
					</template>
				</container-input-two>
			</container-input>
		</div>

		<!-- Загрузчик профиля -->
		<loader-child
			:isLoading="loading.loader.profile"
			:minHeight="600"
			@loaderChildAfterLeave="loaderChildAfterLeave"
		/>
	</block-once>

	<!-- Сертификаты -->
	<block-once>
		<block-title>
			<template #title> Сертификаты </template>
			<template #buttons>
				<icon-save :width="28" :height="28" />
			</template>
		</block-title>

		<div class="profile-list" v-show="loading.sections.certificates">
			<!-- Если сертификаты не выбраны -->
			<div class="item-empty" v-if="spesialist.connections.certificates.length == 0">
				<div class="item-title">Пока тут ничего нет...</div>
			</div>
			<!-- Если специализации выбраны -->
			<div
				class="item"
				v-else
				v-for="certificate in spesialist.connections.certificates"
				:key="certificate.id"
			>
				<div class="item-title">{{ sections.certificates[certificate.id].name }}</div>
				<div class="item-close" @click="removeArrValue('certificates', certificate)">
					<icon-close :width="26" :height="26" />
				</div>
			</div>
		</div>

		<!-- Загрузчик профиля -->
		<loader-child
			:isLoading="loading.loader.clinics"
			:minHeight="100"
			@loaderChildAfterLeave="loaderChildAfterLeave"
		/>

		<block-buttons>
			<button-default @click="addCertificate"> Добавить </button-default>
		</block-buttons>
	</block-once>

	<!-- Специализации и клиники -->
	<block-two>
		<template #title-one>
			<block-title>
				<template #title> Специализации </template>
				<template #buttons>
					<icon-save :width="28" :height="28" />
				</template>
			</block-title>
		</template>
		<template #body-one>
			<div class="profile-list" v-show="loading.sections.specializations">
				<!-- Если специализации не выбраны -->
				<div class="item-empty" v-if="spesialist.connections.specializations.length == 0">
					<div class="item-title">Пока тут ничего нет...</div>
				</div>
				<!-- Если специализации выбраны -->
				<div
					class="item"
					v-else
					v-for="specialization in spesialist.connections.specializations"
					:key="specialization.id"
				>
					<div class="item-title">{{ sections.specializations[specialization.id].name }}</div>
					<div class="item-close" @click="removeArrValue('specializations', specialization)">
						<icon-close :width="26" :height="26" />
					</div>
				</div>
			</div>

			<!-- Загрузчик специализаций -->
			<loader-child
				:isLoading="loading.loader.specializations"
				:minHeight="100"
				@loaderChildAfterLeave="loaderChildAfterLeave"
			/>

			<block-buttons>
				<button-default> Добавить </button-default>
			</block-buttons>
		</template>
		<template #title-two>
			<block-title>
				<template #title> Клиники </template>
				<template #buttons>
					<icon-save :width="28" :height="28" />
				</template>
			</block-title>
		</template>
		<template #body-two>
			<div class="profile-list" v-show="loading.sections.clinics">
				<!-- Если клиники не выбраны -->
				<div class="item-empty" v-if="spesialist.connections.clinics.length == 0">
					<div class="item-title">Пока тут ничего нет...</div>
				</div>
				<!-- Если клиники выбраны -->
				<div
					class="item"
					v-else
					v-for="clinic in spesialist.connections.clinics"
					:key="clinic.id"
				>
					<div class="item-title">{{ sections.clinics[clinic.id].name }}</div>
					<div class="item-close" @click="removeArrValue('clinics', clinic)">
						<icon-close :width="26" :height="26" />
					</div>
				</div>
			</div>

			<!-- Загрузчик клиник -->
			<loader-child
				:isLoading="loading.loader.clinics"
				:minHeight="100"
				@loaderChildAfterLeave="loaderChildAfterLeave"
			/>

			<block-buttons>
				<button-default> Добавить </button-default>
			</block-buttons>
		</template>
	</block-two>
	<!-- Образование и места работы -->
	<block-two>
		<template #title-one>
			<block-title>
				<template #title> Образование </template>
				<template #buttons>
					<icon-save :width="28" :height="28" />
				</template>
			</block-title>
		</template>
		<template #body-one>
			<div class="profile-list" v-show="loading.sections.educations">
				<!-- Если образования не выбраны -->
				<div class="item-empty" v-if="spesialist.connections.educations.length == 0">
					<div class="item-title">Пока тут ничего нет...</div>
				</div>
				<!-- Если образования выбраны -->
				<div
					class="item"
					v-else
					v-for="education in spesialist.connections.educations"
					:key="education.id"
				>
					<div class="item-title">{{ sections.educations[education.id].name }}</div>
					<div class="item-close" @click="removeArrValue('educations', education)">
						<icon-close :width="26" :height="26" />
					</div>
				</div>
			</div>

			<!-- Загрузчик образований -->
			<loader-child
				:isLoading="loading.loader.educations"
				:minHeight="100"
				@loaderChildAfterLeave="loaderChildAfterLeave"
			/>

			<block-buttons>
				<button-default> Добавить </button-default>
			</block-buttons>
		</template>
		<template #title-two>
			<block-title>
				<template #title> Места работы </template>
				<template #buttons>
					<icon-save :width="28" :height="28" />
				</template>
			</block-title>
		</template>
		<template #body-two>
			<div class="profile-list" v-show="loading.sections.works">
				<!-- Если образования не выбраны -->
				<div class="item-empty" v-if="spesialist.connections.works.length == 0">
					<div class="item-title">Пока тут ничего нет...</div>
				</div>
				<!-- Если образования выбраны -->
				<div class="item" v-else v-for="work in spesialist.connections.works" :key="work.id">
					<div class="item-title">{{ sections.works[work.id].name }}</div>
					<div class="item-close" @click="removeArrValue('works', work)">
						<icon-close :width="26" :height="26" />
					</div>
				</div>
			</div>

			<!-- Загрузчик мест работы -->
			<loader-child
				:isLoading="loading.loader.works"
				:minHeight="100"
				@loaderChildAfterLeave="loaderChildAfterLeave"
			/>

			<block-buttons>
				<button-default> Добавить </button-default>
			</block-buttons>
		</template>
	</block-two>
</template>

<script>
import AdminModal from "../../../components/includes/admin/AdminModal.vue";

import InfoBar from "../../../components/ui/admin/InfoBar.vue";

import LoaderChild from "../../../components/includes/LoaderChild.vue";

import ElementInputLabel from "../../../components/ui/admin/elements/ElementInputLabel.vue";
import BlockOnce from "../../../components/ui/admin/blocks/BlockOnce.vue";
import BlockTwo from "../../../components/ui/admin/blocks/BlockTwo.vue";
import BlockTitle from "../../../components/ui/admin/blocks/BlockTitle.vue";
import BlockButtons from "../../../components/ui/admin/blocks/BlockButtons.vue";
import SpanError from "../../../components/ui/admin/SpanError.vue";

import TableButtonDefault from "../../../components/ui/admin/tables/TableButtonDefault.vue";
import TableButtonRemove from "../../../components/ui/admin/tables/TableButtonRemove.vue";

import IconClose from "../../../components/icons/IconClose.vue";

import ContainerInput from "../../../components/ui/admin/containers/ContainerInput.vue";
import ContainerInputOnce from "../../../components/ui/admin/containers/input/ContainerInputOnce.vue";
import ContainerInputTwo from "../../../components/ui/admin/containers/input/ContainerInputTwo.vue";
import ContainerInputThree from "../../../components/ui/admin/containers/input/ContainerInputThree.vue";

import ButtonDefault from "../../../components/ui/admin/buttons/ButtonDefault.vue";
import ButtonRemove from "../../../components/ui/admin/buttons/ButtonRemove.vue";
import ButtonClaim from "../../../components/ui/admin/buttons/ButtonClaim.vue";

import IconSave from "../../../components/icons/IconSave.vue";

import axios from "axios";

import { RouterView, RouterLink } from "vue-router";

export default {
	components: {
		AdminModal,
		InfoBar,
		LoaderChild,
		ElementInputLabel,
		BlockOnce,
		BlockTwo,
		BlockTitle,
		BlockButtons,
		IconClose,
		SpanError,
		ContainerInput,
		ContainerInputOnce,
		ContainerInputTwo,
		ContainerInputThree,
		TableButtonDefault,
		TableButtonRemove,
		ButtonDefault,
		ButtonRemove,
		ButtonClaim,
		IconSave,
		axios,
		RouterView,
		RouterLink,
	},
	data() {
		return {
			modal: {
				title: "",
				status: false,
				type: null,
				style: {
					create: false,
					delete: false,
				},
				modules: {
					title: true,
					buttons: {
						hide: false,
						close: true,
					},
					images: false,
					body: true,
					footer: true,
				},
			},
			loading: {
				loader: {
					profile: true,
					certificates: true,
					specializations: true,
					clinics: true,
					educations: true,
					works: true,
				},
				sections: {
					profile: false,
					certificates: false,
					specializations: false,
					clinics: false,
					educations: false,
					works: false,
				},
			},
			spesialist: {
				profile: {
					file: "",
					id: {
						body: "",
						edited: false,
					},
					link: {
						body: "",
						edited: false,
					},
					family: {
						body: "",
						edited: false,
					},
					name: {
						body: "",
						edited: false,
					},
					surname: {
						body: "",
						edited: false,
					},
					startWorkAge: {
						body: "",
						edited: false,
					},
					startWorkCity: {
						body: "",
						edited: false,
					},
					adultDoctor: {
						body: false,
						edited: false,
					},
					childrenDoctor: {
						body: false,
						edited: false,
					},
					hide: {
						body: false,
						edited: false,
					},
					filename: {
						body: "",
						edited: false,
					},
					path: {
						body: "",
						edited: false,
					},
				},
				connections: {
					certificates: [],
					specializations: [],
					clinics: [],
					educations: [],
					works: [],
				},
			},
			sections: {
				certificates: [],
				specializations: [],
				clinics: [],
				educations: [],
				works: [],
			},
		};
	},
	methods: {
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                   Загрузчик                       |*/
		/* |___________________________________________________|*/
		/* После скрытия элементы */
		loaderChildAfterLeave() {
			if (!this.loading.loader.profile) {
				for (let key in this.loading.sections) {
					this.loading.sections[key] = true;
				}
			}
		},

		getCertificateName(id) {
			let certificate = this.sections.certificates.filter((item) => {
				if (item.id == id) {
					return item;
				}
			});

			// console.log(id);
			// console.log(certificate);
		},
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                 Модальное окно                    |*/
		/* |___________________________________________________|*/
		/* _____________________________________________________*/
		/* 1. Основные действия                                 */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		/* Открытие */
		openModal(type) {
			switch (type) {
				case "create":
					{
						this.modal.type = "create";
						this.modal.status = true;
						this.modal.style.create = true;
						this.modal.style.delete = false;
						this.clearModalData();
					}
					document.body.classList.add("modal-open");
					break;
				case "edit":
					{
						this.modal.type = "edit";
						// if (this.currentSpecialization.data.create.body) {
						// 	this.modal.style.create = true;
						// } else {
						// 	this.modal.style.create = false;
						// }
						this.modal.status = true;
						this.modal.style.delete = false;
					}
					document.body.classList.add("modal-open");
					break;
				default:
					{
						let debbugStory = {
							title: "Ошибка.",
							body: "Низвестный тип открытия модального окна.",
							type: "Error",
						};
						this.$store.commit("debuggerState", debbugStory);
					}
					break;
			}
		},
		/* Закрытие */
		closeModal() {
			this.modal.status = false;
			document.body.classList.remove("modal-open");
		},
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                    ПРОФИЛЬ                        |*/
		/* |___________________________________________________|*/
		/* _____________________________________________________*/
		/* 1. Основные действия                                 */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		/* Открытие специализации для редактирования */
		addCertificate(selectedSpecialization) {
			// this.clearModalData();

			// for (let key in this.currentSpecialization.data) {
			// 	this.currentSpecialization.data[key].body = selectedSpecialization[key];
			// }

			this.openModal("edit");
		},
		/* Открытие специализации для создания */
		createSpecialization() {
			this.openModal("create");
		},
		/* _____________________________________________________*/
		/* 2. Сохранение, обновление и удаление                 */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		/* Метод удаления значения из массива */
		removeArrValue(array, value) {
			this.spesialist.connections[array] = this.spesialist.connections[array].filter((item) => {
				if (item.id !== value.id) {
					return item;
				}
			});
		},
	},
	mounted() {
		axios({
			method: "post",
			headers: {
				Accept: "application/json",
			},
			url: `${this.$store.state.axios.urlApi}` + `get-specialist-all`,
			data: {
				id: this.$route.params.id,
			},
		})
			.then((response) => {
				for (let key in response.data.specialist.profile) {
					this.spesialist.profile[key].body = response.data.specialist.profile[key];
				}

				for (let key in response.data.specialist.connections) {
					this.spesialist.connections[key] = response.data.specialist.connections[key];
				}

				for (let key in response.data.sections) {
					this.sections[key] = response.data.sections[key];
				}
			})
			.catch((error) => {
				console.log(error);
			})
			.finally(() => {
				for (let key in this.loading.loader) {
					this.loading.loader[key] = false;
				}
			});
	},
};
</script>

<style scoped>
.certificates-list {
	display: flex;
	flex-direction: column;
	gap: 10px;
}

.certificates-list > .item {
	display: grid;
	grid-template-columns: 30px 1fr 1fr;
	gap: 10px;
	border: 2px solid var(--input-border-color-inactive);
	padding: 10px;
	border-radius: 10px;
}

.certificates-list > .item > label {
	font-size: 18px;
}

.container-profile {
	display: grid;
	grid-template-columns: 1fr 1fr;
	gap: 20px;

	animation: show-bottom-to-top-15 0.5s ease-in-out;
}

.container-profile-other {
	animation: show-bottom-to-top-15 0.5s ease-in-out;
}

.profile-image-loader {
	display: flex;
	justify-content: center;
	align-items: center;
	font-size: 20px;
	color: #bcbcbc;
	border-radius: 15px;
}

.profile-image {
	align-self: center;
	justify-self: center;
	flex: 1 0 340px;
	max-width: 450px;
	border-radius: 10px;
}

.profile-info {
	flex-grow: 1;
	display: flex;
	flex-direction: column;
	gap: 10px;
}

.profile-file {
	box-sizing: border-box;
	outline: none;

	padding: 10px;
	border: 2px solid var(--input-border-color-inactive);
	border-radius: 10px;

	font-size: 20px;
	caret-color: var(--input-border-color-active);

	transition: all 0.2s;
}

.profile-file::file-selector-button {
	flex-grow: 1;
	cursor: pointer;
	background-color: var(--button-default-color);
	border: 0px;
	border-radius: 5px;
	color: white;
	padding: 5px;
}

.profile-file:focus {
	border: 2px solid var(--input-border-color-active);
}

.profile-list {
	flex-grow: 1;
	display: flex;
	flex-direction: column;
	gap: 10px;

	animation: show-bottom-to-top-15 0.5s ease-in-out;
}

.profile-list > .item {
	display: flex;
	justify-content: space-between;
	align-items: center;
	gap: 10px;

	border: 2px solid var(--input-border-color-inactive);
	border-radius: 10px;
	padding: 10px;
	transition: all 0.2s;
}

.profile-list > .item-empty {
	display: flex;
	justify-content: center;
	align-items: center;
	height: 100%;
	color: #bcbcbc;
}

@media screen and (max-width: 1400px) {
	.container-profile {
		display: flex;
		flex-wrap: wrap;
		justify-content: center;
		align-items: center;
		gap: 20px;
	}
}
</style>
