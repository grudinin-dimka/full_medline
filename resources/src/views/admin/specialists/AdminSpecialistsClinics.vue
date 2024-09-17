<template>
	<info-bar>
		<template v-slot:title>Специалисты</template>
		<template v-slot:addreas>specialists</template>
	</info-bar>

	<block-once>
		<block-title>
			<template #title>Клиники</template>
			<template #buttons>
				<icon-save :width="28" :height="28" />
			</template>
		</block-title>

		<admin-specialists-table :array="specializations" @useFilter="filterSpecializations" />

		<block-buttons>
			<button-default> Добавить </button-default>
		</block-buttons>
	</block-once>
</template>

<script>
import InfoBar from "../../../components/ui/admin/InfoBar.vue";

import LoaderChild from "../../../components/includes/LoaderChild.vue";

import ElementInputLabel from "../../../components/ui/admin/ElementInputLabel.vue";
import BlockOnce from "../../../components/ui/admin/BlockOnce.vue";
import BlockTitle from "../../../components/ui/admin/BlockTitle.vue";
import BlockButtons from "../../../components/ui/admin/BlockButtons.vue";

import AdminSpecialistsTable from "./AdminSpecialistsTable.vue";

import ButtonDefault from "../../../components/ui/admin/ButtonDefault.vue";
import ButtonRemove from "../../../components/ui/admin/ButtonRemove.vue";

import IconSave from "../../../components/icons/IconSave.vue";

export default {
	components: {
		InfoBar,
		LoaderChild,
		ElementInputLabel,
		AdminSpecialistsTable,
		BlockOnce,
		BlockTitle,
		BlockButtons,
		ButtonDefault,
		ButtonRemove,
		IconSave,
	},
	data() {
		return {
			specializations: [
				{
					id: 1,
					name: "г. Шадринск, ул. Комсомольская, 16",
				},
				{
					id: 2,
					name: "г. Шадринск, ул. Карла Либкнехта, 10",
				},
				{
					id: 3,
					name: "г. Шадринск, ул. Октябрьская, 3",
				},
				{
					id: 4,
					name: "р. п. Каргаполье, ул. Мира, 5г",
				},
			],
		};
	},
	methods: {
		/* _____________________________________________________*/
		/* ?. Фильтрация                                        */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		filterSpecializations(column, type) {
			switch (column) {
				case "id":
					if (type == "default") {
						this.specializations.sort((a, b) => {
							if (a.id > b.id) {
								return 1;
							}
							if (a.id < b.id) {
								return -1;
							}
							// a должно быть равным b
							return 0;
						});
					}

					if (type == "reverse") {
						this.specializations.sort((a, b) => {
							if (a.id < b.id) {
								return 1;
							}
							if (a.id > b.id) {
								return -1;
							}
							// a должно быть равным b
							return 0;
						});
					}
					break;
				case "name":
					if (type == "default") {
						this.specializations.sort((a, b) => {
							let aName = a.name.toLowerCase();
							let bName = b.name.toLowerCase();

							let aNameFirstLetter = aName[0].charCodeAt(0);
							let bNameFirstLetter = bName[0].charCodeAt(0);

							if (aNameFirstLetter > bNameFirstLetter) {
								return 1;
							}
							if (aNameFirstLetter < bNameFirstLetter) {
								return -1;
							}
							// a должно быть равным b
							return 0;
						});
					}

					if (type == "reverse") {
						this.specializations.sort((a, b) => {
							let aName = a.name.toLowerCase();
							let bName = b.name.toLowerCase();

							let aNameFirstLetter = aName[0].charCodeAt(0);
							let bNameFirstLetter = bName[0].charCodeAt(0);

							if (aNameFirstLetter < bNameFirstLetter) {
								return 1;
							}
							if (aNameFirstLetter > bNameFirstLetter) {
								return -1;
							}
							// a должно быть равным b
							return 0;
						});
					}

					break;
				default:
					break;
			}
		},
	},
};
</script>

<style scoped></style>
