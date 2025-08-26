<template>
	<!-- Модальное окно: Путевка -->
	<VueModal ref="modalVacancy" :settings="modalVacancy">
		<template #title>
			{{ modalVacancy.values.title }}
		</template>

		<template
			#buttons
			v-if="
				(modalVacancy.values.look == 'default') &
				!currentVacancy.data.delete.value &
				!currentVacancy.data.create.value
			"
		>
			<VueIcon
				v-if="!currentVacancy.data.hide.value"
				:name="'Visibility'"
				:fill="'var(--primary-color)'"
				:hover="'var(--primary-color-hover)'"
				:cursor="'pointer'"
				:width="'26px'"
				:height="'26px'"
				@click="currentVacancy.data.hide.value = true"
			/>
			<VueIcon
				v-else
				:name="'Visibility Off'"
				:fill="'var(--primary-color)'"
				:hover="'var(--primary-color-hover)'"
				:cursor="'pointer'"
				:width="'26px'"
				:height="'26px'"
				@click="currentVacancy.data.hide.value = false"
			/>
		</template>

		<template #body>
			<VueInput
				:placeholder="'Введите заголовок'"
				:type="'text'"
				v-model="currentVacancy.data.title.value"
				:error="currentVacancy.errors.title.status"
			>
				<template #label> ЗАГОЛОВОК </template>
				<template #error>
					{{ currentVacancy.errors.title.message }}
				</template>
			</VueInput>

			<VueInput
				:placeholder="'Введите адрес'"
				:type="'text'"
				v-model="currentVacancy.data.address.value"
				:error="currentVacancy.errors.address.status"
			>
				<template #label> АДРЕС </template>
				<template #error>
					{{ currentVacancy.errors.address.message }}
				</template>
			</VueInput>

			<VueInput
				:placeholder="'Введите график'"
				:type="'text'"
				v-model="currentVacancy.data.schedule.value"
				:error="currentVacancy.errors.schedule.status"
			>
				<template #label> ГРАФИК </template>
				<template #error>
					{{ currentVacancy.errors.schedule.message }}
				</template>
			</VueInput>

			<VueTiptap
				ref="tiptapRequirements"
				v-model="currentVacancy.data.requirements.value"
				:editable="true"
				:limit="1_000"
				:minHeight="150"
				:options="['align', 'format', 'list']"
				:placeholder="'Введите требования'"
				:error="currentVacancy.errors.requirements.status"
			>
				<template #error>
					{{ currentVacancy.errors.requirements.message }}
				</template>
			</VueTiptap>

			<VueTiptap
				ref="tiptapConditions"
				v-model="currentVacancy.data.conditions.value"
				:editable="true"
				:limit="1_000"
				:minHeight="150"
				:options="['align', 'format', 'list']"
				:placeholder="'Введите условия'"
				:error="currentVacancy.errors.conditions.status"
			>
				<template #error>
					{{ currentVacancy.errors.conditions.message }}
				</template>
			</VueTiptap>

			<VueTiptap
				ref="tiptapDescription"
				v-model="currentVacancy.data.description.value"
				:editable="true"
				:limit="1_000"
				:minHeight="150"
				:options="['align', 'format', 'list']"
				:placeholder="'Введите описание'"
				:error="currentVacancy.errors.description.status"
			>
				<template #error>
					{{ currentVacancy.errors.description.message }}
				</template>
			</VueTiptap>

			<VueInput
				:placeholder="'Введите зарплату'"
				:type="'number'"
				:inputmode="'numeric'"
				v-model="currentVacancy.data.salary.value"
				:error="currentVacancy.errors.salary.status"
			>
				<template #label> ЗАРПЛАТА </template>
				<template #error>
					{{ currentVacancy.errors.salary.message }}
				</template>
			</VueInput>
		</template>

		<template #footer>
			<VueButton
				@click="addVacancy"
				v-if="modalVacancy.values.look == 'create'"
				:disabled="disabled.vacancies.create"
			>
				<VueIcon :name="'Add'" :fill="'white'" :width="'26px'" :height="'26px'" />
				Добавить
			</VueButton>

			<template v-else>
				<VueButton
					@click="updateTravel"
					v-if="!currentVacancy.data.delete.value"
					:disabled="disabled.vacancies.update"
				>
					<VueIcon :name="'Edit'" :fill="'white'" :width="'28px'" :height="'28px'" />
					Обновить
				</VueButton>

				<VueButton @click="deleteVacancy" v-if="currentVacancy.data.delete.value">
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
	<VueModal ref="modalVacancyRequirement" :settings="modalVacancyRequirement">
		<template #title>
			{{ modalVacancyRequirement.values.title }}
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
			<VueButton
				:disabled="disabled.vacancies.save"
				:look="'inverse'"
				@click="saveVacanciesChanges"
			>
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
			<template v-if="loading.sections.vacancies">
				<VueTable
					:table="table"
					@create="openVacancyCreate"
					@edite="openVacancyEdite"
					@delete="setVacancyDelete"
				>
					<template v-slot:hide="{ row }">
						<div class="table__hide" v-if="row.hide" @click="setVacancyHide(row)">Да</div>
						<div class="table__hide" v-else @click="setVacancyHide(row)">Нет</div>
					</template>
				</VueTable>

				<!-- <div class="evacancies" v-if="vacancies.length">
					<div
						class="evacancies__item"
						v-for="vacancy in vacancies"
						:class="{
							'evacancies__item--hide': vacancy.hide,
							'evacancies__item--create': vacancy.create,
							'evacancies__item--delete': vacancy.delete,
						}"
						@click="openVacancyEdite(vacancy)"
					>
						<div class="evacancies__item-header">
							<div class="evacancies__header-item">id: {{ vacancy.id }}</div>
						</div>
						<div class="evacancies__item-body">
							<div class="evacancies__body-title">{{ vacancy.title }}</div>
							<div class="evacancies__body-address">
								Адрес: {{ vacancy.address || "Не указан" }}
							</div>
							<div class="evacancies__body-schedule">График: {{ vacancy.schedule }}</div>
							<div class="evacancies__body-requirements">
								<div class="evacancies__requirements-title">Требования:</div>
								<div class="evacancies__requirements-body">
									{{ vacancy.requirements }}
								</div>
							</div>
							<div class="evacancies__body-conditions">
								<div class="evacancies__conditions-title">Условия:</div>
								<div class="evacancies__conditions-body">
									{{ vacancy.conditions }}
								</div>
							</div>
							<div class="evacancies__body-salary">
								<VueIcon
									:name="'Payments'"
									:fill="'var(--primary-color)'"
									:width="'26px'"
									:height="'26px'"
								/>
								{{ vacancy.salary }}
							</div>
						</div>
					</div>
				</div> -->

				<!-- Элемент пустой страницы -->
				<!-- <empty :minHeight="300" v-if="vacancies.length == 0" /> -->
			</template>

			<!-- Загрузка слайдов -->
			<VueLoader
				:isLoading="loading.loader.vacancies"
				:isChild="true"
				:minHeight="300"
				@afterLeave="loaderChildAfterLeave"
			/>
		</template>

		<!-- <template #buttons>
			<VueButton @click="openVacancyCreate" :disabled="disabled.vacancies.create">
				<VueIcon :name="'Add'" :fill="'white'" :width="'26px'" :height="'26px'" />
				Добавить
			</VueButton>
		</template> -->
	</block-once>
