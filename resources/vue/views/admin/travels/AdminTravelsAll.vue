<template>
	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                    СПИСОК ЦЕН                     |-->
	<!--|___________________________________________________|-->
	<info-bar>
		<template v-slot:title>Путевки</template>
		<template v-slot:addreas>travels</template>
	</info-bar>

	<block-once :minHeight="200">
		<template #title>СПИСОК</template>

		<template #options>
			<VueButton :disabled="disabled.travels.save" :look="'inverse'">
				<VueIcon
					:name="'save'"
					:fill="'var(--primary-color)'"
					:width="'28px'"
					:height="'28px'"
				/>
				Сохранить
			</VueButton>

			<VueButton
				:look="'inverse'"
				@click="
					$store.commit('openWiki', {
						title: 'СПИСОК ЦЕН',
						component: 'PricesAll',
					})
				"
				:minWidth="'30px'"
			>
				<VueIcon
					:name="'Info'"
					:fill="'var(--primary-color)'"
					:width="'30px'"
					:height="'30px'"
				/>
			</VueButton>
		</template>

		<template #body>
			<div class="etravels">
				<div class="etravels__item">
					<div class="etravels__item-header">
						<div class="etravels__item-id">id: 1</div>
						<div class="etravels__item-order">order: 1</div>
					</div>
					<div class="etravels__item-body">
						<div class="etravels__item-title">ПОДАРИ ЗДОРОВЬЕ РОДИТЕЛЯМ</div>
						<div class="etravels__item-other">
							<div class="etravels__item-image">
								<img :src="`/storage/img/Vjwl6ugvdfQ.webp`" alt="" />
							</div>
							<div class="etravels__item-info">
								<div class="etravels__item-range">
									<VueIcon
										:name="'Calendar Month'"
										:fill="'var(--primary-color)'"
										:width="'26px'"
										:height="'26px'"
									/>
									10 дней
								</div>
								<div class="etravels__item-description">
									Наша программа «Подари здоровье родителям» является хитом продаж среди
									других программ!
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</template>

		<template #buttons>
			<VueButton @click="console.log('add')" :disabled="disabled.travels.create">
				<VueIcon :name="'Add'" :fill="'white'" :width="'26px'" :height="'26px'" />
				Добавить
			</VueButton>
		</template>
	</block-once>
</template>

<script>
import VueModal from "../../../components/modules/modal/VueModal.vue";
import VueTable from "../../../components/modules/table/VueTable.vue";
import VueInput from "../../../components/modules/input/VueInput.vue";
import Empty from "../../../components/modules/Empty.vue";

import InfoBar from "../../../components/ui/admin/InfoBar.vue";
import BlockOnce from "../../../components/ui/admin/blocks/BlockOnce.vue";
import BlockTitle from "../../../components/ui/admin/blocks/BlockTitle.vue";

import VueLoader from "../../../components/modules/VueLoader.vue";
import VueIcon from "../../../components/modules/icon/VueIcon.vue";
import VueButton from "../../../components/ui/VueButton.vue";

import api from "../../../mixin/api";
import validate from "../../../services/validate";
import shared from "../../../services/shared";
import files from "../../../services/files";

