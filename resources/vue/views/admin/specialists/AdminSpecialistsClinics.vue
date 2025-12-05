<template>
	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                  МОДАЛЬНОЕ ОКНО                   |-->
	<!--|___________________________________________________|-->
	<VueModal ref="modal" :settings="modal">
		<template #title>
			{{ modal.values.title }}
		</template>

		<template #body>
			<VueFieldset :count="2" :gap="'20px'">
				<template #legend> НАЗВАНИЕ И ДРУГОЕ </template>
				<template #inputs>
					<VueValues
						v-model.trim="currentClinic.data.name.value"
						:type="'text'"
						:placeholder="'Введите название'"
						:error="currentClinic.errors.name.status"
					>
						<template #label>
							<VueIcon :name="'Title'" :fill="'var(--primary-color)'" :width="'20px'" :height="'20px'" />
							НАЗВАНИЕ
						</template>
						<template #error>
							{{ currentClinic.errors.name.message }}
						</template>
					</VueValues>

					<VueValues
						v-model.trim="currentClinic.data.other.value"
						:type="'text'"
						:placeholder="'Введите другое'"
						:error="currentClinic.errors.other.status"
					>
						<template #label>
							<VueIcon :name="'Text Ad'" :fill="'var(--primary-color)'" :width="'20px'" :height="'20px'" />
							ДРУГОЕ
						</template>
						<template #error>
							{{ currentClinic.errors.other.message }}
						</template>
					</VueValues>
				</template>
			</VueFieldset>

			<VueFieldset :count="2" :gap="'20px'">
				<template #legend> ГОРОД И УЛИЦА </template>
				<template #inputs>
					<VueValues
						v-model.trim="currentClinic.data.city.value"
						:type="'text'"
						:placeholder="'Введите город'"
						:error="currentClinic.errors.city.status"
					>
						<template #label>
							<VueIcon :name="'Home'" :fill="'var(--primary-color)'" :width="'20px'" :height="'20px'" />
							ГОРОД
						</template>
						<template #error>
							{{ currentClinic.errors.city.message }}
						</template>
					</VueValues>

					<VueValues
						v-model.trim="currentClinic.data.street.value"
						:type="'text'"
						:placeholder="'Введите улицу'"
						:error="currentClinic.errors.street.status"
					>
						<template #label>
							<VueIcon :name="'Home'" :fill="'var(--primary-color)'" :width="'20px'" :height="'20px'" />
							УЛИЦА
						</template>
						<template #error>
							{{ currentClinic.errors.street.message }}
						</template>
					</VueValues>
				</template>
			</VueFieldset>

			<VueFieldset :count="2" :gap="'10px'">
				<template #legend> ДОМ И ИНДЕКС </template>
				<template #inputs>
					<VueValues
						v-model.trim="currentClinic.data.home.value"
						:type="'text'"
						:placeholder="'Введите дом'"
						:error="currentClinic.errors.home.status"
					>
						<template #label>
							<VueIcon :name="'Home'" :fill="'var(--primary-color)'" :width="'20px'" :height="'20px'" />
							ДОМ
						</template>
						<template #error>
							{{ currentClinic.errors.home.message }}
						</template>
					</VueValues>

					<VueValues
						v-model.trim="currentClinic.data.index.value"
						:type="'mask'"
						:mask="'######'"
						:placeholder="'______'"
						:inputmode="'numeric'"
						:error="currentClinic.errors.index.status"
					>
						<template #label>
							<VueIcon :name="'Home'" :fill="'var(--primary-color)'" :width="'20px'" :height="'20px'" />
							ИНДЕКС
						</template>
						<template #error>
							{{ currentClinic.errors.index.message }}
						</template>
					</VueValues>
				</template>
			</VueFieldset>

			<VueFieldset :count="2" :gap="'20px'">
				<template #legend> ГЕОГРАФИЧЕСКИЕ КООРДИНАТЫ </template>
				<template #inputs>
					<VueValues
						v-model.trim="currentClinic.data.geoWidth.value"
						:type="'mask'"
						:mask="'##.######'"
						:placeholder="'00.000000'"
						:inputmode="'decimal'"
						:error="currentClinic.errors.geoWidth.status"
					>
						<template #label>
							<VueIcon :name="'Location On'" :fill="'var(--primary-color)'" :width="'20px'" :height="'20px'" />
							ШИРИНА
						</template>
						<template #error>
							{{ currentClinic.errors.geoWidth.message }}
						</template>
					</VueValues>

					<VueValues
						v-model.trim="currentClinic.data.geoLongitude.value"
						:type="'mask'"
						:mask="'##.######'"
						:placeholder="'00.000000'"
						:inputmode="'decimal'"
						:error="currentClinic.errors.geoLongitude.status"
					>
						<template #label>
							<VueIcon :name="'Location On'" :fill="'var(--primary-color)'" :width="'20px'" :height="'20px'" />
							ДОЛГОТА
						</template>
						<template #error>
							{{ currentClinic.errors.geoLongitude.message }}
						</template>
					</VueValues>
				</template>
			</VueFieldset>
		</template>

		<template #footer>
			<template v-if="modal.values.look == 'create'">
				<VueButton @click="addClinic">
					<VueIcon :name="'Add'" :fill="'white'" :width="'26px'" :height="'26px'" />
					Добавить
				</VueButton>
			</template>

			<template v-if="modal.values.look == 'default'">
				<VueButton @click="updateClinic">
					<VueIcon :name="'Edit'" :fill="'white'" :width="'28px'" :height="'28px'" />
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
			<VueButton @click.prevent="saveClinicsChanges" :disabled="disabled.clinics.save" :look="'inverse'">
				<VueIcon :name="'Save'" :fill="'var(--primary-color)'" :width="'28px'" :height="'28px'" />
				Сохранить
			</VueButton>

			<VueButton
				:look="'inverse'"
				@click="
					$store.commit('openWiki', {
						title: 'Клиники',
						component: 'SpecialistsClinics',
					})
				"
				:minWidth="'30px'"
			>
				<VueIcon :name="'Info'" :fill="'var(--primary-color)'" :width="'30px'" :height="'30px'" />
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
import InfoBar from "../../../components/ui/admin/InfoBar.vue";
import BlockOnce from "../../../components/ui/admin/blocks/BlockOnce.vue";

import api from "../../../mixin/api";
import shared from "../../../services/shared";
import validate from "../../../services/validate";

export default {
	components: {
		InfoBar,
		BlockOnce,
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
				touch: true,
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
						options: {
							column: {
								style: {
									width: "400px",
								},
							},
						},
					},
					{
						name: "city",
						title: "Город",
						type: "string",
						options: {
							column: {
								style: {
									width: "250px",
								},
							},
						},
					},
					{
						name: "street",
						title: "Улица",
						type: "string",
						options: {
							column: {
								style: {
									width: "250px",
								},
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
