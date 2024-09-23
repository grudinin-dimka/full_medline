<template>
	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                  МОДАЛЬНОЕ ОКНО                   |-->
	<!--|___________________________________________________|-->
	<admin-modal ref="modal" @touchCloseModal="closeModal" :modal="modal">
		<template #title> Образование (редактирование) </template>
		<template #body>
			<container-input>
				<!-- Название -->
				<container-input-once>
					<template #title>НАЗВАНИЕ*</template>
					<template #input>
						<input
							type="text"
							placeholder="Введите название"
							autocomplete="off"
							:class="{ error: false }"
							v-model="currentEducation.data.name.body"
						/>
					</template>
					<template #error>
						<span class="error" v-if="false"> Ошибка </span>
					</template>
				</container-input-once>
				<!-- Организация -->
				<container-textarea-once>
					<template #title>ОРГАНИЗАЦИЯ*</template>
					<template #textarea>
						<textarea
							rows="4"
							placeholder="Введите организацию"
							autocomplete="off"
							:class="{ error: false }"
							v-model="currentEducation.data.organization.body"
						></textarea>
					</template>
					<template #error>
						<span class="error" v-if="false"> Ошибка </span>
					</template>
				</container-textarea-once>
				<container-input-once>
					<template #title>ОРГАНИЗАЦИЯ*</template>
					<template #input>
						<input
							type="text"
							placeholder="Введите организацию"
							autocomplete="off"
							:class="{ error: false }"
							v-model="currentEducation.data.organization.body"
						/>
					</template>
					<template #error>
						<span class="error" v-if="false"> Ошибка </span>
					</template>
				</container-input-once>
				<!-- Дата получения образования -->
				<container-input-once>
					<template #title>ДАТА ПОЛУЧЕНИЯ*</template>
					<template #input>
						<input
							type="date"
							placeholder="Введите организацию"
							autocomplete="off"
							:class="{ error: false }"
							v-model="currentEducation.data.date.body"
						/>
					</template>
					<template #error>
						<span class="error" v-if="false"> Ошибка </span>
					</template>
				</container-input-once>
				<!-- Выбор специализации -->
				<container-select-once>
					<template #title>СПЕЦИАЛИЗАЦИЯ*</template>
					<template #select>
						<select v-model="currentEducation.data.specialization.body">
							<option value="null">Ничего не выбрано...</option>
							<option :value="specialization.id" v-for="specialization in specializations">
								{{ specialization.name }}
							</option>
						</select>
					</template>
					<template #error>
						<span class="error" v-if="false"> Ошибка </span>
					</template>
				</container-select-once>
			</container-input>
		</template>
		<template #footer>
			<BlockButtons>
				<ButtonDefault v-if="modal.type == 'create'" @click=""> Создать </ButtonDefault>
				<ButtonDefault v-if="modal.type == 'edit'" @click=""> Обновить </ButtonDefault>
			</BlockButtons>
		</template>
	</admin-modal>

	<info-bar>
		<template v-slot:title>Специалисты</template>
		<template v-slot:addreas>specialists</template>
	</info-bar>

	<block-once>
		<block-title>
			<template #title>Образование</template>
			<template #buttons>
				<icon-save :width="28" :height="28" />
			</template>
		</block-title>

		<!-- Таблица -->
		<admin-specialists-table
			v-show="loading.table"
			:array="educations"
			@useFilter="filterEducations"
			@touchEditArrValue="editEducation"
			@touchRemoveArrValue="removeEducation"
		/>

		<!-- Загрузчик -->
		<loader-child
			:isLoading="loading.loader"
			:minHeight="200"
			@loaderChildAfterLeave="loaderChildAfterLeave"
		/>

		<block-buttons>
			<button-default @click=""> Добавить </button-default>
		</block-buttons>
	</block-once>
</template>

<script>
import AdminModal from "../../../components/includes/admin/AdminModal.vue";

import InfoBar from "../../../components/ui/admin/InfoBar.vue";

import LoaderChild from "../../../components/includes/LoaderChild.vue";

import ElementInputLabel from "../../../components/ui/admin/ElementInputLabel.vue";
import BlockOnce from "../../../components/ui/admin/BlockOnce.vue";
import BlockTitle from "../../../components/ui/admin/BlockTitle.vue";
import BlockButtons from "../../../components/ui/admin/BlockButtons.vue";

import ContainerInput from "../../../components/ui/admin/containers/ContainerInput.vue";
import ContainerInputOnce from "../../../components/ui/admin/containers/input/ContainerInputOnce.vue";
import ContainerInputTwo from "../../../components/ui/admin/containers/input/ContainerInputTwo.vue";
import ContainerInputThree from "../../../components/ui/admin/containers/input/ContainerInputThree.vue";
import ContainerSelectOnce from "../../../components/ui/admin/containers/select/ContainerSelectOnce.vue";
import ContainerTextareaOnce from "../../../components/ui/admin/containers/textarea/ContainerTextareaOnce.vue";

import AdminSpecialistsTable from "./AdminSpecialistsTable.vue";

import ButtonDefault from "../../../components/ui/admin/ButtonDefault.vue";
import ButtonRemove from "../../../components/ui/admin/ButtonRemove.vue";

import IconSave from "../../../components/icons/IconSave.vue";

import axios from "axios";