</template>

<script>
import VueModal from "../../../components/modules/modal/VueModal.vue";
import VueModalList from "../../../components/modules/modal/VueModalList.vue";
import VueTable from "../../../components/modules/table/VueTable.vue";
import VueInput from "../../../components/modules/input/VueInput.vue";
import VueTiptap from "../../../components/modules/VueTiptap.vue";
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
import { rowIsHeader } from "@tiptap/pm/tables";
import sorted from "../../../services/sorted";

export default {
	components: {
		VueModal,
		VueModalList,
		VueTable,
		VueInput,
		VueTiptap,
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
				vacancies: {
					save: false,
					create: false,
					update: false,
					download: false,
				},
			},

			/* Загрузчик */
			loading: {
				loader: {
					vacancies: true,
				},
				sections: {
					vacancies: false,
				},
			},

			/* Модальное окно */
			modalVacancy: {
				thin: false,
				clamped: false,
				values: {
					title: "",
					look: "default",
				},
			},

			modalVacancyRequirement: {
				thin: true,
				clamped: false,
				values: {
					title: "",
					look: "default",
				},
			},

			/* Форма */
			currentVacancy: {
				errors: {
					title: {
						message: "",
						status: false,
					},
					address: {
						message: "",
						status: false,
					},
					schedule: {
						message: "",
						status: false,
					},
					requirements: {
						message: "",
						status: false,
					},
					conditions: {
						message: "",
						status: false,
					},
					description: {
						message: "",
						status: false,
					},
					salary: {
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
					address: {
						value: "",
						edited: false,
					},
					schedule: {
						value: "",
						edited: false,
					},
					requirements: {
						value: "",
						edited: false,
					},
					conditions: {
						value: "",
						edited: false,
					},
					description: {
						value: "",
						edited: false,
					},
					salary: {
						value: "",
						edited: false,
					},
					hide: {
						value: false,
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
						name: "title",
						title: "Заголовок",
						type: "string",
						options: {
							column: {
								style: {
									width: "300px",
								},
							},
						},
					},
					{
						name: "address",
						title: "Адрес",
						type: "string",
						options: {
							column: {
								style: {
									width: "100px",
								},
							},
						},
					},
					{
						name: "schedule",
						title: "График",
						type: "string",
						options: {
							column: {
								style: {
									width: "100px",
								},
							},
						},
					},
					{
						name: "salary",
						title: "Зарплата",
						type: "number",
						options: {
							column: {
								style: {
									width: "300px",
								},
							},
						},
					},
					{
						name: "hide",
						title: "Скрытие",
						type: "slot",
						options: {
							column: {
								style: {
									width: "100px",
								},
							},
							data: {
								style: {
									justifyContent: "center",
								},
							},
						},
					},
				],

				// Элементы
				body: [],
			},

			/* Данные */
			vacancies: [
				{
					id: 1,
					title: "АКУШЕР",
					address: "г. Москва, ул. Пушкина, д. 10",
					schedule: "Сменный",
					requirements: "",
					conditions: "",
					description: "",
					salary: 50_000,
					hide: false,
					create: false,
					delete: false,
				},
			],
		};
	},
	methods: {
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                   Загрузчик                       |*/
		/* |___________________________________________________|*/
		/* После скрытия элементы */
		loaderChildAfterLeave() {
			this.loading.sections.vacancies = true;
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
		openVacancyCreate() {
			shared.clearObjectFull(this.currentVacancy);

			this.openModal("modalVacancy", "ВАКАНСИЯ", "create");
		},

		openVacancyEdite(value) {
			shared.clearObjectFull(this.currentVacancy);
			shared.setData(value, this.currentVacancy);

			this.openModal("modalVacancy", "ВАКАНСИЯ", "default");
		},

		/* Вакансия -> Требования */
		openTravelServiceCreate() {
			shared.clearObjectFull(this.currentTravelService);

			this.openModal("modalTravelServices", "УСЛУГА", "create");
		},

		openTravelServiceEdite(value) {
			shared.clearObjectFull(this.currentTravelService);
			shared.setData(value, this.currentTravelService);

			this.openModal("modalTravelServices", "УСЛУГА", "default");
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                    Вакансии                       |*/
		/* |___________________________________________________|*/
		/* Добавление путевки */
		addVacancy() {
			try {
				if (
					validate.checkInputsAll(this.currentVacancy, [
						{
							key: "title",
							type: "text",
						},
						{
							key: "address",
							type: "text",
						},
						{
							key: "schedule",
							type: "text",
						},
						{
							key: "requirements",
							type: "text",
						},
						{
							key: "conditions",
							type: "text",
						},
						{
							key: "description",
							type: "text",
						},
						{
							key: "salary",
							type: "number",
						},
					])
				)
					return;

				this.table.body.push({
					id: shared.getMaxId(this.vacancies) + 1,
					title: this.currentVacancy.data.title.value,
					address: this.currentVacancy.data.address.value,
					schedule: this.currentVacancy.data.schedule.value,
					requirements: this.currentVacancy.data.requirements.value,
					conditions: this.currentVacancy.data.conditions.value,
					description: this.currentVacancy.data.description.value,
					salary: this.currentVacancy.data.salary.value,
					hide: false,
					create: true,
					delete: false,
				});

				this.$refs.modalVacancy.close();
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
				if (
					validate.checkInputsAll(this.currentVacancy, [
						{
							key: "title",
							type: "text",
						},
						{
							key: "address",
							type: "text",
						},
						{
							key: "schedule",
							type: "text",
						},
						{
							key: "requirements",
							type: "text",
						},
						{
							key: "conditions",
							type: "text",
						},
						{
							key: "description",
							type: "text",
						},
						{
							key: "salary",
							type: "number",
						},
					])
				)
					return;

				let vacancy = this.table.body.find(
					(item) => item.id === this.currentVacancy.data.id.value
				);

				vacancy.title = this.currentVacancy.data.title.value;
				vacancy.address = this.currentVacancy.data.address.value;
				vacancy.schedule = this.currentVacancy.data.schedule.value;
				vacancy.requirements = this.currentVacancy.data.requirements.value;
				vacancy.conditions = this.currentVacancy.data.conditions.value;
				vacancy.description = this.currentVacancy.data.description.value;
				vacancy.salary = this.currentVacancy.data.salary.value;
				vacancy.hide = this.currentVacancy.data.hide.value;

				this.$refs.modalVacancy.close();
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка",
					body: error,
					type: "error",
				});
			}
		},

		/* Удаление */
		setVacancyDelete(vacancy) {
			vacancy.delete = !vacancy.delete;
		},

		/* Скрытие */
		setVacancyHide(vacancy) {
			vacancy.hide = !vacancy.hide;
		},

		/* Сохранение изменений в базе данных */
		saveVacanciesChanges() {
			this.disabled.vacancies.save = true;

			api({
				method: "post",
				url: this.$store.getters.urlApi + `save-vacancies-changes`,
				headers: {
					Accept: "application/json",
				},
				data: {
					vacancies: this.table.body,
				},
			})
				.then((response) => {
					if (!response) return;

					shared.updateId(this.table.body, response.data.result);
					shared.clearDeletes(this.table.body);
					shared.clearFlags(this.table.body);
					shared.updateOrders(this.table.body);
				})
				.catch((error) => {
					this.$store.commit("addDebugger", {
						title: "Ошибка",
						body: error,
						type: "error",
					});
				})
				.finally(() => {
					this.disabled.vacancies.save = false;
				});
		},
	},
	mounted() {
		this.loading.loader.vacancies = false;

		api({
			method: "get",
			url: this.$store.getters.urlApi + `get-vacancies-all`,
		})
			.then((response) => {
				if (!response) return;

				this.table.body = response.data.result;

				this.table.body.forEach((item) => {
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
				this.loading.loader.vacancies = false;
			});
	},
};
</script>

<style scoped>
/* Таблица */
.table__hide {
	user-select: none;
	cursor: pointer;
}

.table__hide:hover {
	color: var(--primary-color);
}
</style>
