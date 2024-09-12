<template>
	<info-bar>
		<template v-slot:title>Специалисты</template>
		<template v-slot:addreas>specialists</template>
	</info-bar>

	<block>
		<block-title>
			<template #title>Список врачей</template>
			<template #buttons>
				<icon-save :width="28" :height="28"/>
			</template>
		</block-title>

		<admin-specialists-table :specialists="specialists" />

		<block-buttons>
			<button-default> Добавить </button-default>
		</block-buttons>
	</block>
</template>

<script>
import InfoBar from "../../../components/ui/admin/InfoBar.vue";

import LoaderChild from "../../../components/includes/LoaderChild.vue";

import ElementInputLabel from "../../../components/ui/admin/ElementInputLabel.vue";
import Block from "../../../components/ui/admin/Block.vue";
import BlockTitle from "../../../components/ui/admin/BlockTitle.vue";
import BlockButtons from "../../../components/ui/admin/BlockButtons.vue";
import AdminSpecialistsTable from "./AdminSpecialistsAllTable.vue";
import SpanError from "../../../components/ui/admin/SpanError.vue";

import ButtonDefault from "../../../components/ui/admin/ButtonDefault.vue";
import ButtonRemove from "../../../components/ui/admin/ButtonRemove.vue";

import IconSave from "../../../components/icons/IconSave.vue";

import axios from "axios";

import { RouterView, RouterLink } from "vue-router";

export default {
	components: {
      InfoBar,
      LoaderChild,
      ElementInputLabel,
      Block,
      BlockTitle, 
      BlockButtons,
      AdminSpecialistsTable,
      SpanError,
      ButtonDefault,
      ButtonRemove,
      IconSave,
      axios,
      RouterView,
      RouterLink,
   },
	data() {
		return {
         specialists: [
            {
               id: 1,
               name: "Иванов Иван Иванович",
               specialization: "Везде и нигде",
               hide: false,
               delete: false,
            },
            {
               id: 2,
               name: "Ёжиков Ёжик Ёжикович",
               specialization: "Лесной зверёк",
               hide: false,
               delete: false,
            },
            {
               id: 3,
               name: "Слонов Слон Слонович",
               specialization: "Большой и добрый",
               hide: true,
               delete: false,
            },
            {
               id: 4,
               name: "Лисов Лис Лисович",
               specialization: "Самый хитрый",
               hide: true,
               delete: false,
            },
            {
               id: 5,
               name: "Медведов Медвед Медведович",
               specialization: "Гроза леса",
               hide: false,
               delete: false,
            },
         ]
      };
	},
};
</script>

<style scoped></style>
