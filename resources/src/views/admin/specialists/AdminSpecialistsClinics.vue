<template>
	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                  МОДАЛЬНОЕ ОКНО                   |-->
	<!--|___________________________________________________|-->
	<admin-modal ref="modal" @touchCloseModal="" :modal="modal">
		<template #title> Клиника (редактирование) </template>
		<template #body>
			<container-input>
				<!-- Название и другое -->
				<container-input-two :fieldset="true">
					<template #legend>НАЗВАНИЕ И ДРУГОЕ</template>
					<!-- Город -->
					<template #title-one> Название <span v-if="false">(Изменено)</span> </template>
					<template #input-one>
						<input type="text" ref="inputName" placeholder="Введите название" />
					</template>
					<template #error-one>
						<span class="error" v-if="false"> Ошибка </span>
					</template>
					<!-- Долгота -->
					<template #title-two> ДРУГОЕ <span v-if="false">(Изменено)</span> </template>
					<template #input-two>
						<input type="text" placeholder="Введите другое" />
					</template>
					<template #error-two>
						<span class="error" v-if="false"> Ошибка </span>
					</template>
				</container-input-two>


				<!-- Город и улица -->
				<container-input-two :fieldset="true">
					<template #legend>ГОРОД И УЛИЦА</template>
					<!-- Город -->
					<template #title-one> ГОРОД <span v-if="false">(Изменено)</span> </template>
					<template #input-one>
						<input type="text" placeholder="Введите город" />
					</template>
					<template #error-one>
						<span class="error" v-if="false"> Ошибка </span>
					</template>
					<!-- Долгота -->
					<template #title-two> УЛИЦА <span v-if="false">(Изменено)</span> </template>
					<template #input-two>
						<input type="text" placeholder="Введите улицу" />
					</template>
					<template #error-two>
						<span class="error" v-if="false"> Ошибка </span>
					</template>
				</container-input-two>

				<!-- Дом и индекс -->
				<container-input-two :fieldset="true">
					<template #legend>ДОМ И ИНДЕКС</template>
					<!-- Ширина -->
					<template #title-one> ДОМ <span v-if="false">(Изменено)</span> </template>
					<template #input-one>
						<input type="text" placeholder="Введите дом" />
					</template>
					<template #error-one>
						<span class="error" v-if="false"> Ошибка </span>
					</template>
					<!-- Долгота -->
					<template #title-two> ИНДЕКС <span v-if="false">(Изменено)</span> </template>
					<template #input-two>
						<input type="tel" v-mask="'######'" placeholder="______"/>
					</template>
					<template #error-two>
						<span class="error" v-if="false"> Ошибка </span>
					</template>
				</container-input-two>

				<!-- Географические координаты -->
				<container-input-two :fieldset="true">
					<template #legend>ГЕОГРАФИЧЕСКИЕ КООРДИНАТЫ</template>
					<!-- Ширина -->
					<template #title-one> ШИРИНА <span v-if="false">(Изменено)</span> </template>
					<template #input-one>
						<input type="number" ref="inputName" placeholder="Введите индекс" />
					</template>
					<template #error-one>
						<span class="error" v-if="false"> Ошибка </span>
					</template>
					<!-- Долгота -->
					<template #title-two> ДОЛГОТА <span v-if="false">(Изменено)</span> </template>
					<template #input-two>
						<input type="number" ref="inputName" placeholder="Введите индекс" />
					</template>
					<template #error-two>
						<span class="error" v-if="false"> Ошибка </span>
					</template>
				</container-input-two>
			</container-input>
		</template>
		<template #footer>
			<BlockButtons>
				<ButtonDefault> Создать </ButtonDefault>
				<ButtonDefault> Обновить </ButtonDefault>
			</BlockButtons>
		</template>
	</admin-modal>

	<info-bar>
		<template v-slot:title>Специалисты</template>
		<template v-slot:addreas>specialists</template>
	</info-bar>

	<block-once>
		<block-title>
			<template #title>Клиники</template>
			<template #buttons>
				<icon-save :width="28" :height="28" />
			</template>
		</block-title>

		<!-- Таблица -->
		<admin-specialists-table v-show="loading.table" :array="clinics" @useFilter="filterClinics" />

		<!-- Загрузчик -->
		<loader-child
			:isLoading="loading.loader"
			:minHeight="200"
			@loaderChildAfterLeave="loaderChildAfterLeave"
		/>

		<block-buttons>
			<button-default> Добавить </button-default>
		</block-buttons>
	</block-once>
