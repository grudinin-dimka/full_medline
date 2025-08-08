<template>
	<!-- Модальное окно: Путевка -->
	<VueModal ref="modalTravels" :settings="modalTravels">
		<template #title>
			<template v-if="modalTravels.values.look == 'default' && !currentTravel.data.delete.value">
				<VueIcon
					:name="'arrow'"
					:fill="'var(--icon-multi-fill)'"
					:hover="'var(--icon-nulti-fill-hover)'"
					:width="'16px'"
					:height="'16px'"
					:rotate="-90"
					:cursor="'pointer'"
					@click="changeOrderItem('down')"
				/>
				#{{ currentTravel.data.order.value }}
				<VueIcon
					:name="'arrow'"
					:fill="'var(--icon-multi-fill)'"
					:hover="'var(--icon-nulti-fill-hover)'"
					:width="'16px'"
					:height="'16px'"
					:rotate="90"
					:cursor="'pointer'"
					@click="changeOrderItem('up')"
				/>
			</template>
			<template v-else>
				{{ modalTravels.values.title }}
			</template>
		</template>

		<template #body>
			<div class="modal__info">
				<div class="modal__info-image">
					<template v-if="currentTravel.data.path.value">
						<img :src="currentTravel.data.path.value" alt="" />
					</template>

					<template v-else>
						<img :src="`/storage/default/travels-none-default.png`" alt="" />
					</template>
				</div>
				<div class="modal__info-form">
					<VueInput
						:placeholder="'Загрузите файл'"
						:type="'file'"
						v-model="currentTravel.data.file.value"
						:error="currentTravel.errors.file.status"
						ref="fileImage"
					>
						<template #label> ИЗОБРАЖЕНИЕ (500x600) </template>
						<template #error>
							{{ currentTravel.errors.file.message }}
						</template>
					</VueInput>

					<VueInput
						:placeholder="'Введите заголовок'"
						:type="'text'"
						v-model="currentTravel.data.title.value"
						:error="currentTravel.errors.title.status"
					>
						<template #label> ЗАГОЛОВОК </template>
						<template #error>
							{{ currentTravel.errors.title.message }}
						</template>
					</VueInput>

					<VueInput
						:placeholder="'Введите описание'"
						:type="'textarea'"
						v-model="currentTravel.data.description.value"
						:error="currentTravel.errors.description.status"
					>
						<template #label> ОПИСАНИЕ </template>
						<template #error>
							{{ currentTravel.errors.description.message }}
						</template>
					</VueInput>
				</div>
			</div>

			<VueModalList
				:list="currentTravel.data.services.value"
				@create="openTravelServiceCreate"
				@edit="console.log('edit')"
				@delete="console.log('delete')"
			>
				<template #title>
					<VueIcon
						:name="'Design Services'"
						:fill="'var(--primary-color)'"
						:width="'26px'"
						:height="'26px'"
					/>
					УСЛУГИ
				</template>
			</VueModalList>

			<VueModalList
				:list="currentTravel.data.services.value"
				@create="openTravelPriceCreate"
				@edit="console.log('edit')"
				@delete="console.log('delete')"
			>
				<template #title>
					<VueIcon
						:name="'Payments'"
						:fill="'var(--primary-color)'"
						:width="'26px'"
						:height="'26px'"
					/>
					ЦЕНЫ
				</template>
			</VueModalList>
		</template>

		<template #footer>
			<VueButton @click="console.log('add')" v-if="modalTravels.values.look == 'create'">
				<VueIcon :name="'Add'" :fill="'white'" :width="'26px'" :height="'26px'" />
				Добавить
			</VueButton>

			<template v-else>
				<VueButton
					:look="'delete'"
					@click="console.log('delete')"
					v-if="!currentTravel.data.delete.value && !currentTravel.data.create.value"
				>
					<VueIcon :name="'Delete'" :fill="'white'" :width="'28px'" :height="'28px'" />
					Удалить
				</VueButton>

				<VueButton @click="console.log('update')" v-if="!currentTravel.data.delete.value">
					<VueIcon :name="'Edit'" :fill="'white'" :width="'28px'" :height="'28px'" />
					Обновить
				</VueButton>

				<VueButton @click="console.log('restore')" v-if="currentTravel.data.delete.value">
					<VueIcon
						:name="'Restore From Trash'"
						:fill="'white'"
						:width="'28px'"
						:height="'28px'"
					/>
					Вернуть
				</VueButton>
			</template>
		</template>
	</VueModal>

	<!-- Модальное окно: Услуги -->
	<VueModal ref="modalTravelServices" :settings="modalTravelServices">
		<template #title>
			{{ modalTravelServices.values.title }}
		</template>

		<template #body>
			<VueInput
				:placeholder="'Введите заголовок'"
				:type="'text'"
				v-model="currentTravelServices.data.title.value"
				:error="currentTravelServices.errors.title.status"
			>
				<template #label> ЗАГОЛОВОК </template>
				<template #error>
					{{ currentTravelServices.errors.title.message }}
				</template>
			</VueInput>

			<VueInput
				:placeholder="'Введите заголовок'"
				:type="'textarea'"
				v-model="currentTravelServices.data.description.value"
				:error="currentTravelServices.errors.description.status"
			>
				<template #label> ОПИСАНИЕ </template>
				<template #error>
					{{ currentTravelServices.errors.description.message }}
				</template>
			</VueInput>
		</template>

		<template #footer>
			<VueButton @click="console.log('add')" v-if="modalTravelServices.values.look == 'create'">
				<VueIcon :name="'Add'" :fill="'white'" :width="'26px'" :height="'26px'" />
				Добавить
			</VueButton>

			<VueButton @click="console.log('edit')" v-if="modalTravelServices.values.look == 'default'">
				<VueIcon :name="'Edit'" :fill="'white'" :width="'26px'" :height="'26px'" />
				Добавить
			</VueButton>
		</template>
	</VueModal>

	<!-- Модальное окно: Цены -->
	<VueModal ref="modalTravelPrices" :settings="modalTravelPrices">
		<template #title>
			{{ modalTravelPrices.values.title }}
		</template>

		<template #body> </template>

		<template #footer> </template>
	</VueModal>

	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                    ПУТЕВКИ                        |-->
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
			<VueButton @click="openTravelCreate" :disabled="disabled.travels.create">
				<VueIcon :name="'Add'" :fill="'white'" :width="'26px'" :height="'26px'" />
				Добавить
			</VueButton>
		</template>
	</block-once>
