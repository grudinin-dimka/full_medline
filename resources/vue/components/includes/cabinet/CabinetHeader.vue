<template>
	<!-- Модальное окно: Информация -->
	<VueModal ref="modalInfo" :settings="modalInfo">
		<template #title>
			{{ modalInfo.values.title }}
		</template>

		<template #body>
			<div class="table">
				<div class="table__thead">
					<div class="table__thead-img">
						<div class="table__thead-img-count" v-if="count > 0">
							{{ count }}
						</div>

						<img class="table__thead-img-image" @click="count++" :src="`/storage/img/logo.svg`" alt="" />
					</div>
				</div>
				<div class="table__tbody">
					<div class="table__tbody-tr">
						<div class="table__tbody-td">Версия:</div>
						<div class="table__tbody-td">1.0</div>
					</div>

					<div class="table__tbody-tr">
						<div class="table__tbody-td">Организация:</div>
						<div class="table__tbody-td">ООО «Медицинская линия» (ООО «МедЛайн»)</div>
					</div>

					<div class="table__tbody-tr">
						<div class="table__tbody-td">ИНН:</div>
						<div class="table__tbody-td">4502019933</div>
					</div>

					<div class="table__tbody-tr">
						<div class="table__tbody-td">ОГРН:</div>
						<div class="table__tbody-td">1074502000990</div>
					</div>

					<div class="table__tbody-tr">
						<div class="table__tbody-td">Почтовый адрес:</div>
						<div class="table__tbody-td">
							641870, Курганская область, г. Шадринск, ул. Комсомольская, д. 16, корп. 2, оф. 311.
						</div>
					</div>

					<div class="table__tbody-tr">
						<div class="table__tbody-td">Телефон:</div>
						<div class="table__tbody-td">8 (35253) 9-000-9</div>
					</div>

					<div class="table__tbody-tr">
						<div class="table__tbody-td">Лицензия:</div>
						<div class="table__tbody-td">
							<a
								class="link"
								target="_blank"
								href="https://roszdravnadzor.gov.ru/services/licenses?qrguid=ce6bc584aebd5ecea4499dc22ccb1dd8 "
							>
								Лицензия № Л041-01107-72/00572725 от 11.09.2020 г., выдана Департаментом здравоохранения Курганской
								области
							</a>
						</div>
					</div>
				</div>
			</div>
		</template>
	</VueModal>

	<!-- Модальное окно: Сессии -->
	<VueModal ref="modalDevices" :settings="modalDevices">
		<template #title>
			{{ modalDevices.values.title }}
		</template>

		<template #body>
			<div class="devices">
				<div class="devices__item" v-for="device in $store.getters.getClientDevices">
					<div class="devices__item-info">
						<div class="devices__item-platform">{{ device.platform }}</div>
						<div class="devices__item-location">
							{{
								new Date(device.updated_at).toLocaleString("ru", {
									day: "2-digit",
									month: "short",
									year: "numeric",
									hour: "numeric",
									minute: "numeric",
									second: "numeric",
								})
							}}
							- {{ device.browser }} - {{ device.ip }}
						</div>
					</div>
					<div class="devices__item-info">
						<VueButton
							:look="'delete-secondary'"
							v-if="device.uuid !== $store.getters.getUuid"
							:disabled="disabled.delete"
							@click="deleteDevice(device.id)"
						>
							Выйти
						</VueButton>

						<template v-else>
							<VueButton :look="'create-secondary'">Текущий сеанс</VueButton>
						</template>
					</div>
				</div>
			</div>
		</template>

		<template #footer>
			<VueButton :look="'delete'" :disabled="disabled.reset" @click="deleteDevices">
				<VueIcon :name="'Delete Forever'" :fill="'white'" :width="'26px'" :height="'26px'" />
				Сбросить
			</VueButton>

			<VueButton :disabled="disabled.get" @click="getDevices">
				<VueIcon :name="'Refresh'" :fill="'white'" :width="'26px'" :height="'26px'" />
				Обновить
			</VueButton>
		</template>
	</VueModal>

	<VueHeader ref="header">
		<template #body>
			<div class="header__close" @click="$store.commit('toggleAsideSmall')">
				<VueIcon
					v-if="$store.getters.getAsideSmall"
					:name="'Arrow Menu Open'"
					:fill="'black'"
					:hover="'var(--primary-color)'"
					:width="'30px'"
					:height="'30px'"
				/>
				<VueIcon
					v-else
					:name="'Arrow Menu Close'"
					:fill="'black'"
					:hover="'var(--primary-color)'"
					:width="'30px'"
					:height="'30px'"
				/>
			</div>

			<div class="header__user" @click="$refs.header.open()">
				<span class="header__user-info">
					<span class="header__user-name">
						{{ $store.getters.getClientSurname + " " + $store.getters.getClientName }}
					</span>
					<span class="header__user-email"> {{ $store.getters.getClientSnils }} </span>
				</span>

				<div class="header__user-icon">
					<VueIcon :name="'Account Circle'" :fill="'black'" :width="'50px'" :height="'50px'" />
				</div>
			</div>

			<VueBurger :isActive="!$store.getters.getAsideHide" @click="$store.commit('toggleAsideHide')" />
		</template>

		<template #dropdown>
			<div class="header__dropdown__item" @click="openModalInfo">
				<VueIcon :name="'Info'" :fill="'var(--primary-color)'" :width="'24px'" :height="'24px'" />
				Информация
			</div>

			<div class="header__dropdown__item" @click="openModalDevices">
				<VueIcon :name="'Devices'" :fill="'var(--primary-color)'" :width="'24px'" :height="'24px'" />
				Устройства
			</div>

			<div class="header__dropdown__item" @click="makeDebug">
				<VueIcon :name="'Contract'" :fill="'var(--primary-color)'" :width="'24px'" :height="'24px'" />
				Соглашение
			</div>

			<div class="header__dropdown__item" @click="logout">
				<VueIcon :name="'Logout'" :fill="'var(--delete-background-color)'" :width="'24px'" :height="'24px'" />
				Выход
			</div>
		</template>
	</VueHeader>
