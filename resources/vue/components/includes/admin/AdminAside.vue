<template>
	<VueAside :is-hide="$store.getters.getIsHide">
		<template #header>
			<div class="aside__logo">
				<div class="aside__logo-icon">
					<img :src="`/storage/img/logo.webp`" alt="Логотип" />
				</div>

				<div class="aside__logo-label" v-if="!$store.getters.getIsHide">
					<div class="aside__logo-label-main">МЕДЛАЙН</div>
					<div class="aside__logo-label-sub">РЕДАКТИРОВАНИЕ</div>
				</div>
			</div>
		</template>

		<template #body>
			<VueAsideButton
				:link="'/admin/profile'"
				:is-active="isActive('/admin/profile')"
				@click.prevent="insertPage('profile')"
			>
				<VueIcon :name="'Account Circle'" :fill="'black'" :width="'28px'" :height="'28px'" />
				<span v-if="!$store.getters.getIsHide" class="aside__item-button-label">ПРОФИЛЬ</span>
			</VueAsideButton>

			<VueAsideButton :link="'/admin/ehome'" :is-active="isActive('/admin/ehome')" @click.prevent="insertPage('ehome')">
				<VueIcon :name="'Home'" :fill="'black'" :width="'28px'" :height="'28px'" />
				<span v-if="!$store.getters.getIsHide" class="aside__item-button-label">ГЛАВНАЯ</span>
			</VueAsideButton>

			<VueAsideList :alias="['especialists-all', 'especialists-specializations', 'especialists-clinics']">
				<template #title>
					<VueIcon :name="'Ecg Heart'" :fill="'black'" :width="'28px'" :height="'28px'" />
					<span v-if="!$store.getters.getIsHide" class="aside__item-button-label"> СПЕЦИАЛИСТЫ </span>
				</template>

				<template #content>
					<VueAsideButton
						:link="'/admin/especialists'"
						:is-active="isActive('/admin/especialists')"
						:is-child="true"
						@click.prevent="insertPage('especialists-all')"
					>
						<span v-if="!$store.getters.getIsHide" class="aside__item-button-label"> СПИСОК </span>
					</VueAsideButton>

					<VueAsideButton
						:link="'/admin/especialists/specializations'"
						:is-active="isActive('/admin/especialists/specializations')"
						:is-child="true"
						@click.prevent="insertPage('especialists-specializations')"
					>
						<span v-if="!$store.getters.getIsHide" class="aside__item-button-label"> СПЕЦИАЛИЗАЦИИ </span>
					</VueAsideButton>

					<VueAsideButton
						:link="'/admin/especialists/clinics'"
						:is-active="isActive('/admin/especialists/clinics')"
						:is-child="true"
						@click.prevent="insertPage('especialists-clinics')"
					>
						<span v-if="!$store.getters.getIsHide" class="aside__item-button-label"> КЛИНИКИ </span>
					</VueAsideButton>
				</template>
			</VueAsideList>

			<VueAsideList :alias="['eprices-all', 'eprices-template']">
				<template #title>
					<VueIcon :name="'Payments'" :fill="'black'" :width="'28px'" :height="'28px'" />
					<span v-if="!$store.getters.getIsHide" class="aside__item-button-label"> ЦЕНЫ </span>
				</template>

				<template #content>
					<VueAsideButton
						:link="'/admin/eprices'"
						:is-active="isActive('/admin/eprices')"
						:is-child="true"
						@click.prevent="insertPage('eprices-all')"
					>
						<span v-if="!$store.getters.getIsHide" class="aside__item-button-label"> СПИСОК </span>
					</VueAsideButton>

					<VueAsideButton
						:link="'/admin/eprices/rules'"
						:is-active="isActive('/admin/eprices/rules')"
						:is-child="true"
						@click.prevent="insertPage('eprices-template')"
					>
						<span v-if="!$store.getters.getIsHide" class="aside__item-button-label"> ШАБЛОН </span>
					</VueAsideButton>
				</template>
			</VueAsideList>

			<VueAsideButton
				:link="'/admin/evacancies'"
				:is-active="isActive('/admin/evacancies')"
				@click.prevent="insertPage('evacancies')"
			>
				<VueIcon :name="'Badge'" :fill="'black'" :width="'28px'" :height="'28px'" />
				<span v-if="!$store.getters.getIsHide" class="aside__item-button-label">ВАКАНСИИ</span>
			</VueAsideButton>

			<VueAsideButton
				:link="'/admin/eschedule'"
				:is-active="isActive('/admin/eschedule')"
				@click.prevent="insertPage('eschedule')"
			>
				<VueIcon :name="'Calendar Month'" :fill="'black'" :width="'28px'" :height="'28px'" />
				<span v-if="!$store.getters.getIsHide" class="aside__item-button-label">РАСПИСАНИЕ</span>
			</VueAsideButton>

			<VueAsideButton
				:link="'/admin/etravels'"
				:is-active="isActive('/admin/etravels')"
				@click.prevent="insertPage('etravels')"
			>
				<VueIcon :name="'Travel'" :fill="'black'" :width="'28px'" :height="'28px'" />
				<span v-if="!$store.getters.getIsHide" class="aside__item-button-label">ПУТЕВКИ</span>
			</VueAsideButton>

			<VueAsideList :alias="['enews', 'evideo', 'econtacts', 'eabout']">
				<template #title>
					<VueIcon :name="'Info'" :fill="'black'" :width="'28px'" :height="'28px'" />
					<span v-if="!$store.getters.getIsHide" class="aside__item-button-label"> ИНФОРМАЦИЯ </span>
				</template>

				<template #content>
					<VueAsideButton
						:link="'/admin/enews'"
						:is-active="isActive('/admin/enews')"
						:is-child="true"
						@click.prevent="insertPage('enews')"
					>
						<span v-if="!$store.getters.getIsHide" class="aside__item-button-label"> НОВОСТИ </span>
					</VueAsideButton>

					<VueAsideButton
						:link="'/admin/evideo'"
						:is-active="isActive('/admin/evideo')"
						:is-child="true"
						@click.prevent="insertPage('evideo')"
					>
						<span v-if="!$store.getters.getIsHide" class="aside__item-button-label"> ВИДЕО </span>
					</VueAsideButton>

					<VueAsideButton
						:link="'/admin/econtacts'"
						:is-active="isActive('/admin/econtacts')"
						:is-child="true"
						@click.prevent="insertPage('econtacts')"
					>
						<span v-if="!$store.getters.getIsHide" class="aside__item-button-label"> КОНТАКТЫ </span>
					</VueAsideButton>

					<VueAsideButton
						:link="'/admin/eabout'"
						:is-active="isActive('/admin/eabout')"
						:is-child="true"
						@click.prevent="insertPage('eabout')"
					>
						<span v-if="!$store.getters.getIsHide" class="aside__item-button-label"> О НАС </span>
					</VueAsideButton>
				</template>
			</VueAsideList>

			<VueAsideList v-if="isCreator" :alias="['eclients', 'users']">
				<template #title>
					<VueIcon :name="'Admin Panel Settings'" :fill="'black'" :width="'28px'" :height="'28px'" />
					<span v-if="!$store.getters.getIsHide" class="aside__item-button-label"> СОЗДАТЕЛЬ </span>
				</template>

				<template #content>
					<VueAsideButton
						:link="'/admin/eclients'"
						:is-active="isActive('/admin/eclients')"
						:is-child="true"
						@click.prevent="insertPage('eclients')"
					>
						<VueIcon :name="'Patient List'" :fill="'black'" :width="'28px'" :height="'28px'" />
						<span v-if="!$store.getters.getIsHide" class="aside__item-button-label">КЛИЕНТЫ</span>
					</VueAsideButton>

					<VueAsideButton
						:link="'/admin/users'"
						:is-active="isActive('/admin/users')"
						:is-child="true"
						@click.prevent="insertPage('users')"
					>
						<VueIcon :name="'Group'" :fill="'black'" :width="'28px'" :height="'28px'" />
						<span v-if="!$store.getters.getIsHide" class="aside__item-button-label">ПОЛЬЗОВАТЕЛИ</span>
					</VueAsideButton>
				</template>
			</VueAsideList>
		</template>

		<template #footer>
			<VueAsideButton @click.prevent="$store.commit('logoutOpen')">
				<VueIcon :name="'Logout'" :fill="'black'" :width="'28px'" :height="'28px'" />
				<span v-if="!$store.getters.getIsHide" class="aside__item-button-label"> ВЫХОД </span>
			</VueAsideButton>
		</template>
	</VueAside>
