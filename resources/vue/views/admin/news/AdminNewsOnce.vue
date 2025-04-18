<template>
	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                     НОВОСТИ                       |-->
	<!--|___________________________________________________|-->
	<info-bar>
		<template v-slot:title>Новости</template>
		<template v-slot:addreas>news/new</template>
	</info-bar>

	<block-once :minHeight="200">
		<template #title>БЛОК НОВОСТЕЙ</template>

		<template #options>
			<template v-if="$route.params.id === 'new'">
				<icon-load :width="28" :height="28" v-if="disabled.news.add" />
				<icon-add :width="28" :height="28" v-else @click="addNews" />
			</template>
			<template v-else>
				<icon-load :width="28" :height="28" v-if="disabled.news.save" />
				<icon-save :width="28" :height="28" @click="console.log('save')" v-else />
			</template>
		</template>

		<template #body>
			<div class="news__once" v-if="loading.sections.news">
				<div class="news__once-head">
					<div class="news__once-image">
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
									Файл не загружен
								</span>
							</label>
							<div class="input__file-button-error" v-if="currentNews.errors.image.status">
								{{ currentNews.errors.image.message }}
							</div>
						</div>
					</div>

					<Tiptap
						ref="tiptapTitle"
						v-model="currentNews.data.title.value"
						:editable="true"
						:limit="500"
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

				this.$refs.imageWrapper.innerHTML = event.target.files[0].name;
			}
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                  База данных                      |*/
		/* |___________________________________________________|*/
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
						console.log(response.data.data);
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
	},
	mounted() {
		this.loading.loader.news = false;
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
	padding: 20px;
	border: var(--input-border);
	border-radius: calc(var(--default-border-radius) / 2);

	background-color: var(--item-background-color);
}

.input__wrapper {
	width: 100%;
	position: relative;
	text-align: center;
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

.input__file-button-error {
	color: var(--input-error-color);
	margin-top: 5px;
}

.input__file-button {
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

	width: max(250px, 700px);
	height: 300px;

	font-size: 1.25rem;

	color: rgba(0, 0, 0, 0.25);
	background-color: white;
	border: var(--input-border);
	transition: all 0.2s;
}

.input__file-button:is(:hover, .active) {
	color: var(--primary-color);
	background-color: var(--item-background-color-active);
	border: var(--input-border-focus);
}

.input__file-button.error {
	border: var(--input-error-border);
	background-color: var(--input-error-background-color);

	color: var(--input-error-color);
}
</style>
