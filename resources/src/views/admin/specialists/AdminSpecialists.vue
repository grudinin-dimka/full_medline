<template>
	<!-- Модальное окно -->
	<admin-modal @closeModal="closeModal" :modal="modal">
		<template #title>{{ modal.title }}</template>
		<template #img>
			<div
				v-if="modal.type == 'edit'"
				class="modal-body-img"
				:style="{
					backgroundImage: `url(${currentSpecialist.data.path})`,
				}"
				ref="modalImg"
			></div>
			<div
				v-if="modal.type == 'create'"
				class="modal-body-img"
				:style="{
					backgroundImage: `url(/storage/default/specialist.png)`,
				}"
				ref="modalImg"
			></div>
		</template>
		<template #img-input>
			<input
				class="modal-img-input"
				type="file"
				ref="fileUpload"
				placeholder="Файл"
			/>
		</template>
		<template #body>
			<div class="modal-body-inputs">
				<!-- Имя доктора -->
				<article>
					<element-input-label> Имя доктора </element-input-label>
					<input
						type="text"
						ref="inputName"
						placeholder="Имя"
						v-model="currentSpecialist.data.name"
					/>
				</article>
				<!-- Специализация -->
				<article>
					<element-input-label> Специализация </element-input-label>
					<input
						type="text"
						ref="inputSpecialization"
						placeholder="Имя"
						v-model="currentSpecialist.data.specialization"
					/>
				</article>
				<!-- Дата начала работы -->
				<article>
					<element-input-label> Дата начала работы </element-input-label>
					<input
						type="date"
						ref="inputStartWorkAge"
						placeholder="Имя"
						v-model="currentSpecialist.data.startWorkAge"
					/>
				</article>
				<!-- Обучение -->
				<article>
					<element-input-label> Обучение </element-input-label>
					<textarea
						rows="4"
						ref="inputEducation"
						placeholder="Имя"
						v-model="currentSpecialist.data.education"
					>
					</textarea>
				</article>
				<!-- Повышение квалификации -->
				<article>
					<element-input-label>
						Повышение квалификации
					</element-input-label>
					<textarea
						rows="4"
						ref="inputEducation"
						placeholder="Имя"
						v-model="currentSpecialist.data.advancedTraining"
					>
					</textarea>
				</article>
				<!-- Сертификаты -->
				<article>
					<element-input-label> Сертификаты </element-input-label>
					<textarea
						rows="4"
						ref="inputStartWorkAge"
						placeholder="Имя"
						v-model="currentSpecialist.data.certificates"
					>
					</textarea>
				</article>
			</div>
		</template>
		<template #footer>
			<BlockButtons v-if="modal.type == 'edit'">
				<ButtonRemove> Удалить </ButtonRemove>
				<ButtonDefault @click="updateSpecialist"> Обновить </ButtonDefault>
			</BlockButtons>
			<BlockButtons v-if="modal.type == 'create'">
				<ButtonDefault> Создать </ButtonDefault>
			</BlockButtons>
		</template>
	</admin-modal>

	<info-bar>
		<template v-slot:title>Специалисты</template>
		<template v-slot:addreas>specialists</template>
	</info-bar>

	<block>
		<block-title>
			<template #title>Список врачей</template>
			<template #buttons>
				<icon-save :width="28" :height="28" @click="" />
			</template>
		</block-title>

		<LoaderChild :isLoading="loading.loader.specialists"></LoaderChild>

		<admin-specialists-table
			v-if="loading.specialists"
			:specialists="specialists"
			@editSpecialist="editSpecialist"
			@removeSpecialist="removeSpecialist"
			@useFilter="filterspecialists"
		/>

		<block-buttons>
			<button-default @click="addSpecialist"> Добавить </button-default>
		</block-buttons>
	</block>
</template>

<script>
import AdminModal from "../../../components/includes/admin/AdminModal.vue";
import InfoBar from "../../../components/ui/admin/InfoBar.vue";
import LoaderChild from "../../../components/includes/LoaderChild.vue";

