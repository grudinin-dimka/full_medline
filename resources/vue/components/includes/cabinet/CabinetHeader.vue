<template>
	<!-- Модальное окно: Информация -->
	<VueModal ref="modalInfo" :settings="modalInfo">
		<template #title>
			{{ modalInfo.values.title }}
		</template>

		<template #body>
			<table class="info">
				<tbody>
					<tr class="info__tr">
						<td class="info__td">Организация:</td>
						<td class="info__td">ООО «Медицинская линия» (ООО «МедЛайн»)</td>
					</tr>

					<tr class="info__tr">
						<td class="info__td">ИНН:</td>
						<td class="info__td">4502019933</td>
					</tr>

					<tr class="info__tr">
						<td class="info__td">ОГРН:</td>
						<td class="info__td">1074502000990</td>
					</tr>

					<tr class="info__tr">
						<td class="info__td">Почтовый адрес:</td>
						<td class="info__td">
							641870, Курганская область, г. Шадринск, ул. Комсомольская, д. 16, корп. 2, оф. 311.
						</td>
					</tr>

					<tr class="info__tr">
						<td class="info__td">Телефон:</td>
						<td class="info__td">8 (35253) 9-000-9</td>
					</tr>

					<tr class="info__tr">
						<td class="info__td">Лицензия:</td>
						<td class="info__td">
							<a
								class="link"
								target="_blank"
								href="https://roszdravnadzor.gov.ru/services/licenses?qrguid=ce6bc584aebd5ecea4499dc22ccb1dd8 "
							>
								Лицензия № Л041-01107-72/00572725 от 11.09.2020 г., выдана Департаментом здравоохранения Курганской
								области
							</a>
						</td>
					</tr>
				</tbody>
			</table>
		</template>
	</VueModal>

	<!-- Модальное окно: Сессии -->
	<VueModal ref="modalDevices" :settings="modalDevices">
		<template #title>
			{{ modalDevices.values.title }}
		</template>

		<template #body>
			<div class="devices">
				<div class="devices__item" v-for="device in devices">
					<div class="devices__item-info">
						<div class="devices__item-platform">{{ device.platform }}</div>
						<div class="devices__item-location">
							{{ device.last_used_at }} - {{ device.location }} - {{ device.ip }}
						</div>
					</div>
					<div class="devices__item-info">
						<VueButton :look="'delete'">Выйти</VueButton>
					</div>
				</div>
			</div>
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

export default {
	components: {
		VueHeader,
		VueBurger,
	},
	data() {
		return {
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
		/* |                 МЕТОДЫ ПЕРЕХОДА                   |*/
		/* |___________________________________________________|*/
		pushPage(page) {
			this.$router.push({ name: `${page}` });
			this.$refs.header.close();
		},

		logout() {
			this.$store.commit("logoutOpen");
			this.$refs.header.close();
		},
	},
};
</script>

<style scoped>
.info__tr {
	font-size: 1.125rem;
}

.info__td:first-of-type {
	font-size: large;
	color: rgba(0, 0, 0, 0.5);

	width: 175px;
}

.info__td {
	height: 30px;
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
	gap: calc(var(--default-gap) / 2);
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
