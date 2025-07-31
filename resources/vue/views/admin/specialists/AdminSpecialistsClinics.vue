<template>
	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                  МОДАЛЬНОЕ ОКНО                   |-->
	<!--|___________________________________________________|-->
	<VueModal ref="modal" :settings="modal">
		<template #title>
			{{ modal.values.title }}
		</template>

		<template #body>
			<VueInputContainer :direction="'row'" :count="2" :gap="'10px'">
				<template #legend> НАЗВАНИЕ И ДРУГОЕ </template>
				<template #inputs>
					<VueInput
						v-model="currentClinic.data.name.value"
						:type="'text'"
						:placeholder="'Введите название'"
						:error="currentClinic.errors.name.status"
					>
						<template #label> НАЗВАНИЕ </template>
						<template #error>
							{{ currentClinic.errors.name.message }}
						</template>
					</VueInput>

					<VueInput
						v-model="currentClinic.data.other.value"
						:type="'text'"
						:placeholder="'Введите другое'"
						:error="currentClinic.errors.other.status"
					>
						<template #label> ДРУГОЕ </template>
						<template #error>
							{{ currentClinic.errors.other.message }}
						</template>
					</VueInput>
				</template>
			</VueInputContainer>

			<VueInputContainer :direction="'row'" :count="2" :gap="'10px'">
				<template #legend> ГОРОД И УЛИЦА </template>
				<template #inputs>
					<VueInput
						v-model="currentClinic.data.city.value"
						:type="'text'"
						:placeholder="'Введите город'"
						:error="currentClinic.errors.city.status"
					>
						<template #label> ГОРОД </template>
						<template #error>
							{{ currentClinic.errors.city.message }}
						</template>
					</VueInput>

					<VueInput
						v-model="currentClinic.data.street.value"
						:type="'text'"
						:placeholder="'Введите улицу'"
						:error="currentClinic.errors.street.status"
					>
						<template #label> УЛИЦА </template>
						<template #error>
							{{ currentClinic.errors.street.message }}
						</template>
					</VueInput>
				</template>
			</VueInputContainer>

			<VueInputContainer :direction="'row'" :count="2" :gap="'10px'">
				<template #legend> ДОМ И ИНДЕКС </template>
				<template #inputs>
					<VueInput
						v-model="currentClinic.data.home.value"
						:type="'text'"
						:placeholder="'Введите дом'"
						:error="currentClinic.errors.home.status"
					>
						<template #label> ДОМ </template>
						<template #error>
							{{ currentClinic.errors.home.message }}
						</template>
					</VueInput>

					<VueInput
						v-model="currentClinic.data.index.value"
						:type="'mask'"
						:mask="'######'"
						:placeholder="'______'"
						:error="currentClinic.errors.index.status"
					>
						<template #label> ИНДЕКС </template>
						<template #error>
							{{ currentClinic.errors.index.message }}
						</template>
					</VueInput>
				</template>
			</VueInputContainer>

			<VueInputContainer :direction="'row'" :count="2" :gap="'10px'">
				<template #legend> ГЕОГРАФИЧЕСКИЕ КООРДИНАТЫ </template>
				<template #inputs>
					<VueInput
						v-model="currentClinic.data.geoWidth.value"
						:type="'mask'"
						:mask="'##.######'"
						:placeholder="'00.000000'"
						:error="currentClinic.errors.geoWidth.status"
					>
						<template #label> ШИРИНА </template>
						<template #error>
							{{ currentClinic.errors.geoWidth.message }}
						</template>
					</VueInput>

					<VueInput
						v-model="currentClinic.data.geoLongitude.value"
						:type="'mask'"
						:mask="'##.######'"
						:placeholder="'00.000000'"
						:error="currentClinic.errors.geoLongitude.status"
					>
						<template #label> ДОЛГОТА </template>
						<template #error>
							{{ currentClinic.errors.geoLongitude.message }}
						</template>
					</VueInput>
				</template>
			</VueInputContainer>
		</template>

		<template #footer>
			<template v-if="modal.values.look == 'create'">
				<VueButton @click="addClinic">
					<VueIcon :name="'add'" :fill="'white'" :width="'23px'" :height="'23px'" />
					Добавить
				</VueButton>
			</template>

			<template v-if="modal.values.look == 'default'">
				<VueButton @click="updateClinic">
					<VueIcon :name="'edit'" :fill="'white'" :width="'28px'" :height="'28px'" />
					Обновить
				</VueButton>
			</template>
		</template>
	</VueModal>

	<info-bar>
		<template v-slot:title>Специалисты</template>
		<template v-slot:addreas>specialists</template>
	</info-bar>

	<block-once :minHeight="100">
		<template #title>КЛИНИКИ</template>

		<template #options>
			<VueButton
				@click.prevent="saveClinicsChanges"
				:disabled="disabled.clinics.save"
				:look="'inverse'"
			>
				<VueIcon
					:name="'save'"
					:fill="'var(--primary-color)'"
					:width="'28px'"
					:height="'28px'"
				/>
				Сохранить
			</VueButton>
		</template>

		<template #body>
			<VueTable
				v-if="loading.sections.clinics"
				:table="table"
				@create="openModalСreate()"
				@edite="openModalEdite"
				@delete="removeClinic"
			/>

			<VueLoader
				:isLoading="loading.loader.clinics"
				:isChild="true"
				:minHeight="300"
				@afterLeave="loaderChildAfterLeave"
			/>
		</template>
	</block-once>
