<template>
	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                  МОДАЛЬНОЕ ОКНО                   |-->
	<!--|___________________________________________________|-->
	<Modal ref="modal" :settings="modal">
		<template #title>
			{{ modal.values.title }}
		</template>

		<template
			#buttons
			v-if="
				(modal.values.look == 'default') &
				!currentSlide.data.delete.value &
				!currentSlide.data.create.value
			"
		>
			<Icon
				v-if="currentSlide.data.hide.value"
				:name="'hide'"
				:fill="'var(--primary-color)'"
				:hover="'var(--primary-color-hover)'"
				:cursor="'pointer'"
				:width="'26px'"
				:height="'26px'"
				@click="currentSlide.data.hide.value = false"
			/>
			<Icon
				v-else
				:name="'visible'"
				:fill="'var(--primary-color)'"
				:hover="'var(--primary-color-hover)'"
				:cursor="'pointer'"
				:width="'26px'"
				:height="'26px'"
				@click="currentSlide.data.hide.value = true"
			/>
		</template>

		<template
			#title
			v-if="
				(modal.values.look == 'default') &
				!currentSlide.data.delete.value &
				!currentSlide.data.create.value
			"
		>
			<Icon
				:name="'arrow'"
				:fill="'var(--icon-multi-fill)'"
				:hover="'var(--icon-nulti-fill-hover)'"
				:width="'16px'"
				:height="'16px'"
				:rotate="-90"
				:cursor="'pointer'"
				@click="changeSlideOrder('down')"
			/>
			#{{ currentSlide.data.order.value }}
			<Icon
				:name="'arrow'"
				:fill="'var(--icon-multi-fill)'"
				:hover="'var(--icon-nulti-fill-hover)'"
				:width="'16px'"
				:height="'16px'"
				:rotate="90"
				:cursor="'pointer'"
				@click="changeSlideOrder('up')"
			/>
		</template>

		<template #body>
			<div
				class="modal-slide-img"
				v-if="modal.values.look == 'default'"
				:style="{
					backgroundImage: `url(${currentSlide.data.path.value})`,
				}"
				ref="modalImg"
			></div>
			<div
				class="modal-slide-img"
				v-if="modal.values.look == 'create'"
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
			<template v-if="modal.values.look == 'default'">
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
			</template>

			<template v-if="modal.values.look == 'create'">
				<button-default @click.prevent="addSlide" :disabled="disabled.slider.create">
					<Icon :name="'add'" :fill="'white'" :width="'23px'" :height="'23px'" />
					Добавить
				</button-default>
			</template>
		</template>
	</Modal>

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
						@click="openModalEdite(slide)"
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
								<Icon
									:name="'user-card'"
									:fill="'var(--primary-color)'"
									:width="'30px'"
									:height="'30px'"
								/>
								<label>
									{{ slide.name }}
								</label>
							</article>
							<article>
								<Icon
									:name="'link'"
									:fill="'var(--primary-color)'"
									:width="'30px'"
									:height="'30px'"
								/>
								<label>
									{{ slide.link }}
								</label>
							</article>
							<article>
								<Icon
									:name="'directory-link'"
									:fill="'var(--primary-color)'"
									:width="'30px'"
									:height="'30px'"
								/>
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
			<ButtonDefault @click="openModalСreate" :disabled="disabled.slider.add">
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
import LoaderChild from "../../components/modules/LoaderChild.vue";
import Empty from "../../components/modules/Empty.vue";

import Modal from "../../components/modules/modal/Modal.vue";
import InfoBar from "../../components/ui/admin/InfoBar.vue";
import Tiptap from "../../components/modules/Tiptap.vue";

import ContainerInput from "../../components/ui/admin/containers/ContainerInput.vue";
import ContainerInputOnce from "../../components/ui/admin/containers/input/ContainerInputOnce.vue";
import ContainerTextareaOnce from "../../components/ui/admin/containers/textarea/ContainerTextareaOnce.vue";

import BlockOnce from "../../components/ui/admin/blocks/BlockOnce.vue";

