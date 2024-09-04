<template>
	<!-- Модальное окно -->
	<admin-modal>
		<template #title>
			Добавление нового слайда
		</template>
		<template #body>
			<BlockButtons>
				<ButtonRemove>
					Удалить
				</ButtonRemove>
				<ButtonDefault>
					Обновить
				</ButtonDefault>
			</BlockButtons>
		</template>
	</admin-modal>


	<info-bar>
		<template v-slot:title>Специалисты</template>
		<template v-slot:addreas>specialists</template>
	</info-bar>

	<block>
		<block-title>
			<template #title>Список врачей</template>
			<template #buttons>
				<icon-save :width="28" :height="28" @click="" />
			</template>
		</block-title>

      <LoaderChild :isLoading="loading.loader.specialists"></LoaderChild>

		<table-container v-if="loading.specialists" :doctors="doctors"/>

		<block-buttons>
			<button-default @click=""> Добавить </button-default>
		</block-buttons>
	</block>
</template>

<script>
import AdminModal from "../../components/includes/admin/AdminModal.vue";

import InfoBar from "../../components/ui/admin/InfoBar.vue";

import LoaderChild from "../../components/includes/LoaderChild.vue";

import BlockTitle from "../../components/ui/admin/BlockTitle.vue";
import Block from "../../components/ui/admin/Block.vue";
import BlockLabel from "../../components/ui/admin/BlockLabel.vue";
import BlockButtons from "../../components/ui/admin/BlockButtons.vue";

import TableContainer from "../../components/ui/admin/TableContainer.vue";

import ButtonDefault from "../../components/ui/admin/ButtonDefault.vue";
import ButtonRemove from "../../components/ui/admin/ButtonRemove.vue";

import IconSave from "../../components/icons/IconSave.vue";

import axios from "axios";

export default {
	components: {
      AdminModal,
		InfoBar,
      LoaderChild,
		BlockTitle,
		Block,
		BlockLabel,
		TableContainer,
		BlockButtons,
		ButtonDefault,
		ButtonRemove,
		IconSave,
      axios,
	},
	data() {
		return {
         loading: {
				loader: {
					specialists: true,
				},
				specialists: false,
			},
         doctors: [],
      };
	},
	mounted() {
		// Получение массива докторов с сервера
		axios({
			method: "post",
			url: `${this.$store.state.axios.urlApi}` + `get-doctors`,
		})
			.then((response) => {
				this.doctors = response.data;

            this.loading.loader.specialists = false;
				setTimeout(() => {
					this.loading.specialists = true;
				}, 515);
			})
			.catch((error) => {
				let debbugStory = {
					title: "Ошибка.",
					body: "Произошла ошибка при получении данных о слайдере.",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
			});
   },
};
</script>

<style scoped></style>
