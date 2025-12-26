<template>
	<!-- Модальное окно: Путевка -->
	<VueModal ref="modalTravels" :settings="modalTravels">
		<template #title>
			<template v-if="modalTravels.values.look == 'default' && !currentTravel.data.delete.value">
				<VueIcon
					:name="'Arrow'"
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
					:name="'Arrow'"
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

		<template
			#buttons
			v-if="(modalTravels.values.look == 'default') & !currentTravel.data.delete.value & !currentTravel.data.create.value"
		>
			<VueIcon
				v-if="!currentTravel.data.hide.value"
				:name="'Visibility'"
				:fill="'var(--primary-color)'"
				:hover="'var(--primary-color-hover)'"
				:cursor="'pointer'"
				:width="'26px'"
				:height="'26px'"
				@click="currentTravel.data.hide.value = true"
			/>
			<VueIcon
				v-else
				:name="'Visibility Off'"
				:fill="'var(--primary-color)'"
				:hover="'var(--primary-color-hover)'"
				:cursor="'pointer'"
				:width="'26px'"
				:height="'26px'"
				@click="currentTravel.data.hide.value = false"
			/>
		</template>

		<template #body>
			<div class="travel__image">
				<VueImage
					:path="currentTravel.data.path.value"
					:default="'/storage/default/image-none-default.png'"
					:alt="'Путевка'"
					:height="'500px'"
				/>

				<VueFile
					v-model="currentTravel.data.file.value"
					ref="fileImage"
					:type="'image'"
					:view="'dropzone'"
					:placeholder="'Загрузите картинку'"
					:error="currentTravel.errors.file.status"
					:accept="['.png', '.webp', '.jpg', '.jpeg']"
					@guard="
						({ status, message }) => {
							currentTravel.errors.file.status = status;
							currentTravel.errors.file.message = message;
						}
					"
				>
					<template #label>
						<VueIcon :name="'Attach File'" :fill="'var(--primary-color)'" :width="'20px'" :height="'20px'" />
						ИЗОБРАЖЕНИЕ (500x600)
					</template>
					<template #error>
						{{ currentTravel.errors.file.message }}
					</template>
				</VueFile>
			</div>

			<VueValues
				v-model.trim="currentTravel.data.title.value"
				:type="'text'"
				:placeholder="'Введите заголовок'"
				:error="currentTravel.errors.title.status"
			>
				<template #label>
					<VueIcon :name="'Edit'" :fill="'var(--primary-color)'" :width="'20px'" :height="'20px'" />
					ЗАГОЛОВОК
				</template>
				<template #error>
					{{ currentTravel.errors.title.message }}
				</template>
			</VueValues>

			<VueValues
				v-model.trim="currentTravel.data.duration.value"
				:type="'text'"
				:placeholder="'Введите значение'"
				:error="currentTravel.errors.duration.status"
			>
				<template #label>
					<VueIcon :name="'Calendar Clock'" :fill="'var(--primary-color)'" :width="'20px'" :height="'20px'" />
					ДЛИТЕЛЬНОСТЬ
				</template>
				<template #error>
					{{ currentTravel.errors.duration.message }}
				</template>
			</VueValues>

			<VueTiptap
				ref="tiptapDescription"
				v-model="currentTravel.data.description.value"
				:editable="true"
				:limit="1_000"
				:minHeight="150"
				:options="['format', 'align', 'link']"
				:placeholder="'Введите описание'"
				:error="currentTravel.errors.description.status"
			>
				<template #error>
					{{ currentTravel.errors.description.message }}
				</template>
			</VueTiptap>

			<VueSwitch v-model="currentTravel.data.hit.value" :error="currentTravel.errors.hit.status">
				<template #label> Хит продаж </template>
			</VueSwitch>

			<VueModalList
				:list="currentTravel.data.services.value"
				:keys="{
					value: 'id',
					label: 'title',
				}"
				@create="openTravelServiceCreate"
				@edit="openTravelServiceEdite"
				@delete="deleteTravelService"
			>
				<template #title>
					<VueIcon :name="'Design Services'" :fill="'var(--primary-color)'" :width="'26px'" :height="'26px'" />
					УСЛУГИ
				</template>
			</VueModalList>

			<VueModalList
				:list="getSortedTravelPrices"
				:keys="{
					value: 'id',
					label: ['type', 'subtype', 'price'],
				}"
				@create="openTravelPriceCreate"
				@edit="openTravelPriceEdite"
				@delete="deleteTravelPrice"
			>
				<template #title>
					<VueIcon :name="'Payments'" :fill="'var(--primary-color)'" :width="'26px'" :height="'26px'" />
					ЦЕНЫ
				</template>
			</VueModalList>
		</template>

		<template #footer>
			<VueButton @click="addTravel" v-if="modalTravels.values.look == 'create'" :disabled="disabled.travels.create">
				<VueIcon :name="'Add'" :fill="'white'" :width="'26px'" :height="'26px'" />
				Добавить
			</VueButton>

			<template v-else>
				<VueButton
					:look="'delete'"
					@click="deleteTravel"
					v-if="!currentTravel.data.delete.value && !currentTravel.data.create.value"
				>
					<VueIcon :name="'Delete'" :fill="'white'" :width="'28px'" :height="'28px'" />
					Удалить
				</VueButton>

				<VueButton @click="updateTravel" v-if="!currentTravel.data.delete.value" :disabled="disabled.travels.update">
					<VueIcon :name="'Edit'" :fill="'white'" :width="'28px'" :height="'28px'" />
					Обновить
				</VueButton>

				<VueButton @click="deleteTravel" v-if="currentTravel.data.delete.value">
					<VueIcon :name="'Restore From Trash'" :fill="'white'" :width="'28px'" :height="'28px'" />
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
			<VueValues
				v-model.trim="currentTravelService.data.title.value"
				:type="'text'"
				:placeholder="'Введите заголовок'"
				:error="currentTravelService.errors.title.status"
			>
				<template #label>
					<VueIcon :name="'Edit'" :fill="'var(--primary-color)'" :width="'20px'" :height="'20px'" />
					ЗАГОЛОВОК
				</template>
				<template #error>
					{{ currentTravelService.errors.title.message }}
				</template>
			</VueValues>

			<VueTiptap
				ref="tiptapDescription"
				v-model="currentTravelService.data.description.value"
				:editable="true"
				:limit="500"
				:minHeight="150"
				:options="['align', 'format']"
				:placeholder="'Введите описание'"
				:error="currentTravelService.errors.description.status"
			>
				<template #error>
					{{ currentTravelService.errors.description.message }}
				</template>
			</VueTiptap>
		</template>

		<template #footer>
			<VueButton @click="addTravelService" v-if="modalTravelServices.values.look == 'create'">
				<VueIcon :name="'Add'" :fill="'white'" :width="'26px'" :height="'26px'" />
				Добавить
			</VueButton>

			<VueButton @click="updateTravelService" v-if="modalTravelServices.values.look == 'default'">
				<VueIcon :name="'Edit'" :fill="'white'" :width="'26px'" :height="'26px'" />
				Обновить
			</VueButton>
		</template>
	</VueModal>

	<!-- Модальное окно: Цены -->
	<VueModal ref="modalTravelPrices" :settings="modalTravelPrices">
		<template #title>
			{{ modalTravelPrices.values.title }}
		</template>

		<template #body>
			<VueSelector
				v-model.trim="currentTravelPrice.data.type.value"
				:is-search="false"
				:is-clear="false"
				:list="[
					{
						default: false,
						disabled: false,
						value: 'С питанием',
						label: 'С питанием',
					},
					{
						default: false,
						disabled: false,
						value: 'Без питания',
						label: 'Без питания',
					},
				]"
				:error="currentTravelPrice.errors.type.status"
			>
				<template #label>
					<VueIcon :name="'Category'" :fill="'var(--primary-color)'" :width="'20px'" :height="'20px'" />
					ТИП
				</template>
				<template #error>
					{{ currentTravelPrice.errors.type.message }}
				</template>
			</VueSelector>

			<VueSelector
				v-model="currentTravelPrice.data.subtype.value"
				:is-search="false"
				:is-clear="false"
				:list="[
					{
						value: 'До',
						label: 'До',
					},
					{
						value: 'После',
						label: 'После',
					},
				]"
				:error="currentTravelPrice.errors.subtype.status"
			>
				<template #label>
					<VueIcon :name="'Category'" :fill="'var(--primary-color)'" :width="'20px'" :height="'20px'" />
					ПОДТИП
				</template>
				<template #error>
					{{ currentTravelPrice.errors.subtype.message }}
				</template>
			</VueSelector>

			<VueNumber
				v-model.number="currentTravelPrice.data.price.value"
				:min="0"
				:max="3_000_000"
				:error="currentTravelPrice.errors.price.status"
			>
				<template #label>
					<VueIcon :name="'Payments'" :fill="'var(--primary-color)'" :width="'20px'" :height="'20px'" />
					ЦЕНА
				</template>
				<template #error>
					{{ currentTravelPrice.errors.price.message }}
				</template>
			</VueNumber>
		</template>

		<template #footer>
			<VueButton @click="addTravelPrice" v-if="modalTravelPrices.values.look == 'create'">
				<VueIcon :name="'Add'" :fill="'white'" :width="'26px'" :height="'26px'" />
				Добавить
			</VueButton>

			<VueButton @click="updateTravelPrice" v-if="modalTravelPrices.values.look == 'default'">
				<VueIcon :name="'Edit'" :fill="'white'" :width="'26px'" :height="'26px'" />
				Обновить
			</VueButton>
		</template>
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
			<VueButton :disabled="disabled.travels.save" :look="'inverse'" @click="saveTravelsChanges">
				<VueIcon :name="'Save'" :fill="'var(--primary-color)'" :width="'28px'" :height="'28px'" />
				Сохранить
			</VueButton>

			<VueButton
				:look="'inverse'"
				@click="
					$store.commit('openWiki', {
						title: 'ПУТЕВКИ',
						component: 'TravelsAll',
					})
				"
				:minWidth="'30px'"
			>
				<VueIcon :name="'Info'" :fill="'var(--primary-color)'" :width="'30px'" :height="'30px'" />
			</VueButton>
		</template>

		<template #body>
			<template v-if="loading.sections.travels">
				<div class="etravels" v-if="travels.length">
					<div
						class="etravels__item"
						v-for="travel in travels"
						:class="{
							'etravels__item--hide': travel.hide,
							'etravels__item--create': travel.create,
							'etravels__item--delete': travel.delete,
						}"
						@click="openTravelEdite(travel)"
					>
						<div class="etravels__item-header">
							<div class="etravels__header-item">id: {{ travel.create ? "?" : travel.id }}</div>
							<div class="etravels__header-item">order: {{ travel.order }}</div>
						</div>
						<div class="etravels__item-body">
							<div class="etravels__item-title">{{ travel.title }}</div>
							<div class="etravels__item-other">
								<div class="etravels__item-image">
									<img :src="travel.path" alt="Картинка путевки" />
								</div>
								<div class="etravels__item-info">
									<div class="etravels__item-range">
										<VueIcon
											:name="'Calendar Month'"
											:fill="'var(--primary-color)'"
											:width="'26px'"
											:height="'26px'"
										/>
										{{ travel.duration }}
									</div>
									<div class="etravels__item-description">
										<VueTiptap :editable="false" :limit="1_000" v-model="travel.description" />
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- Элемент пустой страницы -->
				<empty :minHeight="300" v-if="travels.length == 0" />
			</template>

			<!-- Загрузка слайдов -->
			<VueLoader
				:isLoading="loading.loader.travels"
				:isChild="true"
				:minHeight="300"
				@afterLeave="loaderChildAfterLeave"
			/>
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
import Empty from "../../../components/modules/Empty.vue";

