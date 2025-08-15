<template>
	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                     НОВОСТИ                       |-->
	<!--|___________________________________________________|-->
	<info-bar>
		<template v-slot:title>Новости</template>
		<template v-slot:addreas>news/{{ $route.params.date }}/{{ $route.params.time }}</template>
	</info-bar>

	<block-once :minHeight="200">
		<template #title>БЛОК НОВОСТЕЙ</template>

		<template #options>
			<template v-if="$route.params.date === 'new' || $route.params.time === 'new'">
				<VueButton look="inverse" :disabled="disabled.news.add" @click="addNews">
					<VueIcon
						:name="'Add'"
						:fill="'var(--primary-color)'"
						:width="'26px'"
						:height="'26px'"
					/>
					Добавить
				</VueButton>
			</template>
			<template v-else>
				<template v-if="loading.sections.news">
					<VueButton
						look="inverse"
						:disabled="disabled.news.publish"
						@click="publishNews"
						v-if="!currentNews.data.hide.value"
					>
						<VueIcon
							:name="'Publish'"
							:fill="'var(--primary-color)'"
							:width="'28px'"
							:height="'28px'"
						/>
						Опубликовать
					</VueButton>

					<VueButton
						look="inverse"
						:disabled="disabled.news.publish"
						@click="publishNews"
						v-else
					>
						<VueIcon
							:name="'Unpublished'"
							:fill="'var(--primary-color)'"
							:width="'28px'"
							:height="'28px'"
						/>
						Убрать с публикации
					</VueButton>
				</template>

				<VueButton look="inverse" :disabled="disabled.news.save" @click="saveNews">
					<VueIcon
						:name="'save'"
						:fill="'var(--primary-color)'"
						:width="'28px'"
						:height="'28px'"
					/>
					Сохранить
				</VueButton>
			</template>

			<VueButton
				:look="'inverse'"
				@click="
					$store.commit('openWiki', {
						title: 'НОВОСТИ',
						component: 'NewsOnce',
					})
				"
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
			<div class="news__once" v-if="loading.sections.news">
				<div class="news__once-head">
					<div class="news__once-image">
						<template v-if="$route.params.date === 'new' || $route.params.time === 'new'">
							<div class="input__wrapper">
								<input
									name="file"
									type="file"
									id="input__file"
									class="input input__file"
									ref="image"
									@change="handleFileChange"
									multiple
								/>
								<label
									for="input__file"
									class="input__file-button"
									:class="{
										active: hasFile,
										error: currentNews.errors.image.status,
									}"
								>
									<span class="input__file-icon-wrapper" ref="imageWrapper">
										<span class="input__file-text" ref="imageText">Файл не загружен</span>
									</span>
								</label>
								<div
									class="input__file-button-error"
									v-if="currentNews.errors.image.status"
								>
									{{ currentNews.errors.image.message }}
								</div>
							</div>
						</template>

						<template v-else>
							<div class="input__wrapper image">
								<input
									name="file"
									type="file"
									id="input__file"
									class="input input__file"
									ref="image"
									@change="handleFileChange"
									multiple
								/>
								<label
									for="input__file"
									class="input__file-button"
									:class="{
										active: hasFile,
										error: currentNews.errors.image.status,
									}"
								>
									<div class="input__file-icon-wrapper" ref="imageWrapper">
										<svg
											v-if="!hasFile"
											xmlns="http://www.w3.org/2000/svg"
											height="60px"
											viewBox="0 -960 960 960"
											width="60px"
										>
											<path
												d="M440-440H200v-80h240v-240h80v240h240v80H520v240h-80v-240Z"
											/>
										</svg>

										<span class="input__file-text" ref="imageText"></span>
									</div>
								</label>
								<div
									class="input__file-button-error"
									v-if="currentNews.errors.image.status"
								>
									{{ currentNews.errors.image.message }}
								</div>
							</div>

							<img :src="currentNews.data.path.value" alt="Фото новости" />
						</template>
					</div>

					<VueTiptap
						ref="tiptapTitle"
						v-model="currentNews.data.title.value"
						:editable="true"
						:limit="500"
						:options="['format', 'align']"
						:placeholder="'Заголовок'"
						:error="currentNews.errors.title.status"
					>
						<template #error>
							{{ currentNews.errors.title.message }}
						</template>
					</VueTiptap>
				</div>
				<div class="news__once-body">
					<VueTiptap
						ref="tiptapDescription"
						v-model="currentNews.data.description.value"
						:editable="true"
						:limit="10_000"
						:minHeight="300"
						:options="['heading', 'format', 'align', 'list', 'link', 'image']"
						:placeholder="'Текст новости'"
						:error="currentNews.errors.description.status"
					>
						<template #error>
							{{ currentNews.errors.description.message }}
						</template>
					</VueTiptap>
				</div>
			</div>

			<VueLoader
				:isLoading="loading.loader.news"
				:isChild="true"
				:minHeight="300"
				@afterLeave="loaderChildAfterLeave"
			/>
		</template>
	</block-once>
