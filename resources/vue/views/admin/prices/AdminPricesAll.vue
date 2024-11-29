<template>
	<info-bar>
		<template v-slot:title>Цены</template>
		<template v-slot:addreas>prices</template>
	</info-bar>

	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                    СПИСОК ЦЕН                     |-->
	<!--|___________________________________________________|-->
	<block-once :minHeight="200">
		<block-title>
			<template #title>СПИСОК ЦЕН</template>
			<template #buttons>
				<icon-load :width="28" :height="28" v-if="false" />
				<icon-save :width="28" :height="28" @click="" v-else />
			</template>
		</block-title>

		<div class="eprices" v-if="loading.sections.prices">
			<div
				class="item"
				:class="{ active: price.id === getActivePriceid, delete: price.delete }"
				v-for="price in prices"
				:key="price.id"
			>
				<div class="info" @click="updateStatus(price)">
					<input type="radio" :value="price.id" v-model="radioPrice" />
					<div class="filename">{{ price.filename }}</div>
					<div class="date">{{ formatDate(price.created_at) }}</div>
				</div>
				<div class="buttons">
					<a :href="price.path" download>
						<TableButtonDefault>Скачать</TableButtonDefault>
					</a>
					<TableButtonRemove @click="updateDeleteElement(price)">Удалить</TableButtonRemove>
				</div>
			</div>
		</div>

		<fieldset>
			<legend>Радиокнопки</legend>
			<div class="radio">
				<input class="radio-input" type="radio" id="radio2" name="radio" />
				<label class="radio-label" for="radio2">Радиокнопка 2</label>
			</div>
			<div class="radio">
				<input class="radio-input" type="radio" id="radio2-c" name="radio" checked="" />
				<label class="radio-label" for="radio2-c">Радиокнопка 2 (отмечена)</label>
			</div>
		</fieldset>

		<LoaderChild
			:isLoading="loading.loader.prices"
			:minHeight="300"
			@loaderChildAfterLeave="loaderChildAfterLeave"
		></LoaderChild>

		<BlockButtons>
			<ButtonDefault @click=""> Добавить </ButtonDefault>
		</BlockButtons>
	</block-once>
</template>

<script>
import LoaderChild from "../../../components/includes/LoaderChild.vue";

import BlockOnce from "../../../components/ui/admin/blocks/BlockOnce.vue";
import BlockTitle from "../../../components/ui/admin/blocks/BlockTitle.vue";
import InfoBar from "../../../components/ui/admin/InfoBar.vue";

import TableButtonDefault from "../../../components/ui/admin/tables/TableButtonDefault.vue";
import TableButtonRemove from "../../../components/ui/admin/tables/TableButtonRemove.vue";

import ButtonDefault from "../../../components/ui/admin/buttons/ButtonDefault.vue";
import BlockButtons from "../../../components/ui/admin/blocks/BlockButtons.vue";

import IconSave from "../../../components/icons/IconSave.vue";
import IconLoad from "../../../components/icons/IconLoad.vue";

export default {
	components: {
		LoaderChild,
		BlockOnce,
		BlockTitle,
		InfoBar,
		TableButtonDefault,
		TableButtonRemove,
		ButtonDefault,
		BlockButtons,
		IconSave,
		IconLoad,
	},
	data() {
		return {
			loading: {
				loader: {
					prices: true,
				},
				sections: {
					prices: false,
				},
			},
			radioPrice: null,
			prices: [
				{
					id: 1,
					created_at: "2024-01-01",
					status: true,
					filename: "1.ods",
					path: "/storage/files/1.ods",
					create: false,
					delete: false,
				},
				{
					id: 2,
					created_at: "2024-01-02",
					status: false,
					filename: "2.ods",
					path: "/storage/files/2.ods",
					create: false,
					delete: false,
				},
				{
					id: 3,
					created_at: "2024-01-03",
					status: false,
					filename: "3.ods",
					path: "/storage/files/3.ods",
					create: false,
					delete: false,
				},
			],
		};
	},
	computed: {
		getActivePriceid() {
			return this.prices.find((price) => price.status).id;
		},
	},
	methods: {
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                   ЗАГРУЗЧИК                       |*/
		/* |___________________________________________________|*/
		/* После скрытия элементы */
		loaderChildAfterLeave() {
			this.loading.sections.prices = true;
		},
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                      ЦЕНЫ                         |*/
		/* |___________________________________________________|*/
		/* Изменение статуса */
		updateStatus(price) {
			this.prices.forEach((item) => {
				if (item.id === price.id) {
					this.radioPrice = price.id;
					item.status = true;
				} else {
					item.status = false;
				}
			});
		},
		/* Пометка на удаление */
		updateDeleteElement(price) {
			let currentPrice = this.prices.find((item) => item.id === price.id);

			currentPrice.delete = !currentPrice.delete;
		},
		/* Форматирование даты */
		formatDate(created_at) {
			let date = new Date(created_at);

			let options = {
				month: "long",
				day: "numeric",
				year: "numeric",
			};

			return date.toLocaleString("ru", options);
		},
	},
	mounted() {
		this.loading.loader.prices = false;

		this.radioPrice = this.getActivePriceid;
	},
};
</script>

<style scoped>
.eprices {
	display: flex;
	flex-direction: column;
	gap: 10px;

	font-size: 18px;

	animation: show-bottom-to-top-15 0.5s ease-in-out;
}

.eprices > .item {
	display: flex;
	justify-content: space-between;
	padding: 15px;

	border: 2px solid var(--input-border-color-inactive);
	border-radius: 10px;

	transition: all 0.2s;
}

.eprices > .item.active {
	background-color: #f2feff;
	border: 2px solid var(--input-border-color-active);
}

.eprices > .item.delete {
	border: 2px solid #ec7b7b;
}

.eprices > .item:has(.info > .radio:hover) {
	background-color: #f2feff;
	border: 2px solid var(--input-border-color-active);
}

.eprices > .item.delete:has(.info > .radio:hover) {
	border: 2px solid #ec7b7b;
	background-color: #fff2f2;
}

.eprices > .item > .info {
	display: grid;
	align-items: center;
	grid-template-columns: 22px 300px 300px;
}

.eprices > .item > .info > .filename {
	margin-left: 10px;
}

.eprices > .item > .buttons {
	display: flex;
	gap: 10px;
}

/* radio */
.radio {
	margin-top: 5px;
	width: fit-content;
	display: flex;
	justify-content: center;
	align-items: center;
}

.radio-label {
	padding-left: 5px;
}

.radio-input {
	appearance: none;
	position: relative;
	width: 20px;
	height: 20px;
	background: #2cafbd;
	/* box-shadow: inset 0 0 5px rgb(0 0 0 / 0.2); */
	border-radius: 10px;
	border: 1px solid #ffffff;
	transition: 500ms;
}

.radio-input {
	border-radius: 50%;
}

.radio-input::after {
	content: "\2714";
	position: absolute;
	top: -5px;
	left: -1px;
	width: 0px;
	height: 0px;
	font-size: 26px;
	transition: 500ms;
	overflow: hidden;
}

.radio-input::after {
	content: "\1F78B";
	color: white;
}

.radio-input::after {
	content: "";
	position: absolute;
	width: 0px;
	height: 0px;
	font-size: 20px;
	background-image: url("./check.svg");
	background-repeat: no-repeat;
	transition: 500ms;
}

.radio-input::after {
	background-image: url("./check.svg");
}

.radio-input:checked::after {
	width: 26px;
	height: 26px;
	transition: 500ms;
}
</style>
