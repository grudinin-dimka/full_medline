<template>
	<VueAside :is-small="$store.getters.getAsideSmall" :is-hide="$store.getters.getAsideHide">
		<template #header>
			<div class="aside__logo">
				<div class="aside__logo-icon">
					<img :src="`/storage/img/logo.webp`" alt="Логотип" />
				</div>

				<div class="aside__logo-label" v-if="!$store.getters.getAsideSmall">
					<div class="aside__logo-label-main">МЕДЛАЙН</div>
					<div class="aside__logo-label-sub">ЛИЧНЫЙ КАБИНЕТ</div>
				</div>
			</div>
		</template>

		<template #body>
			<VueAsideButton
				:link="'/cabinet/appointments'"
				:is-active="isActive('/cabinet/appointments')"
				:is-small="$store.getters.getAsideSmall"
				@click.prevent="insertPage('cabinet-appointments')"
			>
				<VueIcon :name="'Patient List'" :fill="'black'" :width="'28px'" :height="'28px'" />
				<span :class="{ 'hide': $store.getters.getAsideSmall }" class="aside__item-button-label">ПРИЕМЫ</span>
			</VueAsideButton>

			<VueAsideButton
				:link="'/cabinet/analyzes'"
				:is-active="isActive('/cabinet/analyzes')"
				:is-small="$store.getters.getAsideSmall"
				@click.prevent="insertPage('cabinet-analyzes')"
			>
				<VueIcon :name="'Clinical Notes'" :fill="'black'" :width="'28px'" :height="'28px'" />
				<span :class="{ 'hide': $store.getters.getAsideSmall }" class="aside__item-button-label">АНАЛИЗЫ</span>
			</VueAsideButton>

			<VueAsideButton
				:link="'/cabinet/research'"
				:is-active="isActive('/cabinet/research')"
				:is-small="$store.getters.getAsideSmall"
				@click.prevent="insertPage('cabinet-research')"
			>
				<VueIcon :name="'Lab Research'" :fill="'black'" :width="'28px'" :height="'28px'" />
				<span :class="{ 'hide': $store.getters.getAsideSmall }" class="aside__item-button-label">ИССЛЕДОВАНИЯ</span>
			</VueAsideButton>
		</template>

		<template #footer>
			<VueAsideButton @click.prevent="$store.commit('logoutOpen')">
				<VueIcon :name="'Logout'" :fill="'black'" :width="'28px'" :height="'28px'" />
				<span :class="{ 'hide': $store.getters.getAsideSmall }" class="aside__item-button-label"> ВЫХОД </span>
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
		insertPage(page) {
			localStorage.setItem("page", page);

			this.$router.push({ name: `${page}` });

			// скролл к верху
			window.scrollTo({
				top: 0,
				left: 0,
				behavior: "instant",
			});

			this.$store.commit("closeAside");
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
	white-space: nowrap;
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

.hide {
	display: none;
}

@media screen and (width <= 850px) {
	.aside__logo {
		height: 60px;
	}

	.aside__logo-label {
		display: none;
	}

	.aside__logo-icon img {
		margin-left: 10px;

		height: 100px;
		width: 100px;
	}

	.hide {
		display: block;
	}
}
</style>