</template>

<script>
import VueModal from "../../../components/modules/modal/VueModal.vue";
import VueTable from "../../../components/modules/table/VueTable.vue";
import VueInput from "../../../components/modules/input/VueInput.vue";
import VueInputContainer from "../../../components/modules/input/VueInputContainer.vue";

import InfoBar from "../../../components/ui/admin/InfoBar.vue";
import BlockOnce from "../../../components/ui/admin/blocks/BlockOnce.vue";

import VueLoader from "../../../components/modules/VueLoader.vue";
import VueIcon from "../../../components/modules/icon/VueIcon.vue";
import VueButton from "../../../components/ui/VueButton.vue";

import api from "../../../mixin/api";
import shared from "../../../services/shared";
import validate from "../../../services/validate";

export default {
	components: {
		VueModal,
		VueTable,
		VueInput,
		VueInputContainer,

		InfoBar,
		BlockOnce,

		VueLoader,
		VueIcon,
		VueButton,
	},
	data() {
		return {
			/* Загрузчик */
			loading: {
				loader: {
					clinics: true,
				},
				sections: {
					clinics: false,
				},
			},

			/* Кнопки */
			disabled: {
				clinics: {
					save: false,
				},
			},

			/* Модальное окно */
			modal: {
				thin: false,
				clamped: false,
				values: {
					title: "",
					look: "default",
				},
			},

			/* Текущая клиника */
			currentClinic: {
				errors: {
					id: {
						message: "",
						status: false,
					},
					name: {
						message: "",
						status: false,
					},
					other: {
						message: "",
						status: false,
					},
					city: {
						message: "",
						status: false,
					},
					street: {
						message: "",
						status: false,
					},
					home: {
						message: "",
						status: false,
					},
					index: {
						message: "",
						status: false,
					},
					geoWidth: {
						message: "",
						status: false,
					},
					geoLongitude: {
						message: "",
						status: false,
					},
					create: {
						message: false,
						status: false,
					},
					delete: {
						message: false,
						status: false,
					},
				},
				data: {
					id: {
						value: "",
						edited: false,
					},
					name: {
						value: "",
						edited: false,
					},
					other: {
						value: "",
						edited: false,
					},
					city: {
						value: "",
						edited: false,
					},
					street: {
						value: "",
						edited: false,
					},
					home: {
						value: "",
						edited: false,
					},
					index: {
						value: "",
						edited: false,
					},
					geoWidth: {
						value: "",
						edited: false,
					},
					geoLongitude: {
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

			/* Таблица */
			table: {
				// Настройки
				options: {
					create: true,
					delete: true,
					update: true,
					report: false,
				},

				// Колонки
				head: [
					{
						name: "id",
						title: "ID",
						type: "id",
						hide: true,
					},
					{
						name: "name",
						title: "Название",
						type: "string",
						style: {
							column: {
								width: "400px",
							},
						},
					},
					{
						name: "city",
						title: "Город",
						type: "string",
						style: {
							column: {
								width: "250px",
							},
						},
					},
					{
						name: "street",
						title: "Улица",
						type: "string",
						style: {
							column: {
								width: "250px",
							},
						},
					},
					{
						name: "home",
						title: "Дом",
						type: "string",
					},
				],

				// Элементы
				body: [],
			},
		};
	},
	methods: {
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                   Загрузчик                       |*/
		/* |___________________________________________________|*/
		/* После скрытия элементы */
		loaderChildAfterLeave() {
			this.loading.sections.clinics = true;
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                 Модальное окно                    |*/
		/* |___________________________________________________|*/
		/* Открытие модального окна */
		openModal(name, title, look) {
			this[name].values.title = title;
			this[name].values.look = look;

			this.$refs[name].open();
		},

		/* Открытие модального окна для добавления */
		openModalСreate() {
			shared.clearObjectFull(this.currentClinic);

			this.openModal("modal", "КЛИНИКА", "create");
		},

		/* Открытие модального окна для добавления */
		openModalEdite(value) {
			shared.clearObjectFull(this.currentClinic);
			shared.setData(value, this.currentClinic);

			this.openModal("modal", "КЛИНИКА", "default");
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                    КЛИНИКИ                        |*/
		/* |___________________________________________________|*/
		/* Пометка на удаление */
		removeClinic(clinic) {
			clinic.delete = !clinic.delete;
		},

		/* Обновление элемента */
		updateClinic() {
			if (
				validate.checkInputsAll(this.currentClinic, [
					{
						key: "name",
						type: "text",
					},
					{
						key: "city",
						type: "text",
					},
					{
						key: "street",
						type: "text",
					},
					{
						key: "home",
						type: "text",
					},
					{
						key: "index",
						type: "number",
					},
					{
						key: "geoWidth",
						type: "number",
					},
					{
						key: "geoLongitude",
						type: "number",
					},
				])
			)
				return;

			try {
				let clinicToUpdate = this.table.body.find((item) => {
					if (item.id === this.currentClinic.data.id.value) {
						return item;
					}
				});

				for (let key in this.currentClinic.data) {
					clinicToUpdate[key] = this.currentClinic.data[key].value;
				}

				this.$refs.modal.close();
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			}
		},
		/* Добавление элемента в массив */
		addClinic() {
			if (
				validate.checkInputsAll(this.currentClinic, [
					{
						key: "name",
						type: "text",
					},
					{
						key: "city",
						type: "text",
					},
					{
						key: "street",
						type: "text",
					},
					{
						key: "home",
						type: "text",
					},
					{
						key: "index",
						type: "number",
					},
					{
						key: "geoWidth",
						type: "number",
					},
					{
						key: "geoLongitude",
						type: "number",
					},
				])
			)
				return;

			try {
				this.table.body.push({
					id: shared.getMaxId(this.table.body) + 1,
					name: this.currentClinic.data.name.value,
					city: this.currentClinic.data.city.value,
					street: this.currentClinic.data.street.value,
					home: this.currentClinic.data.home.value,
					index: this.currentClinic.data.index.value,
					geoWidth: this.currentClinic.data.geoWidth.value,
					geoLongitude: this.currentClinic.data.geoLongitude.value,
					create: true,
					delete: false,
				});

				this.$refs.modal.close();
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			}
		},

		/* Сохранение изменений на сервере */
		saveClinicsChanges() {
			this.disabled.clinics.save = true;

			api({
				method: "post",
				url: this.$store.getters.urlApi + `save-clinics-changes`,
				headers: {
					Accept: "application/json",
				},
				data: {
					clinics: this.table.body,
				},
			})
				.then((response) => {
					if (!response) return;

					shared.updateId(this.table.body, response.data.result);
					shared.clearDeletes(this.table.body);
					shared.clearFlags(this.table.body);
				})
				.catch((error) => {
					this.$store.commit("addDebugger", {
						title: "Ошибка.",
						body: error,
						type: "error",
					});
				})
				.finally(() => {
					this.disabled.clinics.save = false;
				});
		},
	},
	mounted() {
		api({
			method: "get",
			url: this.$store.getters.urlApi + `get-clinics-all`,
		})
			.then((response) => {
				if (!response) return;

				this.table.body = response.data.result;

				for (let key in this.table.body) {
					this.table.body[key].create = false;
					this.table.body[key].delete = false;
				}
			})
			.catch((error) => {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			})
			.finally(() => {
				this.loading.loader.clinics = false;
			});
	},
};
</script>

<style scoped>
.container-clinics {
	display: flex;
	flex-direction: column;
	gap: 10px;
}
</style>
