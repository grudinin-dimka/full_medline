<template>
	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|           МОДАЛЬНОЕ ОКНО (СПЕЦИАЛИЗАЦИИ)          |-->
	<!--|___________________________________________________|-->
	<admin-modal @touchCloseModal="closeModal('modalSpecializations')" :modal="modalSpecializations">
		<template #title>
			<span class="create" v-if="modalSpecializations.type == 'create'">
				СПЕЦИАЛИЗАЦИЯ (СОЗДАНИЕ)
			</span>
			<span v-if="modalSpecializations.type == 'edit'">СПЕЦИАЛИЗАЦИИ</span>
		</template>
		<template #body>
			<!-- Список специализаций -->
			<div class="specializations-list">
				<div class="item">
					<div></div>
					<div></div>
					<div>Название</div>
				</div>
				<div
					class="item"
					v-for="(specialization, index) in getSortedSpecializations"
					:key="specialization.id"
					:class="{ active: cheked.specializations.includes(specialization.id) }"
				>
					<div>
						#{{
							index +
							1 +
							paginationSpecializations.elements.range *
								(paginationSpecializations.pages.current - 1)
						}}
					</div>
					<input
						type="checkbox"
						:id="`spec-${specialization.id}`"
						:value="specialization.id"
						v-model="cheked.specializations"
					/>
					<label :for="`spec-${specialization.id}`">{{ specialization.name }}</label>
					<select>
						<option value="" disabled selected>Не выбрано</option>
						<option value="Первая">Первая</option>
						<option value="Вторая">Вторая</option>
						<option value="Третья">Высшая</option>
					</select>
				</div>
			</div>

			<!-- Пагинация -->
			<pagination
				:arrayLength="sections.specializations.length"
				:settings="paginationSpecializations"
				@changePage="changePageSpecializations"
			/>
		</template>
		<template #footer>
			<block-buttons>
				<button-claim @click="" v-if="modalSpecializations.type == 'create'">
					Создать
				</button-claim>
				<button-default
					@click="updateSpecialization"
					v-if="modalSpecializations.type == 'edit'"
				>
					Обновить
				</button-default>
			</block-buttons>
		</template>
	</admin-modal>

	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|              МОДАЛЬНОЕ ОКНО (КЛИНИКИ)             |-->
	<!--|___________________________________________________|-->
	<admin-modal @touchCloseModal="closeModal('modalClinics')" :modal="modalClinics">
		<template #title>
			<span class="create" v-if="modalClinics.type == 'create'"> СПЕЦИАЛИЗАЦИЯ (СОЗДАНИЕ) </span>
			<span v-if="modalClinics.type == 'edit'">КЛИНИКИ</span>
		</template>
		<template #body>
			<!-- Список специализаций -->
			<div class="clinics-list">
				<div class="item">
					<div></div>
					<div></div>
					<div>Название</div>
					<div>Адрес</div>
				</div>
				<div
					class="item"
					v-for="(clinic, index) in getSortedClinics"
					:key="clinic.id"
					:class="{ active: cheked.clinics.includes(clinic.id) }"
				>
					<div>
						#{{
							index +
							1 +
							paginationClinics.elements.range * (paginationClinics.pages.current - 1)
						}}
					</div>
					<input
						type="checkbox"
						:id="`clin-${clinic.id}`"
						:value="clinic.id"
						v-model="cheked.clinics"
					/>
					<label :for="`clin-${clinic.id}`">{{ clinic.name }}</label>
					<label :for="`clin-${clinic.id}`">{{
						`г. ${clinic.city}, ул. ${clinic.street}, д. ${clinic.home}`
					}}</label>
				</div>
			</div>

			<!-- Пагинация -->
			<pagination
				:arrayLength="sections.clinics.length"
				:settings="paginationClinics"
				@changePage="changePageClinics"
			/>
		</template>
		<template #footer>
			<block-buttons>
				<button-claim @click="" v-if="modalClinics.type == 'create'"> Создать </button-claim>
				<button-default @click="updateClinics" v-if="modalClinics.type == 'edit'">
					Обновить
				</button-default>
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

	<!-- Специализации и клиники -->
	<block-two>
		<template #title-one>
			<block-title>
				<template #title> Специализации </template>
				<template #buttons>
					<icon-save :width="28" :height="28" @click="saveSpecializationsChanges" />
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
					<!-- <div class="item-title">1</div> -->
					<div class="item-title">
						{{
							sections.specializations.filter(
								(item) => item.id == specialization.id_specialization
							)[0].name
						}}
					</div>
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
				<button-default @click="editSpecialization"> Добавить </button-default>
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
					<div class="item-title">
						{{ sections.clinics.filter((item) => item.id == clinic.id_clinic)[0].name }}
					</div>
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
				<button-default @click="editClinics"> Добавить </button-default>
			</block-buttons>
		</template>
	</block-two>

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
				<div class="item-title">
					{{ getCertificateName(certificate) }}
				</div>
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
			<button-default> Добавить </button-default>
		</block-buttons>
	</block-once>

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

