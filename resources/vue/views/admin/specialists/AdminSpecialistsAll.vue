<template>
	<!-- Модальное окно: Видео -->
	<Modal ref="modal" :settings="modal">
		<template #title>
			{{ modal.values.title }}
		</template>

		<template #body>
			<VueInput
				v-model="modalForm.data.name.value"
				:type="'text'"
				:placeholder="'Введите название'"
				:error="modalForm.errors.name.status"
			>
				<template #label> КЛИНИКА </template>
				<template #error>
					{{ modalForm.errors.name.message }}
				</template>
			</VueInput>

			<VueInput
				v-model="modalForm.data.company.value"
				:type="'text'"
				:placeholder="'Введите название'"
				:error="modalForm.errors.company.status"
			>
				<template #label> КОМПАНИЯ </template>
				<template #error>
					{{ modalForm.errors.company.message }}
				</template>
			</VueInput>

			<VueInput
				v-model="modalForm.data.url.value"
				:type="'text'"
				:placeholder="'Введите ссылку'"
				:error="modalForm.errors.url.status"
			>
				<template #label> ССЫЛКА НА САЙТ </template>
				<template #error>
					{{ modalForm.errors.url.message }}
				</template>
			</VueInput>

			<VueInput
				v-model="modalForm.data.email.value"
				:type="'text'"
				:placeholder="'Введите почту'"
				:error="modalForm.errors.email.status"
			>
				<template #label> ПОЧТА КОМПАНИИ </template>
				<template #error>
					{{ modalForm.errors.email.message }}
				</template>
			</VueInput>

			<VueInput
				v-model="modalForm.data.description.value"
				:type="'textarea'"
				:placeholder="'Введите название'"
				:error="modalForm.errors.description.status"
			>
				<template #label> ПОЧТА КОМПАНИИ </template>
				<template #error>
					{{ modalForm.errors.description.message }}
				</template>
			</VueInput>
		</template>

		<template #footer>
			<ButtonDefault :look="'white'" @click="fillForm('Медлайн')"> Медлайн </ButtonDefault>

			<ButtonDefault :look="'white'" @click="fillForm('Кардиологика')">
				Кардиологика
			</ButtonDefault>

			<ButtonDefault @click="downloadSpecialistsXML" :disabled="disabled.specialists.download">
				<Icon :name="'download'" :fill="'white'" :width="'24px'" :height="'24px'" />
				Выгрузить
			</ButtonDefault>
		</template>
	</Modal>

	<info-bar>
		<template v-slot:title>Специалисты</template>
		<template v-slot:addreas>specialists</template>
	</info-bar>

	<block-once :minHeight="100" :hide="true">
		<template #title>СПИСОК ВРАЧЕЙ</template>

		<template #options>
			<button-default @click.prevent="openModalEdite" :look="'white'">
				<Icon
					:name="'download'"
					:fill="'var(--primary-color)'"
					:width="'28px'"
					:height="'28px'"
				/>
				Выгрузить
			</button-default>
			<button-default
				@click.prevent="saveSpecialistChanges"
				:disabled="disabled.specialists.save"
				:look="'white'"
			>
				<Icon :name="'save'" :fill="'var(--primary-color)'" :width="'28px'" :height="'28px'" />
				Сохранить
			</button-default>
		</template>

		<template #body>
			<BaseTable
				v-if="loading.sections.specialists"
				:table="table"
				@create="$router.push('especialists/new')"
				@edite="openSpecialist"
				@delete="removeSpecialist"
			>
				<template v-slot:hideSpecialist="{ row }">
					<div class="table__hide" v-if="row.hide" @click="hideSpecialist(row)">Да</div>
					<div class="table__hide" v-else @click="hideSpecialist(row)">Нет</div>
				</template>
			</BaseTable>

			<loader-child
				:isLoading="loading.loader.specialists"
				:minHeight="300"
				@loaderChildAfterLeave="loaderChildAfterLeave"
			/>
		</template>
	</block-once>
</template>

<script>
import InfoBar from "../../../components/ui/admin/InfoBar.vue";
import BaseTable from "../../../components/modules/table/BaseTable.vue";
import LoaderChild from "../../../components/modules/LoaderChild.vue";
import Modal from "../../../components/modules/modal/Modal.vue";
import VueInput from "../../../components/modules/VueInput.vue";

import BlockOnce from "../../../components/ui/admin/blocks/BlockOnce.vue";

import ButtonDefault from "../../../components/ui/admin/buttons/ButtonDefault.vue";
import ButtonRemove from "../../../components/ui/admin/buttons/ButtonRemove.vue";

import Icon from "../../../components/modules/icon/Icon.vue";

import api from "../../../services/api";
import validate from "../../../services/validate";
import shared from "../../../services/shared";

import { RouterView, RouterLink } from "vue-router";

