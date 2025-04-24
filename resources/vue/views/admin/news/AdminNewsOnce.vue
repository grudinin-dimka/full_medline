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
				<icon-load :width="28" :height="28" v-if="disabled.news.add" />
				<icon-add :width="28" :height="28" v-else @click="addNews" />
			</template>
			<template v-else>
				<icon-load :width="28" :height="28" v-if="disabled.news.save" />
				<icon-save :width="28" :height="28" @click="saveNews" v-else />
			</template>
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

					<Tiptap
						ref="tiptapTitle"
						v-model="currentNews.data.title.value"
						:editable="true"
						:limit="500"
						:look="'title'"
						:placeholder="'Заголовок'"
						:error="currentNews.errors.title.status"
					>
						<template #error>
							{{ currentNews.errors.title.message }}
						</template>
					</Tiptap>
				</div>
				<div class="news__once-body">
					<Tiptap
						ref="tiptapDescription"
						v-model="currentNews.data.description.value"
						:editable="true"
						:limit="10_000"
						:placeholder="'Текст новости'"
						:error="currentNews.errors.description.status"
					>
						<template #error>
							{{ currentNews.errors.description.message }}
						</template>
					</Tiptap>
				</div>
			</div>

			<loader-child
				:isLoading="loading.loader.news"
				:minHeight="200"
				@loaderChildAfterLeave="loaderChildAfterLeave"
			/>
		</template>
	</block-once>
</template>

<script>
import BlockOnce from "../../../components/ui/admin/blocks/BlockOnce.vue";
import LoaderChild from "../../../components/modules/LoaderChild.vue";
import InfoBar from "../../../components/ui/admin/InfoBar.vue";

import BaseTable from "../../../components/modules/table/BaseTable.vue";
import Tiptap from "../../../components/modules/Tiptap.vue";

import ButtonDefault from "../../../components/ui/admin/buttons/ButtonDefault.vue";

import IconLoad from "../../../components/icons/IconLoad.vue";
import IconAdd from "../../../components/icons/IconAdd.vue";
import IconSave from "../../../components/icons/IconSave.vue";

import axios from "axios";
import validate from "../../../services/validate";

export default {
	components: {
		BlockOnce,
		LoaderChild,
		InfoBar,

		BaseTable,
		Tiptap,

		ButtonDefault,

		IconLoad,
		IconAdd,
		IconSave,
	},
	data() {
		return {
			/* Кнопки */
			disabled: {
				news: {
					save: false,
					add: false,
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
						value: this.$refs.image,
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

			axios({
				method: "post",
				url: `${this.$store.getters.urlApi}` + `add-news`,
				headers: {
					ContentType: "multipart/form-data",
					Authorization: `Bearer ${localStorage.getItem("token")}`,
				},
				data: formData,
			})
				.then((response) => {
					if (response.data.status) {
						this.currentNews.data.id.value = response.data.data.id;
						this.currentNews.data.image.value = response.data.data.image;
						this.currentNews.data.path.value = response.data.data.path;
						this.currentNews.data.title.value = response.data.data.title;
						this.currentNews.data.description.value = response.data.data.description;

						this.hasFile = false;

						this.$router.push({
							name: "enews-once",
							params: {
								date: response.data.data.url_date,
								time: response.data.data.url_time,
							},
						});

						this.$store.commit("addDebugger", {
							title: "Успешно!",
							body: response.data.message,
							type: "completed",
						});
					} else {
						this.$store.commit("addDebugger", {
							title: "Ошибка.",
							body: response.data.message,
							type: "error",
						});
					}
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
							value: this.$refs.image,
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

			axios({
				method: "post",
				url: `${this.$store.getters.urlApi}` + `save-news-changes-once`,
				headers: {
					ContentType: "multipart/form-data",
					Authorization: `Bearer ${localStorage.getItem("token")}`,
				},
				data: formData,
			})
				.then((response) => {
					if (response.data.status) {
						for (let key in response.data.data) {
							this.currentNews.data[key].value = response.data.data[key];
						}

						this.hasFile = false;
						this.$refs.image.value = "";
						this.$refs.imageText.innerHTML = "";

						this.$store.commit("addDebugger", {
							title: "Успешно!",
							body: response.data.message,
							type: "completed",
						});
					} else {
						this.$store.commit("addDebugger", {
							title: "Ошибка.",
							body: response.data.message,
							type: "error",
						});
					}
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
	},
	mounted() {
		if (this.$route.params.date === "new" || this.$route.params.time === "new") {
			this.loading.loader.news = false;
		} else {
			axios({
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
					if (response.data.status) {
						for (let key in response.data.data) {
							this.currentNews.data[key].value = response.data.data[key];
						}

						this.loading.loader.news = false;
					} else {
						this.$store.commit("addDebugger", {
							title: "Ошибка.",
							body: response.data.message,
							type: "error",
						});
					}
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
	unmounted() {},
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
	border-radius: calc(var(--default-border-radius) / 2);

	height: 400px;

	background-color: var(--item-background-color);
}

.news__once-image > img {
	user-select: none;
	border-radius: calc(var(--default-border-radius) / 2);

	object-fit: contain;
	width: 100%;
	height: 100%;

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

	border-radius: calc(var(--default-border-radius) / 2);
	margin: 0 auto;

	width: 100%;

	font-size: 1.25rem;

	color: rgba(0, 0, 0, 0.25);
	background-color: white;

	transition: all 0.2s;
}

.input__file-button:is(:hover, .active) {
	color: var(--primary-color);
	background-color: var(--item-background-color-active);
}

.input__file-button.error {
	border-radius: 10px 10px 0px 0px;
	background-color: var(--input-error-background-color);

	color: var(--input-error-color);
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
