<template>
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
               specialization: "Ортопед",
               hide: false,
               delete: false,
            }
         ]
      };
	},
};
</script>

<style scoped></style>