</template>

<script>
import VueHeader from "../../ui/VueHeader.vue";
import VueBurger from "../../ui/VueBurger.vue";

import capi from "../../../mixin/capi";

export default {
	components: {
		VueHeader,
		VueBurger,
	},
	data() {
		return {
			count: 0,

			disabled: {
				get: false,
				reset: false,
				delete: false,
			},

			/* Модальное окно */
			modalInfo: {
				clamped: true,
				values: {
					title: "",
					look: "default",
				},
			},

			modalDevices: {
				clamped: true,
				values: {
					title: "",
					look: "default",
				},
			},

			devices: [
				{
					id: 1,
					platform: "Windows",
					location: "Россия, Москва",
					ip: "192.168.0.1",
					last_used_at: "22 янв. 2026 в 11:00",
				},
				{
					id: 2,
					platform: "Android",
					location: "Россия, Екаторинбург",
					ip: "192.168.0.1",
					last_used_at: "20 янв. 2026 в 16:31",
				},
			],
		};
	},
	watch: {
		count: {
			handler(newValue, oldValue) {
				if (newValue === 1 && oldValue === 0) {
					this.$store.commit("addDebugger", {
						title: "Ураааа!",
						body: "Вы нашли скрытую пасхалку :).",
						type: "completed",
					});
				}

				if (newValue === 100 && oldValue < 100) {
					this.$store.commit("addDebugger", {
						title: "Зачем?",
						body: "Эй, здесь больше ничего нет!",
						type: "completed",
					});
				}

				if (newValue === 1000 && oldValue < 1000) {
					this.$store.commit("addDebugger", {
						title: "Железная воля.",
						body: "Не бойся того, кто изучал 10 000 ударов, бойся того, кто изучал 1 удар 10 000 раз.",
						type: "completed",
					});
				}

				if (newValue === 10000 && oldValue < 10000) {
					this.$store.commit("addDebugger", {
						title: "Заслуженный анекдот!",
						body: `Врач — пациенту:
							— У меня для вас две новости: хорошая и плохая. С какой начать?
							— Давайте с хорошей, доктор.
							— Болезнь назовут в вашу честь!`,
						type: "completed",
					});
				}
			},
		},
	},
	methods: {
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                 МОДАЛЬНОЕ ОКНО                    |*/
		/* |___________________________________________________|*/
		/* Открытие модального окна */
		openModal(name, title, look) {
			this[name].values.title = title;
			this[name].values.look = look;

			this.$refs[name].open();
		},

		/* Открытие модального окна для добавления */
		openModalInfo() {
			this.$refs.header.close();

			this.openModal("modalInfo", "Информация", "default");
		},

		openModalDevices() {
			this.$refs.header.close();

			this.openModal("modalDevices", "Устройства", "default");
		},

		makeDebug() {
			window.location.href = "/storage/other/personal.docx";
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                    Интерфейс                      |*/
		/* |___________________________________________________|*/
		pushPage(page) {
			this.$router.push({ name: `${page}` });
			this.$refs.header.close();
		},

		logout() {
			this.$store.commit("logoutOpen");
			this.$refs.header.close();
		},

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                    Устройства                     |*/
		/* |___________________________________________________|*/
		getDevices() {
			this.disabled.get = true;

			capi({
				method: "get",
				url: `/devices`,
			})
				.then((response) => {
					if (!response) return;

					// Запись токена в глобальную переменную
					this.$store.commit("updateClientDevices", response.data.result);
				})
				.finally(() => {
					this.disabled.get = false;
				});
		},

		deleteDevices() {
			this.disabled.reset = true;

			capi({
				method: "delete",
				url: `/devices`,
			})
				.then((response) => {
					if (!response) return;

					// Сброс токена
					this.$store.commit("removeCabinetToken");
				})
				.finally(() => {
					this.disabled.reset = false;
				});
		},

		deleteDevice(id) {
			this.disabled.delete = true;

			capi({
				method: "delete",
				url: `/device`,
				data: {
					id: id,
				},
				headers: {
					ContentType: "application/json",
				},
			})
				.then((response) => {
					if (!response) return;

					let devices = this.$store.getters.getClientDevices;

					devices = devices.filter((device) => device.id !== id);

					// Запись токена в глобальную переменную
					this.$store.commit("updateClientDevices", devices);
				})
				.finally(() => {
					this.disabled.delete = false;
				});
		},
	},
};
</script>

<style scoped>
.table__thead {
	display: flex;
	justify-content: center;
	align-items: center;

	padding: var(--default-padding);
}

.table__thead-img {
	position: relative;
	display: flex;
	justify-content: center;
	align-items: center;

	border-radius: 100px;

	aspect-ratio: 1 / 1;
}

.table__thead-img:hover {
	background-color: rgba(var(--primary-color-rgb), 0.15);
}

.table__thead-img-image {
	cursor: pointer;
}

.table__thead-img-count {
	position: absolute;
	display: flex;
	justify-content: center;
	align-items: center;

	top: 0px;
	right: 0px;

	min-height: 30px;
	min-width: 30px;
	padding: 2.5px;
	border-radius: 100px;

	background-color: var(--primary-color);
	color: white;
}

.table__tbody-td:first-of-type {
	font-size: large;
	color: rgba(0, 0, 0, 0.5);

	width: 175px;
}

.table__tbody-tr {
	display: grid;
	grid-template-columns: 175px 1fr;
	grid-template-rows: auto;
}

.table__tbody-td {
	min-height: 30px;

	padding: max(calc(var(--default-padding) / 3), 12.5px);

	border-top: var(--default-border-width);
	border-right: 0px;
	border-bottom: 0px;
	border-left: var(--default-border-width);
	border-style: var(--default-border-style);
	border-color: var(--default-border-color);

	word-break: break-all;
}

.table__tbody-td:last-of-type {
	border-top: var(--default-border-width);
	border-right: var(--default-border-width);
	border-bottom: 0px;
	border-left: var(--default-border-width);
	border-style: var(--default-border-style);
	border-color: var(--default-border-color);
	border-radius: 0px;
}

.table__tbody-tr:first-of-type .table__tbody-td:first-child {
	border-radius: calc(var(--default-border-radius) / 1.5) 0px 0px 0px;
}

.table__tbody-tr:first-of-type .table__tbody-td:last-child {
	border-radius: 0px calc(var(--default-border-radius) / 1.5) 0px 0px;
}

.table__tbody-tr:last-of-type .table__tbody-td {
	border-top: var(--default-border-width);
	border-right: var(--default-border-width);
	border-bottom: var(--default-border-width);
	border-left: var(--default-border-width);
	border-style: var(--default-border-style);
	border-color: var(--default-border-color);
}

.table__tbody-tr:last-of-type .table__tbody-td {
	border-top: var(--default-border-width);
	border-right: var(--default-border-width);
	border-bottom: var(--default-border-width);
	border-left: var(--default-border-width);
	border-style: var(--default-border-style);
	border-color: var(--default-border-color);
}

.table__tbody-tr:last-of-type .table__tbody-td:first-child {
	border-top: var(--default-border-width);
	border-right: 0px;
	border-bottom: var(--default-border-width);
	border-left: var(--default-border-width);
	border-style: var(--default-border-style);
	border-color: var(--default-border-color);

	border-radius: 0px 0px 0px calc(var(--default-border-radius) / 1.5);
}

.table__tbody-tr:last-of-type .table__tbody-td:first-child {
	border-radius: 0px 0px 0px calc(var(--default-border-radius) / 1.5);
}

.table__tbody-tr:last-of-type .table__tbody-td:last-child {
	border-radius: 0px 0px calc(var(--default-border-radius) / 1.5) 0px;
}

/* Пользователь */
.header__user {
	display: flex;
	justify-content: center;
	align-items: center;
	gap: calc(var(--default-gap) / 2);
}

.header__user-name {
	font-size: 1.125rem;
}

.header__user-email {
	font-size: 1.125rem;
	color: rgba(0, 0, 0, 0.5);
}

.header__user-info {
	display: flex;
	flex-direction: column;
	justify-content: center;
	align-items: flex-end;
	gap: 5px;
}

.header__user-icon {
	cursor: pointer;

	border-radius: 200px;

	width: 50px;
	height: 50px;
}

.header__user-icon img {
	width: 100%;
	height: 100%;

	border-radius: 100px;
	object-fit: cover;
}

/* Открывающееся меню */
.header__dropdown__item {
	user-select: none;
	cursor: pointer;
	display: flex;
	justify-content: flex-start;
	align-items: center;
	gap: 10px;

	background-color: white;
	padding: 10px;
	border-radius: calc(var(--default-border-radius) / 1.5);

	font: var(--default-font-weight) 1.125rem var(--default-font-family);
}

.header__dropdown__item:hover {
	background-color: rgba(0, 0, 0, 0.05);
}

/* Устройства */
.devices {
	display: flex;
	flex-direction: column;
	gap: calc(var(--default-gap) / 2);
}

.devices__item {
	display: grid;
	grid-template-columns: 1fr auto;
	gap: calc(var(--default-gap) / 2);

	border: var(--default-border);
	border-radius: var(--default-border-radius);
	padding: calc(var(--default-padding) / 2);
}

.devices__item-info {
	display: flex;
	flex-direction: column;
	justify-content: center;
	gap: calc(var(--default-gap) / 2);

	font-size: 1.125rem;
}

.devices__item-seans {
	color: var(--create-secondary-color);
}

@media screen and (width < 850px) {
	.header__close {
		display: none;
	}

	.header__user-info {
		align-items: flex-start;
		order: 2;
	}

	.header__user-icon {
		order: 1;
	}
}
</style>