import Pagination from "../../../components/ui/admin/pagination/Pagination.vue";

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
		Pagination,
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
			/* Модальные окна */
			modalSpecializations: {
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
			modalClinics: {
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
			/* Загрузчик */
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
			/* Пагинация */
			paginationSpecializations: {
				pages: {
					current: 1,
					range: 5,
				},
				elements: {
					range: 10,
				},
			},
			paginationClinics: {
				pages: {
					current: 1,
					range: 5,
				},
				elements: {
					range: 10,
				},
			},
			/* Специалист */
			spesialist: {
				// Основная информация
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
				// Связи
				connections: {
					certificates: [],
					specializations: [],
					clinics: [],
					educations: [],
					works: [],
				},
			},
			/* Секции */
			sections: {
				certificates: [],
				specializations: [],
				clinics: [],
				educations: [],
				works: [],
			},
			/* Чекбоксовые массивы */
			cheked: {
				counter: 0,
				specializations: [],
				clinics: [],
			},
		};
	},
	computed: {
		/* _____________________________________________________*/
		/* 1. Специалиации                                      */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		getPagesSpecializationsTotal() {
			return Math.ceil(
				this.sections.specializations.length / this.paginationSpecializations.elements.range
			);
		},
		getSortedSpecializations() {
			return [...this.sections.specializations].splice(
				(this.paginationSpecializations.pages.current - 1) *
					this.paginationSpecializations.elements.range,
				this.paginationSpecializations.elements.range
			);
		},
		/* _____________________________________________________*/
		/* 2. Клиники                                           */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		getPagesClinicsTotal() {
			return Math.ceil(this.sections.clinics.length / this.paginationClinics.elements.range);
		},
		getSortedClinics() {
			return [...this.sections.clinics].splice(
				(this.paginationClinics.pages.current - 1) * this.paginationClinics.elements.range,
				this.paginationClinics.elements.range
			);
		},
	},
	watch: {
		// "pagination.pages.current"(newValue, oldValue) {
		// 	console.log(newValue, oldValue);
		// },
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
		/* Получить название сертификата */
		getCertificateName(id) {
			let certificate = this.sections.certificates.filter((item) => {
				if (item.id == id.id_certificate) {
					return item;
				}
			});
			return certificate[0].name;
		},
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                 Модальное окно                    |*/
		/* |___________________________________________________|*/
		/* _____________________________________________________*/
		/* 1. Основные действия                                 */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		/* Открытие */
		openModal(type, modalName) {
			switch (type) {
				case "create":
					{
						this[modalName].type = "create";
						this[modalName].status = true;
						this[modalName].style.create = true;
						this[modalName].style.delete = false;
						this.clearModalData();
					}
					document.body.classList.add("modal-open");
					break;
				case "edit":
					{
						this[modalName].type = "edit";
						this[modalName].status = true;
						this[modalName].style.delete = false;
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
		closeModal(modalName) {
			this[modalName].status = false;
			document.body.classList.remove("modal-open");
		},
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                   ПАГИНАТОР                       |*/
		/* |___________________________________________________|*/
		/* Изменение текущей страницы */
		changePageSpecializations(pageNumber) {
			// Проверка на превышение количества страниц
			if (
				pageNumber >
				Math.ceil(
					this.sections.specializations.length / this.paginationSpecializations.elements.range
				)
			) {
				return;
			} else if (pageNumber < 1) {
				return;
			}

			this.paginationSpecializations.pages.current = pageNumber;
		},
		/* Изменение текущей страницы */
		changePageClinics(pageNumber) {
			// Проверка на превышение количества страниц
			if (
				pageNumber >
				Math.ceil(this.sections.clinics.length / this.paginationClinics.elements.range)
			) {
				return;
			} else if (pageNumber < 1) {
				return;
			}

			this.paginationClinics.pages.current = pageNumber;
		},
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                    ПРОФИЛЬ                        |*/
		/* |___________________________________________________|*/
		/* _____________________________________________________*/
		/* 1. Специалиации                                      */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		/* Открытие списка специализация */
		editSpecialization() {
			// Обнуление выбранных специализаций
			this.cheked.specializations = [];

			// Заполнение выбранных специализаций
			this.spesialist.connections.specializations.forEach((item) => {
				this.cheked.specializations.push(item.id_specialization);
			});

			this.openModal("edit", "modalSpecializations");
		},
		/* Обновление специализаций */
		updateSpecialization() {
			try {
				// Обнуление сертификатов специалиста
				this.spesialist.connections.specializations = [];

				let maxId = 0;
				// Если ничего не выбрано, то оставляем пустой массив, иначе заполняем массив выбранными значениями
				if (this.cheked.specializations.length !== 0) {
					this.cheked.specializations.sort((a, b) => {
						if (a > b) {
							return 1;
						} else if (a < b) {
							return -1;
						} else {
							return 0;
						}
					});

					/* Заполнение выбранных специализаций в массив */
					this.cheked.specializations.forEach((item) => {
						this.spesialist.connections.specializations.push({
							id: maxId + 1,
							id_specialist: this.spesialist.profile.id.body,
							id_specialization: item,
						});

						/* Поиск максимального id */
						this.spesialist.connections.specializations.filter((item) => {
							if (item.id > maxId) maxId++;
						});
					});
				}

				this.closeModal("modalSpecializations");
			} catch (error) {
				let debbugStory = {
					title: "Ошибка.",
					body: "При обновлении значений произошла ошибка.",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
			}
		},
		/* Сохранение изменений */
		saveSpecializationsChanges() {
			try {
				let newArray = [];

				// Заполнение нового массива
				this.spesialist.connections.specializations.forEach((item) => {
					newArray.push(Object.assign({}, item));
				});

				// Сортировка нового массива
				newArray.sort((a, b) => {
					if (a.id > b.id) {
						return 1;
					} else if (a.id < b.id) {
						return -1;
					} else {
						return 0;
					}
				});

				axios({
					method: "post",
					url: `${this.$store.state.axios.urlApi}` + `save-specialist-specializations-changes`,
					headers: {
						Accept: "application/json",
						Authorization: `Bearer ${localStorage.getItem("token")}`,
					},
					data: {
						idSpecialist: this.spesialist.profile.id.body,
						specialistSpecializations: newArray,
					},
				})
					.then((response) => {
						console.log(response.data);

						let debbugStory = {
							title: "Успешно!",
							body: "Данные сохранились.",
							type: "Completed",
						};
						this.$store.commit("debuggerState", debbugStory);
					})
					.catch((error) => {
						let debbugStory = {
							title: "Ошибка.",
							body: "Данные почему-то не сохранились...",
							type: "Error",
						};
						this.$store.commit("debuggerState", debbugStory);
					});
			} catch (error) {
				let debbugStory = {
					title: "Ошибка.",
					body: "При сохранении значений произошла ошибка.",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
			}
		},
		/* _____________________________________________________*/
		/* 2. Клиники                                           */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		/* Открытие списка клиник */
		editClinics() {
			try {
				// Обнуление массива
				this.cheked.clinics = [];

				// Заполнение выбранных специализаций из массива
				this.spesialist.connections.clinics.forEach((item) => {
					this.cheked.clinics.push(item.id_clinic);
				});

				this.openModal("edit", "modalClinics");
			} catch (error) {
				let debbugStory = {
					title: "Ошибка.",
					body: "При открытии списка произошла ошибка.",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
			}
		},
		/* Обновление клиник */
		updateClinics() {
			try {
				// Обнуление сертификатов специалиста
				this.spesialist.connections.clinics = [];

				let maxId = 0;
				// Если ничего не выбрано, то оставляем пустой массив, иначе заполняем массив выбранными значениями
				if (this.cheked.clinics.length !== 0) {
					this.cheked.clinics.sort((a, b) => {
						if (a > b) {
							return 1;
						} else if (a < b) {
							return -1;
						} else {
							return 0;
						}
					});

					/* Заполнение выбранных специализаций в массив */
					for (let key in this.cheked.clinics) {
						this.spesialist.connections.clinics.push({
							id: maxId + 1,
							id_specialist: this.spesialist.profile.id.body,
							id_clinic: this.cheked.clinics[key],
						});

						// Поиск максимального id
						this.spesialist.connections.clinics.filter((item) => {
							if (item.id > maxId) maxId++;
						});
					}
				}

				this.closeModal("modalClinics");
			} catch (error) {
				let debbugStory = {
					title: "Ошибка.",
					body: "При обновлении значений произошла ошибка.",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
			}
		},
		/* _____________________________________________________*/
		/* ?. Общие методы                                         */
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
.specializations-list,
.clinics-list {
	display: flex;
	flex-direction: column;
	gap: 10px;
}

.specializations-list > .item {
	display: grid;
	grid-template-columns: 20px 30px 1fr 1fr;
	gap: 10px;
	border: 2px solid var(--input-border-color-inactive);
	padding: 10px;
	border-radius: 10px;

	transition: all 0.2s;
}

.specializations-list > .item > select {
	border: 0px;
	background-color: rgba(0, 0, 0, 0);
	font-size: 18px;
	outline: none;
}

.red {
	color: red;
}

.clinics-list > .item {
	display: grid;
	grid-template-columns: 20px 30px 1fr 1fr;
	gap: 10px;
	border: 2px solid var(--input-border-color-inactive);
	padding: 10px;
	border-radius: 10px;

	transition: all 0.2s;
}

.specializations-list > .item.active,
.clinics-list > .item.active {
	border: 2px solid var(--input-border-color-active);
	background-color: #f2feff;
}

.specializations-list > .item > input[type="checkbox"],
.clinics-list > .item > input[type="checkbox"] {
	cursor: pointer;
}

.specializations-list > .item > input[type="checkbox"]:checked,
.clinics-list > .item > input[type="checkbox"]:checked {
	accent-color: #8fe5ee;
}

.specializations-list > .item:first-of-type,
.clinics-list > .item:first-of-type {
	display: grid;
	gap: 10px;

	border: 0px;
	padding: 0px 10px;
	border-radius: 10px;

	font-size: 18px;
	color: var(--input-border-color-active);
}

.specializations-list > .item:first-of-type {
	grid-template-columns: 20px 30px 1fr;
}

.clinics-list > .item:first-of-type {
	grid-template-columns: 20px 30px 1fr 1fr;
}

.specializations-list > .item:not(:first-of-type):hover,
.clinics-list > .item:not(:first-of-type):hover {
	border: 2px solid var(--input-border-color-active);
	background-color: #f2feff;
}

.specializations-list > .item > label,
.clinics-list > .item > label {
	cursor: pointer;
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

	font-size: 18px;

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

@media screen and (min-width: 1960px) {
	.profile-image {
		flex: 1 0 300px;
		max-width: 300px;
		border-radius: 10px;
	}
}
</style>
