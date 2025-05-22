<template>
	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                  МОДАЛЬНОЕ ОКНО                   |-->
	<!--|___________________________________________________|-->
	<Modal ref="modal" :settings="modal">
		<template #title>
			{{ modal.values.title }}
		</template>

		<template #body>
			<container-input>
				<!-- Название и другое -->
				<container-input-two :fieldset="true">
					<template #legend>
						<span>НАЗВАНИЕ И ДРУГОЕ</span>
					</template>
					<!-- НАЗВАНИЕ -->
					<template #title-one>
						НАЗВАНИЕ*<span v-if="currentClinic.data.name.edited">(ИЗМЕНЕНО)</span>
					</template>
					<template #input-one>
						<input
							type="text"
							placeholder="Введите название"
							autocomplete="off"
							:class="{ error: currentClinic.errors.name.status }"
							v-model="currentClinic.data.name.value"
							@input="currentClinic.data.name.edited = true"
						/>
					</template>
					<template #error-one>
						<span class="error" v-if="currentClinic.errors.name.status">
							{{ currentClinic.errors.name.message }}
						</span>
					</template>
					<!-- ДРУГОЕ -->
					<template #title-two>
						ДРУГОЕ <span v-if="currentClinic.data.other.edited">(ИЗМЕНЕНО)</span>
					</template>
					<template #input-two>
						<input
							type="text"
							placeholder="Введите другое"
							autocomplete="off"
							v-model="currentClinic.data.other.value"
							@input="currentClinic.data.other.edited = true"
						/>
					</template>
					<template #error-two>
						<span class="error" v-if="currentClinic.errors.other.status">
							{{ currentClinic.errors.other.message }}
						</span>
					</template>
				</container-input-two>

				<!-- Город и улица -->
				<container-input-two :fieldset="true">
					<template #legend>
						<span>ГОРОД И УЛИЦА</span>
					</template>
					<!-- Город -->
					<template #title-one>
						ГОРОД* <span v-if="currentClinic.data.city.edited">(ИЗМЕНЕНО)</span>
					</template>
					<template #input-one>
						<input
							type="text"
							placeholder="Введите город"
							autocomplete="off"
							:class="{ error: currentClinic.errors.city.status }"
							v-model="currentClinic.data.city.value"
							@input="currentClinic.data.city.edited = true"
						/>
					</template>
					<template #error-one>
						<span class="error" v-if="currentClinic.errors.city.status">
							{{ currentClinic.errors.city.message }}
						</span>
					</template>
					<!-- УЛИЦА -->
					<template #title-two>
						УЛИЦА* <span v-if="currentClinic.data.street.edited">(ИЗМЕНЕНО)</span>
					</template>
					<template #input-two>
						<input
							type="text"
							placeholder="Введите улицу"
							autocomplete="off"
							:class="{ error: currentClinic.errors.street.status }"
							v-model="currentClinic.data.street.value"
							@input="currentClinic.data.street.edited = true"
						/>
					</template>
					<template #error-two>
						<span class="error" v-if="currentClinic.errors.street.status">
							{{ currentClinic.errors.street.message }}
						</span>
					</template>
				</container-input-two>

				<!-- Дом и индекс -->
				<container-input-two :fieldset="true">
					<template #legend>
						<span>ДОМ И ИНДЕКС</span>
					</template>
					<!-- ДОМ -->
					<template #title-one>
						ДОМ* <span v-if="currentClinic.data.home.edited">(ИЗМЕНЕНО)</span>
					</template>
					<template #input-one>
						<input
							type="text"
							placeholder="Введите дом"
							autocomplete="off"
							:class="{ error: currentClinic.errors.home.status }"
							v-model="currentClinic.data.home.value"
							@input="currentClinic.data.home.edited = true"
						/>
					</template>
					<template #error-one>
						<span class="error" v-if="currentClinic.errors.home.status">
							{{ currentClinic.errors.home.message }}
						</span>
					</template>
					<!-- ИНДЕКС -->
					<template #title-two>
						ИНДЕКС* <span v-if="currentClinic.data.index.edited">(ИЗМЕНЕНО)</span>
					</template>
					<template #input-two>
						<input
							type="tel"
							v-mask="'######'"
							placeholder="______"
							autocomplete="off"
							:class="{ error: currentClinic.errors.index.status }"
							v-model="currentClinic.data.index.value"
							@input="currentClinic.data.index.edited = true"
						/>
					</template>
					<template #error-two>
						<span class="error" v-if="currentClinic.errors.index.status">
							{{ currentClinic.errors.index.message }}
						</span>
					</template>
				</container-input-two>

				<!-- Географические координаты -->
				<container-input-two :fieldset="true">
					<template #legend>
						<span>ГЕОГРАФИЧЕСКИЕ КООРДИНАТЫ</span>
					</template>
					<!-- Ширина -->
					<template #title-one>
						ШИРИНА* <span v-if="currentClinic.data.geoWidth.edited">(ИЗМЕНЕНО)</span>
					</template>
					<template #input-one>
						<input
							type="tel"
							autocomplete="off"
							placeholder="00.000000"
							v-mask="'##.######'"
							:class="{ error: currentClinic.errors.geoWidth.status }"
							v-model="currentClinic.data.geoWidth.value"
							@input="currentClinic.data.geoWidth.edited = true"
						/>
					</template>
					<template #error-one>
						<span class="error" v-if="currentClinic.errors.geoWidth.status">
							{{ currentClinic.errors.geoWidth.message }}
						</span>
					</template>
					<!-- Долгота -->
					<template #title-two>
						ДОЛГОТА* <span v-if="currentClinic.data.geoLongitude.edited">(ИЗМЕНЕНО)</span>
					</template>
					<template #input-two>
						<input
							type="tel"
							autocomplete="off"
							placeholder="00.000000"
							v-mask="'##.######'"
							:class="{ error: currentClinic.errors.geoLongitude.status }"
							v-model="currentClinic.data.geoLongitude.value"
							@input="currentClinic.data.geoLongitude.edited = true"
						/>
					</template>
					<template #error-two>
						<span class="error" v-if="currentClinic.errors.geoLongitude.status">
							{{ currentClinic.errors.geoLongitude.message }}
						</span>
					</template>
				</container-input-two>
			</container-input>
		</template>

		<template #footer>
			<template v-if="modal.values.look == 'create'">
				<button-default @click="addClinic">
					<Icon :name="'add'" :fill="'white'" :width="'23px'" :height="'23px'" />
					Добавить
				</button-default>
			</template>

			<template v-if="modal.values.look == 'default'">
				<button-default @click="updateClinic">
					<Icon :name="'edit'" :fill="'white'" :width="'28px'" :height="'28px'" />
					Обновить
				</button-default>
			</template>
		</template>
	</Modal>

	<info-bar>
		<template v-slot:title>Специалисты</template>
		<template v-slot:addreas>specialists</template>
	</info-bar>

	<block-once :minHeight="100">
		<template #title>КЛИНИКИ</template>

		<template #options>
			<button-default
				@click.prevent="saveClinicsChanges"
				:disabled="disabled.clinics.save"
				:look="'white'"
			>
				<Icon :name="'save'" :fill="'var(--primary-color)'" :width="'28px'" :height="'28px'" />
				Сохранить
			</button-default>
		</template>

		<template #body>
			<BaseTable
				v-if="loading.sections.clinics"
				:table="table"
				@create="openModalСreate"
				@edite="openModalEdite"
				@delete="removeClinic"
			/>

			<loader-child
				:isLoading="loading.loader.clinics"
				:minHeight="200"
				@loaderChildAfterLeave="loaderChildAfterLeave"
			/>
		</template>
	</block-once>