</template>

<script>
import VueTiptap from "../../../components/modules/VueTiptap.vue";

import InfoBar from "../../../components/ui/admin/InfoBar.vue";
import BlockOnce from "../../../components/ui/admin/blocks/BlockOnce.vue";

import VueLoader from "../../../components/modules/VueLoader.vue";
import VueIcon from "../../../components/modules/icon/VueIcon.vue";
import VueButton from "../../../components/ui/VueButton.vue";

import api from "../../../mixin/api";
import validate from "../../../services/validate";

export default {
	components: {
		VueTiptap,

		InfoBar,
		BlockOnce,

		VueLoader,
		VueIcon,
		VueButton,
	},
	data() {
		return {
			/* Кнопки */
			disabled: {
				news: {
					save: false,
					add: false,
					publish: false,
				},
			},

			/* Загрузчик */
			loading: {
				loader: {
					news: true,
				},
				sections: {
					news: false,
				},
			},

			/* Форма */
			currentNews: {
				errors: {
					id: {
						status: false,
						message: null,
					},
					image: {
						status: false,
						message: null,
					},
					title: {
						status: false,
						message: null,
					},
					description: {
						status: false,
						message: null,
					},
				},
				data: {
					id: {
						value: "",
						edited: false,
					},
					hide: {
						value: false,
						edited: false,
					},
					path: {
						value: "",
						edited: false,
					},
					image: {
						value: "",
						edited: false,
					},
					title: {
						value: "",
						edited: false,
					},
					description: {
						value: "",
						edited: false,
					},
				},
			},

			/* Файл */
			hasFile: false,

			/* Таблица */
			table: {
				// Настройки
				options: {
					create: true,
					delete: true,
					update: true,
					report: false,
				},

				// Колонки
				head: [
					{ name: "id", text: "ID", columnType: "id" },
					{
						name: "date",
						text: "Дата",
						columnType: "time",
						columnSize: "200px",
					},
					{
						name: "title",
						text: "Заголовок",
						columnType: "default",
						columnSize: "auto",
					},
				],

				// Элементы
				body: [],
			},

			content: "",
		};
	},
	methods: {
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                   Загрузчик                       |*/
		/* |___________________________________________________|*/
		/* После скрытия элементы */
		loaderChildAfterLeave() {
			this.loading.sections.news = true;
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                      Файл                         |*/
		/* |___________________________________________________|*/
		handleFileChange(event) {
			const files = event.target.files;

			if (files && files.length > 0) {
				this.hasFile = true;

				this.$refs.imageText.innerHTML = event.target.files[0].name;
			}
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                  База данных                      |*/
		/* |___________________________________________________|*/
		/* Добавление данных */
		addNews() {
			if (
				validate.checkInputsAll(this.currentNews, [
					{
						key: "image",
						type: "file",
						value: this.$refs.image.files,
						formats: ["jpg", "jpeg", "png", "webp"],
					},
					{
						key: "title",
						type: "tiptap",
						value: this.$refs.tiptapTitle,
					},
					{
						key: "description",
						type: "tiptap",
						value: this.$refs.tiptapDescription,
					},
				])
			)
				return;

			let formData = new FormData();
			formData.append("image", this.$refs.image.files[0]);
			formData.append("title", this.currentNews.data.title.value);
			formData.append("description", this.currentNews.data.description.value);

			this.disabled.news.add = true;

			api({
				method: "post",
				url: this.$store.getters.urlApi + `add-news`,
				headers: {
					ContentType: "multipart/form-data",
				},
				data: formData,
			})
				.then((response) => {
					if (!response) return;

					this.currentNews.data.id.value = response.data.result.id;
					this.currentNews.data.image.value = response.data.result.image;
					this.currentNews.data.path.value = response.data.result.path;
					this.currentNews.data.title.value = response.data.result.title;
					this.currentNews.data.description.value = response.data.result.description;

					this.hasFile = false;

					this.$router.push({
						name: "enews-once",
						params: {
							date: response.data.result.url_date,
							time: response.data.result.url_time,
						},
					});
				})
				.catch((error) => {
					this.$store.commit("addDebugger", {
						title: "Ошибка.",
						body: error,
						type: "error",
					});
				})
				.finally(() => {
					this.disabled.news.add = false;
				});
		},

		/* Сохранение изменений */
		saveNews() {
			let errors = 0;

			if (
				validate.checkInputsAll(this.currentNews, [
					{
						key: "title",
						type: "tiptap",
						value: this.$refs.tiptapTitle,
					},
					{
						key: "description",
						type: "tiptap",
						value: this.$refs.tiptapDescription,
					},
				])
			)
				errors++;

			let formData = new FormData();
			if (this.$refs.image.files[0]) {
				if (
					validate.checkInputsAll(this.currentNews, [
						{
							key: "image",
							type: "file",
							value: this.$refs.image.files,
							formats: ["jpg", "jpeg", "png", "webp"],
						},
					])
				)
					errors++;

				formData.append("image", this.$refs.image.files[0]);
			}

			if (errors) return;

			formData.append("id", this.currentNews.data.id.value);
			formData.append("title", this.currentNews.data.title.value);
			formData.append("description", this.currentNews.data.description.value);

			this.disabled.news.save = true;

			api({
				method: "post",
				url: this.$store.getters.urlApi + `save-news-changes-once`,
				headers: {
					ContentType: "multipart/form-data",
				},
				data: formData,
			})
				.then((response) => {
					if (!response) return;

					for (let key in response.data.result) {
						this.currentNews.data[key].value = response.data.result[key];
					}

					this.hasFile = false;
					this.$refs.image.value = "";
					this.$refs.imageText.innerHTML = "";
				})
				.catch((error) => {
					this.$store.commit("addDebugger", {
						title: "Ошибка.",
						body: error,
						type: "error",
					});
				})
				.finally(() => {
					this.disabled.news.save = false;
				});
		},

		publishNews() {
			this.disabled.news.publish = true;

			api({
				method: "post",
				url: this.$store.getters.urlApi + `publish-news-once`,
				headers: {
					ContentType: "multipart/form-data",
				},
				data: {
					id: this.currentNews.data.id.value,
				},
			})
				.then((response) => {
					if (!response) return;

					this.currentNews.data.hide.value = response.data.result;
				})
				.catch((error) => {
					this.$store.commit("addDebugger", {
						title: "Ошибка.",
						body: error,
						type: "error",
					});
				})
				.finally(() => {
					this.disabled.news.publish = false;
				});
		},
	},
	mounted() {
		if (this.$route.params.date === "new" || this.$route.params.time === "new") {
			this.loading.loader.news = false;
		} else {
			api({
				method: "post",
				url: `${this.$store.getters.urlApi}` + `get-news-once`,
				headers: {
					Accept: "application/json",
				},
				data: {
					date: this.$route.params.date,
					time: this.$route.params.time,
				},
			})
				.then((response) => {
					if (!response) return;

					for (let key in response.data.result) {
						this.currentNews.data[key].value = response.data.result[key];
					}

					this.loading.loader.news = false;
				})
				.catch((error) => {
					this.$store.commit("addDebugger", {
						title: "Ошибка.",
						body: error,
						type: "error",
					});
				});
		}
	},
};
</script>

<style scoped>
.news__once {
	display: flex;
	flex-direction: column;
	gap: 20px;

	animation: show-bottom-to-top-15 0.5s ease-in-out;
}

.news__once-head {
	display: flex;
	flex-direction: column;
	justify-content: center;
	gap: 20px;
}

.news__once-image {
	position: relative;
	padding: 20px;
	border: var(--input-border);
	border-radius: calc(var(--default-border-radius) / 1.5);

	height: 400px;

	background-color: var(--item-background-color);
}

.news__once-image > img {
	user-select: none;
	border-radius: calc(var(--default-border-radius) / 1.5);

	object-fit: contain;
	width: 100%;
	height: 100%;

	background-color: black;

	animation: show 0.5s ease;
}

.input__wrapper {
	height: 100%;
	width: 100%;
	position: relative;
	text-align: center;

	display: flex;
	flex-direction: column;
}

.input__file {
	opacity: 0;
	visibility: hidden;
	position: absolute;
}

.input__file-icon-wrapper {
	margin-right: 15px;
	display: -webkit-box;
	display: -ms-flexbox;
	display: flex;
	flex-wrap: wrap;
	word-wrap: break-word;
	word-break: break-all;
	width: 250px;

	-webkit-box-align: center;
	-ms-flex-align: center;
	align-items: center;
	-webkit-box-pack: center;
	-ms-flex-pack: center;
	justify-content: center;
}

.input__file-button {
	box-sizing: border-box;
	flex: 1 0 100px;
	user-select: none;
	cursor: pointer;
	display: -webkit-box;
	display: -ms-flexbox;
	display: flex;
	-webkit-box-align: center;
	-ms-flex-align: center;
	align-items: center;
	-webkit-box-pack: start;
	-ms-flex-pack: start;
	justify-content: center;

	border: var(--default-border);
	border-radius: calc(var(--default-border-radius) / 2);
	margin: 0 auto;

	width: 100%;

	font-size: 1.25rem;

	color: rgba(0, 0, 0, 0.25);
	background-color: white;

	transition: all 0.2s;
}

.input__file-button:is(:hover, .active) {
	border: var(--input-border-focus);
	color: var(--primary-color);
	background-color: var(--item-background-color-active);
}

.input__file-button.error {
	border: var(--input-error-border);
	border-radius: 10px 10px 0px 0px;

	color: var(--input-error-color);
	background-color: var(--input-error-background-color);
}

/* .image */
.input__wrapper.image {
	position: absolute;

	width: calc(100% - 40px);
	height: calc(100% - 40px);
}

.input__wrapper.image {
	position: absolute;

	width: calc(100% - 40px);
	height: calc(100% - 40px);
}

.input__wrapper.image > .input__file-button {
	color: rgba(0, 0, 0, 0.25);
	background-color: rgba(255, 255, 255, 0);
	border: 0px;
	transition: all 0.2s;
}

.input__wrapper.image > .input__file-button:is(:hover, .active) {
	background-color: rgba(0, 0, 0, 0.2);
	color: white;
}

.input__wrapper > .input__file-button-error {
	margin: 0px;
	padding: 10px;
	background-color: rgba(135, 10, 10, 0.6);

	color: rgb(255, 255, 255);

	border-radius: 0px 0px 10px 10px;
}

.input__wrapper.image > .input__file-button.error {
	border-radius: 10px 10px 0px 0px;
}

.input__wrapper.image > .input__file-button > .input__file-icon-wrapper {
	visibility: hidden;
	fill: white;
}

.input__wrapper.image > .input__file-button:is(:hover, .active) > .input__file-icon-wrapper {
	visibility: visible;
}
</style>
