<template>
	<info-bar>
		<template v-slot:title>Контакты</template>
		<template v-slot:addreas>contacts</template>
	</info-bar>

	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                      СЛАЙДЕР                      |-->
	<!--|___________________________________________________|-->
	<block-once>
		<block-title>
			<template #title>СЛАЙДЕР</template>
			<template #buttons>
				<icon-save :width="28" :height="28" @click="" />
			</template>
		</block-title>

		<!-- Загрузка слайдов -->
		<loader-child
			:isLoading="loading.loader.slider"
			:minHeight="300"
			@loaderChildAfterLeave="loaderChildAfterLeave"
		/>

		<BlockButtons>
			<ButtonDefault @click="openSlide(null, 'create')"> Добавить </ButtonDefault>
		</BlockButtons>
	</block-once>
</template>

<script>
import InfoBar from "../../../components/ui/admin/InfoBar.vue";

import LoaderChild from "../../../components/includes/LoaderChild.vue";
import Empty from "../../../components/includes/Empty.vue";

import BlockTitle from "../../../components/ui/admin/blocks/BlockTitle.vue";
import BlockOnce from "../../../components/ui/admin/blocks/BlockOnce.vue";
import BlockButtons from "../../../components/ui/admin/blocks/BlockButtons.vue";

import ButtonDefault from "../../../components/ui/admin/buttons/ButtonDefault.vue";
import ButtonRemove from "../../../components/ui/admin/buttons/ButtonRemove.vue";
import ButtonClaim from "../../../components/ui/admin/buttons/ButtonClaim.vue";

import IconArrow from "../../../components/icons/IconArrow.vue";
import IconHide from "../../../components/icons/IconHide.vue";
import IconVisible from "../../../components/icons/IconVisible.vue";
import IconSave from "../../../components/icons/IconSave.vue";

export default {
	components: {
		InfoBar,
		LoaderChild,
		Empty,
		BlockTitle,
		BlockOnce,
		BlockButtons,
		ButtonDefault,
		ButtonRemove,
		ButtonClaim,
		IconArrow,
		IconHide,
		IconVisible,
		IconSave,
	},
	data() {
		return {
			loading: {
				loader: {
					slider: true,
					footer: true,
				},
				slider: false,
				footer: false,
			},
		};
	},
};
</script>

<style scoped></style>
