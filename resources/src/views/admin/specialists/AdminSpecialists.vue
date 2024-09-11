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
						ФИО доктора*
						<span v-if="currentSpecialist.data.name.edited"> (Изменено) </span>
					</element-input-label>
					<input
						type="text"
						ref="inputName"
						placeholder="введите фио"
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
						<span v-if="currentSpecialist.data.specialization.edited"> (Изменено) </span>
					</element-input-label>
					<input
						type="text"
						ref="inputSpecialization"
						placeholder="введите специализацию"
						v-model="currentSpecialist.data.specialization.body"
						:class="{
							error: currentSpecialist.errors.specialization.status,
						}"
						@input="currentSpecialist.data.specialization.edited = true"
						@blur="checkModalInput('specialization', 'text')"
					/>
					<span-error v-if="currentSpecialist.errors.specialization.status">
						{{ currentSpecialist.errors.specialization.value }}
					</span-error>
				</article>
				<!-- Дата начала работы -->
				<article>
					<element-input-label>
						Дата начала работы*
						<span v-if="currentSpecialist.data.startWorkAge.edited"> (Изменено) </span>
					</element-input-label>
					<input
						type="date"
						ref="inputStartWorkAge"
						placeholder="введите дату"
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
				<!-- Повышение квалификации -->
				<article>
					<element-input-label>
						Узкопрофильная специализация*
						<span v-if="currentSpecialist.data.specializationAdvanced.edited">
							(Изменено)
						</span>
					</element-input-label>
					<textarea
						rows="4"
						ref="inputEducation"
						placeholder="введите специализацию"
						v-model="currentSpecialist.data.specializationAdvanced.body"
						:class="{
							error: currentSpecialist.errors.specializationAdvanced.status,
						}"
						@input="currentSpecialist.data.specializationAdvanced.edited = true"
						@blur="checkModalInput('specializationAdvanced', 'text')"
					>
					</textarea>
					<span-error v-if="currentSpecialist.errors.specializationAdvanced.status">
						{{ currentSpecialist.errors.specializationAdvanced.value }}
					</span-error>
				</article>
				<!-- Сертификаты -->
				<article>
					<element-input-label>
						Обучение и квалифиция*
						<span v-if="currentSpecialist.data.education.edited"> (Изменено) </span>
					</element-input-label>
					<textarea
						rows="4"
						ref="inputStartWorkAge"
						placeholder="введите обучение и квалифицию"
						v-model="currentSpecialist.data.education.body"
						:class="{
							error: currentSpecialist.errors.education.status,
						}"
						@input="currentSpecialist.data.education.edited = true"
						@blur="checkModalInput('education', 'text')"
					>
					</textarea>
					<span-error v-if="currentSpecialist.errors.education.status">
						{{ currentSpecialist.errors.education.value }}
					</span-error>
				</article>
				<!-- Ссылка на продокторов -->
				<article>
					<element-input-label>
						Ссылка на продокторов*
						<span> (Изменено) </span>
					</element-input-label>
					<input
						type="text"
						ref="inputLink"
						placeholder="введите ссылку"
						v-model="currentSpecialist.data.link.body"
						:class="{
							error: currentSpecialist.errors.link.status,
						}"
						@input="currentSpecialist.data.link.edited = true"
						@blur="checkModalInput('link', 'text')"
					/>
					<span-error v-if="currentSpecialist.errors.link.status">
						{{ currentSpecialist.errors.link.value }}
					</span-error>
				</article>
			</div>
		</template>
		<template #footer>
			<BlockButtons v-if="modal.type == 'edit'">
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
				<icon-save :width="28" :height="28" @click="saveSpecialistChanges" />
			</template>
		</block-title>

		<LoaderChild :isLoading="loading.loader.specialists"></LoaderChild>

		<admin-specialists-table
			v-if="loading.specialists"
			:specialists="specialists"
			@touchEditSpecialist="openModal"
			@touchHideSpecialist="hideSpecialist"
			@touchRemoveSpecialist="removeSpecialist"
			@useFilter="filterSpecialists"
		/>

		<block-buttons>
			<button-default @click="openModal('create', null)"> Добавить </button-default>
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
					specializationAdvanced: {
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
					specializationAdvanced: {
						body: "",
						edited: false,
					},
					link: {
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
					if (specialist.create) {
						this.modal.style.create = true;
					} else {
						this.modal.style.create = false;
					}

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
					errorLog = this.checkInputText(this.currentSpecialist.data[dataKey].body);
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

						this.currentSpecialist.errors.file.status = false;
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

			// Очистка полей ввода файла
			this.currentSpecialist.file = "";
			this.$refs.fileUpload.value = "";
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
		filterSpecialists(column, type) {
			switch (column) {
				case "id":
					if (type == "default") {
						this.specialists.sort((a, b) => {
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
						this.specialists.sort((a, b) => {
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
						this.specialists.sort((a, b) => {
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
						this.specialists.sort((a, b) => {
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
				case "hide":
					if (type == "default") {
						this.specialists.sort((a, b) => {
							if (a.hide > b.hide) {
								return 1;
							}
							if (a.hide < b.hide) {
								return -1;
							}
							// a должно быть равным b
							return 0;
						});
					}

					if (type == "reverse") {
						this.specialists.sort((a, b) => {
							if (a.hide < b.hide) {
								return 1;
							}
							if (a.hide > b.hide) {
								return -1;
							}
							// a должно быть равным b
							return 0;
						});
					}
					break;
				case "specialization":
					break;
				default:
					break;
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
					"specializationAdvanced",
					"link",
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

			/* Присваивание данных поля ввода файла пользователем в переменную */
			this.currentSpecialist.file = this.$refs.fileUpload.files[0];

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
					this.specialists.push({
						id: 1 + maxId,
						name: this.currentSpecialist.data.name.body,
						specialization: this.currentSpecialist.data.specialization.body,
						startWorkAge: this.currentSpecialist.data.startWorkAge.body,
						specializationAdvanced: this.currentSpecialist.data.specializationAdvanced.body,
						education: this.currentSpecialist.data.education.body,
						link: this.currentSpecialist.data.link.body,
						filename: response.data.replace("/storage/specialists/", ""),
						path: response.data,
						hide: false,
						create: true,
						delete: false,
					});
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
		// Скрытие выбранного доктора
		hideSpecialist(selectedSpecialist) {
			let specialistToHide = this.specialists.filter((specialist) => {
				if (selectedSpecialist.id === specialist.id) {
					return specialist;
				}
			});
			if (specialistToHide[0].hide === true) {
				specialistToHide[0].hide = false;
			} else {
				specialistToHide[0].hide = true;
			}
		},
		// Удаление выбранного доктора
		removeSpecialist(selectedSpecialist) {
			let specialistToDelete = this.specialists.filter((specialist) => {
				if (specialist.id === selectedSpecialist.id) {
					return specialist;
				}
			});

			if (specialistToDelete[0].delete == true) {
				specialistToDelete[0].delete = false;
			} else {
				specialistToDelete[0].delete = true;
			}
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
					"specializationAdvanced",
					"link",
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
				filteredSpecialistCurrent[key] = this.currentSpecialist.data[key].body;
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
				this.currentSpecialist.errors.file.status = true;
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
				this.currentSpecialist.errors.file.status = true;
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
		/* _____________________________________________________*/
		/* 5. Сохранение                                        */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		saveSpecialistChanges() {
			let specialists = [];

			// Заполнение нового массива данными из массива this.specialists
			for (let index = 0; index < this.specialists.length; index++) {
				specialists.push(Object.assign({}, this.specialists[index]));
			}

			// Сортировка массива по id
			specialists.sort((a, b) => {
				if (a.id < b.id) return -1;
				if (a.id > b.id) return 1;
				return 0;
			});

			// Получение массива докторов с сервера
			axios({
				method: "post",
				headers: {
					Accept: "application/json",
					Authorization: `Bearer ${localStorage.getItem("token")}`,
				},
				data: {
					specialists: specialists,
				},
				url: `${this.$store.state.axios.urlApi}` + `save-specialists-changes`,
			})
				.then((response) => {
					/* Обновление свойства create в массиве слайдов */
					for (let index in this.specialists) {
						if (this.specialists[index].create) {
							this.specialists[index].create = false;
						}
					}

					// Получения нового массива слайдов, помеченных на удаление
					let specialistsStatusDelete = this.specialists.filter((specialist) => {
						if (specialist.delete == true) {
							return Object.assign({}, specialist);
						}
					});

					// Повторять, пока не будут удалены все слайды, помеченные на удаление
					while (specialistsStatusDelete.length > 0) {
						this.specialists.splice(this.specialists.indexOf(specialistsStatusDelete[0]), 1);

						specialistsStatusDelete = this.specialists.filter((specialist) => {
							if (specialist.delete == true) {
								return Object.assign({}, specialist);
							}
						});
					}

					let debbugStory = {
						title: "Успешно!",
						body: "Данные о специалистах обновлены в базе данных.",
						type: "Completed",
					};
					this.$store.commit("debuggerState", debbugStory);
				})
				.catch((error) => {
					let debbugStory = {
						title: "Ошибка.",
						body: "Произошла ошибка при сохранении изменений.",
						type: "Error",
					};
					this.$store.commit("debuggerState", debbugStory);
					return;
				});
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
					this.specialists[key].create = false;
					this.specialists[key].delete = false;
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