</template>

<script>
import Modal from "../../../components/modules/modal/Modal.vue";

import InfoBar from "../../../components/ui/admin/InfoBar.vue";
import BaseTable from "../../../components/modules/table/BaseTable.vue";

import LoaderChild from "../../../components/modules/LoaderChild.vue";

import ElementInputLabel from "../../../components/ui/admin/elements/ElementInputLabel.vue";
import BlockOnce from "../../../components/ui/admin/blocks/BlockOnce.vue";
import BlockTitle from "../../../components/ui/admin/blocks/BlockTitle.vue";
import BlockButtons from "../../../components/ui/admin/blocks/BlockButtons.vue";

import ContainerInput from "../../../components/ui/admin/containers/ContainerInput.vue";
import ContainerInputOnce from "../../../components/ui/admin/containers/input/ContainerInputOnce.vue";
import ContainerInputTwo from "../../../components/ui/admin/containers/input/ContainerInputTwo.vue";
import ContainerInputThree from "../../../components/ui/admin/containers/input/ContainerInputThree.vue";

import ButtonDefault from "../../../components/ui/admin/buttons/ButtonDefault.vue";
import ButtonRemove from "../../../components/ui/admin/buttons/ButtonRemove.vue";
import ButtonClaim from "../../../components/ui/admin/buttons/ButtonClaim.vue";

