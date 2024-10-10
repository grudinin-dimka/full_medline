<template>
	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                  МОДАЛЬНОЕ ОКНО                   |-->
	<!--|___________________________________________________|-->
	<modal ref="modal" @touchCloseModal="closeSlide" :modal="modal">
		<template
			#buttonHide
			v-if="
				(modal.type == 'edit') & !currentSlide.data.delete.body & !currentSlide.data.create.body
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
			<span v-if="modal.style.create">СЛАЙД (СОЗДАНИЕ)</span>
			<span v-if="modal.style.delete">СЛАЙД (УДАЛЕНИЕ)</span>
		</template>
		<template
			#title
			v-if="
				(modal.type == 'edit') & !currentSlide.data.delete.body & !currentSlide.data.create.body
			"
		>
			<icon-arrow :width="16" :height="16" :rotate="-90" @click="changeSlideOrder('down')" />
			#{{ currentSlide.data.order.body }}
			<icon-arrow :width="16" :height="16" :rotate="90" @click="changeSlideOrder('up')" />
		</template>
		<template #img>
			<div
				class="modal-slide-img"
				v-if="modal.type == 'edit'"
				:style="{
					backgroundImage: `url(${currentSlide.data.path.body})`,
				}"
				ref="modalImg"
			></div>
			<div
				class="modal-slide-img"
				v-if="modal.type == 'create'"
				:style="{
					backgroundImage: `url(/storage/default/image-none-create.png)`,
				}"
				ref="modalImg"
			></div>
		</template>
		<template #body>
			<container-input>
				<!-- Название -->
				<container-input-once :type="modal.type == 'create' ? 'create' : 'edit'">
					<template #title>
						<span>ИЗОБРАЖЕНИЕ*</span>
						<span v-if="currentSlide.data.file.edited"> (ИЗМЕНЕНО) </span>
					</template>
					<template #input>
						<input
							type="file"
							class="slide-file"
							placeholder="Загрузите изображение"
							ref="fileUpload"
							:class="{ error: currentSlide.errors.file.status }"
							:disabled="currentSlide.data.delete.body"
							@input="currentSlide.data.file.edited = true"
						/>
					</template>
					<!-- TODO Сделать ошибки, как в профиле врача -->
					<!-- TODO Проверить также загрузку изображений -->
					<!-- STOP Делал ошибки картинок -->
					<template #error>
						<span class="error" v-if="currentSlide.errors.file.status">
							{{ currentSlide.errors.file.value }}
						</span>
					</template>
				</container-input-once>
				<!-- Название -->
				<container-input-once :type="modal.type == 'create' ? 'create' : 'edit'">
					<template #title>
						<span>НАЗВАНИЕ*</span>
						<span v-if="currentSlide.data.name.edited"> (ИЗМЕНЕНО) </span>
					</template>
					<template #input>
						<input
							type="text"
							v-model="currentSlide.data.name.body"
							ref="inputName"
							@input="currentSlide.data.name.edited = true"
							@blur="checkModalInput('name', 'text')"
							:class="{ error: currentSlide.errors.name.status }"
							placeholder="Введите название"
							:disabled="currentSlide.data.delete.body"
						/>
					</template>
					<template #error>
						<span class="error" v-if="currentSlide.errors.name.status">
							{{ currentSlide.errors.name.value }}
						</span>
					</template>
				</container-input-once>
				<!-- Ссылка -->
				<container-input-once :type="modal.type == 'create' ? 'create' : 'edit'">
					<template #title>
						<span>ССЫЛКА*</span>
						<span v-if="currentSlide.data.link.edited">(ИЗМЕНЕНО)</span>
					</template>
					<template #input>
						<input
							type="text"
							v-model="currentSlide.data.link.body"
							ref="inputLink"
							@input="currentSlide.data.link.edited = true"
							@blur="checkModalInput('link', 'text')"
							:class="{ error: currentSlide.errors.link.status }"
							placeholder="Введите ссылку"
							:disabled="currentSlide.data.delete.body"
						/>
					</template>
					<template #error>
						<span class="error" v-if="currentSlide.errors.link.status">
							{{ currentSlide.errors.link.value }}
						</span>
					</template>
				</container-input-once>
			</container-input>
		</template>
		<template #footer>
			<block-buttons v-if="modal.type == 'edit'">
				<button-remove
					v-if="!currentSlide.data.create.body & !currentSlide.data.delete.body"
					@click.prevent="markDeleteSlide"
				>
					Удалить
				</button-remove>
				<button-default v-if="!currentSlide.data.delete.body" @click.prevent="updateSlide">
					Обновить
				</button-default>
				<button-default v-if="currentSlide.data.delete.body" @click.prevent="markDeleteSlide">
					Восстановить
				</button-default>
			</block-buttons>
			<block-buttons v-if="modal.type == 'create'">
				<button-claim @click.prevent="createSlide"> Создать </button-claim>
			</block-buttons>
		</template>
	</modal>

	<info-bar>
		<template #title>Главная</template>
		<template #addreas>home</template>
	</info-bar>

	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                      СЛАЙДЕР                      |-->
	<!--|___________________________________________________|-->
	<block-once>
		<block-title>
			<template #title>СЛАЙДЕР</template>
			<template #buttons>
				<icon-save :width="28" :height="28" @click="saveSlidesChanges" />
			</template>
		</block-title>

		<div class="slider" v-show="loading.slider">
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

		<!-- Загрузка слайдов -->
		<loader-child
			:isLoading="loading.loader.slider"
			:minHeight="300"
			@loaderChildAfterLeave="loaderChildAfterLeave"
		/>

		<BlockButtons>
			<ButtonDefault @click="openSlide(null, 'create')"> Добавить </ButtonDefault>
		</BlockButtons>
	</block-once>

	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                       ФУТЕР                       |-->
	<!--|___________________________________________________|-->
	<block-once>
		<block-title>
			<template #title>ФУТЕР</template>
			<template #buttons>
				<icon-save :width="28" :height="28" @click="saveFooterChanges" />
			</template>
		</block-title>

		<div class="footer-container" v-show="loading.footer">
			<containerInput>
				<!-- Заголовок -->
				<div class="block-textarea">
					<container-textarea-once :type="'edit'">
						<template #title>
							<span>ЗАГОЛОВОК</span>
							<span v-if="footer.title.edited"> (ИЗМЕНЕНО)</span>
						</template>
						<template #textarea>
							<textarea
								rows="4"
								placeholder="Введите заголовок"
								autocomplete="off"
								:class="{ error: false }"
								v-model="footer.title.body"
								@input="controlSymbols('title')"
							></textarea>
						</template>
						<template #error>
							<span class="error" v-if="false"> Ошибка </span>
						</template>
					</container-textarea-once>
					<span>{{ footer.title.symbolsCount }}/{{ footer.title.symbolsCountMax }}</span>
				</div>
				<!-- Дополнение к заголовку -->
				<div class="block-textarea">
					<container-textarea-once :type="'edit'">
						<template #title>
							<span>ДОПОЛНЕНИЕ К ЗАГОЛОВКУ</span>
							<span v-if="footer.titleDesc.edited"> (ИЗМЕНЕНО)</span>
						</template>
						<template #textarea>
							<textarea
								rows="10"
								placeholder="Введите дополнение"
								autocomplete="off"
								:class="{ error: false }"
								v-model="footer.titleDesc.body"
								@input="controlSymbols('titleDesc')"
							></textarea>
						</template>
						<template #error>
							<span class="error" v-if="false"> Ошибка </span>
						</template>
					</container-textarea-once>
					<span
						>{{ footer.titleDesc.symbolsCount }}/{{ footer.titleDesc.symbolsCountMax }}</span
					>
				</div>
				<!-- Лицензия -->
				<div class="block-textarea">
					<container-textarea-once :type="'edit'">
						<template #title>
							<span>ЛИЦЕНЗИЯ</span>
							<span v-if="footer.license.edited"> (ИЗМЕНЕНО)</span>
						</template>
						<template #textarea>
							<textarea
								rows="4"
								placeholder="Введите лицензию"
								autocomplete="off"
								:class="{ error: false }"
								v-model="footer.license.body"
								@input="controlSymbols('license')"
							></textarea>
						</template>
						<template #error>
							<span class="error" v-if="false"> Ошибка </span>
						</template>
					</container-textarea-once>
					<span>{{ footer.license.symbolsCount }}/{{ footer.license.symbolsCountMax }}</span>
				</div>
				<!-- Лицензия -->
				<div class="block-textarea">
					<container-textarea-once :type="'edit'">
						<template #title>
							<span>ОСНОВНОЙ ТЕКСТ</span>
							<span v-if="footer.licenseDesc.edited"> (ИЗМЕНЕНО)</span>
						</template>
						<template #textarea>
							<textarea
								rows="10"
								placeholder="Введите основной текст"
								autocomplete="off"
								:class="{ error: false }"
								v-model="footer.licenseDesc.body"
								@input="controlSymbols('licenseDesc')"
							></textarea>
						</template>
						<template #error>
							<span class="error" v-if="false"> Ошибка </span>
						</template>
					</container-textarea-once>
					<span
						>{{ footer.licenseDesc.symbolsCount }}/{{
							footer.licenseDesc.symbolsCountMax
						}}</span
					>
				</div>
				<!-- Лицензия -->
				<div class="block-textarea">
					<container-textarea-once :type="'edit'">
						<template #title>
							<span>ПОДВАЛ</span>
							<span v-if="footer.footer.edited"> (ИЗМЕНЕНО)</span>
						</template>
						<template #textarea>
							<textarea
								rows="10"
								placeholder="Введите подвал"
								autocomplete="off"
								:class="{ error: false }"
								v-model="footer.footer.body"
								@input="controlSymbols('footer')"
							></textarea>
						</template>
						<template #error>
							<span class="error" v-if="false"> Ошибка </span>
						</template>
						<template #counter>
							<span class="counter"
								>{{ footer.footer.symbolsCount }}/{{ footer.footer.symbolsCountMax }}</span
							>
						</template>
					</container-textarea-once>
				</div>
			</containerInput>
		</div>

		<!-- Загрузка футера -->
		<loader-child
			:isLoading="loading.loader.footer"
			:minHeight="300"
			@loaderChildAfterLeave="loaderChildAfterLeave"
		/>
	</block-once>
