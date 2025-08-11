<template>
	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                  МОДАЛЬНОЕ ОКНО                   |-->
	<!--|___________________________________________________|-->
	<VueModal ref="modal" :settings="modal">
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
			<VueIcon
				v-if="currentSlide.data.hide.value"
				:name="'hide'"
				:fill="'var(--primary-color)'"
				:hover="'var(--primary-color-hover)'"
				:cursor="'pointer'"
				:width="'26px'"
				:height="'26px'"
				@click="currentSlide.data.hide.value = false"
			/>
			<VueIcon
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
			<VueIcon
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
			<VueIcon
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

			<VueInput
				v-model="currentSlide.data.file.value"
				ref="fileImage"
				:type="'file'"
				:placeholder="'Загрузите картинку'"
				:error="currentSlide.errors.file.status"
			>
				<template #label> ИЗОБРАЖЕНИЕ (820х958) </template>
				<template #error>
					{{ currentSlide.errors.file.message }}
				</template>
			</VueInput>

			<VueInput
				v-model="currentSlide.data.name.value"
				:type="'text'"
				:placeholder="'Введите название'"
				:error="currentSlide.errors.name.status"
			>
				<template #label> НАЗВАНИЕ </template>
				<template #error>
					{{ currentSlide.errors.name.message }}
				</template>
			</VueInput>

			<VueInput
				v-model="currentSlide.data.link.value"
				:type="'text'"
				:placeholder="'Введите название'"
				:error="currentSlide.errors.link.status"
			>
				<template #label> ССЫЛКА </template>
				<template #error>
					{{ currentSlide.errors.link.message }}
				</template>
			</VueInput>
		</template>

		<template #footer>
			<template v-if="modal.values.look == 'default'">
				<VueButton
					:look="'delete'"
					v-if="!currentSlide.data.create.value & !currentSlide.data.delete.value"
					@click.prevent="markDeleteSlide"
				>
					<VueIcon :name="'Delete'" :fill="'white'" :width="'28px'" :height="'28px'" />
					Удалить
				</VueButton>

				<VueButton
					v-if="!currentSlide.data.delete.value"
					@click.prevent="updateSlide"
					:disabled="disabled.slider.update"
				>
					<VueIcon :name="'Edit'" :fill="'white'" :width="'28px'" :height="'28px'" />
					Обновить
				</VueButton>

				<VueButton v-if="currentSlide.data.delete.value" @click.prevent="markDeleteSlide">
					<VueIcon
						:name="'Restore From Trash'"
						:fill="'white'"
						:width="'28px'"
						:height="'28px'"
					/>
					Вернуть
				</VueButton>
			</template>

			<template v-if="modal.values.look == 'create'">
				<VueButton @click.prevent="addSlide" :disabled="disabled.slider.create">
					<VueIcon :name="'Add'" :fill="'white'" :width="'26px'" :height="'26px'" />
					Добавить
				</VueButton>
			</template>
		</template>
	</VueModal>

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
			<VueButton :look="'inverse'" :disabled="disabled.slider.save" @click="saveSlidesChanges">
				<VueIcon
					:name="'save'"
					:fill="'var(--primary-color)'"
					:width="'28px'"
					:height="'28px'"
				/>
				Сохранить
			</VueButton>

			<VueButton
				:look="'inverse'"
				@click="$store.commit('openWiki', { title: 'Слайдер', component: 'HomeSlider' })"
				:minWidth="'30px'"
			>
				<VueIcon
					:name="'Info'"
					:fill="'var(--primary-color)'"
					:width="'30px'"
					:height="'30px'"
				/>
			</VueButton>
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
								<VueIcon
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
								<VueIcon
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
								<VueIcon
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
				<empty :minHeight="300" v-else="slides.length == 0" />
			</template>

			<!-- Загрузка слайдов -->
			<VueLoader
				:isLoading="loading.loader.slider"
				:isChild="true"
				:minHeight="300"
				@afterLeave="loaderChildAfterLeave"
			/>
		</template>

		<template #buttons>
			<VueButton @click="openModalСreate()" :disabled="disabled.slider.add">
				<VueIcon :name="'Add'" :fill="'white'" :width="'26px'" :height="'26px'" />
				Добавить
			</VueButton>
		</template>
	</block-once>

	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                       ФУТЕР                       |-->
	<!--|___________________________________________________|-->
	<block-once :minHeight="400">
		<template #title>ФУТЕР</template>

		<template #options>
			<VueButton :look="'inverse'" :disabled="disabled.footer.save" @click="saveFooterChanges">
				<VueIcon
					:name="'save'"
					:fill="'var(--primary-color)'"
					:width="'28px'"
					:height="'28px'"
				/>
				Сохранить
			</VueButton>

			<VueButton
				:look="'inverse'"
				@click="$store.commit('openWiki', { title: 'Футер', component: 'HomeFooter' })"
				:minWidth="'30px'"
			>
				<VueIcon
					:name="'Info'"
					:fill="'var(--primary-color)'"
					:width="'30px'"
					:height="'30px'"
				/>
			</VueButton>
		</template>

		<template #body>
			<div class="footer-container" v-show="loading.footer">
				<VueTiptap
					v-model="footer"
					:editable="true"
					:limit="10_000"
					:minHeight="300"
					:options="['format', 'align', 'list', 'link']"
					:placeholder="'Введите текст футера'"
				/>
			</div>

			<!-- Загрузка футера -->
			<VueLoader
				:isLoading="loading.loader.footer"
				:isChild="true"
				:minHeight="300"
				@afterLeave="loaderChildAfterLeave"
			/>
		</template>
	</block-once>
