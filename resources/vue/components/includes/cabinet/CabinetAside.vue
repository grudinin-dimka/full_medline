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
				:link="'/cabinet/profile'"
				:is-active="isActive('/cabinet/profile')"
				@click.prevent="insertPage('cabinet-profile')"
			>
				<VueIcon :name="'Account Circle'" :fill="'black'" :width="'28px'" :height="'28px'" />
				<span v-if="!$store.getters.getIsHide" class="aside__item-button-label">ПРОФИЛЬ</span>
			</VueAsideButton>

			<VueAsideButton
				:link="'/cabinet/analyzes'"
				:is-active="isActive('/cabinet/analyzes')"
				@click.prevent="insertPage('cabinet-analyzes')"
			>
				<VueIcon :name="'Clinical Notes'" :fill="'black'" :width="'28px'" :height="'28px'" />
				<span v-if="!$store.getters.getIsHide" class="aside__item-button-label">АНАЛИЗЫ</span>
			</VueAsideButton>

			<VueAsideButton
				:link="'/cabinet/appointments'"
				:is-active="isActive('/cabinet/appointments')"
				@click.prevent="insertPage('cabinet-appointments')"
			>
				<VueIcon :name="'Patient List'" :fill="'black'" :width="'28px'" :height="'28px'" />
				<span v-if="!$store.getters.getIsHide" class="aside__item-button-label">ПРИЕМЫ</span>
			</VueAsideButton>

			<VueAsideButton
				:link="'/cabinet/research'"
				:is-active="isActive('/cabinet/research')"
				@click.prevent="insertPage('cabinet-research')"
			>
				<VueIcon :name="'Lab Research'" :fill="'black'" :width="'28px'" :height="'28px'" />
				<span v-if="!$store.getters.getIsHide" class="aside__item-button-label">ИССЛЕДОВАНИЯ</span>
			</VueAsideButton>
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
import VueAsideButton from "../../ui/aside/VueAsideLink.vue";
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