</template>

<script>
import axios from "axios";

import ElementInputLabel from "../../components/ui/admin/elements/ElementInputLabel.vue";

import LoaderChild from "../../components/includes/LoaderChild.vue";

import Modal from "../../components/includes/admin/AdminModal.vue";
import InfoBar from "../../components/ui/admin/InfoBar.vue";

import ContainerInput from "../../components/ui/admin/containers/ContainerInput.vue";
import ContainerInputOnce from "../../components/ui/admin/containers/input/ContainerInputOnce.vue";
import ContainerTextareaOnce from "../../components/ui/admin/containers/textarea/ContainerTextareaOnce.vue";

import BlockTitle from "../../components/ui/admin/blocks/BlockTitle.vue";
import BlockOnce from "../../components/ui/admin/blocks/BlockOnce.vue";
import BlockButtons from "../../components/ui/admin/blocks/BlockButtons.vue";

import ButtonDefault from "../../components/ui/admin/buttons/ButtonDefault.vue";
import ButtonRemove from "../../components/ui/admin/buttons/ButtonRemove.vue";
import ButtonClaim from "../../components/ui/admin/buttons/ButtonClaim.vue";

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
		ContainerInput,
		ContainerInputOnce,
		ContainerTextareaOnce,
		BlockOnce,
		BlockTitle,
		BlockButtons,
		ButtonDefault,
		ButtonRemove,
		ButtonClaim,
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
				modules: {
					title: true,
					buttons: {
						hide: true,
						close: true,
					},
					images: true,
					body: true,
					footer: true,
				},
			},
			currentSlide: {
				status: false,
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
					file: {
						body: null,
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
		/* |                   Загрузчик                       |*/
		/* |___________________________________________________|*/
		/* После скрытия элементы */
		loaderChildAfterLeave() {
			if (!this.loading.loader.slider) {
				this.loading.slider = true;
			}
			if (!this.loading.loader.footer) {
				this.loading.footer = true;
			}
		},
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                    СЛАЙДЕР                        |*/
		/* |___________________________________________________|*/
		/* _____________________________________________________*/
		/* 1. Работа с полями ввода                             */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		// Проверка введенного значения
		checkInputText(value) {
			/* Проверка на пустую строку */
			if (value == "" || value == null) {
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
					errorLog = this.checkInputText(this.currentSlide.data[dataKey].body);
					break;
				default:
					break;
			}

			if (errorLog.status) {
				this.currentSlide.errors[dataKey].value = errorLog.message;
				this.currentSlide.errors[dataKey].status = true;

				return true;
			} else {
				this.currentSlide.errors[dataKey].value = "";
				this.currentSlide.errors[dataKey].status = false;

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
						/* Проверка на тип загруженного файла */
						if (this.$refs.fileUpload.files[0].type !== "image/png") {
							this.currentSlide.errors.file.status = true;
							this.currentSlide.errors.file.value = "Разрешенный формат файла: png.";
							errorCount++;

							continue;
						}

						/* Проверка на размер загруженного файла */
						let fileSize = this.$refs.fileUpload.files[0].size / 1024 / 1024;
						if (fileSize > 10) {
							this.currentSlide.errors.file.status = true;
							this.currentSlide.errors.file.value = "Размер файла более 10 МБ.";
							errorCount++;

							continue;
						}

						this.currentSlide.errors.file.status = false;

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

							this.currentSlide.data.delete.body = false;
							this.modal.style.create = true;
							this.modal.style.delete = false;
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
								this.modal.style.create = true;
							} else {
								this.modal.title = "";
								this.modal.style.create = false;
							}

							// Проверка, помечен ли слайд на удаление
							if (this.currentSlide.data.delete.body === true) {
								this.modal.title = "СЛАЙД (УДАЛЕНИЕ)";
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
						(slide) => slide.order === this.currentSlide.data.order.body - 1
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
				let lastSlideStatus = this.currentSlide.data.order.body == this.slides.length;

				/* Объявление переменных следующего слайда */
				let resultSlideNext = [null];
				let filteredSlideNext = [null];
				if (lastSlideStatus) {
					resultSlideNext = this.slides.filter((slide) => slide.order === 1);
					filteredSlideNext = resultSlideNext[0];
				} else {
					resultSlideNext = this.slides.filter(
						(slide) => slide.order === this.currentSlide.data.order.body + 1
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

				/* Проверка на загрузку файла пользователем */
				if (!this.$refs.fileUpload.files[0]) {
					this.currentSlide.errors.file.status = true;
					this.currentSlide.errors.file.value = "Вы не загрузили изображение.";

					// Проверка на заполненность полей ввода
					if (this.checkModalInputsAll(["name", "link"])) {
						return;
					}
				} else {
					// Проверка на заполненность полей ввода
					if (this.checkModalInputsAll(["name", "link", "file"])) {
						return;
					}
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
						if (response.data.status) {
							try {
								// Поиск максимального id
								let maxId = 0;
								for (let key in this.slides) {
									if (this.slides[key].id > maxId) {
										maxId = this.slides[key].id;
									}
								}

								this.slides.push({
									id: 1 + maxId,
									name: this.$refs.inputName.value,
									link: this.$refs.inputLink.value,
									path: response.data.data,
									filename: response.data.data.replace("/storage/slides/", ""),
									hide: false,
									order: 1 + this.slides[this.slides.length - 1].order,
									create: true,
									delete: false,
								});

								this.closeSlide();

								let debbugStory = {
									title: "Успешно!",
									body: "Новый слайд добавлен.",
									type: "Completed",
								};
								this.$store.commit("debuggerState", debbugStory);
							} catch (error) {
								let debbugStory = {
									title: "Ошибка.",
									body: "При добавлении что-то пошло не так...",
									type: "Error",
								};
								this.$store.commit("debuggerState", debbugStory);
							}
						} else {
							let debbugStory = {
								title: "Ошибка.",
								body: response.data.message,
								type: "Error",
							};
							this.$store.commit("debuggerState", debbugStory);
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
				// Проверка на заполненность полей ввода
				if (this.checkModalInputsAll(["name", "link"])) {
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

				// Если файл не загружен, то закрываем модальное окно
				if (!this.$refs.fileUpload.files[0]) return this.closeSlide();

				// Проверка на заполненность полей ввода
				if (this.checkModalInputsAll(["file"])) return;

				/* Загрузка файла */
				let formData = new FormData();
				formData.append("image", this.$refs.fileUpload.files[0]);
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
						this.currentSlide.data.path.body = response.data.data;
						filteredSlideCurrent.path = response.data.data;
						filteredSlideCurrent.filename = response.data.data.replace(
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
					// Обновление id добавленных элементов на данные из бд
					for (let key in response.data) {
						let slide = this.slides.filter((slide) => {
							if (slide.id === response.data[key].old) {
								return slide;
							}
						});
						slide[0].id = response.data[key].new;
					}

					// Получения нового массива слайдов, помеченных на удаление
					let res = this.slides.filter((slide) => {
						if (slide.delete == true) {
							return Object.assign({}, slide);
						}
					});

					// Повторять, пока не будут удалены все элементы, помеченные на удаление
					while (res.length > 0) {
						/* Получение индекса элемента, помеченного на удаление из массива специалистов */
						this.slides.splice(this.slides.indexOf(res[0]), 1);
						/* Обновление списка с элементами, помеченными на удаление */
						res = this.slides.filter((slide) => {
							if (slide.delete == true) {
								return Object.assign({}, slide);
							}
						});
					}

					// Сброс флагов добавления и удаления
					for (let index in this.slides) {
						this.slides[index].create = false;
						this.slides[index].delete = false;
					}

					// Обновление свойства order в массиве слайдов
					let count = 0;
					for (let slide in this.slides) {
						count++;
						this.slides[slide].order = count;
					}

					let debbugStory = {
						title: "Успешно!",
						body: "Данные о слайдах сохранены.",
						type: "Completed",
					};
					this.$store.commit("debuggerState", debbugStory);
				})
				.catch((error) => {
					let debbugStory = {
						title: "Ошибка.",
						body: "Что-то помешало сохранить данные о слайдах.",
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
			if (this.footer[`${obj}`].body.length >= this.footer[`${obj}`].symbolsCountMax) {
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
						body: "Данные о футере сохранены.",
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
.modal-slide-img {
	background-size: contain;
	background-position: center center;
	background-repeat: no-repeat;

	width: 100%;
	height: 400px;
}

.slider {
	display: grid;
	grid-template-columns: repeat(4, 1fr);
	gap: 20px;
	animation: show-bottom-to-top-15 0.5s ease-in-out;
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
	border: 2px solid var(--create-border-color);
}

.slider-block.create:hover {
	background-color: var(--create-background-color);
	border: 2px solid var(--create-border-color);
}

.slider-block.delete {
	border: 2px solid #ec7b7b;
}

.slider-block.delete:hover {
	border: 2px solid #f79999;
	background-color: rgb(255, 240, 240);
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
	cursor: pointer;
	word-break: break-all;
}

.block-textarea {
	position: relative;
	display: flex;
	flex-direction: column;
}

.block-textarea > span {
	position: absolute;
	right: 0px;
	bottom: -20px;
}

.footer-container {
	animation: show-bottom-to-top-15 0.5s ease-in-out;
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
