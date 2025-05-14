<template>
	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                  МОДАЛЬНОЕ ОКНО                   |-->
	<!--|___________________________________________________|-->
	<modal ref="modal" @touchCloseModal="closeSlide" :modal="modal">
		<template
			#buttonHide
			v-if="
				(modal.type == 'edit') &
				!currentSlide.data.delete.value &
				!currentSlide.data.create.value
			"
		>
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
		<template #title>
			{{ modal.title }}
		</template>
		<template
			#title
			v-if="
				(modal.type == 'edit') &
				!currentSlide.data.delete.value &
				!currentSlide.data.create.value
			"
		>
			<icon-arrow :width="16" :height="16" :rotate="-90" @click="changeSlideOrder('down')" />
			#{{ currentSlide.data.order.value }}
			<icon-arrow :width="16" :height="16" :rotate="90" @click="changeSlideOrder('up')" />
		</template>
		<template #body>
			<div
				class="modal-slide-img"
				v-if="modal.type == 'edit'"
				:style="{
					backgroundImage: `url(${currentSlide.data.path.value})`,
				}"
				ref="modalImg"
			></div>
			<div
				class="modal-slide-img"
				v-if="modal.type == 'create'"
				:style="{
					backgroundImage: `url(/storage/default/image-none-default.png)`,
				}"
				ref="modalImg"
			></div>
			<container-input>
				<!-- Название -->
				<container-input-once>
					<template #title>
						<span>ИЗОБРАЖЕНИЕ (820х958)*</span>
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
						<span v-if="currentSlide.data.link.value">(ИЗМЕНЕНО)</span>
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
			<block-buttons v-if="modal.type == 'edit'">
				<button-remove
					v-if="!currentSlide.data.create.value & !currentSlide.data.delete.value"
					@click.prevent="markDeleteSlide"
				>
					<Icon :name="'delete'" :fill="'white'" :width="'24px'" :height="'22px'" />
					Удалить
				</button-remove>
				<button-default
					v-if="!currentSlide.data.delete.value"
					@click.prevent="updateSlide"
					:disabled="disabled.slider.update"
				>
					<Icon :name="'edit'" :fill="'white'" :width="'28px'" :height="'28px'" />
					Обновить
				</button-default>
				<button-default v-if="currentSlide.data.delete.value" @click.prevent="markDeleteSlide">
					<Icon :name="'restore'" :fill="'white'" :width="'28px'" :height="'28px'" />
					Вернуть
				</button-default>
			</block-buttons>
			<block-buttons v-if="modal.type == 'create'">
				<button-default @click.prevent="addSlide" :disabled="disabled.slider.create">
					<Icon :name="'add'" :fill="'white'" :width="'23px'" :height="'23px'" />
					Добавить
				</button-default>
			</block-buttons>
		</template>
	</modal>

	<info-bar>
		<template #title>Главная</template>
		<template #addreas></template>
	</info-bar>

	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                      СЛАЙДЕР                      |-->
	<!--|___________________________________________________|-->
	<block-once :minHeight="300">
		<template #title>СЛАЙДЕР</template>

		<template #options>
			<ButtonDefault look="white" :disabled="disabled.slider.save" @click="saveSlidesChanges">
				<Icon :name="'save'" :fill="'var(--primary-color)'" :width="'28px'" :height="'28px'" />
				Сохранить
			</ButtonDefault>
		</template>

		<template #body>
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
						@click="openSlide(slide, 'edit')"
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
		</template>

		<template #buttons>
			<ButtonDefault @click="openSlide(null, 'create')" :disabled="disabled.slider.add">
				<Icon :name="'add'" :fill="'white'" :width="'23px'" :height="'23px'" />
				Добавить
			</ButtonDefault>
		</template>
	</block-once>

	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                       ФУТЕР                       |-->
	<!--|___________________________________________________|-->
	<block-once :minHeight="400">
		<template #title>ФУТЕР</template>

		<template #options>
			<ButtonDefault look="white" :disabled="disabled.footer.save" @click="saveFooterChanges">
				<Icon :name="'save'" :fill="'var(--primary-color)'" :width="'28px'" :height="'28px'" />
				Сохранить
			</ButtonDefault>
		</template>

		<template #body>
			<div class="footer-container" v-show="loading.footer">
				<Tiptap
					v-model="footer"
					:editable="true"
					:limit="10_000"
					:minHeight="300"
					:options="['format', 'align', 'list', 'link']"
					:placeholder="'Введите текст футера'"
				/>
			</div>

			<!-- Загрузка футера -->
			<loader-child
				:isLoading="loading.loader.footer"
				:minHeight="400"
				@loaderChildAfterLeave="loaderChildAfterLeave"
			/>
		</template>
	</block-once>
</template>

<script>
import ElementInputLabel from "../../components/ui/admin/elements/ElementInputLabel.vue";

import LoaderChild from "../../components/modules/LoaderChild.vue";
import Empty from "../../components/modules/Empty.vue";

import Modal from "../../components/includes/admin/AdminModal.vue";
import InfoBar from "../../components/ui/admin/InfoBar.vue";
import Tiptap from "../../components/modules/Tiptap.vue";

