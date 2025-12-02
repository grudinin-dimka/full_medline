<template>
	<!-- Новости -->
	<info-bar>
		<template v-slot:title>Новости</template>
		<template v-slot:addreas>news</template>
	</info-bar>

	<block-once :minHeight="200">
		<template #title>НОВОСТИ</template>

		<template #options>
			<VueButton @click.prevent="saveNewsAll" :disabled="disabled.news.save" :look="'inverse'">
				<VueIcon
					:name="'Save'"
					:fill="'var(--primary-color)'"
					:width="'28px'"
					:height="'28px'"
				/>
				Сохранить
			</VueButton>

			<VueButton
				:look="'inverse'"
				@click="
					$store.commit('openWiki', {
						title: 'НОВОСТИ',
						component: 'NewsAll',
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
			<VueTable
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
			>
				<template v-slot:hide="{ row }">
					<div class="table__hide" v-if="row.hide" @click="setHideNews(row)">Да</div>
					<div class="table__hide" v-else @click="setHideNews(row)">Нет</div>
				</template>
			</VueTable>

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
import InfoBar from "../../../components/ui/admin/InfoBar.vue";
import BlockOnce from "../../../components/ui/admin/blocks/BlockOnce.vue";

import shared from "../../../services/shared";
import api from "../../../mixin/api";

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
					{
						name: "id",
						title: "ID",
						type: "id",
						hide: true,
					},
					{
						name: "date",
						title: "Дата",
						type: "time",
						options: {
							column: {
								style: {
									width: "200px",
								},
							},
							data: {
								style: {
									justifyContent: "center",
								},
							},
						},
					},
					{
						name: "title",
						title: "Заголовок",
						type: "string",
					},
					{
						name: "hide",
						title: "Публикация",
						type: "slot",
						options: {
							column: {
								style: {
									width: "100px",
								},
							},
							data: {
								style: {
									justifyContent: "center",
								},
							},
						},
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

		/* Скрытие */
		setHideNews(news) {
			news.hide = !news.hide;
		},

		/* Сохранение */
		saveNewsAll() {
			this.disabled.news.save = true;

			api({
				method: "post",
				url: this.$store.getters.urlApi + `save-news-changes-all`,
				headers: {
					Accept: "application/json",
				},
				data: {
					news: this.table.body,
				},
			})
				.then((response) => {
					if (!response) return;

					shared.clearDeletes(this.table.body);
					shared.clearFlags(this.table.body);
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
		api({
			method: "get",
			url: this.$store.getters.urlApi + `get-news-all`,
		})
			.then((response) => {
				if (!response) return;

				this.table.body = response.data.result;

				for (let i = 0; i < this.table.body.length; i++) {
					const tempDiv = document.createElement("div");
					tempDiv.innerHTML = this.table.body[i].title;
					const plainText = tempDiv.textContent || tempDiv.innerText;

					this.table.body[i].title = plainText;
					this.table.body[i].create = false;
					this.table.body[i].delete = false;
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
/* Таблица */
.table__hide {
	user-select: none;
	cursor: pointer;
}

.table__hide:hover {
	color: var(--primary-color);
}
</style>
