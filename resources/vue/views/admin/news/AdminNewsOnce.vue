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
					<VueIcon :name="'Add'" :fill="'var(--primary-color)'" :width="'26px'" :height="'26px'" />
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
						<VueIcon :name="'Publish'" :fill="'var(--primary-color)'" :width="'28px'" :height="'28px'" />
						Опубликовать
					</VueButton>

					<VueButton look="inverse" :disabled="disabled.news.publish" @click="publishNews" v-else>
						<VueIcon :name="'Unpublished'" :fill="'var(--primary-color)'" :width="'28px'" :height="'28px'" />
						Снять с публикации
					</VueButton>
				</template>

				<VueButton look="inverse" :disabled="disabled.news.save" @click="saveNews">
					<VueIcon :name="'Save'" :fill="'var(--primary-color)'" :width="'28px'" :height="'28px'" />
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
				<VueIcon :name="'Info'" :fill="'var(--primary-color)'" :width="'30px'" :height="'30px'" />
			</VueButton>
		</template>

		<template #body>
			<div class="news__once" v-if="loading.sections.news">
				<VueFile v-model="currentNews.data.image.value" :view="'dropzone'">
					<template #label>
						<VueIcon :name="'Attach File'" :fill="'var(--primary-color)'" :width="'26px'" :height="'26px'" />
						ИЗОБРАЖЕНИЕ
					</template>
				</VueFile>

				<div class="news__once-head">
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

			<VueLoader :isLoading="loading.loader.news" :isChild="true" :minHeight="300" @afterLeave="loaderChildAfterLeave" />
		</template>
	</block-once>
</template>

<script>
import InfoBar from "../../../components/ui/admin/InfoBar.vue";
import BlockOnce from "../../../components/ui/admin/blocks/BlockOnce.vue";

import api from "../../../mixin/api";
import validate from "../../../services/validate";

export default {
	components: {
		InfoBar,
		BlockOnce,
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

.news__once-wrapper {
	position: relative;
	padding: 20px;
	border: var(--input-border);
	border-radius: calc(var(--default-border-radius) / 1.5);

	height: 400px;

	background-color: var(--item-background-color);
}

/* .news__once-wrapper > img {
	user-select: none;
	border-radius: calc(var(--default-border-radius) / 1.5);

	object-fit: contain;
	width: 100%;
	height: 100%;

	background-color: black;

	animation: show 0.5s ease;
} */
</style>