import ContainerInput from "../../components/ui/admin/containers/ContainerInput.vue";
import ContainerInputOnce from "../../components/ui/admin/containers/input/ContainerInputOnce.vue";
import ContainerTextareaOnce from "../../components/ui/admin/containers/textarea/ContainerTextareaOnce.vue";

import BlockOnce from "../../components/ui/admin/blocks/BlockOnce.vue";
import BlockButtons from "../../components/ui/admin/blocks/BlockButtons.vue";

import ButtonDefault from "../../components/ui/admin/buttons/ButtonDefault.vue";
import ButtonRemove from "../../components/ui/admin/buttons/ButtonRemove.vue";
import ButtonClaim from "../../components/ui/admin/buttons/ButtonClaim.vue";

import SlideUserCard from "../../components/icons/SlideUserCard.vue";
import SlideLink from "../../components/icons/SlideLink.vue";
import SlidePath from "../../components/icons/SlidePath.vue";

import Icon from "../../components/modules/icon/Icon.vue";
import IconArrow from "../../components/icons/IconArrow.vue";
import IconHide from "../../components/icons/IconHide.vue";
import IconVisible from "../../components/icons/IconVisible.vue";

import axios from "axios";
import shared from "../../services/shared";
import files from "../../services/files";
import sorted from "../../services/sorted";
import validate from "../../services/validate";

