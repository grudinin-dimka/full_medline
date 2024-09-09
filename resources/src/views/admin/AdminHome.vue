<template>
	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                  МОДАЛЬНОЕ ОКНО                   |-->
	<!--|___________________________________________________|-->
	<modal ref="modal" @closeModal="closeSlide" :modal="modal">
		<template
			#button-hide
			v-if="
				(modal.type == 'edit') &
				!currentSlide.data.delete.body &
				!currentSlide.data.create.body
			"
		>
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
		<template #title v-if="modal.style.create || modal.style.delete">
			{{ modal.title }}
		</template>
		<template
			#title
			v-if="
				(modal.type == 'edit') &
				!currentSlide.data.delete.body &
				!currentSlide.data.create.body
			"
		>
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
		<template #img>
			<div
				v-if="modal.type == 'edit'"
				class="modal-body-img"
				:style="{
					backgroundImage: `url(${currentSlide.data.path.body})`,
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
				:class="{ error: currentSlide.errors.file.status }"
				placeholder="Файл"
			/>
		</template>
		<template #body>
			<div class="modal-body-inputs">
				<div class="modal-body-inputs-block">
					<element-input-label>
						Название*
						<span v-if="currentSlide.data.name.edited"> (Изменено) </span>
					</element-input-label>
					<article>
						<SlideUserCard :height="50" :width="50" />
						<input
							type="text"
							v-model="currentSlide.data.name.body"
							ref="inputName"
							@input="currentSlide.data.name.edited = true"
							@blur="checkSliderName"
							:class="{ error: currentSlide.errors.name.status }"
							placeholder="Название слайда"
						/>
					</article>
					<span v-if="currentSlide.errors.name.status">
						{{ currentSlide.errors.name.value }}
					</span>
				</div>
				<div class="modal-body-inputs-block">
					<element-input-label>
						Ссылка*
						<span v-if="currentSlide.data.link.edited">(Изменено)</span>
					</element-input-label>
					<article>
						<SlideLink :height="50" :width="50" />
						<input
							type="text"
							v-model="currentSlide.data.link.body"
							ref="inputLink"
							@input="currentSlide.data.link.edited = true"
							@blur="checkSliderLink"
							:class="{ error: currentSlide.errors.link.status }"
							placeholder="Ссылка слайда"
						/>
					</article>
					<span v-if="currentSlide.errors.link.status">
						{{ currentSlide.errors.link.value }}
					</span>
				</div>
			</div>
		</template>
		<template #footer>
			<BlockButtons v-if="modal.type == 'edit'">
				<ButtonRemove
					v-if="
						!currentSlide.data.create.body &
						!currentSlide.data.delete.body
					"
					@click.prevent="markDeleteSlide"
				>
					Удалить
				</ButtonRemove>
				<ButtonDefault
					v-if="!currentSlide.data.delete.body"
					@click.prevent="updateSlide"
				>
					Обновить
				</ButtonDefault>
				<ButtonDefault
					v-if="currentSlide.data.delete.body"
					@click.prevent="markDeleteSlide"
				>
					Восстановить
				</ButtonDefault>
			</BlockButtons>
			<BlockButtons v-if="modal.type == 'create'">
				<ButtonDefault @click.prevent="createSlide">
					Создать
				</ButtonDefault>
			</BlockButtons>
		</template>
	</modal>

	<info-bar>
		<template #title>Главная</template>
		<template #addreas>home</template>
	</info-bar>

	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                      СЛАЙДЕР                      |-->
	<!--|___________________________________________________|-->
	<block>
		<block-title>
			<template #title>Слайдер</template>
			<template #buttons>
				<icon-save :width="28" :height="28" @click="saveSlidesChanges" />
			</template>
		</block-title>

		<LoaderChild :isLoading="loading.loader.slider"></LoaderChild>

		<div class="slider" v-if="loading.slider">
			<div
				v-for="slide in slides"
				:key="slide.id"
				class="slider-block"
				:class="{
					hide: slide.hide,
					create: slide.create,
					delete: slide.delete,
				}"
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
			<ButtonDefault @click="openSlide(null, 'create')">
				Добавить
			</ButtonDefault>
		</BlockButtons>
	</block>

	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                       ФУТЕР                       |-->
	<!--|___________________________________________________|-->
	<block>
		<block-title>
			<template #title>Футер</template>
			<template #buttons>
				<icon-save :width="28" :height="28" @click="saveFooterChanges" />
			</template>
		</block-title>

		<LoaderChild :isLoading="loading.loader.footer"></LoaderChild>

		<div class="footer-container" v-if="loading.footer">
			<!-- Заголовок -->
			<element-input-label>
				Заголовок <span v-if="footer.title.edited">(Изменено)</span>
			</element-input-label>
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
			<element-input-label>
				Дополнение к заголовку
				<span v-if="footer.titleDesc.edited"
					>(Изменено)</span
				></element-input-label
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
			<element-input-label>
				Лицензия
				<span v-if="footer.license.edited">(Изменено)</span>
			</element-input-label>
			<div class="block-textarea">
				<textarea
					rows="4"
					placeholder="Заголовок"
					v-model="footer.license.body"
					@input="controlSymbols('license')"
				>
				</textarea>
				<span>
					{{ footer.license.symbolsCount }}
					/
					{{ footer.license.symbolsCountMax }}
				</span>
			</div>
			<!-- Основной текст -->
			<element-input-label>
				Основной текст
				<span v-if="footer.licenseDesc.edited"> (Изменено) </span>
			</element-input-label>
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
			<element-input-label>
				Подвал
				<span v-if="footer.footer.edited"
					>(Изменено)</span
				></element-input-label
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
					{{ footer.footer.symbolsCount }}/{{
						footer.footer.symbolsCountMax
					}}
				</span>
			</div>
		</div>
	</block>
