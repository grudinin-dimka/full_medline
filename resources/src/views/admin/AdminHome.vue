<template>
	<!-- Модальное окно -->
	<modal ref="modal" @closeModal="closeSlide" :modal="modal">
		<template #button-hide v-if="modal.type == 'edit'">
			<IconHide
				v-if="currentSlide.data.hide.body"
				:height="26"
				:width="26"
				@click="changeSlideHide"
			/>
			<IconVisible
				v-else="currentSlide.data.hide.body"
				:height="26"
				:width="26"
				@click="changeSlideHide"
			/>
		</template>
		<template #title v-if="modal.type == 'add'">
			Добавление нового слайда
		</template>
		<template #title v-if="modal.type == 'edit'">
			<icon-arrow
				:width="16"
				:height="16"
				:rotate="-90"
				@click="changeSlideOrder('down')"
			/>
			#{{ currentSlide.data.order.body }}
			<icon-arrow
				:width="16"
				:height="16"
				:rotate="90"
				@click="changeSlideOrder('up')"
			/>
		</template>
		<template #body>
			<div
				v-if="modal.type == 'edit'"
				class="modal-body-img"
				:style="{
					backgroundImage: `url(${currentSlide.data.path.body})`,
				}"
				ref="modalImg"
			></div>
			<div class="modal-body-inputs">
				<div class="modal-body-inputs-block">
					<block-label
						>Название
						<span v-if="currentSlide.data.name.edited"
							>(Изменено)</span
						></block-label
					>
					<article>
						<SlideUserCard :height="50" :width="50" />
						<input
							type="text"
							v-model="currentSlide.data.name.body"
							@input="currentSlide.data.name.edited = true"
							@blur="checkName"
							:class="{ error : currentSlide.errors.name.status }"
							placeholder="Название слайда"
						/>
					</article>
					<span v-if="currentSlide.errors.name.status">
						{{ currentSlide.errors.name.value }}
					</span>
				</div>
				<div class="modal-body-inputs-block">
					<block-label
						>Ссылка
						<span v-if="currentSlide.data.link.edited"
							>(Изменено)</span
						></block-label
					>
					<article>
						<SlideLink :height="50" :width="50" />
						<input
							type="text"
							v-model="currentSlide.data.link.body"
							@input="currentSlide.data.link.edited = true"
							@blur="checkLink"
							:class="{ error : currentSlide.errors.link.status }"
							placeholder="Ссылка слайда"
						/>
					</article>
					<span v-if="currentSlide.errors.link.status">
						{{ currentSlide.errors.link.value }}
					</span>
				</div>
				<div class="modal-body-inputs-block">
					<block-label>Загрузить новое фото (820x958)</block-label>
					<article>
						<SlidePath :height="50" :width="50" />
						<input
							type="file"
							ref="fileUpload"
							:class="{ erros: currentSlide.errors.file.status }"
							placeholder="Файл"
						/>
					</article>
					<span v-if="currentSlide.errors.file.status">
						{{ currentSlide.errors.file.value }}
					</span>
				</div>
			</div>
			<BlockButtons v-if="modal.type == 'edit'">
				<ButtonRemove @click.prevent="deleteSlide"> Удалить </ButtonRemove>
				<ButtonDefault @click.prevent="updateSlide">
					Обновить
				</ButtonDefault>
			</BlockButtons>
			<BlockButtons v-if="modal.type == 'add'">
				<ButtonDefault @click.prevent="createSlide">
					Добавить
				</ButtonDefault>
			</BlockButtons>
		</template>
	</modal>

	<info-bar>
		<template #title>Главная</template>
		<template #addreas>home</template>
	</info-bar>

	<!-- Слайдер -->
	<block>
		<block-title>
			<template #title>Слайдер</template>
			<template #buttons>
				<icon-save :width="28" :height="28" @click="saveSlidesChanges" />
			</template>
		</block-title>
		<div class="slider">
			<div
				v-for="slide in slides"
				:key="slide.id"
				class="slider-block"
				:class="{ hide: slide.hide }"
				@click="openSlide(slide, 'edit')"
			>
				<div
					v-bind:style="{
						'background-image': `url(${slide.path})`,
						height: '275px',
						'background-size': 'contain	',
						'background-position': 'center center',
						'background-repeat': 'no-repeat',
					}"
				></div>
				<div class="slider-block-id">#{{ slide.order }}</div>
				<div class="slider-block-info">
					<article>
						<SlideUserCard />
						<label>
							{{ slide.name }}
						</label>
					</article>
					<article>
						<SlideLink />
						<label>
							{{ slide.link }}
						</label>
					</article>
					<article>
						<SlidePath />
						<label> {{ slide.path }} </label>
					</article>
				</div>
			</div>
		</div>
		<BlockButtons>
			<ButtonDefault @click="openSlide(null, 'add')">
				Добавить
			</ButtonDefault>
		</BlockButtons>
	</block>

	<!-- Футер -->
	<block>
		<block-title>
			<template #title>Футер</template>
			<template #buttons>
				<icon-save :width="28" :height="28" @click="saveFooterChanges" />
			</template>
		</block-title>
		<!-- Заголовок -->
		<block-label>
			Заголовок <span v-if="footer.title.edited">(Изменено)</span>
		</block-label>
		<div class="block-textarea">
			<textarea
				rows="4"
				placeholder="Заголовок"
				v-model="footer.title.body"
				@input="controlSymbols('title')"
			>
			</textarea>
			<span
				>{{ footer.title.symbolsCount }}/{{
					footer.title.symbolsCountMax
				}}</span
			>
		</div>
		<!-- Дополнение к заголовку -->
		<block-label>
			Дополнение к заголовку
			<span v-if="footer.titleDesc.edited">(Изменено)</span></block-label
		>
		<div class="block-textarea">
			<textarea
				rows="10"
				placeholder="Заголовок"
				v-model="footer.titleDesc.body"
				@input="controlSymbols('titleDesc')"
			>
			</textarea>
			<span
				>{{ footer.titleDesc.symbolsCount }}/{{
					footer.titleDesc.symbolsCountMax
				}}</span
			>
		</div>
		<!-- Лицензия -->
		<block-label>
			Лицензия
			<span v-if="footer.license.edited">(Изменено)</span></block-label
		>
		<div class="block-textarea">
			<textarea
				rows="4"
				placeholder="Заголовок"
				v-model="footer.license.body"
				@input="controlSymbols('license')"
			>
			</textarea>
			<span
				>{{ footer.license.symbolsCount }}/{{
					footer.license.symbolsCountMax
				}}</span
			>
		</div>
		<!-- Основной текст -->
		<block-label>
			Основной текст
			<span v-if="footer.licenseDesc.edited">(Изменено)</span></block-label
		>
		<div class="block-textarea">
			<textarea
				rows="10"
				placeholder="Основной текст"
				v-model="footer.licenseDesc.body"
				@input="controlSymbols('licenseDesc')"
			>
			</textarea>
			<span
				>{{ footer.licenseDesc.symbolsCount }}/{{
					footer.licenseDesc.symbolsCountMax
				}}</span
			>
		</div>
		<!-- Подвал -->
		<block-label>
			Подвал <span v-if="footer.footer.edited">(Изменено)</span></block-label
		>
		<div class="block-textarea">
			<textarea
				rows="10"
				placeholder="Подвал"
				v-model="footer.footer.body"
				@input="controlSymbols('footer')"
			>
			</textarea>
			<span>
				{{ footer.footer.symbolsCount }}/{{ footer.footer.symbolsCountMax }}
			</span>
		</div>
	</block>
