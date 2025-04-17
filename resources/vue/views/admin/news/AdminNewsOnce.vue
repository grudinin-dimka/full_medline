<template>
	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                     НОВОСТИ                       |-->
	<!--|___________________________________________________|-->
	<info-bar>
		<template v-slot:title>Новости</template>
		<template v-slot:addreas>news/new</template>
	</info-bar>

	<block-once :minHeight="200">
		<template #title>НОВОСТЬ (СОЗДАНИЕ)</template>

		<template #options>
			<icon-load :width="28" :height="28" v-if="disabled.news.save" />
			<icon-save :width="28" :height="28" @click="console.log('save')" v-else />
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
							<label for="input__file" class="input__file-button">
								<span class="input__file-icon-wrapper">Файл не загружен</span>
							</label>
						</div>
					</div>

					<Tiptap
						ref="tiptap"
						:content="content"
						:editable="true"
						:limit="500"
						@export="test"
					/>
				</div>
				<div class="news__once-body">
					<Tiptap
						ref="tiptap"
						:content="content"
						:editable="true"
						:limit="10_000"
						@export="test"
					/>
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
import IconSave from "../../../components/icons/IconSave.vue";

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
		/* |                  Блок текста                      |*/
		/* |___________________________________________________|*/
		doTest() {
			this.$refs.tiptap.exportHtml();
		},

		/* Тестовая функция */
		test(value) {
			console.log(value);
		},

		handleFileChange(event) {
			let button = document.querySelector(".input__file-button");
			let buttonWrapper = document.querySelector(".input__file-icon-wrapper");

			const files = event.target.files;

			if (files && files.length > 0) {
				buttonWrapper.innerHTML = event.target.files[0].name;
				button.classList.add("active");
			}
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
	border-radius: var(--input-border-radius);
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

.input__file-button-text {
	line-height: 1;
	margin-top: 1px;
}

.input__file-button {
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

	border-radius: 20px;
	margin: 0 auto;

	width: max(250px, 700px);
	height: 300px;

	font-size: 1.25rem;

	color: rgba(0, 0, 0, 0.25);
	border: var(--input-border);
	transition: all 0.2s;
}

.input__file-button:is(:hover, .active) {
	color: var(--primary-color);
	background-color: var(--item-background-color-active);
	border: var(--input-border-focus);
}
</style>
