<template>
	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                  МОДАЛЬНОЕ ОКНО                   |-->
	<!--|___________________________________________________|-->
	<Modal ref="modalSlide" :settings="modalSlide">
		<template #title>
			{{ modalSlide.values.title }}
		</template>
		<template #buttons>
			<template v-if="modalSlide.values.look == 'default'">
				<icon-arrow :width="16" :height="16" :rotate="-90" @click="changeSlideOrder('down')" />
				#{{ currentSlide.data.order.value }}
				<icon-arrow :width="16" :height="16" :rotate="90" @click="changeSlideOrder('up')" />

				<IconHide
					v-if="currentSlide.data.hide.value"
					:height="26"
					:width="26"
					@click="currentSlide.data.hide.value = false"
				/>
				<IconVisible
					v-else="currentSlide.data.hide.body"
					:height="26"
					:width="26"
					@click="currentSlide.data.hide.value = true"
				/>
			</template>
		</template>
		<template #body>
			<div
				class="modal-slide-img"
				v-if="modalSlide.values.look == 'default'"
				:style="{
					backgroundImage: `url(${currentSlide.data.path.value})`,
				}"
				ref="modalImg"
			></div>
			<div
				class="modal-slide-img"
				v-if="modalSlide.values.look == 'create'"
				:style="{
					backgroundImage: `url(/storage/default/image-none-default.png)`,
				}"
				ref="modalImg"
			></div>
			<container-input>
				<!-- Название -->
				<container-input-once>
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
							:disabled="currentSlide.data.delete.value"
							@input="currentSlide.data.file.edited = true"
						/>
					</template>
					<template #error>
						<span class="error" v-if="currentSlide.errors.file.status">
							{{ currentSlide.errors.file.message }}
						</span>
					</template>
				</container-input-once>
				<!-- Название -->
				<container-input-once>
					<template #title>
						<span>НАЗВАНИЕ*</span>
						<span v-if="currentSlide.data.name.edited"> (ИЗМЕНЕНО) </span>
					</template>
					<template #input>
						<input
							type="text"
							v-model="currentSlide.data.name.value"
							ref="inputName"
							@input="currentSlide.data.name.edited = true"
							:class="{ error: currentSlide.errors.name.status }"
							placeholder="Введите название"
							:disabled="currentSlide.data.delete.value"
						/>
					</template>
					<template #error>
						<span class="error" v-if="currentSlide.errors.name.status">
							{{ currentSlide.errors.name.message }}
						</span>
					</template>
				</container-input-once>
				<!-- Ссылка -->
				<container-input-once>
					<template #title>
						<span>ССЫЛКА*</span>
						<span v-if="currentSlide.data.link.edited">(ИЗМЕНЕНО)</span>
					</template>
					<template #input>
						<input
							type="text"
							v-model="currentSlide.data.link.value"
							ref="inputLink"
							@input="currentSlide.data.link.edited = true"
							:class="{ error: currentSlide.errors.link.status }"
							placeholder="Введите ссылку"
							:disabled="currentSlide.data.delete.value"
						/>
					</template>
					<template #error>
						<span class="error" v-if="currentSlide.errors.link.status">
							{{ currentSlide.errors.link.message }}
						</span>
					</template>
				</container-input-once>
			</container-input>
		</template>
		<template #footer>
			<block-buttons v-if="modalSlide.values.look == 'default'">
				<button-remove
					v-if="!currentSlide.data.create.value & !currentSlide.data.delete.value"
					@click.prevent="markDeleteSlide"
				>
					Удалить
				</button-remove>
				<button-default
					v-if="!currentSlide.data.delete.value"
					@click.prevent="updateSlide"
					:disabled="disabled.slider.update"
				>
					Обновить
				</button-default>
				<button-default v-if="currentSlide.data.delete.value" @click.prevent="markDeleteSlide">
					Восстановить
				</button-default>
			</block-buttons>
			<block-buttons v-else-if="modalSlide.values.look == 'create'">
				<button-default @click.prevent="addSlide" :disabled="disabled.slider.create">
					Создать
				</button-default>
			</block-buttons>
		</template>
	</Modal>

	<info-bar>
		<template #title>Главная</template>
		<template #addreas></template>
	</info-bar>

	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                      СЛАЙДЕР                      |-->
	<!--|___________________________________________________|-->
	<block-once :minHeight="400">
		<block-title>
			<template #title>СЛАЙДЕР</template>
			<template #buttons>
				<icon-load :width="28" :height="28" v-if="disabled.slider.save" />
				<icon-save :width="28" :height="28" @click="saveSlidesChanges" v-else />
			</template>
		</block-title>

		<template v-if="loading.slider">
			<div class="slider" v-if="slides.length > 0">
				<div
					v-for="slide in slides"
					:key="slide.id"
					class="slider-block"
					:class="{
						hide: slide.hide,
						create: slide.create,
						delete: slide.delete,
					}"
					@click="openSlideEdite(slide)"
				>
					<div class="head">
						<div>id: {{ slide.create ? "?" : slide.id }}</div>
						<div>order: {{ slide.order }}</div>
					</div>
					<div
						v-bind:style="{
							'background-image': `url(${slide.path})`,
							height: '275px',
							'background-size': 'contain	',
							'background-position': 'center center',
							'background-repeat': 'no-repeat',
						}"
					></div>
					<!-- <div class="slider-block-id">#{{ slide.order }}</div> -->
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
			<!-- Элемент пустой страницы -->
			<empty :minHeight="300" v-if="slides.length == 0" />
		</template>

		<!-- Загрузка слайдов -->
		<loader-child
			:isLoading="loading.loader.slider"
			:minHeight="300"
			@loaderChildAfterLeave="loaderChildAfterLeave"
		/>

		<BlockButtons>
			<ButtonDefault @click="openSlideCreate" :disabled="disabled.slider.add">
				Добавить
			</ButtonDefault>
		</BlockButtons>
	</block-once>

	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                       ФУТЕР                       |-->
	<!--|___________________________________________________|-->
	<block-once :minHeight="400">
		<block-title>
			<template #title>ФУТЕР</template>
			<template #buttons>
				<icon-load :width="28" :height="28" v-if="disabled.footer.save" />
				<icon-save :width="28" :height="28" @click="saveFooterChanges" v-else />
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
			:minHeight="400"
			@loaderChildAfterLeave="loaderChildAfterLeave"
		/>
	</block-once>
