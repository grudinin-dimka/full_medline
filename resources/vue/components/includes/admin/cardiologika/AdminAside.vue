<template>
	<VueAside :is-small="$store.getters.getAsideSmall">
		<template #header>
			<div class="aside__logo">
				<div class="aside__logo-icon">
					<img :src="`/storage/img/logo.webp`" alt="Логотип" />
				</div>

				<div class="aside__logo-label" v-if="!$store.getters.getAsideSmall">
					<div class="aside__logo-label-main">КАРДИОЛОГИКА</div>
					<div class="aside__logo-label-sub">АДМИН</div>
				</div>
			</div>
		</template>

		<template #body>
			<VueAsideButton
				:link="'/admin/profile'"
				:is-active="isActive('/admin/profile')"
				:is-small="$store.getters.getAsideSmall"
				@click.prevent="insertPage('profile')"
			>
				<VueIcon :name="'Account Circle'" :fill="'black'" :width="'28px'" :height="'28px'" />
				<span v-if="!$store.getters.getAsideSmall" class="aside__item-button-label">ПРОФИЛЬ</span>
			</VueAsideButton>

			<VueAsideButton
				:link="'/admin/ehome'"
				:is-active="isActive('/admin/ehome')"
				:is-small="$store.getters.getAsideSmall"
				@click.prevent="insertPage('ehome')"
			>
				<VueIcon :name="'Home'" :fill="'black'" :width="'28px'" :height="'28px'" />
				<span v-if="!$store.getters.getAsideSmall" class="aside__item-button-label">ГЛАВНАЯ</span>
			</VueAsideButton>

			<VueAsideList
				:alias="['especialists-all', 'especialists-specializations', 'especialists-clinics']"
				:is-small="$store.getters.getAsideSmall"
			>
				<template #title>
					<VueIcon :name="'Ecg Heart'" :fill="'black'" :width="'28px'" :height="'28px'" />
					<span v-if="!$store.getters.getAsideSmall" class="aside__item-button-label"> СПЕЦИАЛИСТЫ </span>
				</template>

				<template #content>
					<VueAsideButton
						:link="'/admin/especialists'"
						:is-active="isActive('/admin/especialists')"
						:is-child="true"
						:is-small="$store.getters.getAsideSmall"
						@click.prevent="insertPage('especialists-all')"
					>
						<VueIcon :name="'List'" :fill="'black'" :width="'28px'" :height="'28px'" />
						<span v-if="!$store.getters.getAsideSmall" class="aside__item-button-label"> СПИСОК </span>
					</VueAsideButton>

					<VueAsideButton
						:link="'/admin/especialists/specializations'"
						:is-active="isActive('/admin/especialists/specializations')"
						:is-child="true"
						:is-small="$store.getters.getAsideSmall"
						@click.prevent="insertPage('especialists-specializations')"
					>
						<VueIcon :name="'Category'" :fill="'black'" :width="'28px'" :height="'28px'" />
						<span v-if="!$store.getters.getAsideSmall" class="aside__item-button-label"> СПЕЦИАЛИЗАЦИИ </span>
					</VueAsideButton>

					<VueAsideButton
						:link="'/admin/especialists/clinics'"
						:is-active="isActive('/admin/especialists/clinics')"
						:is-child="true"
						:is-small="$store.getters.getAsideSmall"
						@click.prevent="insertPage('especialists-clinics')"
					>
						<VueIcon :name="'Home'" :fill="'black'" :width="'28px'" :height="'28px'" />
						<span v-if="!$store.getters.getAsideSmall" class="aside__item-button-label"> КЛИНИКИ </span>
					</VueAsideButton>
				</template>
			</VueAsideList>

			<VueAsideList :alias="['eprices-all', 'eprices-template']" :is-small="$store.getters.getAsideSmall">
				<template #title>
					<VueIcon :name="'Payments'" :fill="'black'" :width="'28px'" :height="'28px'" />
					<span v-if="!$store.getters.getAsideSmall" class="aside__item-button-label"> ЦЕНЫ </span>
				</template>

				<template #content>
					<VueAsideButton
						:link="'/admin/eprices'"
						:is-active="isActive('/admin/eprices')"
						:is-child="true"
						:is-small="$store.getters.getAsideSmall"
						@click.prevent="insertPage('eprices-all')"
					>
						<VueIcon :name="'List'" :fill="'black'" :width="'28px'" :height="'28px'" />
						<span v-if="!$store.getters.getAsideSmall" class="aside__item-button-label"> СПИСОК </span>
					</VueAsideButton>

					<VueAsideButton
						:link="'/admin/eprices/rules'"
						:is-active="isActive('/admin/eprices/rules')"
						:is-child="true"
						:is-small="$store.getters.getAsideSmall"
						@click.prevent="insertPage('eprices-template')"
					>
						<VueIcon :name="'Article'" :fill="'black'" :width="'28px'" :height="'28px'" />
						<span v-if="!$store.getters.getAsideSmall" class="aside__item-button-label"> ШАБЛОН </span>
					</VueAsideButton>
				</template>
			</VueAsideList>

			<VueAsideButton
				:link="'/admin/evacancies'"
				:is-active="isActive('/admin/evacancies')"
				:is-small="$store.getters.getAsideSmall"
				@click.prevent="insertPage('evacancies')"
			>
				<VueIcon :name="'Badge'" :fill="'black'" :width="'28px'" :height="'28px'" />
				<span v-if="!$store.getters.getAsideSmall" class="aside__item-button-label">ВАКАНСИИ</span>
			</VueAsideButton>

			<VueAsideButton
				:link="'/admin/eschedule'"
				:is-active="isActive('/admin/eschedule')"
				:is-small="$store.getters.getAsideSmall"
				@click.prevent="insertPage('eschedule')"
			>
				<VueIcon :name="'Calendar Month'" :fill="'black'" :width="'28px'" :height="'28px'" />
				<span v-if="!$store.getters.getAsideSmall" class="aside__item-button-label">РАСПИСАНИЕ</span>
			</VueAsideButton>

			<VueAsideList
				:alias="['enews-all', 'evideo-all', 'econtacts-all', 'eabout-all']"
				:is-small="$store.getters.getAsideSmall"
			>
				<template #title>
					<VueIcon :name="'Info'" :fill="'black'" :width="'28px'" :height="'28px'" />
					<span v-if="!$store.getters.getAsideSmall" class="aside__item-button-label"> ИНФОРМАЦИЯ </span>
				</template>

				<template #content>
					<VueAsideButton
						:link="'/admin/enews'"
						:is-active="isActive('/admin/enews')"
						:is-child="true"
						:is-small="$store.getters.getAsideSmall"
						@click.prevent="insertPage('enews')"
					>
						<VueIcon :name="'News'" :fill="'black'" :width="'28px'" :height="'28px'" />
						<span v-if="!$store.getters.getAsideSmall" class="aside__item-button-label"> НОВОСТИ </span>
					</VueAsideButton>

					<VueAsideButton
						:link="'/admin/evideo'"
						:is-active="isActive('/admin/evideo')"
						:is-child="true"
						:is-small="$store.getters.getAsideSmall"
						@click.prevent="insertPage('evideo')"
					>
						<VueIcon :name="'Videocam'" :fill="'black'" :width="'28px'" :height="'28px'" />
						<span v-if="!$store.getters.getAsideSmall" class="aside__item-button-label"> ВИДЕО </span>
					</VueAsideButton>

					<VueAsideButton
						:link="'/admin/econtacts'"
						:is-active="isActive('/admin/econtacts')"
						:is-child="true"
						:is-small="$store.getters.getAsideSmall"
						@click.prevent="insertPage('econtacts-all')"
					>
						<VueIcon :name="'Groups'" :fill="'black'" :width="'28px'" :height="'28px'" />
						<span v-if="!$store.getters.getAsideSmall" class="aside__item-button-label"> КОНТАКТЫ </span>
					</VueAsideButton>

					<VueAsideButton
						:link="'/admin/eabout'"
						:is-active="isActive('/admin/eabout')"
						:is-child="true"
						:is-small="$store.getters.getAsideSmall"
						@click.prevent="insertPage('eabout-all')"
					>
						<VueIcon :name="'Info I'" :fill="'black'" :width="'28px'" :height="'28px'" />
						<span v-if="!$store.getters.getAsideSmall" class="aside__item-button-label"> О НАС </span>
					</VueAsideButton>
				</template>
			</VueAsideList>

			<VueAsideList :alias="['statistics-list', 'statistics-graph']" :is-small="$store.getters.getAsideSmall">
				<template #title>
					<VueIcon :name="'Monitoring'" :fill="'black'" :width="'28px'" :height="'28px'" />
					<span v-if="!$store.getters.getAsideSmall" class="aside__item-button-label"> СТАТИСТИКА </span>
				</template>

				<template #content>
					<VueAsideButton
						:link="'/admin/statistics/list'"
						:is-active="isActive('/admin/statistics/list')"
						:is-child="true"
						:is-small="$store.getters.getAsideSmall"
						@click.prevent="insertPage('statistics-list')"
					>
						<VueIcon :name="'List'" :fill="'black'" :width="'28px'" :height="'28px'" />
						<span v-if="!$store.getters.getAsideSmall" class="aside__item-button-label"> СПИСОК </span>
					</VueAsideButton>

					<VueAsideButton
						:link="'/admin/statistics/graphs'"
						:is-active="isActive('/admin/statistics/graphs')"
						:is-child="true"
						:is-small="$store.getters.getAsideSmall"
						@click.prevent="insertPage('statistics-graph')"
					>
						<VueIcon :name="'Monitoring'" :fill="'black'" :width="'28px'" :height="'28px'" />
						<span v-if="!$store.getters.getAsideSmall" class="aside__item-button-label"> ГРАФИКИ </span>
					</VueAsideButton>
				</template>
			</VueAsideList>

			<VueAsideList v-if="isCreator()" :alias="['eclients', 'users']" :is-small="$store.getters.getAsideSmall">
				<template #title>
					<VueIcon :name="'Admin Panel Settings'" :fill="'black'" :width="'28px'" :height="'28px'" />
					<span v-if="!$store.getters.getAsideSmall" class="aside__item-button-label"> СОЗДАТЕЛЬ </span>
				</template>

				<template #content>
					<VueAsideButton
						:link="'/admin/users'"
						:is-active="isActive('/admin/users')"
						:is-child="true"
						:is-small="$store.getters.getAsideSmall"
						@click.prevent="insertPage('users')"
					>
						<VueIcon :name="'Group'" :fill="'black'" :width="'28px'" :height="'28px'" />
						<span v-if="!$store.getters.getAsideSmall" class="aside__item-button-label">ПОЛЬЗОВАТЕЛИ</span>
					</VueAsideButton>
				</template>
			</VueAsideList>
		</template>

		<template #footer>
			<VueAsideButton @click.prevent="$store.commit('logoutOpen')">
				<VueIcon :name="'Logout'" :fill="'black'" :width="'28px'" :height="'28px'" />
				<span v-if="!$store.getters.getAsideSmall" class="aside__item-button-label"> ВЫХОД </span>
			</VueAsideButton>
		</template>
	</VueAside>
</template>

<script>
import VueAside from "../../../ui/aside/VueAside.vue";
import VueAsideButton from "../../../ui/aside/VueAsideLink.vue";
import VueAsideList from "../../../ui/aside/VueAsideList.vue";

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