</template>

<script>
import VueModal from "../../../components/modules/modal/VueModal.vue";
import VueModalList from "../../../components/modules/modal/VueModalList.vue";
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
		VueModalList,
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
			modalTravels: {
				thin: false,
				clamped: false,
				values: {
					title: "",
					look: "default",
				},
			},

			modalTravelServices: {
				thin: true,
				clamped: false,
				values: {
					title: "",
					look: "default",
				},
			},

			modalTravelPrices: {
				thin: true,
				clamped: false,
				values: {
					title: "",
					look: "default",
				},
			},

			/* Форма */
			currentTravel: {
				errors: {
					file: {
						message: "",
						status: false,
					},
					title: {
						message: "",
						status: false,
					},
					duration: {
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
						value: "/storage/img/Vjwl6ugvdfQ.webp",
						edited: false,
					},
					image: {
						value: "Vjwl6ugvdfQ.webp",
						edited: false,
					},
					file: {
						value: "",
						edited: false,
					},
					title: {
						value: "",
						edited: false,
					},
					duration: {
						value: "",
						edited: false,
					},
					description: {
						value: "",
						edited: false,
					},
					services: {
						value: [],
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

			currentTravelServices: {
				errors: {
					title: {
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
					title: {
						value: "",
						edited: false,
					},
					description: {
						value: "",
						edited: false,
					},
				},
			},

			currentTravelPrice: {
				errors: {},
				data: {
					id: {
						value: "",
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
		openTravelCreate() {
			shared.clearObjectFull(this.currentTravel);

			this.openModal("modalTravels", "ПУТЕВКА", "create");
		},

		/* Открытие модального окна для редактирования */
		openTravelEdite(value) {
			shared.clearObjectFull(this.currentTravel);
			shared.setData(value, this.currentTravel);

			this.openModal("modalTravels", "ПУТЕВКА", "default");
		},

		/* Открытие модального окна для добавления */
		openTravelServiceCreate() {
			shared.clearObjectFull(this.currentTravelServices);

			this.openModal("modalTravelServices", "УСЛУГА", "create");
		},

		/* Открытие модального окна для добавления */
		openTravelPriceCreate() {
			shared.clearObjectFull(this.currentTravelPrice);

			this.openModal("modalTravelPrices", "ЦЕНА", "create");
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
.modal__info {
	display: flex;
	gap: var(--default-gap);
}

.modal__info-image {
	border-radius: calc(var(--default-border-radius) / 1.5);

	height: 375px;
	aspect-ratio: 1 / 1.2;
}

.modal__info-form {
	display: flex;
	flex-direction: column;
	gap: calc(var(--default-gap) / 2);

	width: 100%;
}

.modal__info-image > img {
	object-fit: cover;
	height: 100%;
	width: 100%;
	aspect-ratio: 1 / 1.2;
	border-radius: calc(var(--default-border-radius) / 1.5);
}

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
