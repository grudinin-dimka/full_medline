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
			<icon-save :width="28" :height="28" @click="console.log('save')" v-else />
		</template>

		<template #body>
			<BaseTable
				v-if="loading.sections.news"
				:table="table"
				@create="$router.push('enews/new')"
				@edite="console.log('edite')"
				@delete="console.log('delete')"
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

			content: `
            <p style="text-align: left">qweqweqwe</p>
            <p style="text-align: left">qeweqw</p>
            <p style="text-align: left">qew</p>
            <p style="text-align: left">qwe</p>
            <p style="text-align: left">qwe</p>
            <p style="text-align: left">qwe</p>
            <p style="text-align: left">qwe</p>
            <p style="text-align: left">qwewqewq</p>
         `,
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
		doTest() {
			this.$refs.tiptap.exportHtml();
		},

		/* Тестовая функция */
		test(value) {
			console.log(value);
		},
	},
	mounted() {
		this.loading.loader.news = false;
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