</template>

<script>
import AdminModal from "../../../components/includes/admin/AdminModal.vue";

import InfoBar from "../../../components/ui/admin/InfoBar.vue";

import LoaderChild from "../../../components/includes/LoaderChild.vue";

import ElementInputLabel from "../../../components/ui/admin/ElementInputLabel.vue";
import BlockOnce from "../../../components/ui/admin/BlockOnce.vue";
import BlockTitle from "../../../components/ui/admin/BlockTitle.vue";
import BlockButtons from "../../../components/ui/admin/BlockButtons.vue";

import ContainerInput from "../../../components/ui/admin/ContainerInput.vue";
import ContainerInputOnce from "../../../components/ui/admin/ContainerInputOnce.vue";
import ContainerInputTwo from "../../../components/ui/admin/ContainerInputTwo.vue";
import ContainerInputThree from "../../../components/ui/admin/ContainerInputThree.vue";

import AdminSpecialistsTable from "./AdminSpecialistsTable.vue";

import ButtonDefault from "../../../components/ui/admin/ButtonDefault.vue";
import ButtonRemove from "../../../components/ui/admin/ButtonRemove.vue";

import IconSave from "../../../components/icons/IconSave.vue";

import axios from "axios";

export default {
	components: {
		AdminModal,
		InfoBar,
		LoaderChild,
		ElementInputLabel,
		BlockOnce,
		BlockTitle,
		BlockButtons,
		ContainerInput,
		ContainerInputOnce,
		ContainerInputTwo,
		ContainerInputThree,
		AdminSpecialistsTable,
		ButtonDefault,
		ButtonRemove,
		IconSave,
		axios,
	},
	data() {
		return {
			modal: {
				title: "",
				status: true,
				type: null,
				style: {
					create: false,
					delete: false,
				},
				modules: {
					title: true,
					buttons: {
						hide: false,
						close: true,
					},
					images: false,
					body: true,
					footer: true,
				},
			},
			loading: {
				loader: true,
				table: false,
			},
			clinics: [],
		};
	},
	methods: {
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                   Загрузчик                       |*/
		/* |___________________________________________________|*/
		/* После скрытия элементы */
		loaderChildAfterLeave() {
			this.loading.table = true;
		},

		/* _____________________________________________________*/
		/* ?. Фильтрация                                        */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		filterClinics(column, type) {
			switch (column) {
				case "id":
					if (type == "default") {
						this.clinics.sort((a, b) => {
							if (a.id > b.id) {
								return 1;
							}
							if (a.id < b.id) {
								return -1;
							}
							// a должно быть равным b
							return 0;
						});
					}

					if (type == "reverse") {
						this.clinics.sort((a, b) => {
							if (a.id < b.id) {
								return 1;
							}
							if (a.id > b.id) {
								return -1;
							}
							// a должно быть равным b
							return 0;
						});
					}
					break;
				case "name":
					if (type == "default") {
						this.clinics.sort((a, b) => {
							let aName = a.name.toLowerCase();
							let bName = b.name.toLowerCase();

							let aNameFirstLetter = aName[0].charCodeAt(0);
							let bNameFirstLetter = bName[0].charCodeAt(0);

							if (aNameFirstLetter > bNameFirstLetter) {
								return 1;
							}
							if (aNameFirstLetter < bNameFirstLetter) {
								return -1;
							}
							// a должно быть равным b
							return 0;
						});
					}

					if (type == "reverse") {
						this.clinics.sort((a, b) => {
							let aName = a.name.toLowerCase();
							let bName = b.name.toLowerCase();

							let aNameFirstLetter = aName[0].charCodeAt(0);
							let bNameFirstLetter = bName[0].charCodeAt(0);

							if (aNameFirstLetter < bNameFirstLetter) {
								return 1;
							}
							if (aNameFirstLetter > bNameFirstLetter) {
								return -1;
							}
							// a должно быть равным b
							return 0;
						});
					}

					break;
				default:
					break;
			}
		},
	},
	mounted() {
		axios({
			method: "post",
			headers: {
				Accept: "application/json",
			},
			url: `${this.$store.state.axios.urlApi}` + `get-clinics-all`,
		})
			.then((response) => {
				this.clinics = response.data;
				this.loading.loader = false;
			})
			.catch((error) => {
				console.log(error);
			});
	},
};
</script>

<style scoped></style>