export default {
	components: {
		AdminModal,
		InfoBar,
		LoaderChild,
		ElementInputLabel,
		BlockOnce,
		BlockTitle,
		BlockButtons,
		ContainerInput,
		ContainerInputOnce,
		ContainerInputTwo,
		ContainerInputThree,
		ContainerSelectOnce,
		ContainerTextareaOnce,
		AdminSpecialistsTable,
		ButtonDefault,
		ButtonRemove,
		IconSave,
		axios,
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
				loader: true,
				table: false,
			},
			currentEducation: {
				errors: {
					id: {
						body: null,
						status: false,
					},
					name: {
						body: null,
						status: false,
					},
					organization: {
						body: null,
						status: false,
					},
					date: {
						body: null,
						status: false,
					},
					specialization: {
						body: null,
						status: false,
					},
					create: {
						body: false,
						status: false,
					},
					delete: {
						body: false,
						status: false,
					},
				},
				data: {
					id: {
						body: null,
						edited: false,
					},
					name: {
						body: null,
						edited: false,
					},
					organization: {
						body: null,
						edited: false,
					},
					date: {
						body: null,
						edited: false,
					},
					specialization: {
						body: null,
						edited: false,
					},
					create: {
						body: false,
						edited: false,
					},
					delete: {
						body: false,
						edited: false,
					},
				},
			},
			educations: [],
			specializations: [
				{
					id: 1,
					name: "Терапевт",
				},
				{
					id: 2,
					name: "Хирург",
				},
			],
		};
	},
	methods: {
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                   Загрузчик                       |*/
		/* |___________________________________________________|*/
		/* После скрытия элементы */
		loaderChildAfterLeave() {
			this.loading.table = true;
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
					this.clearModalErrors();

					this.modal.type = "create";
					this.modal.status = true;
					this.modal.style.create = true;
					this.modal.style.delete = false;
					this.clearModalData();

					document.body.classList.toggle("modal-open");
					break;
				case "edit":
					this.clearModalErrors();

					this.modal.type = "edit";
					// if (this.currentClinic.data.create.body) {
					// 	this.modal.style.create = true;
					// } else {
					// 	this.modal.style.create = false;
					// }
					this.modal.status = true;
					this.modal.style.delete = false;

					document.body.classList.toggle("modal-open");
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
			document.body.classList.toggle("modal-open");
		},
		/* _____________________________________________________*/
		/* 2. Работа с полями ввода модального окна             */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		/* Очистка содержимого модального окна */
		clearModalData() {
			for (let key in this.currentEducation.data) {
				this.currentEducation.data[key].body = "";
				this.currentEducation.data[key].edited = false;
			}
		},
		/* Очистка ошибок */
		clearModalErrors() {
			for (let key in this.currentEducation.errors) {
				this.currentEducation.errors[key].body = "";
				this.currentEducation.errors[key].status = false;
			}
		},
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                   ОБРАЗОВАНИЕ                     |*/
		/* |___________________________________________________|*/
		/* _____________________________________________________*/
		/* 1. Фильтрация                                        */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		filterEducations(column, type) {
			switch (column) {
				case "id":
					if (type == "default") {
						this.educations.sort((a, b) => {
							if (a.id > b.id) {
								return 1;
							}
							if (a.id < b.id) {
								return -1;
							}
							// a должно быть равным b
							return 0;
						});
					}

					if (type == "reverse") {
						this.educations.sort((a, b) => {
							if (a.id < b.id) {
								return 1;
							}
							if (a.id > b.id) {
								return -1;
							}
							// a должно быть равным b
							return 0;
						});
					}
					break;
				case "name":
					if (type == "default") {
						this.educations.sort((a, b) => {
							let aName = a.name.toLowerCase();
							let bName = b.name.toLowerCase();

							let aNameFirstLetter = aName[0].charCodeAt(0);
							let bNameFirstLetter = bName[0].charCodeAt(0);

							if (aNameFirstLetter > bNameFirstLetter) {
								return 1;
							}
							if (aNameFirstLetter < bNameFirstLetter) {
								return -1;
							}
							// a должно быть равным b
							return 0;
						});
					}

					if (type == "reverse") {
						this.educations.sort((a, b) => {
							let aName = a.name.toLowerCase();
							let bName = b.name.toLowerCase();

							let aNameFirstLetter = aName[0].charCodeAt(0);
							let bNameFirstLetter = bName[0].charCodeAt(0);

							if (aNameFirstLetter < bNameFirstLetter) {
								return 1;
							}
							if (aNameFirstLetter > bNameFirstLetter) {
								return -1;
							}
							// a должно быть равным b
							return 0;
						});
					}

					break;
				default:
					break;
			}
		},
		/* _____________________________________________________*/
		/* 2. Основные действия                                 */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		/* Открытие специализации для редактирования */
		editEducation(selectedEducation) {
			this.clearModalData();

			for (let key in this.currentEducation.data) {
				this.currentEducation.data[key].body = selectedEducation[key];
			}
			this.currentEducation.data.specialization.body = selectedEducation["id_specialization"];

			this.openModal("edit");
		},
		/* Открытие специализации для создания */
		createEducation() {
			this.openModal("create");
		},
		/* _____________________________________________________*/
		/* 3. Сохранение, обновление и удаление                 */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		/* Пометка на удаление */
		removeEducation(id) {
			let educationToDelete = this.educations.filter((education) => {
				if (education.id === id) {
					return education;
				}
			});

			educationToDelete[0].delete = !educationToDelete[0].delete;
		},
	},
	mounted() {
		axios({
			method: "post",
			headers: {
				Accept: "application/json",
			},
			url: `${this.$store.state.axios.urlApi}` + `get-educations-all`,
		})
			.then((response) => {
				console.log(response.data);
				this.educations = response.data.educations;
				this.specializations = response.data.spezializations;
				this.loading.loader = false;

				for (let key in this.educations) {
					this.educations[key].create = false;
					this.educations[key].delete = false;
				}
			})
			.catch((error) => {
				console.log(error);
			});
	},
};
</script>

<style scoped></style>
