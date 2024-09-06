<template>
	<!-- Модальное окно -->
	<admin-modal @closeModal="closeModal" :modal="modal">
		<template #title>{{ modal.title }}</template>
		<template #img>
			<div
				v-if="modal.type == 'edit'"
				class="modal-body-img"
				:style="{
					backgroundImage: `url(${currentSpecialist.data.path.body})`,
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
					<element-input-label>
						Имя доктора*
						<span v-if="currentSpecialist.data.name.edited">
							(Изменено)
						</span>
					</element-input-label>
					<input
						type="text"
						ref="inputName"
						placeholder="Имя"
						v-model="currentSpecialist.data.name.body"
						@input="currentSpecialist.data.name.edited = true"
					/>
				</article>
				<!-- Специализация -->
				<article>
					<element-input-label>
						Специализация*
						<span v-if="currentSpecialist.data.specialization.edited">
							(Изменено)
						</span>
					</element-input-label>
					<input
						type="text"
						ref="inputSpecialization"
						placeholder="Имя"
						v-model="currentSpecialist.data.specialization.body"
						@input="currentSpecialist.data.specialization.edited = true"
					/>
				</article>
				<!-- Дата начала работы -->
				<article>
					<element-input-label>
						Дата начала работы*
						<span v-if="currentSpecialist.data.startWorkAge.edited">
							(Изменено)
						</span>
					</element-input-label>
					<input
						type="date"
						ref="inputStartWorkAge"
						placeholder="Имя"
						v-model="currentSpecialist.data.startWorkAge.body"
						@input="currentSpecialist.data.startWorkAge.edited = true"
					/>
				</article>
				<!-- Обучение -->
				<article>
					<element-input-label>
						Обучение*
						<span v-if="currentSpecialist.data.education.edited">
							(Изменено)
						</span>
					</element-input-label>
					<textarea
						rows="4"
						ref="inputEducation"
						placeholder="Имя"
						v-model="currentSpecialist.data.education.body"
						@input="currentSpecialist.data.education.edited = true"
					>
					</textarea>
				</article>
				<!-- Повышение квалификации -->
				<article>
					<element-input-label>
						Повышение квалификации*
						<span v-if="currentSpecialist.data.advancedTraining.edited">
							(Изменено)
						</span>
					</element-input-label>
					<textarea
						rows="4"
						ref="inputEducation"
						placeholder="Имя"
						v-model="currentSpecialist.data.advancedTraining.body"
						@input="currentSpecialist.data.advancedTraining.edited = true"
					>
					</textarea>
				</article>
				<!-- Сертификаты -->
				<article>
					<element-input-label>
						Сертификаты*
						<span v-if="currentSpecialist.data.certificates.edited">
							(Изменено)
						</span>
					</element-input-label>
					<textarea
						rows="4"
						ref="inputStartWorkAge"
						placeholder="Имя"
						v-model="currentSpecialist.data.certificates.body"
						@input="currentSpecialist.data.certificates.edited = true"
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
					specialization: {
						status: false,
						value: null,
					},
					startWorkAge: {
						status: false,
						value: null,
					},
					education: {
						status: false,
						value: null,
					},
					advancedTraining: {
						status: false,
						value: null,
					},
					certificates: {
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
						body: "asdsada",
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
					hide: {
						body: false,
					},
					path: {
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

			this.clearModalInputsEdited();
		},
		/* _____________________________________________________*/
		/* 2. Работа с полями ввода модального окна             */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		// Проверка поля имени
		checkModalName() {
			if (this.currentSpecialist.data.name.body === "") {
				this.currentSpecialist.errors.name.status = true;
				this.currentSpecialist.errors.name.value =
					"Поле не может быть пустым";
				return false;
			}

			this.currentSlide.errors.name.status = false;
			this.currentSlide.errors.name.value = "";
			return true;
		},
		checkSliderFile() {
			/* Присваивание данных поля ввода файла пользователем в переменную */
			this.currentSlide.file = this.$refs.fileUpload.files[0];

			/* Проверка на загрузку файла пользователем */
			if (!this.currentSlide.file) {
				this.currentSlide.errors.file.status = true;
				this.currentSlide.errors.file.value = "Поле не может быть пустым";
				return false;
			}

			/* Проверка на тип загруженного файла */
			if (this.currentSlide.file.type !== "image/png") {
				this.currentSlide.errors.file.value = "Недопустимый тип файла.";
				this.currentSlide.errors.file.status = true;
				return false;
			}

			this.currentSlide.errors.file.status = false;
			this.currentSlide.errors.file.value = "";
			return true;
		},
		// Проверка всех полей
		checkModalInputs(name, link, file) {
			let errors = 0;

			if (name) {
				if (!this.checkSliderName()) errors++;
			}
			if (link) {
				if (!this.checkSliderLink()) errors++;
			}
			if (file) {
				if (!this.checkSliderFile()) errors++;
			}

			if (errors !== 0) return false;
			else return true;
		},
		// Очистка состояния редактирования
		clearModalInputsEdited() {
			for (let key in this.currentSpecialist.data) {
				this.currentSpecialist.data[key].edited = false;
			}
		},
		// Очистка состояния редактирования
		clearModalInputsErrors() {
			for (let key in this.currentSpecialist.errors) {
				this.currentSlide.errors[key].status = false;
			}
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
				this.currentSpecialist.data[key].body = specialist[key];
			}

			this.openModal("edit");
		},
		// Удаление выбранного доктора
		removeSpecialist(specialist) {
			console.log(specialist);
		},
		// Обновление данных выбранного доктора после редактирования в массиве this.specialists
		updateSpecialist() {
			console.log('Обновление данных.');

			/* Получение текущего объекта из массива this.specialists */
			let resultSpecialistCurrent = this.specialists.filter((specialist) => {
				if (specialist.id === this.currentSpecialist.data.id.body) {
					return specialist;
				}
			});
			let filteredSpecialistCurrent = resultSpecialistCurrent[0];

			/* Изменение выбранного доктора в массиве this.specialists */
			for (let key in filteredSpecialistCurrent) {
				filteredSpecialistCurrent[key] = this.currentSpecialist.data[key].body;
			}
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

				// Удаление свойства url
				for (let key in this.specialists) {
					delete this.specialists[key].url;
				}

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