import ButtonDefault from "../../components/ui/admin/buttons/ButtonDefault.vue";
import ButtonRemove from "../../components/ui/admin/buttons/ButtonRemove.vue";
import ButtonClaim from "../../components/ui/admin/buttons/ButtonClaim.vue";

import Icon from "../../components/modules/icon/Icon.vue";

import api from "../../services/api";
import shared from "../../services/shared";
import files from "../../services/files";
import sorted from "../../services/sorted";
import validate from "../../services/validate";

export default {
	components: {
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
		ButtonDefault,
		ButtonRemove,
		ButtonClaim,
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

			/* Модальное окно */
			modal: {
				thin: false,
				clamped: false,
				values: {
					title: "",
					look: "default",
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

			/* Данные */
			slides: [],
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
		/* Открытие модального окна */
		openModal(name, title, look) {
			this[name].values.title = title;
			this[name].values.look = look;

			this.$refs[name].open();
		},

		/* Открытие модального окна для добавления */
		openModalEdite(value) {
			this.$refs.fileUpload.value = "";

			shared.clearObjectFull(this.currentSlide);
			shared.setData(value, this.currentSlide);

			this.openModal("modal", "СЛАЙД", "default");
		},

		/* Открытие модального окна для добавления */
		openModalСreate(type) {
			this.$refs.fileUpload.value = "";

			shared.clearObjectFull(this.currentSlide);

			this.openModal("modal", "СЛАЙД", "create");
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                     СЛАЙДЕР                       |*/
		/* |___________________________________________________|*/
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

		// Создание нового слайда
		addSlide() {
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

			api({
				method: "post",
				url: this.$store.getters.urlApi + "upload-file",
				headers: {
					"Content-Type": "multipart/form-data",
				},
				data: formData,
			})
				.then((response) => {
					this.slides.push({
						id: shared.getMaxId(this.slides) + 1,
						order: shared.getMaxOrder(this.slides) + 1,
						name: this.$refs.inputName.value,
						link: this.$refs.inputLink.value,
						path: response.data.result,
						filename: files.basename(response.data.result),
						hide: false,
						create: true,
						delete: false,
					});

					this.$refs.modal.close();
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

			this.$refs.modal.close();
		},

		/* Обновление выбранного слайда */
		updateSlide() {
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

				this.$refs.modal.close();
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

			api({
				method: "post",
				url: this.$store.getters.urlApi + "upload-file",
				headers: {
					"Content-Type": "multipart/form-data",
				},
				data: formData,
			})
				.then((response) => {
					if (!response) return;

					this.currentSlide.data.path.value = response.data.result;
					slideCurrent.path = response.data.result;
					slideCurrent.filename = files.basename(response.data.result);

					this.$refs.modal.close();
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

			api({
				method: "post",
				url: this.$store.getters.urlApi + `save-slides-changes`,
				headers: {
					Accept: "application/json",
				},
				data: {
					slides: dataSlides,
				},
			})
				.then((response) => {
					if (!response) return;

					shared.updateId(this.slides, response.data.result);
					shared.clearDeletes(this.slides);
					shared.clearFlags(this.slides);
					shared.updateOrders(this.slides);
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
			api({
				method: "post",
				url: this.$store.getters.urlApi + `save-footer`,
				headers: {
					Accept: "application/json",
				},
				data: {
					description: this.footer,
				},
			}).finally(() => {
				this.disabled.footer.save = false;
			});
		},
	},
	mounted() {
		// Получение массива слайдов с сервера
		api({
			method: "get",
			url: this.$store.getters.urlApi + "get-slides-all",
		})
			.then((response) => {
				if (!response) return;

				this.slides = response.data.result;

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
		api({
			method: "get",
			url: this.$store.getters.urlApi + `get-footer`,
		})
			.then((response) => {
				if (!response) return;

				this.footer = response.data.result;
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
	border: var(--input-create-border-focus);
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
	border: var(--input-delete-border);
}

.slider-block.delete:hover {
	border: var(--input-delete-border);
	background-color: var(--input-delete-background-color-hover);
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