</template>

<script>
import VueModal from "../../../components/modules/modal/VueModal.vue";
import VueLoader from "../../../components/modules/VueLoader.vue";
import VueTiptap from "../../../components/modules/VueTiptap.vue";

import VueInput from "../../../components/modules/input/VueInput.vue";

import Empty from "../../../components/modules/Empty.vue";
import InfoBar from "../../../components/ui/admin/InfoBar.vue";
import BlockOnce from "../../../components/ui/admin/blocks/BlockOnce.vue";

import VueIcon from "../../../components/modules/icon/VueIcon.vue";
import VueButton from "../../../components/ui/VueButton.vue";

import api from "../../../mixin/api";
import shared from "../../../services/shared";
import files from "../../../services/files";
import sorted from "../../../services/sorted";
import validate from "../../../services/validate";

export default {
	components: {
		VueLoader,
		VueModal,
		VueTiptap,
		VueInput,

		VueIcon,
		VueButton,

		Empty,
		InfoBar,
		BlockOnce,
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
			this.$refs.fileImage.clear();

			shared.clearObjectFull(this.currentSlide);
			shared.setData(value, this.currentSlide);

			this.openModal("modal", "СЛАЙД", "default");
		},

		/* Открытие модального окна для добавления */
		openModalСreate(type) {
			this.$refs.fileImage.clear();

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
						value: this.$refs.fileImage.files(),
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
			let formData = new FormData();
			formData.append("file", this.$refs.fileImage.files()[0]);
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
					if (!response) return;

					this.slides.push({
						id: shared.getMaxId(this.slides) + 1,
						order: shared.getMaxOrder(this.slides) + 1,
						name: this.currentSlide.data.name.value,
						link: this.currentSlide.data.link.value,
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
			let errors = 0;

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
				errors++;

			if (this.$refs.fileImage.files().length > 0) {
				if (
					validate.checkInputsAll(this.currentSlide, [
						{
							key: "file",
							type: "file",
							value: this.$refs.fileImage.files(),
							formats: ["png", "webp"],
						},
					])
				)
					errors++;
			}

			if (errors > 0) return;

			if (this.$refs.fileImage.files().length > 0) {
				this.disabled.slider.update = true;

				let formData = new FormData();
				formData.append("file", this.$refs.fileImage.files()[0]);
				formData.append("type", "slides");
				formData.append("formats", ["png", "webp"]);

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

						let slideCurrent = this.slides.find(
							(slide) => slide.order === this.currentSlide.data.order.value
						);

						this.currentSlide.data.path.value = response.data.result;
						slideCurrent.path = response.data.result;
						slideCurrent.filename = files.basename(response.data.result);

						slideCurrent.name = this.currentSlide.data.name.value;
						slideCurrent.link = this.currentSlide.data.link.value;

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
			} else {
				let slideCurrent = this.slides.find(
					(slide) => slide.order === this.currentSlide.data.order.value
				);

				slideCurrent.name = this.currentSlide.data.name.value;
				slideCurrent.link = this.currentSlide.data.link.value;

				this.$refs.modal.close();
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

				sorted.sortNumberByKey("up", this.slides, "order");
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