</template>

<script>
import ElementInputLabel from "../../components/ui/admin/elements/ElementInputLabel.vue";

import LoaderChild from "../../components/modules/LoaderChild.vue";
import Empty from "../../components/modules/Empty.vue";

import Modal from "../../components/modules/Modal.vue";
import AdminModal from "../../components/includes/admin/AdminModal.vue";
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
import IconLoad from "../../components/icons/IconLoad.vue";

import axios from "axios";
import shared from "../../services/shared";
import sorted from "../../services/sorted";
import validate from "../../services/validate";

import nshared from "../../services/nshared";
import nvalidate from "../../services/nvalidate";

export default {
	components: {
		axios,
		ElementInputLabel,
		LoaderChild,
		Empty,

		Modal,
		AdminModal,
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
		IconLoad,
	},
	data() {
		return {
			/* Кнопки */
			disabled: {
				slider: {
					update: false,
					delete: false,
					create: false,
					add: false,
					save: false,
				},
				footer: {
					save: false,
				},
			},

			/* Загрузчик */
			loading: {
				loader: {
					slider: true,
					footer: true,
				},
				slider: false,
				footer: false,
			},

			/* Модальные окна */
			modalSlide: {
				thin: false,
				clamped: false,
				values: {
					title: "",
					look: "default",
				},
			},
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
					images: false,
					body: true,
					footer: true,
				},
			},

			/* Текущие значения */
			currentSlide: {
				status: false,
				errors: {
					name: {
						status: false,
						message: null,
					},
					link: {
						status: false,
						message: null,
					},
					file: {
						status: false,
						message: null,
					},
				},
				data: {
					id: {
						value: "",
						edited: false,
					},
					name: {
						value: "",
						edited: false,
					},
					link: {
						value: "",
						edited: false,
					},
					filename: {
						value: "",
						edited: false,
					},
					path: {
						value: "",
						edited: false,
					},
					order: {
						value: "",
						edited: false,
					},
					file: {
						value: null,
						edited: false,
					},
					hide: {
						value: "",
						edited: false,
					},
					create: {
						value: false,
						edited: false,
					},
					delete: {
						value: false,
						edited: false,
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

			slides: [],
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
		/* |                 МОДАЛЬНОЕ ОКНО                    |*/
		/* |___________________________________________________|*/
		/* Открытие модального окна */
		openModal(name, title, look) {
			this[name].values.title = title;
			this[name].values.look = look;

			this.$refs[name].open();
		},

		/* Открытие модального окна для добавления */
		openSlideCreate() {
			nshared.clearObjectFull(this.currentSlide);
			this.$refs.fileUpload.value = null;

			this.openModal("modalSlide", "Слайд", "create");
		},

		/* Открытие модального окна для редактирования */
		openSlideEdite(slide) {
			nshared.clearObjectFull(this.currentSlide);
			nshared.setData(slide, this.currentSlide);
			this.$refs.fileUpload.value = null;

			this.openModal("modalSlide", "Слайд", "default");
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                     СЛАЙДЕР                       |*/
		/* |___________________________________________________|*/
		/* _____________________________________________________*/
		/* 2. Изменение состояний                               */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		/* Изменение порядка выбранного слайда */
		changeSlideOrder(type) {
			try {
				if (this.slides.length <= 1) {
					return;
				}

				// Является ли текущий элемент первым
				let firstSlideStatus = this.currentSlide.data.order.value == 1;

				// Предидущей элемент
				let slidePrevious = null;
				if (firstSlideStatus) {
					slidePrevious = this.slides.find((slide) => slide.order === this.slides.length);
				} else {
					slidePrevious = this.slides.find(
						(slide) => slide.order === this.currentSlide.data.order.value - 1
					);
				}

				// Текущий элемент
				let slideCurrent = this.slides.find(
					(slide) => slide.order === this.currentSlide.data.order.value
				);

				// Является ли текущий элемент последним
				let lastSlideStatus = this.currentSlide.data.order.value == this.slides.length;

				// Следующий элемент
				let slideNext = null;
				if (lastSlideStatus) {
					slideNext = this.slides.find((slide) => slide.order === 1);
				} else {
					slideNext = this.slides.find(
						(slide) => slide.order === this.currentSlide.data.order.value + 1
					);
				}

				// Изменение порядка
				switch (type) {
					case "up":
						{
							if (lastSlideStatus) {
								this.currentSlide.data.order.value = 1;
								slideCurrent.order = 1;
								slideNext.order = this.slides.length;
								sorted.sortByOrder("up", this.slides);
							} else {
								this.currentSlide.data.order.value++;
								slideCurrent.order++;
								slideNext.order--;
								sorted.sortByOrder("up", this.slides);
							}
						}
						break;
					case "down":
						{
							if (firstSlideStatus) {
								this.currentSlide.data.order.value = this.slides.length;
								slideCurrent.order = this.slides.length;
								slidePrevious.order = 1;
								sorted.sortByOrder("up", this.slides);
							} else {
								this.currentSlide.data.order.value--;
								slideCurrent.order--;
								slidePrevious.order++;
								sorted.sortByOrder("up", this.slides);
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
		/* 3. Сохранение, обновление, удаление                  */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		/* Создание нового слайда */
		addSlide() {
			try {
				this.currentSlide.data.file.value = this.$refs.fileUpload.files[0];

				if (
					nvalidate.checkInputsAll(this.currentSlide, [
						{
							key: "name",
							type: "text",
						},
						{
							key: "link",
							type: "text",
						},
						{
							key: "file",
							type: "file",
							formats: ["image/png", "image/jpg", "image/jpeg", "image/webp"],
						},
					])
				)
					return;

				let formData = new FormData();
				formData.append("image", this.$refs.fileUpload.files[0]);
				formData.append("type", "slide");
				formData.append("formats", ["png", "jpg", "jpeg", "webp"]);

				this.disabled.slider.create = true;

				axios({
					method: "post",
					url: `${this.$store.getters.urlApi}` + `upload-file`,
					headers: {
						"Content-Type": "multipart/form-data",
						Authorization: `Bearer ${localStorage.getItem("token")}`,
					},
					data: formData,
				})
					.then((response) => {
						if (response.data.status) {
							this.slides.push({
								id: shared.getMaxId(this.slides) + 1,
								order: shared.getMaxOrder(this.slides) + 1,
								name: this.$refs.inputName.value,
								link: this.$refs.inputLink.value,
								path: response.data.data,
								filename: response.data.data.replace("/storage/slides/", ""),
								hide: false,
								create: true,
								delete: false,
							});

							this.$refs.modalSlide.close();

							let debbugStory = {
								title: "Успешно!",
								body: "Новый слайд добавлен.",
								type: "Completed",
							};
							this.$store.commit("debuggerState", debbugStory);
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
						let debbugStory = {
							title: "Ошибка.",
							body: error,
							type: "Error",
						};
						this.$store.commit("debuggerState", debbugStory);
					})
					.finally(() => {
						this.disabled.slider.create = false;
						this.currentSlide.data.file.value = null;
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

		/* Пометка на удаление выбранного слайда */
		markDeleteSlide() {
			// Получение текущего объекта из массива this.slides
			let slide = this.slides.find(
				(slide) => slide.order === this.currentSlide.data.order.value
			);

			if (slide.delete) {
				slide.delete = false;
			} else {
				slide.delete = true;
			}

			this.$refs.modalSlide.close();
		},

		/* Обновление данных слайда по данным из модального окна */
		updateSlide() {
			try {
				// Получение текущего слайда
				let slideCurrent = this.slides.find(
					(slide) => slide.order === this.currentSlide.data.order.value
				);

				// Если файл не загружен
				if (!this.$refs.fileUpload.files[0]) {
					if (
						nvalidate.checkInputsAll(this.currentSlide, [
							{
								key: "name",
								type: "text",
							},
							{
								key: "link",
								type: "text",
							},
						])
					)
						return;

					for (let key in slideCurrent) {
						if (key == "name" || key == "link") {
							slideCurrent[key] = this.currentSlide.data[key].value;
						} else if (key == "hide") {
							slideCurrent[key] = this.currentSlide.data[key].value;
						}
					}

					this.$refs.modalSlide.close();
					return;
				}

				// Если файл имеется
				this.currentSlide.data.file.value = this.$refs.fileUpload.files[0];
				if (
					nvalidate.checkInputsAll(this.currentSlide, [
						{
							key: "name",
							type: "text",
						},
						{
							key: "link",
							type: "text",
						},
						{
							key: "file",
							type: "file",
							formats: ["image/png", "image/jpg", "image/jpeg", "image/webp"],
						},
					])
				)
					return;

				// Загрузка файла
				let formData = new FormData();
				formData.append("image", this.$refs.fileUpload.files[0]);
				formData.append("type", "slide");
				formData.append("formats", ["png", "jpg", "jpeg", "webp"]);

				this.disabled.slider.update = true;

				axios({
					method: "post",
					url: `${this.$store.getters.urlApi}` + `upload-file`,
					headers: {
						"Content-Type": "multipart/form-data",
						Authorization: `Bearer ${localStorage.getItem("token")}`,
					},
					data: formData,
				})
					.then((response) => {
						if (response.data.status) {
							this.currentSlide.data.path.value = response.data.data;
							slideCurrent.path = response.data.data;
							slideCurrent.filename = response.data.data.replace("/storage/slides/", "");

							this.$refs.modalSlide.close();
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
						let debbugStory = {
							title: "Ошибка.",
							body: error,
							type: "Error",
						};
						this.$store.commit("debuggerState", debbugStory);
					})
					.finally(() => {
						this.disabled.slider.update = false;
						this.currentSlide.data.file.value = null;
					});
			} catch (error) {
				let debbugStory = {
					title: "Ошибка.",
					body: "Не удалось обновить данные слайда.",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
			}
		},
		/* Сохранение изменений в базе данных */
		saveSlidesChanges() {
			this.disabled.slider.save = true;

			let dataSlides = [];
			// Копирование данных слайдов
			for (let i = 0; i < this.slides.length; i++) {
				dataSlides.push(Object.assign({}, this.slides[i]));
			}
			// Удаление свойства path из данных
			for (let i = 0; i < dataSlides.length; i++) {
				for (let key in dataSlides[i]) {
					delete dataSlides[i].path;
				}
			}
			axios({
				method: "post",
				url: `${this.$store.getters.urlApi}` + `save-slides-changes`,
				headers: {
					Accept: "application/json",
					Authorization: `Bearer ${localStorage.getItem("token")}`,
				},
				data: {
					slides: dataSlides,
				},
			})
				.then((response) => {
					if (response.data.status) {
						shared.updateId(this.slides, response.data.data);
						shared.clearDeletes(this.slides);
						shared.clearFlags(this.slides);
						shared.updateOrders(this.slides);

						this.disabled.slider.save = false;

						let debbugStory = {
							title: "Успешно!",
							body: response.data.message,
							type: "Completed",
						};
						this.$store.commit("debuggerState", debbugStory);
					} else {
						this.disabled.slider.save = false;

						let debbugStory = {
							title: "Ошибка.",
							body: response.data.message,
							type: "Error",
						};
						this.$store.commit("debuggerState", debbugStory);
					}
				})
				.catch((error) => {
					this.disabled.slider.save = false;

					let debbugStory = {
						title: "Ошибка.",
						body: "Что-то помешало сохранить данные о слайдах.",
						type: "Error",
					};
					this.$store.commit("debuggerState", debbugStory);
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
			this.disabled.footer.save = true;

			// Сохранение данных в базу данных
			axios({
				method: "post",
				url: `${this.$store.getters.urlApi}` + `save-footer`,
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
					this.clearTextareaEdited();
					this.disabled.footer.save = false;

					let debbugStory = {
						title: "Успешно!",
						body: "Данные о футере сохранены.",
						type: "Completed",
					};
					this.$store.commit("debuggerState", debbugStory);
				})
				.catch((error) => {
					this.disabled.footer.save = false;

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
			url: `${this.$store.getters.urlApi}` + `get-slides-all`,
		})
			.then((response) => {
				this.slides = response.data;

				// Добавление полей "delete" и "create" в каждую строку массива
				for (let key in this.slides) {
					this.slides[key].delete = false;
					this.slides[key].create = false;
				}
				sorted.sortByOrder("up", this.slides);
			})
			.catch((error) => {
				let debbugStory = {
					title: "Ошибка.",
					body: "Произошла ошибка при получении данных о слайдере.",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
			})
			.finally(() => {
				this.loading.loader.slider = false;
			});

		// Получение массива данных о футере с сервера
		axios({
			method: "post",
			url: `${this.$store.getters.urlApi}` + `get-footer`,
		})
			.then((response) => {
				if (response.data.status) {
					for (let key in response.data.data) {
						// Проверка полученного поля в объекте на пустоту
						if (response.data.data[key] !== null) {
							// Заполнение полей данными с сервера
							this.footer[key].body = response.data.data[key];
							this.footer[key].symbolsCount = response.data.data[key].length;
						}
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
				let debbugStory = {
					title: "Ошибка.",
					body: "Произошла ошибка при получении данных о футере.",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
			})
			.finally(() => {
				this.loading.loader.footer = false;
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
	min-height: 250px;
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
	gap: 10px;

	border: 2px solid var(--input-border-color-inactive);
	border-radius: 10px;
	padding: 10px;
	overflow: hidden;

	transition: all 0.2s;
}

.slider-block > .head {
	display: flex;
	gap: 10px;
}

.slider-block > .head > div {
	padding: 5px 10px;
	border-radius: 7.5px;
	border: 2px solid var(--input-border-color-inactive);

	transition: all 0.2s;
}

.slider-block:hover > .head > div {
	border: 2px solid var(--input-border-color-active);
}

.slider-block.create > .head > div {
	border: 2px solid var(--create-border-color);
}

.slider-block.delete > .head > div {
	border: 2px solid #ec7b7b;
}

.slider-block.hide {
	border: 2px solid rgb(210, 210, 210);
	background-color: rgb(240, 240, 240);
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
</style>