export default {
	components: {
		InfoBar,
		BaseTable,
		LoaderChild,
		Modal,
		VueInput,

		BlockOnce,

		ButtonDefault,
		ButtonRemove,

		Icon,

		RouterView,
		RouterLink,
	},
	data() {
		return {
			/* Загрузчик */
			loading: {
				loader: {
					specialists: true,
				},
				sections: {
					specialists: false,
				},
			},

			/* Кнопки */
			disabled: {
				specialists: {
					save: false,
					download: false,
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

			/* Форма */
			modalForm: {
				errors: {
					name: {
						status: false,
						message: "",
					},
					company: {
						status: false,
						message: "",
					},
					url: {
						status: false,
						message: "",
					},
					email: {
						status: false,
						message: "",
					},
					description: {
						status: false,
						message: "",
					},
				},
				data: {
					name: {
						value: "",
						edited: false,
					},
					company: {
						value: "",
						edited: false,
					},
					url: {
						value: "",
						edited: false,
					},
					email: {
						value: "",
						edited: false,
					},
					description: {
						value: "",
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
						columnSize: "600px",
					},
					{
						name: "specializations",
						text: "Специализации",
						columnType: "default",
						columnSize: "auto",
					},
					{
						name: "hideSpecialist",
						text: "Скрытие",
						columnType: "hideSpecialist",
						columnSize: "100px",
						columnJustify: "center",
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
			this.loading.sections.specialists = true;
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
		openModalEdite() {
			shared.clearObjectFull(this.modalForm);

			this.openModal("modal", "ВЫГРУЗКА", "default");
		},

		/* Заполнение формы */
		fillForm(name) {
			switch (name) {
				case "Медлайн":
					this.modalForm.data.name.value = `Медицинский центр "Медлайн"`;
					this.modalForm.data.company.value = `ООО "Медлайн"`;
					this.modalForm.data.url.value =
						window.document.location.protocol + "//" + window.document.location.hostname;
					this.modalForm.data.email.value = "qualitycontrol@medline45.ru";
					this.modalForm.data.description.value =
						"«МедЛайн» в Шадринске: врачебные приёмы, анализы, УЗИ, процедурный кабинет. Ваше здоровье – наша забота! Доступные цены, отличный сервис!";
					break;
				case "Кардиологика":
					this.modalForm.data.name.value = `Медицинский центр "Кардиологика"`;
					this.modalForm.data.company.value = `ООО "Кардиологика"`;
					this.modalForm.data.url.value =
						window.document.location.protocol + "//" + window.document.location.hostname;
					this.modalForm.data.email.value = "cardiologica@yandex.ru";
					this.modalForm.data.description.value =
						"«Кардиологика». Отличные врачи, УЗИ, массаж, ЭКГ, кинезиотейпирование, ЭЭГ, функциональная диагностика, низкие цены. 8 мкрн, 26, пом. 1.";
					break;
				default:
					break;
			}
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                  Специалисты                      |*/
		/* |___________________________________________________|*/
		/* _____________________________________________________*/
		/* Сохранение, обновление, удаление                     */
		/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
		openSpecialist(specialist) {
			this.$router.push({ name: "especialist-profile", params: { id: specialist.id } });
		},

		/* Скрытие выбранного доктора */
		hideSpecialist(specialist) {
			specialist.hide = !specialist.hide;
		},

		/* Удаление выбранного доктора */
		removeSpecialist(specialist) {
			specialist.delete = !specialist.delete;
		},

		/* Сохранение изменений */
		saveSpecialistChanges() {
			this.disabled.specialists.save = true;

			// Получение массива докторов с сервера
			api({
				method: "post",
				url: this.$store.getters.urlApi + `save-specialists-changes`,
				headers: {
					Accept: "application/json",
				},
				data: {
					specialists: this.table.body,
				},
			})
				.then((response) => {
					if (!response) return;

					shared.clearDeletes(this.table.body);
				})
				.catch((error) => {
					this.$store.commit("addDebugger", {
						title: "Ошибка.",
						body: error,
						type: "error",
					});
				})
				.finally(() => {
					this.disabled.specialists.save = false;
				});
		},

		downloadSpecialistsXML() {
			if (
				validate.checkInputsAll(this.modalForm, [
					{
						key: "name",
						type: "text",
					},
					{
						key: "company",
						type: "text",
					},
					{
						key: "url",
						type: "text",
					},
					{
						key: "email",
						type: "text",
					},
					{
						key: "description",
						type: "text",
					},
				])
			)
				return;

			this.disabled.specialists.download = true;

			api({
				method: "post",
				url: this.$store.getters.urlApi + `make-specialists-xml`,
				headers: {
					Accept: "application/json",
				},
				data: {
					name: this.modalForm.data.name.value,
					company: this.modalForm.data.company.value,
					url: this.modalForm.data.url.value,
					email: this.modalForm.data.email.value,
					description: this.modalForm.data.description.value,
				},
			})
				.then((response) => {
					if (!response) return;

					this.$refs.modal.close();
				})
				.catch((error) => {
					this.$store.commit("addDebugger", {
						title: "Ошибка.",
						body: error,
						type: "error",
					});
				})
				.finally(() => {
					this.disabled.specialists.download = false;
				});
		},
	},
	mounted() {
		// Получение массива докторов с сервера
		api({
			method: "get",
			url: `${this.$store.getters.urlApi}` + `get-specialists-short`,
		})
			.then((response) => {
				if (!response) return;

				this.table.body = response.data.result;

				for (let key in this.table.body) {
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
				this.loading.loader.specialists = false;
			});
	},
};
</script>

<style scoped>
.table__hide {
	user-select: none;
	cursor: pointer;
}

.table__hide:hover {
	color: var(--primary-color);
}
</style>
