<template>
	<!-- Модальное окно: Печать -->
	<VueModal ref="modalInfo" :settings="modalInfo">
		<template #title>
			{{ modalInfo.values.title }}
		</template>

		<template #body>
			<table class="info">
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
					<td class="info__td">641870, Курганская область, г. Шадринск, ул. Комсомольская, д. 16, корп. 2, оф. 311.</td>
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
			</table>
		</template>
	</VueModal>

	<VueHeader ref="header">
		<template #left>
			<div class="header__close" @click="$store.commit('setIsHide')">
				<VueIcon
					v-if="$store.getters.getIsHide"
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
		</template>

		<template #right>
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
		</template>

		<template #dropdown>
			<div class="header__dropdown__item" @click="openModalInfo">
				<VueIcon :name="'Info'" :fill="'var(--primary-color)'" :width="'24px'" :height="'24px'" />
				Информация
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

export default {
	components: { VueHeader },
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

		makeDebug() {
			this.$store.commit("addDebugger", {
				title: 'Соглашение',
				body: "Узнавайте в медицинских центрах.",
				type: "completed",
			});
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
	border-radius: 10px;

	font: var(--default-font-weight) 1.125rem var(--default-font-family);
}

.header__dropdown__item:hover {
	background-color: rgba(0, 0, 0, 0.05);
}
</style>
