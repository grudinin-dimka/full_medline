<template>
	<!-- Новости -->
	<info-bar>
		<template v-slot:title>Новости</template>
		<template v-slot:addreas>video</template>
	</info-bar>

	<block-once :minHeight="200">
		<template #title>ВИДЕО</template>

		<template #options>
			<icon-load :width="28" :height="28" v-if="disabled.video.save" />
			<icon-save :width="28" :height="28" @click="console.log('save')" v-else />
		</template>

		<template #body>
			<div class="evideo">
				<div class="evideo__item">
					<div class="evideo__item-title">Заголовок</div>
					<div class="evideo__item-input"></div>
				</div>
			</div>

			<loader-child
				:isLoading="loading.loader.video"
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
				video: {
					save: false,
					update: false,
				},
			},

			/* Загрузчик */
			loading: {
				loader: {
					video: true,
				},
				sections: {
					video: false,
				},
			},
		};
	},
	methods: {
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                   Загрузчик                       |*/
		/* |___________________________________________________|*/
		/* После скрытия элементы */
		loaderChildAfterLeave() {
			this.loading.sections.video = true;
		},
	},
};
</script>

<style scoped></style>