export default {
	components: {
		VueModal,
		VueTable,
		VueInput,
		Empty,

		InfoBar,
		BlockOnce,
		BlockTitle,

		VueLoader,
		VueIcon,
		VueButton,
	},
	data() {
		return {
			/* Кнопки */
			disabled: {
				travels: {
					save: false,
					create: false,
					download: false,
				},
			},

			/* Загрузчик */
			loading: {
				loader: {
					travels: true,
				},
				sections: {
					travels: false,
				},
			},

			/* Модальное окно */
			modalVideo: {
				thin: false,
				clamped: false,
				values: {
					title: "",
					look: "default",
				},
			},

			/* Форма */
			currentVideo: {
				errors: {
					file: {
						message: "",
						status: false,
					},
					description: {
						message: "",
						status: false,
					},
				},
				data: {
					id: {
						value: "",
						edited: false,
					},
					order: {
						value: "",
						edited: false,
					},
					path: {
						value: "/storage/video/IMG_8240.webm",
						edited: false,
					},
					video: {
						value: "IMG_8240.webm",
						edited: false,
					},
					file: {
						value: "",
						edited: false,
					},
					description: {
						value: "",
						edited: false,
					},
					create: {
						value: false,
						edited: false,
					},
					delete: {
						value: false,
						edited: false,
					},
				},
			},

			/* Данные */
			travels: [],
		};
	},
	methods: {
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                   Загрузчик                       |*/
		/* |___________________________________________________|*/
		/* После скрытия элементы */
		loaderChildAfterLeave() {
			this.loading.sections.travels = true;
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                Модальное окно                     |*/
		/* |___________________________________________________|*/
		/* Открытие модального окна */
		openModal(name, title, look) {
			this[name].values.title = title;
			this[name].values.look = look;

			this.$refs[name].open();
		},

		/* Открытие модального окна для добавления */
		openVideoCreate() {
			shared.clearObjectFull(this.currentVideo);

			this.openModal("modalVideo", "Видео", "create");
		},

		/* Открытие модального окна для редактирования */
		openVideoEdite(video) {
			shared.clearObjectFull(this.currentVideo);
			shared.setData(video, this.currentVideo);

			// Перезагружаем видео
			this.reloadVideo();

			this.openModal("modalVideo", "Видео", "default");
		},

		/* Загрузка видео */
		openVideoUpload() {
			this.$refs.fileVideo.clear();

			this.openModal("modalVideoUpload", "Загрузка видео", "default");
		},
	},
	mounted() {
		// this.loading.loader.travels = false;
		// api({
		// 	method: "get",
		// 	url: this.$store.getters.urlApi + `get-prices-files-all`,
		// })
		// 	.then((response) => {
		// 		if (!response) return;
		// 		this.table.body = response.data.result;
		// 		this.table.body.forEach((item) => {
		// 			item.create = false;
		// 			item.delete = false;
		// 		});
		// 	})
		// 	.catch((error) => {
		// 		this.$store.commit("addDebugger", {
		// 			title: "Ошибка.",
		// 			body: error,
		// 			type: "error",
		// 		});
		// 	})
		// 	.finally(() => {
		// 		this.loading.loader.travels = false;
		// 	});
	},
};
</script>

<style scoped>
.etravels {
	display: grid;
	grid-template-columns: repeat(2, 1fr);
}

.etravels__item {
	cursor: pointer;
	display: flex;
	flex-direction: column;
	gap: calc(var(--default-gap) / 2);
	border: var(--default-border);
	border-radius: calc(var(--default-border-radius) / 1.5);
	padding: calc(var(--default-gap) / 2);

	transition: all 0.2s ease-in-out;
}

.etravels__item:hover {
	border: var(--default-border-active);
	background-color: var(--item-background-color-active);
}

.etravels__item-header {
	display: flex;
	gap: calc(var(--default-gap) / 4);
}

:is(.etravels__item-id, .etravels__item-order) {
	border: var(--default-border);
	border-radius: calc(var(--default-border-radius) / 1.75);
	padding: 5px 10px;
}

.etravels__item-body {
	display: flex;
	flex-direction: column;
	gap: calc(var(--default-gap) / 2);
}

.etravels__item-other {
	display: flex;
	gap: calc(var(--default-gap) / 2);
}

.etravels__item-title {
	font-size: 1.5rem;
	font-weight: 500;

	color: var(--primary-color);
}

.etravels__item-image {
	border-radius: calc(var(--default-border-radius) / 1.5);
	object-fit: contain;
	height: 350px;

	aspect-ratio: 1 / 1.2;
}

.etravels__item-image > img {
	object-fit: cover;
	height: 100%;
	width: 100%;
	border-radius: calc(var(--default-border-radius) / 1.5);
}

.etravels__item-range {
	display: flex;
	align-items: center;
	gap: 5px;
}

.etravels__item-description {
	font-size: 1.125rem;
	overflow: hidden;
	display: -webkit-box;
	-webkit-line-clamp: 13;
	line-clamp: 13;
	-webkit-box-orient: vertical;

	height: 100%;
}

.etravels__item-info {
	display: flex;
	flex-direction: column;
	gap: calc(var(--default-gap) / 2);

	font-size: 1.125rem;
}
</style>