</template>

<script>
import axios from "axios";

import Modal from "../../components/includes/admin/AdminModal.vue";
import InfoBar from "../../components/ui/InfoBar.vue";

import BlockTitle from "../../components/ui/BlockTitle.vue";
import Block from "../../components/ui/Block.vue";
import BlockLabel from "../../components/ui/BlockLabel.vue";
import BlockButtons from "../../components/ui/BlockButtons.vue";

import ButtonDefault from "../../components/ui/ButtonDefault.vue";
import ButtonRemove from "../../components/ui/ButtonRemove.vue";

import SlideUserCard from "../../components/icons/SlideUserCard.vue";
import SlideLink from "../../components/icons/SlideLink.vue";
import SlidePath from "../../components/icons/SlidePath.vue";

import IconArrow from "../../components/icons/IconArrow.vue";
import IconHide from "../../components/icons/IconHide.vue";
import IconVisible from "../../components/icons/IconVisible.vue";
import IconSave from "../../components/icons/IconSave.vue";

export default {
	components: {
		axios,
		Modal,
		InfoBar,
		Block,
		BlockTitle,
		BlockButtons,
		BlockLabel,
		ButtonDefault,
		ButtonRemove,
		SlideUserCard,
		SlideLink,
		SlidePath,
		IconArrow,
		IconHide,
		IconVisible,
		IconSave,
	},
	data() {
		return {
			url: {
				images: import.meta.env.VITE_SOME_URL,
				public: "http://127.0.0.1:5173/storage/app/public/img",
			},
			slides: [],
			modal: {
				status: false,
				type: null,
			},
			currentSlide: {
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
					link: {
						body: null,
						edited: false,
					},
					name: {
						body: null,
						edited: false,
					},
					filename: {
						body: null,
						edited: false,
					},
					path: {
						body: null,
						edited: false,
					},
					order: {
						body: null,
						edited: false,
					},
					hide: {
						body: null,
					},
				},
			},
			footer: {
				title: {
					body: null,
					symbolsCount: 0,
					symbolsCountMax: 500,
					edited: false,
				},
				titleDesc: {
					body: null,
					symbolsCount: 0,
					symbolsCountMax: 1000,
					edited: false,
				},
				license: {
					body: null,
					symbolsCount: 0,
					symbolsCountMax: 500,
					edited: false,
				},
				licenseDesc: {
					body: null,
					symbolsCount: 0,
					symbolsCountMax: 1000,
					edited: false,
				},
				footer: {
					body: null,
					symbolsCount: 0,
					symbolsCountMax: 1000,
					edited: false,
				},
			},
		};
	},
	methods: {
		/* -------------------------------------*/
		/* --------Проверки полей ввода---------*/
		/* -------------------------------------*/
		/* Проверка поля имени */
		checkName() {
			// Пустота
			if (this.currentSlide.data.name.body === "") {
				this.currentSlide.errors.name.status = true;
				this.currentSlide.errors.name.value = "Поле не может быть пустым";
				return true;
			}

			this.currentSlide.errors.name.status = false;
			this.currentSlide.errors.name.value = "";
			return false;
		},

		/* Проверка поля ссылки */
		checkLink() {
			if (this.currentSlide.data.link.body === "") {
				this.currentSlide.errors.link.status = true;
				this.currentSlide.errors.link.value = "Поле не может быть пустым";
				return true;
			};

			this.currentSlide.errors.link.status = false;
			this.currentSlide.errors.link.value = "";
			return false;
		},

		/* Проверка поля Файл */
		checkPassword() {
			// Пустота
			if (this.password === "") {
				this.errors.password.status = true;
				this.errors.password.value = "Поле не может быть пустым";
				return true;
			}

			this.errors.password.status = false;
			this.errors.password.value = "";
			return false;
		},

		/* Проверка всех полей */
		checkAllInputs() {
			let errors = 0;

			if (this.checkName()) errors++;
			if (this.checkPassword()) errors++;

			if (errors !== 0) return false;
			else return true;
		},

		/* -------------------------------------*/
		/* ---------------Слайдер---------------*/
		/* -------------------------------------*/
		/* Сортировка списка слайдов по порядку */
		sortSlider() {
			this.slides.sort((a, b) => a.order - b.order);
		},
		/* Получение ссылки к динамичному изображению */
		getImagePathGlob(path) {
			const images = import.meta.glob("/storage/app/public/img/*.png", {
				eager: true,
			});

			const imagePath = `/storage/app/public/img/${path}.png`;
			if (images[imagePath]) {
				return images[imagePath].default;
			} else {
				const imagePath = `/storage/app/public/img/default.png`;
				return images[imagePath].default;
			}
		},
		/* Открытие слайда */
		openSlide(selectedSlide, type) {
			document.body.classList.toggle("modal-open");
			try {
				if (type == "edit") {
					for (let key in selectedSlide) {
						this.currentSlide.data[key].body = selectedSlide[key];
					}
					this.modal.status = true;
					this.modal.type = "edit";
					this.currentSlide.status = true;
				} else if (type == "add") {
					this.modal.status = true;
					this.modal.type = type;
					this.currentSlide.status = true;
				}
			} catch (error) {
				let debbugStory = {
					title: "Ошибка.",
					body: "Не удаётся открыть выбранный слайд.",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
			}
		},
		/* Закрытие слайда */
		closeSlide() {
			document.body.classList.toggle("modal-open");
			for (let key in this.currentSlide.data) {
				this.currentSlide.data[key].body = null;
			}
			this.modal.status = false;
			this.clearSlideEdited();
		},
		/* Изменение состояния скрытия выбранного слайда */
		changeSlideHide() {
			try {
				/* Фильтрация массива с объектми до нашего объекта в новый массив */
				let resultSlideCurrent = this.slides.filter(
					(slide) => slide.order === this.currentSlide.data.order.body
				);
				/* Получение нужного объекта */
				let filteredSlideCurrent = resultSlideCurrent[0];
				// Проверка на состояние скрытия
				if (this.currentSlide.data.hide.body) {
					filteredSlideCurrent.hide = false;
					this.currentSlide.data.hide.body = false;
				} else {
					filteredSlideCurrent.hide = true;
					this.currentSlide.data.hide.body = true;
				}
			} catch (error) {
				let debbugStory = {
					title: "Ошибка.",
					body: "Не удалось обновить состояние скрытия выбранного слайда.",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
			}
		},
		/* Изменение порядка выбранного слайда */
		changeSlideOrder(type) {
			try {
				/* Объявление переменных предидущего слайда */
				let resultSlidePrevious = [null];
				let filteredSlidePrevious = [null];
				// Проверка на то, является ли текущий слайд первым
				let firstSlideStatus = this.currentSlide.data.order.body == 1;

				if (firstSlideStatus) {
					resultSlidePrevious = this.slides.filter(
						(slide) => slide.order === this.slides.length
					);
					filteredSlidePrevious = resultSlidePrevious[0];
				} else {
					resultSlidePrevious = this.slides.filter(
						(slide) =>
							slide.order === this.currentSlide.data.order.body - 1
					);
					filteredSlidePrevious = resultSlidePrevious[0];
				}

				/* Фильтрация массива с объектми до нашего объекта в новый массив */
				let resultSlideCurrent = this.slides.filter(
					(slide) => slide.order === this.currentSlide.data.order.body
				);
				/* Получение текущего объекта */
				let filteredSlideCurrent = resultSlideCurrent[0];

				// Проверка на то, является ли текущий слайд последним
				let lastSlideStatus =
					this.currentSlide.data.order.body == this.slides.length;

				/* Объявление переменных следующего слайда */
				let resultSlideNext = [null];
				let filteredSlideNext = [null];
				if (lastSlideStatus) {
					resultSlideNext = this.slides.filter(
						(slide) => slide.order === 1
					);
					filteredSlideNext = resultSlideNext[0];
				} else {
					resultSlideNext = this.slides.filter(
						(slide) =>
							slide.order === this.currentSlide.data.order.body + 1
					);
					filteredSlideNext = resultSlideNext[0];
				}

				// Изменение порядка
				if (type === "up") {
					if (lastSlideStatus) {
						this.currentSlide.data.order.body = 1;
						filteredSlideCurrent.order = 1;
						filteredSlideNext.order = this.slides.length;
						this.sortSlider();
					} else {
						this.currentSlide.data.order.body++;
						filteredSlideCurrent.order++;
						filteredSlideNext.order--;
						this.sortSlider();
					}
				} else if (type === "down") {
					if (firstSlideStatus) {
						this.currentSlide.data.order.body = this.slides.length;
						filteredSlideCurrent.order = this.slides.length;
						filteredSlidePrevious.order = 1;
						this.sortSlider();
					} else {
						this.currentSlide.data.order.body--;
						filteredSlideCurrent.order--;
						filteredSlidePrevious.order++;
						this.sortSlider();
					}
				}
			} catch (error) {
				let debbugStory = {
					title: "Ошибка.",
					body: "Не удалось обновить порядок выбранного слайда.",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
			}
		},
		/* Очистка состояния редактирования */
		clearSlideEdited() {
			for (let key in this.currentSlide.data) {
				this.currentSlide.data[key].edited = false;
			}
		},
		/* Обновление данных слайда по данным из модального окна */
		updateSlide() {
			/* Получение текущего объекта из массива this.slides */
			let resultSlideCurrent = this.slides.filter(
				(slide) => slide.order === this.currentSlide.data.order.body
			);
			let filteredSlideCurrent = resultSlideCurrent[0];

			for (let key in filteredSlideCurrent) {
				if (key == "name" || key == "link") {
					filteredSlideCurrent[key] = this.currentSlide.data[key].body;
				} else if (key == "hide") {
					filteredSlideCurrent[key] = this.currentSlide.data[key].body;
				}
			}

			/* Присваивание данных поля ввода файла пользователем в переменную */
			this.currentSlide.file = this.$refs.fileUpload.files[0];

			/* Проверка на загрузку файла пользователем */
			if (!this.currentSlide.file) return this.closeSlide();

			/* Проверка на тип загруженного файла */
			if (this.currentSlide.file.type !== "image/png") {
				this.currentSlide.errors.file.value = "Недопустимый тип файла.";
				this.currentSlide.errors.file.status = true;
				return;
			}
			/* Загрузка файла */
			this.currentSlide.file = this.$refs.fileUpload.files[0];
			let formData = new FormData();
			formData.append("image", this.currentSlide.file);

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
					let debbugStory = {
						title: "Успешно!",
						body: "Картинка успешно загружена.",
						type: "Completed",
					};
					this.currentSlide.data.path.body = response.data;
					filteredSlideCurrent.path = response.data;
					filteredSlideCurrent.filename = response.data.substring(
						9,
						response.data.length
					);
				})
				.catch((error) => {
					console.log(error);
				});
			this.closeSlide();
		},
		/* Удаление выбранного слайда */
		deleteSlide() {
			console.log(this.currentSlide);
			console.log("delete");
		},
		/* Создание нового слайда */
		createSlide() {
			/* Присваивание данных поля ввода файла пользователем в переменную */
			this.currentSlide.file = this.$refs.fileUpload.files[0];

			/* Проверка на загрузку файла пользователем */
			if (!this.currentSlide.file) return console.log("Файл не загружен.");

			/* Проверка на тип загруженного файла */
			if (this.currentSlide.file.type !== "image/png") {
				this.currentSlide.errors.file.value = "Недопустимый тип файла.";
				this.currentSlide.errors.file.status = true;
				return;
			}
			/* Загрузка файла */
			this.currentSlide.file = this.$refs.fileUpload.files[0];
			let formData = new FormData();
			formData.append("image", this.currentSlide.file);

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
					let debbugStory = {
						title: "Успешно!",
						body: "Картинка успешно загружена.",
						type: "Completed",
					};

					this.slides.push({
						name: this.currentSlide.data.name.body,
						link: this.currentSlide.data.link.body,
						path: response.data,
						filename: response.data.substring(9, response.data.length),
						hide: false,
						order: 1 + this.slides[this.slides.length - 1].order,
					});
				})
				.catch((error) => {
					console.log(error);
				});

			console.log(this.slides);
		},
		/* Сохранение изменений в базе данных */
		saveSlidesChanges() {
			let dataSlides = [];
			/* Копирование данных слайдов */
			for (let i = 0; i < this.slides.length; i++) {
				dataSlides.push(Object.assign({}, this.slides[i]));
			}
			/* Удаление свойства path из данных */
			for (let i = 0; i < dataSlides.length; i++) {
				for (let key in dataSlides[i]) {
					delete dataSlides[i].path;
				}
			}
			axios({
				method: "post",
				url: `${this.$store.state.axios.urlApi}` + `save-slides-changes`,
				headers: {
					Accept: "application/json",
					Authorization: `Bearer ${localStorage.getItem("token")}`,
				},
				data: {
					slides: dataSlides,
				},
			})
				.then((response) => {
					let debbugStory = {
						title: "Успешно!",
						body: "Данные о слайдах обновлены в базе данных.",
						type: "Completed",
					};
					this.$store.commit("debuggerState", debbugStory);
				})
				.catch((error) => {
					let debbugStory = {
						title: "Ошибка.",
						body: "Что-то помешало обновить данные о слайдах в базе данных.",
						type: "Error",
					};
					this.$store.commit("debuggerState", debbugStory);
					console.log(error);
				});
		},

		/* -------------------------------------*/
		/* ----------Изменение футера-----------*/
		/* -------------------------------------*/
		// Очистка всех полей футера
		clearTextareaAll() {
			for (const obj in this.footer) {
				this.footer[obj].body = "";
				this.footer[obj].symbolsCount = 0;
			}
			return true;
		},
		// Очистка статуса изменения
		clearTextareaEdited() {
			for (const obj in this.footer) {
				this.footer[obj].edited = false;
			}
			return true;
		},
		// Очистка футера
		clearFooter() {
			this.clearTextareaAll();
			this.clearTextareaEdited();
			axios({
				method: "post",
				url: `${this.$store.state.axios.urlApi}` + `clear-footer`,
				headers: {
					Accept: "application/json",
					Authorization: `Bearer ${localStorage.getItem("token")}`,
				},
			})
				.then((response) => {
					let debbugStory = {
						title: "Успешно!",
						body: "Все поля подвала в базе данных очищены.",
						type: "Completed",
					};
					this.$store.commit("debuggerState", debbugStory);
				})
				.catch((error) => {
					let debbugStory = {
						title: "Ошибка.",
						body: error.response.data,
						type: "Error",
					};
					this.$store.commit("debuggerState", debbugStory);
				});
		},
		// Контроль количества символов
		controlSymbols(obj) {
			this.footer[`${obj}`].edited = true;

			// Проверка поля на максимальное количество символов
			if (
				this.footer[`${obj}`].body.length >=
				this.footer[`${obj}`].symbolsCountMax
			) {
				// Если больше, то обрезаем
				this.footer[`${obj}`].body = this.footer[`${obj}`].body.slice(
					0,
					this.footer[`${obj}`].symbolsCountMax
				);
			}

			// Отображение текущего количества символов
			this.footer[`${obj}`].symbolsCount = this.footer[`${obj}`].body.length;
		},
		// Сохранение всех полей
		saveFooterChanges() {
			this.clearTextareaEdited();

			// Сохранение данных в базу данных
			axios({
				method: "post",
				url: `${this.$store.state.axios.urlApi}` + `save-footer`,
				headers: {
					Accept: "application/json",
					Authorization: `Bearer ${localStorage.getItem("token")}`,
				},
				data: {
					title: this.footer.title.body,
					titleDesc: this.footer.titleDesc.body,
					license: this.footer.license.body,
					licenseDesc: this.footer.licenseDesc.body,
					footer: this.footer.footer.body,
				},
			})
				.then((response) => {
					let debbugStory = {
						title: "Успешно!",
						body: "Данные о футере обновлены в базе данных.",
						type: "Completed",
					};
					this.$store.commit("debuggerState", debbugStory);
				})
				.catch((error) => {
					let debbugStory = {
						title: "Ошибка.",
						body: "Данные о футере почему-то не сохранились.",
						type: "Error",
					};
					this.$store.commit("debuggerState", debbugStory);
				});
		},
	},
	mounted() {
		// Получение массива слайдов с сервера
		axios({
			method: "post",
			url: `${this.$store.state.axios.urlApi}` + `get-slides-all`,
		})
			.then((response) => {
				this.slides = response.data;
				this.sortSlider();
			})
			.catch((error) => {
				let debbugStory = {
					title: "Ошибка.",
					body: "Произошла ошибка при получении данных о слайдере.",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
			});

		// Получение массива данных о футере с сервера
		axios({
			method: "post",
			url: `${this.$store.state.axios.urlApi}` + `get-footer`,
		})
			.then((response) => {
				for (let key in response.data) {
					// Проверка полученного поля в объекте на пустоту
					if (response.data[key] !== null) {
						// Заполнение полей данными с сервера
						this.footer[key].body = response.data[key];
						this.footer[key].symbolsCount = response.data[key].length;
					}
				}
			})
			.catch((error) => {
				let debbugStory = {
					title: "Ошибка.",
					body: "Произошла ошибка при получении данных о футере.",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
			});
	},
};
</script>

<style scoped>
.modal-img {
	border: 2px solid var(--input-border-color-inactive);
	border-radius: 20px;
	width: 300px;
	align-self: center;
}

.modal-body-img {
	height: 350px;
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

.modal-body-inputs-block {
	display: flex;
	flex-direction: column;
	gap: 5px;
}

.modal-body-inputs-block > article {
	display: flex;
	justify-content: justify;
	gap: 10px;
}

.modal-body-inputs-block > article > input {
	box-sizing: border-box;
	outline: none;

	padding: 10px;
	border: 2px solid var(--input-border-color-inactive);
	border-radius: 10px;

	width: 100%;
	height: 58px;

	font-size: 20px;
	caret-color: var(--input-border-color-active);

	transition: all 0.2s;
}

.modal-body-inputs-block > article > input::file-selector-button {
	cursor: pointer;
	background-color: var(--button-default-color);
	border: 0px;
	border-radius: 5px;
	color: white;
	padding: 5px;
}

.modal-body-inputs-block > article > input::file-selector-button:hover {
	background-color: var(--button-default-color-hover);
}

.modal-body-inputs-block > article > input:focus {
	border: 2px solid var(--input-border-color-active);
}

.modal-body-inputs-block > article > input.error {
   background-color: var(--input-background-color-error);
   border: 2px solid var(--input-border-color-error);

   caret-color: red;
}

.modal-body-inputs-block > span {
	color: var(--span-color-error);
}

textarea {
	resize: none;
	box-sizing: border-box;
	outline: none;

	padding: 10px;
	border: 2px solid var(--input-border-color-inactive);
	border-radius: 10px;

	width: 100%;

	font-size: 18px;
	caret-color: var(--input-border-color-active);

	transition: all 0.2s;
}

textarea:focus {
	border: 2px solid var(--input-border-color-active);
}

.slider {
	display: grid;
	grid-template-columns: repeat(4, 1fr);
	gap: 20px;

	animation: show 1s;
}

.slider-block {
	cursor: pointer;
	display: flex;
	flex-direction: column;

	border: 2px solid var(--input-border-color-inactive);
	border-radius: 10px;
	padding: 10px;
	overflow: hidden;

	transition: all 0.2s;
}

.slider-block.hide {
	border: 2px solid rgb(210, 210, 210);
	background-color: rgb(230, 230, 230);
}

.slider-block:hover {
	border: 2px solid var(--input-border-color-active);
	background-color: #f2feff;
}

.slider-block-id {
	position: absolute;
}

.slider-block > img {
	border-radius: 10px;
	width: 200px;
	align-self: center;
}

.slider-block-info {
	display: flex;
	flex-direction: column;
	gap: 10px;
}

.slider-block-info > article {
	display: grid;
	grid-template-columns: 30px 1fr;
	align-items: center;
	gap: 10px;
}

.slider-block-info > article > label {
	word-break: break-all;
}

.block-textarea span {
	display: flex;
	flex-direction: column;
	align-items: flex-end;
}

@keyframes show {
	from {
		opacity: 0.5;
	}

	to {
		opacity: 1;
	}
}

@media screen and (max-width: 1900px) {
	.slider {
		grid-template-columns: repeat(3, 1fr);
	}
}

@media screen and (max-width: 1545px) {
	.slider {
		grid-template-columns: repeat(2, 1fr);
	}
}

@media screen and (max-width: 1200px) {
	.slider {
		grid-template-columns: repeat(1, 1fr);
	}
}

@media screen and (max-width: 1000px) {
	.slider {
		grid-template-columns: repeat(2, 1fr);
	}
}

@media screen and (max-width: 810px) {
	.slider {
		grid-template-columns: repeat(1, 1fr);
	}
}

@media screen and (max-width: 600px) {
	.slider-block {
		flex-direction: column;
	}

	.slider-block > img {
		flex-basis: 100px;
		width: 60%;
		margin-bottom: 10px;
	}
}

@media screen and (max-width: 460px) {
	textarea {
		font-size: 3.45vw;
	}

	.slider-block-info > article > label {
		font-size: 3.45vw;
	}
}
</style>