import InfoBar from "../../../components/ui/admin/InfoBar.vue";
import BlockOnce from "../../../components/ui/admin/blocks/BlockOnce.vue";
import BlockTitle from "../../../components/ui/admin/blocks/BlockTitle.vue";

import api from "../../../mixin/api";
import validate from "../../../services/validate";
import shared from "../../../services/shared";
import files from "../../../services/files";

export default {
	components: {
		Empty,

		InfoBar,
		BlockOnce,
		BlockTitle,
	},
	data() {
		return {
			/* Кнопки */
			disabled: {
				travels: {
					save: false,
					create: false,
					update: false,
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
				touch: true,
				values: {
					title: "",
					look: "default",
				},
			},

			modalTravelServices: {
				thin: true,
				clamped: false,
				touch: true,
				values: {
					title: "",
					look: "default",
				},
			},

			modalTravelPrices: {
				thin: true,
				clamped: false,
				touch: true,
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
					hit: {
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
					hide: {
						value: false,
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
					hit: {
						value: false,
						edited: false,
					},
					services: {
						value: [],
						edited: false,
					},
					prices: {
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

			currentTravelService: {
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
				errors: {
					type: {
						message: "",
						status: false,
					},
					subtype: {
						message: "",
						status: false,
					},
					price: {
						message: "",
						status: false,
					},
				},
				data: {
					id: {
						value: "",
						edited: false,
					},
					type: {
						value: "",
						edited: false,
					},
					subtype: {
						value: "",
						edited: false,
					},
					price: {
						value: "",
						edited: false,
					},
				},
			},

			/* Данные */
			travels: [],
		};
	},
	computed: {
		getSortedTravelPrices() {
			return this.currentTravel.data.prices.value.toSorted((a, b) => a.type.localeCompare(b.type));
		},
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

		/* Путевки */
		openTravelCreate() {
			shared.clearObjectFull(this.currentTravel);
			this.$refs.fileImage.clear();

			this.openModal("modalTravels", "ПУТЕВКА", "create");
		},

		openTravelEdite(value) {
			shared.clearObjectFull(this.currentTravel);
			shared.setData(value, this.currentTravel);
			this.$refs.fileImage.clear();

			this.openModal("modalTravels", "ПУТЕВКА", "default");
		},

		/* Путевки -> Услуги */
		openTravelServiceCreate() {
			shared.clearObjectFull(this.currentTravelService);

			this.openModal("modalTravelServices", "УСЛУГА", "create");
		},

		openTravelServiceEdite(value) {
			shared.clearObjectFull(this.currentTravelService);
			shared.setData(value, this.currentTravelService);

			this.openModal("modalTravelServices", "УСЛУГА", "default");
		},

		/* Путевки -> Цены */
		openTravelPriceCreate() {
			shared.clearObjectFull(this.currentTravelPrice);

			this.openModal("modalTravelPrices", "ЦЕНА", "create");
		},

		openTravelPriceEdite(value) {
			shared.clearObjectFull(this.currentTravelPrice);
			shared.setData(value, this.currentTravelPrice);

			this.openModal("modalTravelPrices", "УСЛУГА", "default");
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                    Путевки                        |*/
		/* |___________________________________________________|*/
		/* Изменение порядка выбранного слайда */
		changeOrderItem(type) {
			try {
				shared.changeOrder(this.travels, this.currentTravel, type);
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка",
					body: error,
					type: "error",
				});
			}
		},

		/* Добавление путевки */
		addTravel() {
			try {
				if (
					validate.checkInputsAll(this.currentTravel, [
						{
							key: "file",
							type: "file",
							value: this.$refs.fileImage.files(),
							formats: ["jpg", "jpeg", "png", "webp"],
						},
						{
							key: "title",
							type: "text",
						},
						{
							key: "duration",
							type: "text",
						},
						{
							key: "description",
							type: "text",
						},
						{
							key: "hit",
							type: "boolean",
						},
					])
				)
					return;

				/* Загрузка файла */
				let formData = new FormData();
				formData.append("file", this.$refs.fileImage.files()[0]);
				formData.append("type", "travels");
				formData.append("maxWidth", "1000");
				formData.append("maxHeight", "1200");
				formData.append("formats", ["jpg", "jpeg", "png", "webp"]);

				this.disabled.travels.create = true;

				api({
					method: "post",
					url: "upload-file",
					headers: {
						"Content-Type": "multipart/form-data",
					},
					data: formData,
				})
					.then((response) => {
						if (!response) return;

						this.travels.push({
							id: shared.getMaxId(this.travels) + 1,
							title: this.currentTravel.data.title.value,
							duration: this.currentTravel.data.duration.value,
							description: this.currentTravel.data.description.value,
							image: files.basename(response.data.result),
							hit: this.currentTravel.data.hit.value,
							services: this.currentTravel.data.services.value,
							prices: this.currentTravel.data.prices.value,
							path: response.data.result,
							order: shared.getMaxOrder(this.travels) + 1,
							hide: false,
							create: true,
							delete: false,
						});

						this.$refs.modalTravels.close();
					})
					.catch((error) => {
						this.$store.commit("addDebugger", {
							title: "Ошибка",
							body: error,
							type: "error",
						});
					})
					.finally(() => {
						this.disabled.travels.create = false;
					});
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка",
					body: error,
					type: "error",
				});
			}
		},

		/* Добавление путевки */
		updateTravel() {
			try {
				let errors = 0;

				if (
					validate.checkInputsAll(this.currentTravel, [
						{
							key: "title",
							type: "text",
						},
						{
							key: "duration",
							type: "text",
						},
						{
							key: "description",
							type: "text",
						},
						{
							key: "hit",
							type: "boolean",
						},
					])
				)
					errors++;

				if (this.$refs.fileImage.files().length > 0) {
					if (
						validate.checkInputsAll(this.currentTravel, [
							{
								key: "file",
								type: "file",
								value: this.$refs.fileImage.files(),
								formats: ["jpg", "jpeg", "png", "webp"],
							},
						])
					)
						errors++;
				}

				if (errors > 0) return;

				if (this.$refs.fileImage.files().length > 0) {
					this.disabled.travels.update = true;

					/* Загрузка файла */
					let formData = new FormData();
					formData.append("file", this.$refs.fileImage.files()[0]);
					formData.append("type", "travels");
					formData.append("maxWidth", "500");
					formData.append("maxHeight", "600");
					formData.append("formats", ["jpg", "jpeg", "png", "webp"]);

					api({
						method: "post",
						url: "upload-file",
						headers: {
							"Content-Type": "multipart/form-data",
						},
						data: formData,
					})
						.then((response) => {
							if (!response) return;

							let travelCurrent = this.travels.find((travel) => travel.order === this.currentTravel.data.order.value);

							travelCurrent.path = response.data.result;
							travelCurrent.image = files.basename(response.data.result);

							travelCurrent.title = this.currentTravel.data.title.value;
							travelCurrent.description = this.currentTravel.data.description.value;
							travelCurrent.duration = this.currentTravel.data.duration.value;
							travelCurrent.hit = this.currentTravel.data.hit.value;
							travelCurrent.hide = this.currentTravel.data.hide.value;
							travelCurrent.prices = this.currentTravel.data.prices.value;
							travelCurrent.services = this.currentTravel.data.services.value;

							this.$refs.modalTravels.close();
						})
						.catch((error) => {
							this.$store.commit("addDebugger", {
								title: "Ошибка",
								body: error,
								type: "error",
							});
						})
						.finally(() => {
							this.disabled.travels.update = false;
						});
				} else {
					let travelCurrent = this.travels.find((travel) => travel.order === this.currentTravel.data.order.value);

					travelCurrent.title = this.currentTravel.data.title.value;
					travelCurrent.description = this.currentTravel.data.description.value;
					travelCurrent.duration = this.currentTravel.data.duration.value;
					travelCurrent.hit = this.currentTravel.data.hit.value;
					travelCurrent.hide = this.currentTravel.data.hide.value;
					travelCurrent.prices = this.currentTravel.data.prices.value;
					travelCurrent.services = this.currentTravel.data.services.value;

					this.$refs.modalTravels.close();
				}
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка",
					body: error,
					type: "error",
				});
			}
		},

		deleteTravel() {
			let travel = this.travels.find((item) => item.order === this.currentTravel.data.order.value);

			travel.delete = !travel.delete;

			this.$refs.modalTravels.close();
		},

		/* Сохранение изменений в базе данных */
		saveTravelsChanges() {
			this.disabled.travels.save = true;

			api({
				method: "post",
				url: `save-travels-changes`,
				headers: {
					Accept: "application/json",
				},
				data: {
					travels: this.travels,
				},
			})
				.then((response) => {
					if (!response) return;

					shared.updateId(this.travels, response.data.result);
					shared.clearDeletes(this.travels);
					shared.clearFlags(this.travels);
					shared.updateOrders(this.travels);
				})
				.catch((error) => {
					this.$store.commit("addDebugger", {
						title: "Ошибка",
						body: error,
						type: "error",
					});
				})
				.finally(() => {
					this.disabled.travels.save = false;
				});
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                 Путевки (Услуги)                  |*/
		/* |___________________________________________________|*/
		addTravelService() {
			try {
				if (
					validate.checkInputsAll(this.currentTravelService, [
						{
							key: "title",
							type: "text",
						},
						{
							key: "description",
							type: "text",
						},
					])
				)
					return;

				if (
					this.currentTravel.data.services.value.some(
						(item) => item.title === this.currentTravelService.data.title.value
					)
				) {
					this.$store.commit("addDebugger", {
						title: "Ошибка",
						body: "Услуга с таким названием уже существует",
						type: "error",
					});

					return;
				}

				this.currentTravel.data.services.value.push({
					id: shared.getMaxId(this.currentTravel.data.services.value) + 1,
					title: this.currentTravelService.data.title.value,
					description: this.currentTravelService.data.description.value,
				});

				this.$refs.modalTravelServices.close();
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка",
					body: error,
					type: "error",
				});
			}
		},

		updateTravelService() {
			try {
				if (
					validate.checkInputsAll(this.currentTravelService, [
						{
							key: "title",
							type: "text",
						},
						{
							key: "description",
							type: "text",
						},
					])
				)
					return;

				if (
					this.currentTravel.data.services.value.some((item) => {
						return (
							item.title === this.currentTravelService.data.title.value &&
							item.id !== this.currentTravelService.data.id.value
						);
					})
				) {
					this.$store.commit("addDebugger", {
						title: "Ошибка",
						body: "Услуга с таким названием уже существует",
						type: "error",
					});

					return;
				}

				let service = this.currentTravel.data.services.value.find(
					(item) => item.id === this.currentTravelService.data.id.value
				);

				service.title = this.currentTravelService.data.title.value;
				service.description = this.currentTravelService.data.description.value;

				this.$refs.modalTravelServices.close();
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка",
					body: error,
					type: "error",
				});
			}
		},

		deleteTravelService(value) {
			try {
				this.currentTravel.data.services.value = this.currentTravel.data.services.value.filter((item) => item !== value);
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка",
					body: error,
					type: "error",
				});
			}
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                   Путевки (Цены)                  |*/
		/* |___________________________________________________|*/
		addTravelPrice() {
			try {
				if (
					validate.checkInputsAll(this.currentTravelPrice, [
						{
							key: "type",
							type: "text",
						},
						{
							key: "subtype",
							type: "text",
						},
						{
							key: "price",
							type: "number",
						},
					])
				)
					return;

				if (
					this.currentTravel.data.prices.value.some((item) => {
						return (
							item.type === this.currentTravelPrice.data.type.value &&
							item.subtype === this.currentTravelPrice.data.subtype.value
						);
					})
				) {
					this.$store.commit("addDebugger", {
						title: "Ошибка",
						body: "Цена с таким типом и подтипом уже существует",
						type: "error",
					});

					return;
				}

				this.currentTravel.data.prices.value.push({
					id: shared.getMaxId(this.currentTravel.data.prices.value) + 1,
					type: this.currentTravelPrice.data.type.value,
					subtype: this.currentTravelPrice.data.subtype.value,
					price: this.currentTravelPrice.data.price.value,
				});

				this.$refs.modalTravelPrices.close();
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка",
					body: error,
					type: "error",
				});
			}
		},

		updateTravelPrice() {
			try {
				if (
					validate.checkInputsAll(this.currentTravelPrice, [
						{
							key: "type",
							type: "text",
						},
						{
							key: "subtype",
							type: "text",
						},
						{
							key: "price",
							type: "number",
						},
					])
				)
					return;

				if (
					this.currentTravel.data.prices.value.some((item) => {
						return (
							item.type === this.currentTravelPrice.data.type.value &&
							item.subtype === this.currentTravelPrice.data.subtype.value &&
							item.id !== this.currentTravelPrice.data.id.value
						);
					})
				) {
					this.$store.commit("addDebugger", {
						title: "Ошибка",
						body: "Цена с таким типом и подтипом уже существует",
						type: "error",
					});

					return;
				}

				let price = this.currentTravel.data.prices.value.find(
					(item) => item.id === this.currentTravelPrice.data.id.value
				);

				price.type = this.currentTravelPrice.data.type.value;
				price.subtype = this.currentTravelPrice.data.subtype.value;
				price.price = this.currentTravelPrice.data.price.value;

				this.$refs.modalTravelPrices.close();
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка",
					body: error,
					type: "error",
				});
			}
		},

		deleteTravelPrice(value) {
			try {
				this.currentTravel.data.prices.value = this.currentTravel.data.prices.value.filter((item) => item !== value);
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка",
					body: error,
					type: "error",
				});
			}
		},
	},
	mounted() {
		api({
			method: "get",
			url: `get-travels-all`,
		})
			.then((response) => {
				if (!response) return;

				this.travels = response.data.result;

				this.travels.forEach((item) => {
					item.create = false;
					item.delete = false;
				});
			})
			.catch((error) => {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			})
			.finally(() => {
				this.loading.loader.travels = false;
			});
	},
};
</script>