import Icon from "../../../components/modules/icon/Icon.vue";

import axios from "axios";
import shared from "../../../services/shared";
import validate from "../../../services/validate";

export default {
	components: {
		Modal,
		InfoBar,
		BaseTable,

		LoaderChild,
		ElementInputLabel,

		BlockOnce,
		BlockTitle,
		BlockButtons,

		ContainerInput,
		ContainerInputOnce,
		ContainerInputTwo,
		ContainerInputThree,

		ButtonDefault,
		ButtonRemove,
		ButtonClaim,

		Icon,

		axios,
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
					{ name: "id", text: "ID", columnType: "id" },
					{
						name: "name",
						text: "Название",
						columnType: "default",
						columnSize: "400px",
					},
					{
						name: "city",
						text: "Город",
						columnType: "default",
						columnSize: "250px",
					},
					{
						name: "street",
						text: "Улица",
						columnType: "default",
						columnSize: "250px",
					},
					{
						name: "home",
						text: "Дом",
						columnType: "default",
						columnSize: "auto",
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

			axios({
				method: "post",
				url: `${this.$store.getters.urlApi}` + `save-clinics-changes`,
				headers: {
					Accept: "application/json",
					Authorization: `Bearer ${localStorage.getItem("token")}`,
				},
				data: {
					clinics: this.table.body,
				},
			})
				.then((response) => {
					if (response.data.status) {
						try {
							shared.updateId(this.table.body, response.data.data);
							shared.clearDeletes(this.table.body);
							shared.clearFlags(this.table.body);

							this.$store.commit("addDebugger", {
								title: "Успешно!",
								body: response.data.message,
								type: "completed",
							});
						} catch (error) {
							this.$store.commit("addDebugger", {
								title: "Ошибка.",
								body: error,
								type: "error",
							});
						}
					} else {
						this.$store.commit("addDebugger", {
							title: "Ошибка.",
							body: response.data.message,
							type: "error",
						});
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
					this.disabled.clinics.save = false;
				});
		},
	},
	mounted() {
		axios({
			method: "get",
			headers: {
				Accept: "application/json",
			},
			url: `${this.$store.getters.urlApi}` + `get-clinics-all`,
		})
			.then((response) => {
				if (response.data.status) {
					this.table.body = response.data.data;

					for (let key in this.table.body) {
						this.table.body[key].create = false;
						this.table.body[key].delete = false;
					}
				} else {
					this.$store.commit("addDebugger", {
						title: "Ошибка.",
						body: response.data.message,
						type: "error",
					});
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