</template>

<script>
import VueAside from "../../ui/aside/VueAside.vue";
import VueAsideButton from "../../ui/aside/VueAsideButton.vue";
import VueAsideList from "../../ui/aside/VueAsideList.vue";

import axios from "axios";

export default {
	components: {
		VueAside,
		VueAsideButton,
		VueAsideList,
	},
	methods: {
		isCreator() {
			return this.$store.getters.getUserRights === "creator";
		},

		isAdmin() {
			return this.$store.getters.getUserRights === "admin";
		},

		insertPage(page) {
			localStorage.setItem("page", page);

			this.$router.push({ name: `${page}` });

			// скролл к верху
			window.scrollTo({
				top: 0,
				left: 0,
				behavior: "instant",
			});
		},

		isActive(route) {
			return this.$route.path === route;
		},
	},
};
</script>

<style scoped>
.aside__logo {
	display: flex;
	align-items: center;
	gap: calc(var(--default-gap) / 2);

	height: 80px;
}

.aside__logo-icon img {
	fill: var(--primary-color);

	height: 60px;
	width: 60px;
}

.aside__logo-label {
	font-size: 1.8rem;
	font-weight: bold;

	color: var(--primary-color);

	animation: show 0.2s;
}

.aside__logo-label-main {
	font-size: 1.8rem;
	font-weight: bold;
}

.aside__logo-label-sub {
	font-size: 1.4rem;
	font-weight: 400;
}
</style>