<style scoped>
.travel__image {
	display: grid;
	grid-template-columns: 1fr 0.5fr;
	justify-content: center;
	gap: var(--default-gap);
}

.modal__info-form {
	display: flex;
	flex-direction: column;
	gap: calc(var(--default-gap) / 2);

	width: 100%;
}

.etravels {
	display: grid;
	grid-template-columns: repeat(2, 1fr);
	gap: var(--default-gap);

	animation: show-bottom-to-top-15 0.5s ease-in-out;
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

.etravels__item--hide {
	border: 1px solid rgb(210, 210, 210);
	background-color: rgb(240, 240, 240);
}

.etravels__item:hover {
	border: var(--default-border-focus);
	background-color: var(--item-background-color-active);
}

.etravels__header-item {
	transition: all 0.2s ease-in-out;
}

.etravels__item:hover > .etravels__item-header > .etravels__header-item {
	border: var(--default-border-focus);
}

.etravels__item--create {
	border: var(--input-create-border);
}

.etravels__item--create:hover {
	background-color: var(--input-create-background-color-hover);
	border: var(--input-create-border);
}

.etravels__item--delete {
	border: var(--input-delete-border);
}

.etravels__item--delete:hover {
	border: var(--input-delete-border);
	background-color: var(--input-delete-background-color-hover);
}

.etravels__item-header {
	display: flex;
	gap: calc(var(--default-gap) / 4);
}

:is(.etravels__header-item, .etravels__header-item) {
	border: var(--default-border);
	border-radius: calc(var(--default-border-radius) / 1.75);
	padding: 5px 10px;
}

.etravels__item--create :is(.etravels__header-item, .etravels__header-item) {
	border: var(--input-create-border);
}

.etravels__item--delete :is(.etravels__header-item, .etravels__header-item) {
	border: var(--input-delete-border);
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
	height: 300px;

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

	height: 250px;
	text-align: justify;
}

.etravels__item-info {
	display: flex;
	flex-direction: column;
	gap: calc(var(--default-gap) / 2);

	font-size: 1.125rem;
}

@media screen and (width <= 1500px) {
	.etravels {
		grid-template-columns: 1fr;
	}
}

@media screen and (width <= 600px) {
	.etravels__item-other {
		flex-direction: column;
	}
}
</style>