</template>

<script>
import axios from "axios";

import ElementInputLabel from "../../components/ui/admin/ElementInputLabel.vue";

import LoaderChild from "../../components/includes/LoaderChild.vue";

import Modal from "../../components/includes/admin/AdminModal.vue";
import InfoBar from "../../components/ui/admin/InfoBar.vue";

import BlockTitle from "../../components/ui/admin/BlockTitle.vue";
import Block from "../../components/ui/admin/Block.vue";
import BlockButtons from "../../components/ui/admin/BlockButtons.vue";

import ButtonDefault from "../../components/ui/admin/ButtonDefault.vue";
import ButtonRemove from "../../components/ui/admin/ButtonRemove.vue";

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
		ElementInputLabel,
		LoaderChild,
		Modal,
		InfoBar,
		Block,
		BlockTitle,
		BlockButtons,
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
			loading: {
				loader: {
					slider: true,
					footer: true,
				},
				slider: false,
				footer: false,
			},
			url: {
				images: import.meta.env.VITE_SOME_URL,
				public: "http://127.0.0.1:5173/storage/app/public/img",
			},
			slides: [],
			modal: {
				title: "",
				status: false,
				type: null,
				style: {
					create: false,
					delete: false,
				},
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
					id: {
						body: "",
						edited: false,
					},
					name: {
						body: "",
						edited: false,
					},
					link: {
						body: "",
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
					order: {
						body: "",
						edited: false,
					},
					hide: {
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
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                    СЛАЙДЕР                        |*/
		/* |___________________________________________________|*/
		/* _____________________________________________________*/
		/* 1. Работа с полями ввода                             */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		// Проверка поля имени
		checkSliderName() {
			if (this.currentSlide.data.name.body === "") {
				this.currentSlide.errors.name.status = true;
				this.currentSlide.errors.name.value = "Поле не может быть пустым";
				return false;
			}

			this.currentSlide.errors.name.status = false;
			this.currentSlide.errors.name.value = "";
			return true;
		},
		// Проверка поля ссылки
		checkSliderLink() {
			if (this.currentSlide.data.link.body === "") {
				this.currentSlide.errors.link.status = true;
				this.currentSlide.errors.link.value = "Поле не может быть пустым";
				return false;
			}

			this.currentSlide.errors.link.status = false;
			this.currentSlide.errors.link.value = "";
			return true;
		},
		// Проверка поля Файл
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
		checkAllInputs(name, link, file) {
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
		clearSlideEdited() {
			for (let key in this.currentSlide.data) {
				this.currentSlide.data[key].edited = false;
			}
		},
		// Очистка состояния редактирования
		clearSlideErrors() {
			for (let key in this.currentSlide.errors) {
				this.currentSlide.errors[key].status = false;
			}
		},
		/* _____________________________________________________*/
		/* 2. Модальное окно                                    */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		// Открытие модального окна
		openModal(type) {
			switch (type) {
				case "create":
					{
						this.modal.status = true;
						this.modal.type = "create";
						this.currentSlide.status = true;
					}
					break;
				case "edit":
					{
						this.modal.status = true;
						this.modal.type = "edit";
						this.currentSlide.status = true;
					}
					break;
			}
			document.body.classList.toggle("modal-open");
		},

		// Закрытие модального окна
		closeModal() {
			document.body.classList.toggle("modal-open");
			this.modal.status = false;
		},

		// Закрытие модального окна с выбранным слайдом
		closeSlide() {
			this.closeModal();
			this.clearSlideEdited();
			this.clearSlideErrors();
		},
		/* _____________________________________________________*/
		/* 3. Основные действия                                 */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		// Сортировка списка слайдов по порядку
		sortSlider() {
			this.slides.sort((a, b) => a.order - b.order);
		},
		// Открытие слайда
		openSlide(selectedSlide, type) {
			try {
				this.$refs.fileUpload.value = "";

				switch (type) {
					case "create":
						{
							for (let key in this.currentSlide.data) {
								this.currentSlide.data[key].body = "";
							}

							this.modal.style.create = true;
							this.modal.style.delete = false;
							this.modal.title = "Создание слайда";
							// Открытие модального окна
							this.openModal(type);
						}
						break;
					case "edit":
						{
							for (let key in selectedSlide) {
								this.currentSlide.data[key].body = selectedSlide[key];
							}

							// Проверка, создан ли слайд или уже имеется
							if (this.currentSlide.data.create.body === true) {
								this.modal.title = "Создание слайда";
								this.modal.style.create = true;
							} else {
								this.modal.title = "";
								this.modal.style.create = false;
							}

							// Проверка, помечен ли слайд на удаление
							if (this.currentSlide.data.delete.body === true) {
								this.modal.title = "Удаление слайда";
								this.modal.style.delete = true;
							} else {
								this.modal.title = "";
								this.modal.style.delete = false;
							}

							// Открытие модального окна
							this.openModal(type);
						}
						break;
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
		/* _____________________________________________________*/
		/* 4. Изменение состояний                               */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		// Изменение скрытия выбранного слайда
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
		// Изменение порядка выбранного слайда
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
				switch (type) {
					case "up":
						{
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
						}
						break;
					case "down":
						{
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
						break;
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
		/* _____________________________________________________*/
		/* 5. Сохранение, обновление, удаление                  */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		// Создание нового слайда
		createSlide() {
			try {
				/* Присваивание данных поля ввода файла пользователем в переменную */
				this.currentSlide.file = this.$refs.fileUpload.files[0];

				if (!this.checkAllInputs(true, true, true)) {
					return;
				}

				/* Загрузка файла */
				this.currentSlide.file = this.$refs.fileUpload.files[0];
				let formData = new FormData();
				formData.append("image", this.currentSlide.file);
				formData.append("type", "slide");

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
						if (!response.data) {
							let debbugStory = {
								title: "Ошибка.",
								body: "Файл не прошел проверку.",
								type: "Error",
							};
							this.$store.commit("debuggerState", debbugStory);
						} else {
							try {
								// Поиск максимального id
								let maxId = 0;
								for (let key in this.slides) {
									if (this.slides[key].id > maxId) {
										maxId = this.slides[key].id;
									}
								}
								// Получение элемент массива с объектом у которого id = maxId
								let slideMaxId = this.slides.filter((slide) => {
									if (slide.id == maxId) {
										return slide;
									}
								});

								this.slides.push({
									id: 1 + slideMaxId[0].id,
									name: this.$refs.inputName.value,
									link: this.$refs.inputLink.value,
									path: response.data,
									filename: response.data.replace(
										"/storage/slides/",
										""
									),
									hide: false,
									order: 1 + this.slides[this.slides.length - 1].order,
									create: true,
									delete: false,
								});

								this.closeSlide();

								let debbugStory = {
									title: "Успешно!",
									body: "Создан новый слайд.",
									type: "Completed",
								};
								this.$store.commit("debuggerState", debbugStory);
							} catch (error) {
								let debbugStory = {
									title: "Ошибка.",
									body: "Не удалость создать новый слайд.",
									type: "Error",
								};
								this.$store.commit("debuggerState", debbugStory);
							}
						}
					})
					.catch((error) => {
						console.log(error);
					});
			} catch (error) {
				let debbugStory = {
					title: "Ошибка.",
					body: "Не удалось создать новый слайд.",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
			}
		},
		// Пометка на удаление выбранного слайда
		markDeleteSlide() {
			/* Получение текущего объекта из массива this.slides */
			let resultSlideCurrent = this.slides.filter(
				(slide) => slide.order === this.currentSlide.data.order.body
			);
			let filteredSlideCurrent = resultSlideCurrent[0];

			if (filteredSlideCurrent.delete) {
				filteredSlideCurrent.delete = false;
			} else {
				filteredSlideCurrent.delete = true;
			}

			this.closeSlide();
		},
		// Обновление данных слайда по данным из модального окна
		updateSlide() {
			try {
				if (!this.checkAllInputs(true, true, false)) {
					return;
				}

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
				formData.append("type", "slide");

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
						this.currentSlide.data.path.body = response.data;
						filteredSlideCurrent.path = response.data;
						filteredSlideCurrent.filename = response.data.replace(
							"/storage/slides/",
							""
						);
					})
					.catch((error) => {
						console.log(error);
					});
				this.closeSlide();
			} catch (error) {
				let debbugStory = {
					title: "Ошибка.",
					body: "Не удалось обновить данные слайда.",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
			}
		},
		// Сохранение изменений в базе данных
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

					/* Обновление свойства create в массиве слайдов */
					for (let index in this.slides) {
						if (this.slides[index].create) {
							this.slides[index].create = false;
						}
					}

					// Получения нового массива слайдов, помеченных на удаление
					let res = this.slides.filter((slide) => {
						if (slide.delete == true) {
							return Object.assign({}, slide);
						}
					});

					// Повторять, пока не будут удалены все слайды, помеченные на удаление
					while (res.length > 0) {
						this.slides.splice(this.slides.indexOf(res[0]), 1);
						res = this.slides.filter((slide) => {
							if (slide.delete == true) {
								return Object.assign({}, slide);
							}
						});
					}

					// Обновление свойства order в массиве слайдов
					let count = 0;
					for (let slide in this.slides) {
						count++;
						this.slides[slide].order = count;
					}
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
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                     ФУТЕР                         |*/
		/* |___________________________________________________|*/
		/* _____________________________________________________*/
		/* 1. Работа с текстовыми полями                        */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
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
		// Очистка всех полей футера
		clearFooter() {
			this.clearTextareaAll();
			this.clearTextareaEdited();
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
		/* _____________________________________________________*/
		/* 2. Сохранение, редактирование                        */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
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

				// Добавление полей "delete" и "create" в каждую строку массива
				for (let key in this.slides) {
					this.slides[key].delete = false;
					this.slides[key].create = false;
				}
				this.sortSlider();

				this.loading.loader.slider = false;
				setTimeout(() => {
					this.loading.slider = true;
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

				this.loading.loader.footer = false;
				setTimeout(() => {
					this.loading.footer = true;
				}, 500);
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
.modal-img-input {
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
}

.modal-body-img {
	width: auto;
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

	animation: show 0.5s ease-out;
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

.slider-block.create {
	background-color: var(--create-background-color);
	border: 2px solid var(--create-border-color);
}

.slider-block.delete {
	background-color: var(--delete-background-color);
	border: 2px solid var(--delete-border-color);
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

.footer-container {
	animation: show 0.5s ease-out;
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

@media screen and (max-width: 860px) {
	.modal-img-input {
		max-width: 100%;
	}
	.modal-body-img {
		height: 300px;
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
