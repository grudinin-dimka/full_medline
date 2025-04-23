<template>
	<!-- Новости -->
	<info-bar>
		<template v-slot:title>Новости</template>
		<template v-slot:addreas>news</template>
	</info-bar>

	<block-once :minHeight="200">
		<template #title>НОВОСТИ</template>

		<template #options>
			<icon-load :width="28" :height="28" v-if="disabled.news.save" />
			<icon-save :width="28" :height="28" @click="saveNewsAll" v-else />
		</template>

		<template #body>
			<BaseTable
				v-if="loading.sections.news"
				:table="table"
				@create="
					$router.push({
						name: 'enews-once',
						params: {
							date: 'new',
							time: 'new',
						},
					})
				"
				@edite="openNews"
				@delete="setFlagDelete"
			/>

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
import IconSave from "../../../components/icons/IconSave.vue";

import shared from "../../../services/shared";
import axios from "axios";

export default {
	components: {
		BlockOnce,
		LoaderChild,
		InfoBar,

		BaseTable,
		Tiptap,

		ButtonDefault,

		IconLoad,
		IconSave,
	},
	data() {
		return {
			/* Кнопки */
			disabled: {
				news: {
					save: false,
					update: false,
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
					discription: {
						status: false,
						message: null,
					},
				},
				data: {
					id: {
						edited: false,
						value: null,
					},
					image: {
						edited: false,
						value: null,
					},
					title: {
						edited: false,
						value: null,
					},
					discription: {
						edited: false,
						value: null,
					},
				},
			},

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
		/* |                 Модальное окно                    |*/
		/* |___________________________________________________|*/
		/* Открытие модального окна */
		openModal(name, title, look) {
			this[name].values.title = title;
			this[name].values.look = look;

			this.$refs[name].open();
		},

		/* Открытие модального окна для добавления */
		openNewsCreate() {
			shared.clearObjectFull(this.currentNews);

			this.openModal("modalNews", "НОВОСТЬ", "create");
		},

		/* Открытие модального окна для добавления */
		openNewsDefault() {
			shared.clearObjectFull(this.currentNews);

			this.openModal("modalNews", "НОВОСТЬ", "default");
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                  Блок текста                      |*/
		/* |___________________________________________________|*/
		/* Открытие */
		openNews(news) {
			this.$router.push({
				name: "enews-once",
				params: {
					date: news.url_date,
					time: news.url_time,
				},
			});
		},

		/* Удаление */
		setFlagDelete(news) {
			news.delete = !news.delete;
		},

		/* Сохранение */
		saveNewsAll() {
			this.disabled.news.save = true;

			axios({
				method: "post",
				url: `${this.$store.getters.urlApi}` + `save-news-changes-all`,
				headers: {
					ContentType: "multipart/form-data",
					Authorization: `Bearer ${localStorage.getItem("token")}`,
				},
				data: {
					news: this.table.body,
				},
			})
				.then((response) => {
					if (response.data.status) {
						shared.clearDeletes(this.table.body);
						shared.clearFlags(this.table.body);

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
		axios({
			method: "get",
			url: `${this.$store.getters.urlApi}` + `get-news-all`,
		})
			.then((response) => {
				if (response.data.status) {
					this.table.body = response.data.data;

					for (let i = 0; i < this.table.body.length; i++) {
						const tempDiv = document.createElement("div");
						tempDiv.innerHTML = this.table.body[i].title;
						const plainText = tempDiv.textContent || tempDiv.innerText;

						this.table.body[i].title = plainText;
						this.table.body[i].create = false;
						this.table.body[i].delete = false;
					}
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
				this.loading.loader.news = false;
			});
	},
};
</script>

<style scoped>
.news {
	display: flex;
	flex-direction: column;
	gap: 20px;
}

.news__head {
	display: flex;
	justify-content: space-between;
	gap: 20px;
}

.news__head-title {
	font-size: 1.5rem;
	font-weight: bold;
	color: var(--primary-color);
}

.news__head-button {
	cursor: pointer;
	border: 0px;

	font-size: 1.25rem;

	background-color: rgba(0, 0, 0, 0);
	color: rgba(0, 0, 0, 0.5);
}

.news__head-button:hover {
	color: rgba(0, 0, 0, 1);
}

.news__body {
	display: grid;
	justify-content: space-between;
	gap: 20px;

	grid-template-columns: repeat(3, 1fr);
}

.news__item {
	display: flex;
	flex-direction: column;
	justify-content: space-between;
	gap: 10px;

	padding: 20px;
	border: var(--input-border);
	border-radius: var(--default-border-radius);
}

.news__item-date {
	font-size: 1.125rem;
	color: rgba(0, 0, 0, 0.5);
}

.news__item-image {
	width: 100%;
	max-height: 200px;
	object-fit: contain;
	border-radius: var(--default-border-radius);
}

.news__item-title {
	font-size: 1.25rem;
}

.news__item-content {
	height: 100px;
	overflow-y: auto;

	font-size: 1.125rem;
}
</style>