export default {
	components: {
		axios,
		ElementInputLabel,
		LoaderChild,
		Empty,

		Modal,
		InfoBar,
		Tiptap,
		Icon,

		ContainerInput,
		ContainerInputOnce,
		ContainerTextareaOnce,
		BlockOnce,
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
	},
	data() {
		return {
			/* Загрузчик */
			loading: {
				loader: {
					slider: true,
					footer: true,
				},
				slider: false,
				footer: false,
			},
			slides: [],

			/* Модальное окно */
			modal: {
				title: "СЛАЙД",
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

			/* Форма */
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

			footer: "",
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
		/* _____________________________________________________*/
		/* Основные действия                                    */
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

			shared.clearObjectSelective(this.currentSlide, "data", ["edited"]);
			shared.clearObjectSelective(this.currentSlide, "errors", ["status", "message"]);
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                     СЛАЙДЕР                       |*/
		/* |___________________________________________________|*/
		/* _____________________________________________________*/
		/* 1. Основные действия                                 */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		// Открытие слайда
		openSlide(selectedSlide, type) {
			try {
				this.$refs.fileUpload.value = "";

				switch (type) {
					case "create":
						{
							for (let key in this.currentSlide.data) {
								this.currentSlide.data[key].value = "";
							}

							this.currentSlide.data.delete.value = false;
							// Открытие модального окна
							this.openModal(type);
						}
						break;
					case "edit":
						{
							for (let key in selectedSlide) {
								this.currentSlide.data[key].value = selectedSlide[key];
							}

							// Открытие модального окна
							this.openModal(type);
						}
						break;
				}
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка",
					body: error,
					type: "error",
				});
			}
		},
		/* _____________________________________________________*/
		/* 2. Изменение состояний                               */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		// Изменение порядка выбранного слайда
		changeSlideOrder(type) {
			try {
				shared.changeOrder(this.slides, this.currentSlide, type);
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка",
					body: error,
					type: "error",
				});
			}
		},
		/* _____________________________________________________*/
		/* 3. Сохранение, обновление, удаление                  */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		// Создание нового слайда
		addSlide() {
			try {
				if (
					validate.checkInputsAll(this.currentSlide, [
						{
							key: "file",
							type: "file",
							value: this.$refs.fileUpload,
							formats: ["png", "webp"],
						},
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

				/* Загрузка файла */
				this.currentSlide.file = this.$refs.fileUpload.files[0];
				let formData = new FormData();
				formData.append("file", this.currentSlide.file);
				formData.append("type", "slides");
				formData.append("formats", ["png", "webp"]);

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
							try {
								this.slides.push({
									id: shared.getMaxId(this.slides) + 1,
									order: shared.getMaxOrder(this.slides) + 1,
									name: this.$refs.inputName.value,
									link: this.$refs.inputLink.value,
									path: response.data.data,
									filename: files.basename(response.data.data),
									hide: false,
									create: true,
									delete: false,
								});

								this.closeSlide();

								this.$store.commit("addDebugger", {
									title: "Успешно!",
									body: "Новый слайд добавлен.",
									type: "completed",
								});
							} catch (error) {
								this.$store.commit("addDebugger", {
									title: "Ошибка",
									body: error,
									type: "error",
								});
							}
						} else {
							this.$store.commit("addDebugger", {
								title: "Ошибка",
								body: response.data.message,
								type: "error",
							});
						}
					})
					.catch((error) => {
						this.$store.commit("addDebugger", {
							title: "Ошибка",
							body: error,
							type: "error",
						});
					})
					.finally(() => {
						this.disabled.slider.create = false;
					});
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка",
					body: error,
					type: "error",
				});
			}
		},

		/* Пометка на удаление выбранного слайда */
		markDeleteSlide() {
			let slide = this.slides.find(
				(slide) => slide.order === this.currentSlide.data.order.value
			);

			if (slide.delete) {
				slide.delete = false;
			} else {
				slide.delete = true;
			}

			this.closeSlide();
		},

		/* Обновление выбранного слайда */
		updateSlide() {
			try {
				let slideCurrent = this.slides.find(
					(slide) => slide.order === this.currentSlide.data.order.value
				);

				// Проверка на файл
				if (!this.$refs.fileUpload.files[0]) {
					if (
						validate.checkInputsAll(this.currentSlide, [
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

					this.closeSlide();
					return;
				}

				if (
					validate.checkInputsAll(this.currentSlide, [
						{
							key: "file",
							type: "file",
							value: this.$refs.fileUpload,
							formats: ["png", "webp"],
						},
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

				this.disabled.slider.update = true;

				let formData = new FormData();
				formData.append("file", this.$refs.fileUpload.files[0]);
				formData.append("type", "slides");
				formData.append("formats", ["png", "jpg", "jpeg", "webp"]);

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
							try {
								this.currentSlide.data.path.value = response.data.data;
								slideCurrent.path = response.data.data;
								slideCurrent.filename = files.basename(response.data.data);

								this.closeSlide();
							} catch (error) {
								this.$store.commit("addDebugger", {
									title: "Ошибка",
									body: error,
									type: "error",
								});
							}
						} else {
							this.$store.commit("addDebugger", {
								title: "Ошибка",
								body: response.data.message,
								type: "error",
							});
						}
					})
					.catch((error) => {
						this.$store.commit("addDebugger", {
							title: "Ошибка",
							body: error,
							type: "error",
						});
					})
					.finally(() => {
						this.disabled.slider.update = false;
					});
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка",
					body: error,
					type: "error",
				});
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

						this.$store.commit("addDebugger", {
							title: "Успешно!",
							body: response.data.message,
							type: "completed",
						});
					} else {
						this.$store.commit("addDebugger", {
							title: "Ошибка",
							body: response.data.message,
							type: "error",
						});
					}
				})
				.catch((error) => {
					this.$store.commit("addDebugger", {
						title: "Ошибка",
						body: error,
						type: "error",
					});
				})
				.finally(() => {
					this.disabled.slider.save = false;
				});
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                     ФУТЕР                         |*/
		/* |___________________________________________________|*/
		/* Сохранение всех полей */
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
					description: this.footer,
				},
			})
				.then((response) => {
					if (response.data.status) {
						this.$store.commit("addDebugger", {
							title: "Успешно!",
							body: response.data.message,
							type: "completed",
						});
					} else {
						this.$store.commit("addDebugger", {
							title: "Ошибка",
							body: response.data.message,
							type: "error",
						});
					}
				})
				.catch((error) => {
					this.$store.commit("addDebugger", {
						title: "Ошибка",
						body: error,
						type: "error",
					});
				})
				.finally(() => {
					this.disabled.footer.save = false;
				});
		},
	},
	mounted() {
		// Получение массива слайдов с сервера
		axios({
			method: "get",
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
				this.$store.commit("addDebugger", {
					title: "Ошибка",
					body: error,
					type: "error",
				});
			})
			.finally(() => {
				this.loading.loader.slider = false;
			});

		// Получение массива данных о футере с сервера
		axios({
			method: "get",
			url: `${this.$store.getters.urlApi}` + `get-footer`,
		})
			.then((response) => {
				if (response.data.status) {
					this.footer = response.data.data;
				} else {
					this.$store.commit("addDebugger", {
						title: "Ошибка",
						body: response.data.message,
						type: "error",
					});
				}
			})
			.catch((error) => {
				this.$store.commit("addDebugger", {
					title: "Ошибка",
					body: error,
					type: "error",
				});
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

	border: var(--default-border);
	border-radius: 10px;
	padding: 10px;
	overflow: hidden;

	transition: all 0.2s;
}

.slider-block:hover {
	border: var(--input-border-focus);
	background-color: var(--item-background-color-active);
}

.slider-block > .head {
	display: flex;
	gap: 10px;
}

.slider-block > .head > div {
	padding: 5px 10px;
	border-radius: calc(var(--input-border-radius) / 2);
	border: var(--input-border);

	transition: all 0.2s;
}

.slider-block:hover > .head > div {
	border: var(--input-border-focus);
}

.slider-block.create > .head > div {
	border: var(--input-create-border);
}

.slider-block.delete > .head > div {
	border: var(--input-delete-border);
}

.slider-block.hide {
	border: 1px solid rgb(210, 210, 210);
	background-color: rgb(240, 240, 240);
}

.slider-block.create {
	border: var(--input-create-border);
}

.slider-block.create:hover {
	background-color: var(--input-create-background-color-hover);
	border: var(--input-create-border);
}

.slider-block.delete {
	border: 1px solid #ec7b7b;
}

.slider-block.delete:hover {
	border: 1px solid #f79999;
	background-color: rgb(255, 240, 240);
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
