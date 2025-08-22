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

			<VueInput
				:placeholder="'Введите почту'"
				:type="'text'"
				:inputmode="'email'"
				v-model="currentVacancy.data.email.value"
				:error="currentVacancy.errors.email.status"
			>
				<template #label> ПОЧТА (ДЛЯ РЕЗЮМЕ) </template>
				<template #error>
					{{ currentVacancy.errors.email.message }}
				</template>
			</VueInput>

			<VueInput
				:placeholder="'Введите телефон'"
				:type="'text'"
				v-model="currentVacancy.data.phone.value"
				:error="currentVacancy.errors.phone.status"
			>
				<template #label> ТЕЛЕФОН (ДЛЯ ВОПРОСОВ) </template>
				<template #error>
					{{ currentVacancy.errors.phone.message }}
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
					:look="'delete'"
					@click="deleteVacancy"
					v-if="!currentVacancy.data.delete.value && !currentVacancy.data.create.value"
				>
					<VueIcon :name="'Delete'" :fill="'white'" :width="'28px'" :height="'28px'" />
					Удалить
				</VueButton>

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
				<div class="evacancies" v-if="vacancies.length">
					<div
						class="evacancies__item"
						v-for="vacancy in vacancies"
						:class="{
							'evacancies__item--hide': vacancy.hide,
							'evacancies__item--create': vacancy.create,
							'evacancies__item--delete': vacancy.delete,
						}"
						@click="openVacancyEdite(vacancy)"
					></div>
				</div>

				<!-- Элемент пустой страницы -->
				<empty :minHeight="300" v-if="vacancies.length == 0" />
			</template>

			<!-- Загрузка слайдов -->
			<VueLoader
				:isLoading="loading.loader.vacancies"
				:isChild="true"
				:minHeight="300"
				@afterLeave="loaderChildAfterLeave"
			/>
		</template>

		<template #buttons>
			<VueButton @click="openVacancyCreate" :disabled="disabled.vacancies.create">
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
					email: {
						message: "",
						status: false,
					},
					phone: {
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
					email: {
						value: "",
						edited: false,
					},
					phone: {
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

			/* Данные */
			vacancies: [],
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
							key: "email",
							type: "text",
						},
						{
							key: "phone",
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
							key: "salary",
							type: "number",
						},
					])
				)
					return;

				// this.vacancies.push({
				// 	id: shared.getMaxId(this.vacancies) + 1,
				// 	title: this.currentVacancy.data.title.value,
				// 	duration: this.currentVacancy.data.duration.value,
				// 	description: this.currentVacancy.data.description.value,
				// 	image: files.basename(response.data.result),
				// 	hit: this.currentVacancy.data.hit.value,
				// 	services: this.currentVacancy.data.services.value,
				// 	prices: this.currentVacancy.data.prices.value,
				// 	path: response.data.result,
				// 	order: shared.getMaxOrder(this.vacancies) + 1,
				// 	hide: false,
				// 	create: true,
				// 	delete: false,
				// });

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

				let travelCurrent = this.vacancies.find(
					(travel) => travel.order === this.currentVacancy.data.order.value
				);

				travelCurrent.path = response.data.result;
				travelCurrent.image = files.basename(response.data.result);

				travelCurrent.title = this.currentVacancy.data.title.value;
				travelCurrent.description = this.currentVacancy.data.description.value;
				travelCurrent.duration = this.currentVacancy.data.duration.value;
				travelCurrent.hit = this.currentVacancy.data.hit.value;
				travelCurrent.hide = this.currentVacancy.data.hide.value;
				travelCurrent.prices = this.currentVacancy.data.prices.value;
				travelCurrent.services = this.currentVacancy.data.services.value;

				this.$refs.modalVacancy.close();
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка",
					body: error,
					type: "error",
				});
			}
		},

		deleteVacancy() {
			let vacancy = this.vacancies.find(
				(item) => item.order === this.currentVacancy.data.id.value
			);

			vacancy.delete = !travel.vacancy;

			this.$refs.modalVacancy.close();
		},

		/* Сохранение изменений в базе данных */
		saveVacanciesChanges() {
			this.disabled.vacancies.save = true;

			// api({
			// 	method: "post",
			// 	url: this.$store.getters.urlApi + `save-travels-changes`,
			// 	headers: {
			// 		Accept: "application/json",
			// 	},
			// 	data: {
			// 		travels: this.vacancies,
			// 	},
			// })
			// 	.then((response) => {
			// 		if (!response) return;

			// 		shared.updateId(this.vacancies, response.data.result);
			// 		shared.clearDeletes(this.vacancies);
			// 		shared.clearFlags(this.vacancies);
			// 		shared.updateOrders(this.vacancies);
			// 	})
			// 	.catch((error) => {
			// 		this.$store.commit("addDebugger", {
			// 			title: "Ошибка",
			// 			body: error,
			// 			type: "error",
			// 		});
			// 	})
			// 	.finally(() => {
			// 		this.disabled.vacancies.save = false;
			// 	});
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
					this.currentVacancy.data.services.value.some(
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

				this.currentVacancy.data.services.value.push({
					id: shared.getMaxId(this.currentVacancy.data.services.value) + 1,
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
					this.currentVacancy.data.services.value.some((item) => {
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

				let service = this.currentVacancy.data.services.value.find(
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
				this.currentVacancy.data.services.value =
					this.currentVacancy.data.services.value.filter((item) => item !== value);
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
					this.currentVacancy.data.prices.value.some((item) => {
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

				this.currentVacancy.data.prices.value.push({
					id: shared.getMaxId(this.currentVacancy.data.prices.value) + 1,
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
					this.currentVacancy.data.prices.value.some((item) => {
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

				let price = this.currentVacancy.data.prices.value.find(
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
				this.currentVacancy.data.prices.value = this.currentVacancy.data.prices.value.filter(
					(item) => item !== value
				);
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
			url: this.$store.getters.urlApi + `get-travels-all`,
		})
			.then((response) => {
				if (!response) return;

				this.vacancies = response.data.result;

				this.vacancies.forEach((item) => {
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
.modal__info {
	display: flex;
	flex-direction: column;
	gap: var(--default-gap);
}

.modal__info-image {
	box-sizing: border-box;

	display: flex;
	justify-content: center;
	flex-wrap: wrap;
	border-radius: var(--default-border-radius);

	background-color: var(--item-background-color);
	padding: var(--default-padding);
	border: var(--default-border);
}

.modal__info-image > img {
	object-fit: cover;
	margin: auto;

	border: var(--default-border);
	border-radius: calc(var(--default-border-radius) / 1.5);

	width: 400px;
	background-color: white;
	aspect-ratio: 1 / 1.2;
}

.modal__info-form {
	display: flex;
	flex-direction: column;
	gap: calc(var(--default-gap) / 2);

	width: 100%;
}

.evacancies {
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
	border: var(--default-border-active);
	background-color: var(--item-background-color-active);
}

.etravels__header-item {
	transition: all 0.2s ease-in-out;
}

.etravels__item:hover > .etravels__item-header > .etravels__header-item {
	border: var(--default-border-active);
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
	.evacancies {
		grid-template-columns: 1fr;
	}
}

@media screen and (width <= 600px) {
	.etravels__item-other {
		flex-direction: column;
	}
}
</style>
