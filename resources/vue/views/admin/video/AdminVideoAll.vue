<template>
	<!-- Новости -->
	<info-bar>
		<template v-slot:title>Новости</template>
		<template v-slot:addreas>video</template>
	</info-bar>

	<block-once :minHeight="200">
		<template #title>ВИДЕО</template>

		<template #options>
			<button-default
				@click.prevent="console.log('save')"
				:disabled="disabled.video.save"
				:look="'white'"
			>
				<icon-save :width="28" :height="28" />
				Сохранить
			</button-default>
		</template>

		<template #body>
			<div class="evideo">
				<div class="evideo__item">
					<div class="evideo__item-video">
						<video width="100%" height="300px" preload="metadata">
							<source :src="`/storage/video/IMG_8240.webm`" type="video/webm" />
						</video>
					</div>
					<div class="evideo__item-title">Заголовок</div>
					<div class="evideo__item-description">Описание видео блока</div>
				</div>
				<div class="evideo__item">
					<div class="evideo__item-video">
						<video width="100%" height="300px" preload="metadata">
							<source :src="`/storage/video/IMG_8240.webm`" type="video/webm" />
						</video>
					</div>
					<div class="evideo__item-title">Заголовок</div>
					<div class="evideo__item-description">Описание видео блока</div>
				</div>
				<div class="evideo__item">
					<div class="evideo__item-video">
						<video width="100%" height="300px" preload="metadata">
							<source :src="`/storage/video/IMG_8240.webm`" type="video/webm" />
						</video>
					</div>
					<div class="evideo__item-title">Заголовок</div>
					<div class="evideo__item-description">Описание видео блока</div>
				</div>
			</div>

			<!-- <loader-child
				:isLoading="loading.loader.video"
				:minHeight="200"
				@loaderChildAfterLeave="loaderChildAfterLeave"
			/> -->
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

<style scoped>
.evideo {
	display: grid;
	grid-template-columns: repeat(3, 1fr);
	gap: 20px;
}

.evideo__item {
	cursor: pointer;
	display: flex;
	flex-direction: column;
	gap: 10px;

	padding: var(--default-padding);
	border: var(--default-border);
	border-radius: calc(var(--default-border-radius) / 1.5);
	
	transition: all 0.2s;
}

.evideo__item:hover {
	border: var(--input-border-focus);
	background-color: var(--item-background-color-active);
}

.evideo__item-video {
	width: 100%;
	max-height: 400px;
	object-fit: cover;
}
</style>
