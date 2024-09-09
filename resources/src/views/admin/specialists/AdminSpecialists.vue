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
				:class="{ error: currentSpecialist.errors.file.status }"
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
						:class="{ error: currentSpecialist.errors.name.status }"
						@input="currentSpecialist.data.name.edited = true"
						@blur="checkModalInput('name', 'text')"
					/>
					<span-error v-if="currentSpecialist.errors.name.status">
						{{ currentSpecialist.errors.name.value }}
					</span-error>
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
						:class="{
							error: currentSpecialist.errors.specialization.status,
						}"
						@input="currentSpecialist.data.specialization.edited = true"
						@blur="checkModalInput('specialization', 'text')"
					/>
					<span-error
						v-if="currentSpecialist.errors.specialization.status"
					>
						{{ currentSpecialist.errors.specialization.value }}
					</span-error>
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
						:class="{
							error: currentSpecialist.errors.startWorkAge.status,
						}"
						@input="currentSpecialist.data.startWorkAge.edited = true"
						@blur="checkModalInput('startWorkAge', 'text')"
					/>
					<span-error v-if="currentSpecialist.errors.startWorkAge.status">
						{{ currentSpecialist.errors.startWorkAge.value }}
					</span-error>
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
						:class="{ error: currentSpecialist.errors.education.status }"
						@input="currentSpecialist.data.education.edited = true"
						@blur="checkModalInput('education', 'text')"
					>
					</textarea>
					<span-error v-if="currentSpecialist.errors.education.status">
						{{ currentSpecialist.errors.education.value }}
					</span-error>
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
						:class="{
							error: currentSpecialist.errors.advancedTraining.status,
						}"
						@input="currentSpecialist.data.advancedTraining.edited = true"
						@blur="checkModalInput('advancedTraining', 'text')"
					>
					</textarea>
					<span-error
						v-if="currentSpecialist.errors.advancedTraining.status"
					>
						{{ currentSpecialist.errors.advancedTraining.value }}
					</span-error>
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
						:class="{
							error: currentSpecialist.errors.certificates.status,
						}"
						@input="currentSpecialist.data.certificates.edited = true"
						@blur="checkModalInput('certificates', 'text')"
					>
					</textarea>
					<span-error v-if="currentSpecialist.errors.certificates.status">
						{{ currentSpecialist.errors.certificates.value }}
					</span-error>
				</article>
			</div>
		</template>
		<template #footer>
			<BlockButtons v-if="modal.type == 'edit'">
				<ButtonRemove> Удалить </ButtonRemove>
				<ButtonDefault @click="updateSpecialist"> Обновить </ButtonDefault>
			</BlockButtons>
			<BlockButtons v-if="modal.type == 'create'">
				<ButtonDefault @click="addSpecialist"> Создать </ButtonDefault>
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
			@touchEditSpecialist="openModal"
			@touchHideSpecialist="hideSpecialist"
			@removeSpecialist="removeSpecialist"
			@useFilter="filterSpecialists"
		/>

		<block-buttons>
			<button-default @click="openModal('create', null)">
				Добавить
			</button-default>
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
import SpanError from "../../../components/ui/admin/SpanError.vue";

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
		SpanError,
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
		openModal(type, specialist) {
			switch (type) {
				case "create":
					this.currentSpecialist.data.create.body = true;
					this.clearCurrentSpecialistData();

					this.modal.type = type;
					this.modal.title = "Создание";
					this.modal.status = true;
					break;
				case "edit":
					this.clearCurrentSpecialistData();

					// Заполнение модального окна данными о специалисте
					for (let key in specialist) {
						this.currentSpecialist.data[key].body = specialist[key];
					}

					this.modal.type = type;
					this.modal.title = "Редактирование";
					this.modal.status = true;
					break;
				default:
					let debbugStory = {
						title: "Ошибка.",
						body: "Низвестный тип открытия модального окна.",
						type: "Error",
					};
					this.$store.commit("debuggerState", debbugStory);

					break;
			}
			document.body.classList.toggle("modal-open");
		},
		// Закрытие модального окна с доктором
		closeModal() {
			this.modal.status = false;
			document.body.classList.toggle("modal-open");

			this.clearCurrentSpecialistEdited();
			this.clearCurrentSpecialistErrors();
		},
		/* _____________________________________________________*/
		/* 2. Работа с полями ввода модального окна             */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		// Проверка введенного значения
		checkInputText(value) {
			/* Проверка на пустую строку */
			if (value == "") {
				return {
					status: true,
					message: "Поле не может быть пустым.",
				};
			}

			/* Проверка на соответствие типу string */
			if (typeof value !== "string") {
				return {
					status: true,
					message: "Тип данных не совпадает.",
				};
			}

			return {
				status: false,
				message: "Ошибок нет.",
			};
		},
		// Проверка поля имени
		checkModalInput(dataKey, inputType) {
			let errorLog = {};
			switch (inputType) {
				case "text":
					errorLog = this.checkInputText(
						this.currentSpecialist.data[dataKey].body
					);
					break;
				default:
					break;
			}

			if (errorLog.status) {
				this.currentSpecialist.errors[dataKey].value = errorLog.message;
				this.currentSpecialist.errors[dataKey].status = true;

				return true;
			} else {
				this.currentSpecialist.errors[dataKey].value = "";
				this.currentSpecialist.errors[dataKey].status = false;

				return false;
			}
		},
		// Проверка всех полей ввода модального окна
		checkModalInputsAll(inputKeys) {
			let errorCount = 0;
			for (let i = 0; i < inputKeys.length; i++) {
				switch (inputKeys[i]) {
					// Для поля файл
					case "file":
						/* Присваивание данных поля ввода файла пользователем в переменную */
						let file = this.$refs.fileUpload.files[0];
						/* Проверка на загрузку файла пользователем */
						if (!file) {
							let debbugStory = {
								title: "Ошибка.",
								body: "Вы не загрузили изображение специалиста.",
								type: "Error",
							};
							this.$store.commit("debuggerState", debbugStory);
							errorCount++;
							this.currentSpecialist.errors.file.status = true;

							continue;
						}

						/* Проверка на тип загруженного файла */
						if (file.type !== "image/png") {
							let debbugStory = {
								title: "Ошибка.",
								body: "Разрешенный формат файла: png.",
								type: "Error",
							};
							this.$store.commit("debuggerState", debbugStory);
							errorCount++;
							this.currentSpecialist.errors.file.status = true;

							continue;
						}

						/* Проверка на размер загруженного файла */
						let fileSize = file.size / 1024 / 1024;
						if (fileSize > 10) {
							let debbugStory = {
								title: "Ошибка.",
								body: "Превышен размер загружаемого файла (не более 10 МБ).",
								type: "Error",
							};
							this.$store.commit("debuggerState", debbugStory);
							errorCount++;
							this.currentSpecialist.errors.file.status = true;

							continue;
						}
						break;
					// Для всех остальных полей
					default:
						if (this.checkModalInput(inputKeys[i], "text")) {
							errorCount++;
						}
						break;
				}
			}

			if (errorCount > 0) {
				return true;
			} else {
				return false;
			}
		},
		// Очистка состояния редактирования
		clearCurrentSpecialistData() {
			outer: for (let key in this.currentSpecialist.data) {
				if (key == "hide" || key == "create" || key == "delete") {
					continue outer;
				}

				this.currentSpecialist.data[key].body = "";
			}
		},
		// Очистка состояния редактирования
		clearCurrentSpecialistEdited() {
			for (let key in this.currentSpecialist.data) {
				this.currentSpecialist.data[key].edited = false;
			}
		},
		// Очистка состояния редактирования
		clearCurrentSpecialistErrors() {
			for (let key in this.currentSpecialist.errors) {
				this.currentSpecialist.errors[key].status = false;
			}
		},
		/* _____________________________________________________*/
		/* 3. Фильтрация                                        */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		filterSpecialists(type) {
			if (type == "id") {
				this.specialists.sort((a, b) => {
					b - a;
				});
				console.log("id");
			}

			if (type == "name") {
				this.specialists.sort((a, b) => {
					a + b;
				});
				console.log("name");
			}

			if (type == "specialization") {
				this.specialists.sort((a, b) => {
					a + b;
				});
				console.log("specialization");
			}
		},
		/* _____________________________________________________*/
		/* 4. Добавление, изменение, удаление                   */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		// Добавление нового доктора к массиву this.specialists
		addSpecialist() {
			if (
				this.checkModalInputsAll([
					"name",
					"specialization",
					"startWorkAge",
					"education",
					"advancedTraining",
					"certificates",
					"file",
				])
			)
				return;

			// Поиск максимального id
			let maxId = 0;
			for (let key in this.specialists) {
				if (this.specialists[key].id > maxId) {
					maxId = this.specialists[key].id;
				}
			}

			this.specialists.push({
				id: 1 + maxId,
				name: this.currentSpecialist.data.name.body,
				specialization: this.currentSpecialist.data.specialization.body,
				startWorkAge: this.currentSpecialist.data.startWorkAge.body,
				education: this.currentSpecialist.data.education.body,
				advancedTraining: this.currentSpecialist.data.advancedTraining.body,
				certificates: this.currentSpecialist.data.certificates.body,
				hide: false,
				filename: "vlasov.png",
			});
		},
		// Скрытие выбранного доктора
		hideSpecialist(selectedSpecialist) {
			let specialistToHide = this.specialists.filter((specialist) => {
				if (selectedSpecialist.id === specialist.id) {
					return specialist;
				}
			});
			specialistToHide[0].hidden = true;
			console.log(this.specialists);
		},
		// Удаление выбранного доктора
		removeSpecialist(specialist) {
			console.log(specialist);
		},
		// Обновление данных выбранного доктора после редактирования в массиве this.specialists
		updateSpecialist() {
			// Проверка на заполненность полей ввода
			if (
				this.checkModalInputsAll([
					"name",
					"specialization",
					"startWorkAge",
					"education",
					"advancedTraining",
					"certificates",
				])
			) {
				return;
			}

			/* Получение текущего объекта из массива this.specialists */
			let resultSpecialistCurrent = this.specialists.filter((specialist) => {
				if (specialist.id === this.currentSpecialist.data.id.body) {
					return specialist;
				}
			});
			let filteredSpecialistCurrent = resultSpecialistCurrent[0];

			/* Изменение выбранного доктора в массиве this.specialists */
			for (let key in filteredSpecialistCurrent) {
				filteredSpecialistCurrent[key] =
					this.currentSpecialist.data[key].body;
			}

			/* Присваивание данных поля ввода файла пользователем в переменную */
			this.currentSpecialist.file = this.$refs.fileUpload.files[0];

			/* Проверка на загрузку файла пользователем */
			if (!this.currentSpecialist.file) return this.closeModal();

			/* Проверка на тип загруженного файла */
			if (this.currentSpecialist.file.type !== "image/png") {
				let debbugStory = {
					title: "Ошибка.",
					body: "Разрешенный формат файла: png.",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
				return;
			}

			/* Проверка на размер загруженного файла */
			let fileSize = this.currentSpecialist.file.size / 1024 / 1024;
			if (fileSize > 10) {
				let debbugStory = {
					title: "Ошибка.",
					body: "Превышен размер загружаемого файла (не более 10 МБ).",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
				return;
			}

			/* Загрузка файла */
			let formData = new FormData();
			formData.append("image", this.currentSpecialist.file);
			formData.append("type", "specialist");

			axios({
				method: "post",
				url: `${this.$store.state.axios.urlApi}` + `upload-file`,
				headers: {
					"Content-Type": "multipart/form-data",
					Authorization: `Bearer ${localStorage.getItem("token")}`,
				},
				data: formData,
			})
				.then((response) => {
					console.log(response.data);
					this.currentSpecialist.data.path.body = response.data;
					filteredSpecialistCurrent.path = response.data;
					filteredSpecialistCurrent.filename = response.data.replace(
						"/storage/specialists/",
						""
					);
				})
				.catch((error) => {
					let debbugStory = {
						title: "Ошибка.",
						body: "Произошла ошибка при загрузке файла.",
						type: "Error",
					};
					this.$store.commit("debuggerState", debbugStory);
					return;
				});
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

.modal-img-input.error {
	background-color: var(--input-background-color-error);
	border: 2px solid var(--input-border-color-error);

	caret-color: red;
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

.modal-img-input.error::file-selector-button {
	background-color: var(--input-border-color-error);
	border: 2px solid var(--input-border-color-error);

	caret-color: red;
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

.modal-body-inputs > article > input.error {
	background-color: var(--input-background-color-error);
	border: 2px solid var(--input-border-color-error);

	caret-color: red;
}

.modal-body-inputs > article > input.error:focus {
	border: 2px solid var(--input-border-color-error);
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

.modal-body-inputs > article > textarea.error {
	background-color: var(--input-background-color-error);
	border: 2px solid var(--input-border-color-error);

	caret-color: red;
}

.modal-body-inputs > article > textarea.error:focus {
	border: 2px solid var(--input-border-color-error);
}
</style>
