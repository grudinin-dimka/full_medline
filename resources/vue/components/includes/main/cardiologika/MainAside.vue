<template>
	<VueAside :is-small="$store.getters.getAsideSmall" :is-hide="$store.getters.getAsideHide">
		<template #header>
			<div class="aside__logo">
				<div class="aside__logo-icon">
					<img :src="`/storage/img/logo.webp`" alt="Логотип" />
				</div>
			</div>
		</template>

		<template #body>
			<VueAsideButton
				:link="'/specialists'"
				:is-active="isActive('/specialists')"
				:is-small="$store.getters.getAsideSmall"
				@click.prevent="insertPage('specialists-all')"
			>
				<VueIcon :name="'Ecg Heart'" :fill="'black'" :width="'28px'" :height="'28px'" />
				<span :class="{ 'hide': $store.getters.getAsideSmall }" class="aside__item-button-label">СПЕЦИАЛИСТЫ</span>
			</VueAsideButton>

			<VueAsideButton
				:link="'/prices'"
				:is-active="isActive('/prices')"
				:is-small="$store.getters.getAsideSmall"
				@click.prevent="insertPage('prices-all')"
			>
				<VueIcon :name="'Payments'" :fill="'black'" :width="'28px'" :height="'28px'" />
				<span :class="{ 'hide': $store.getters.getAsideSmall }" class="aside__item-button-label">ЦЕНЫ</span>
			</VueAsideButton>

			<VueAsideButton
				:link="'/vacancies'"
				:is-active="isActive('/vacancies')"
				:is-small="$store.getters.getAsideSmall"
				@click.prevent="insertPage('vacancies')"
			>
				<VueIcon :name="'Badge'" :fill="'black'" :width="'28px'" :height="'28px'" />
				<span :class="{ 'hide': $store.getters.getAsideSmall }" class="aside__item-button-label">ВАКАНСИИ</span>
			</VueAsideButton>

			<VueAsideButton
				:link="'/schedule'"
				:is-active="isActive('/schedule')"
				:is-small="$store.getters.getAsideSmall"
				@click.prevent="insertPage('schedule-all')"
			>
				<VueIcon :name="'Calendar Month'" :fill="'black'" :width="'28px'" :height="'28px'" />
				<span :class="{ 'hide': $store.getters.getAsideSmall }" class="aside__item-button-label">РАСПИСАНИЕ</span>
			</VueAsideButton>

			<VueAsideList :alias="['news-all', 'videos-all', 'contacts', 'about']" :is-small="$store.getters.getAsideSmall">
				<template #title>
					<VueIcon :name="'Info'" :fill="'black'" :width="'28px'" :height="'28px'" />
					<span v-if="!$store.getters.getAsideSmall" class="aside__item-button-label"> ИНФОРМАЦИЯ </span>
				</template>

				<template #content>
					<VueAsideButton
						:link="'/news'"
						:is-active="isActive('/news')"
						:is-child="true"
						:is-small="$store.getters.getAsideSmall"
						@click.prevent="insertPage('news-all')"
					>
						<VueIcon :name="'News'" :fill="'black'" :width="'28px'" :height="'28px'" />
						<span v-if="!$store.getters.getAsideSmall" class="aside__item-button-label"> НОВОСТИ </span>
					</VueAsideButton>

					<VueAsideButton
						:link="'/videos'"
						:is-active="isActive('/videos')"
						:is-child="true"
						:is-small="$store.getters.getAsideSmall"
						@click.prevent="insertPage('videos-all')"
					>
						<VueIcon :name="'Videocam'" :fill="'black'" :width="'28px'" :height="'28px'" />
						<span v-if="!$store.getters.getAsideSmall" class="aside__item-button-label"> ВИДЕО </span>
					</VueAsideButton>

					<VueAsideButton
						:link="'/contacts'"
						:is-active="isActive('/contacts')"
						:is-child="true"
						:is-small="$store.getters.getAsideSmall"
						@click.prevent="insertPage('contacts')"
					>
						<VueIcon :name="'Groups'" :fill="'black'" :width="'28px'" :height="'28px'" />
						<span v-if="!$store.getters.getAsideSmall" class="aside__item-button-label"> КОНТАКТЫ </span>
					</VueAsideButton>

					<VueAsideButton
						:link="'/about'"
						:is-active="isActive('/about')"
						:is-child="true"
						:is-small="$store.getters.getAsideSmall"
						@click.prevent="insertPage('about')"
					>
						<VueIcon :name="'Info I'" :fill="'black'" :width="'28px'" :height="'28px'" />
						<span v-if="!$store.getters.getAsideSmall" class="aside__item-button-label"> О НАС </span>
					</VueAsideButton>
				</template>
			</VueAsideList>
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

aside {
	display: none !important;
}

@media screen and (width < 850px) {
	aside {
		display: grid !important;
	}
}

@media screen and (width < 850px) {
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