import ElementInputLabel from "../../../components/ui/admin/ElementInputLabel.vue";
import Block from "../../../components/ui/admin/Block.vue";
import BlockTitle from "../../../components/ui/admin/BlockTitle.vue";
import BlockButtons from "../../../components/ui/admin/BlockButtons.vue";
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
		Block,
		BlockTitle,
		BlockButtons,
		ButtonDefault,
		AdminSpecialistsTable,
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
				slide: {
					create: false,
					delete: false,
				},
			},
			loading: {
				loader: {
					specialists: true,
				},
				specialists: false,
			},
			currentSpecialist: {
				status: false,
				file: null,
				errors: {
					name: {
						status: false,
						value: null,
					},
					link: {
						status: false,
						value: null,
					},
					file: {
						status: false,
						value: null,
					},
				},
				data: {
					id: {
						body: "",
						edited: false,
					},
					name: {
						body: "",
						edited: false,
					},
					specialization: {
						body: "",
						edited: false,
					},
					startWorkAge: {
						body: "",
						edited: false,
					},
					education: {
						body: "",
						edited: false,
					},
					advancedTraining: {
						body: "",
						edited: false,
					},
					certificates: {
						body: "",
						edited: false,
					},
					filename: {
						body: "",
					},
					create: {
						body: false,
					},
					delete: {
						body: false,
					},
				},
			},
			specialists: [],
		};
	},
	methods: {
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                   СПЕЦИАЛИСТЫ                     |*/
		/* |___________________________________________________|*/
		/* _____________________________________________________*/
		/* 1. Модальное окно                                    */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		// Открытие модального окна с доктором
		openModal(type) {
			switch (type) {
				case "create":
					{
						this.modal.type = type;
						this.modal.title = "Создание";
						this.modal.status = true;
					}
					break;
				case "edit":
					{
						this.modal.type = type;
						this.modal.title = "Редактирование";
						this.modal.status = true;
					}
					break;
			}
			document.body.classList.toggle("modal-open");
		},
		// Закрытие модального окна с доктором
		closeModal() {
			this.modal.status = false;
			document.body.classList.toggle("modal-open");
		},
		/* _____________________________________________________*/
		/* 2. Фильтрация                                        */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		filterspecialists(type) {
			this.specialists.filter((specialist) => {
				switch (type) {
					case "id":
						{
							this.specialists.sort((a, b) => {
								b - a;
							});
							console.log("id");
						}
						break;
					case "name":
						{
							this.specialists.sort((a, b) => {
								a + b;
							});
							console.log("name");
						}
						break;
					case "specialization":
						{
							this.specialists.sort((a, b) => a - b);
						}
						console.log("specialization");
						break;
				}
			});
		},
		/* _____________________________________________________*/
		/* 3. Добавление, изменение, удаление                   */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		// Изменение выбранного доктора
		addSpecialist() {
			this.currentSpecialist.data.create.body = true;
			for (let key in this.currentSpecialist.data) {
				this.currentSpecialist.data[key] = "";
			}
			this.openModal("create");
		},
		// Изменение выбранного доктора
		editSpecialist(specialist) {
			for (let key in specialist) {
				this.currentSpecialist.data[key] = specialist[key];
			}
			this.openModal("edit");
		},
		// Удаление выбранного доктора
		removeSpecialist(specialist) {
			console.log(specialist);
		},
		// Изменение выбранного доктора
		updateSpecialist() {
			console.log(this.currentSpecialist);
			// for (let key in specialist) {
			// 	this.currentSpecialist.data[key] = specialist[key];
			// }
			this.closeModal();
		},
	},
	mounted() {
		// Получение массива докторов с сервера
		axios({
			method: "post",
			url: `${this.$store.state.axios.urlApi}` + `get-specialists`,
		})
			.then((response) => {
				this.specialists = response.data;

				this.loading.loader.specialists = false;
				setTimeout(() => {
					this.loading.specialists = true;
				}, 515);
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
.modal-img-input {
	flex-grow: 1;
	box-sizing: border-box;
	outline: none;
	max-width: 400px;

	padding: 10px;
	border: 2px solid var(--input-border-color-inactive);
	border-radius: 10px;

	font-size: 20px;
	caret-color: var(--input-border-color-active);

	transition: all 0.2s;
}

.modal-img-input::file-selector-button {
	flex-grow: 1;
	cursor: pointer;
	background-color: var(--button-default-color);
	border: 0px;
	border-radius: 5px;
	color: white;
	padding: 5px;
}

.modal-body-img {
	flex: 1 0 400px;
	background-size: contain;
	background-position: center center;
	background-repeat: no-repeat;
	padding: 5px;
	border-radius: 10px;
}

.modal-body-inputs {
	display: flex;
	flex-direction: column;
	gap: 10px;
}

.modal-body-inputs > article {
	display: flex;
	flex-direction: column;
	gap: 5px;
}

.modal-body-inputs > article > input {
	box-sizing: border-box;
	outline: none;

	padding: 10px;
	border: 2px solid var(--input-border-color-inactive);
	border-radius: 10px;

	width: 100%;
	height: 58px;

	font-size: 18px;
	caret-color: var(--input-border-color-active);

	transition: all 0.2s;
}

.modal-body-inputs > article > input:focus {
	border: 2px solid var(--input-border-color-active);
}

.modal-body-inputs > article > textarea {
	box-sizing: border-box;
	outline: none;

	padding: 10px;
	border: 2px solid var(--input-border-color-inactive);
	border-radius: 10px;

	width: 100%;
	resize: none;

	font-size: 18px;
	caret-color: var(--input-border-color-active);

	transition: all 0.2s;
}

.modal-body-inputs > article > textarea:focus {
	border: 2px solid var(--input-border-color-active);
}
</style>
