<template>
	<VueModal ref="modalExit" :settings="modalExit">
		<template #title>{{ modalExit.values.title }}</template>
		<template #body>Вы уверены, что хотите выйти из системы?</template>
		<template #footer>
			<VueButton :wide="true" :look="'secondary'" @click="$refs.modalExit.close()"> Отменить </VueButton>

			<VueButton :wide="true" :disabled="$store.getters.getLogoutStatus" @click="$store.dispatch('logoutCabinet')">
				Выход
			</VueButton>
		</template>
	</VueModal>

	<!-- Дебагер -->
	<VueDebugger />
	<!-- Вики -->
	<VueWiki />

	<!-- Загрузка -->
	<VueLoader :isLoading="loader.loading" />

	<template v-if="!loader.loading">
		<section id="page">
			<CabinetHeader />

			<CabinetAside />

			<CabinetContent>
				<RouterView></RouterView>
			</CabinetContent>
		</section>
	</template>
</template>

<script>
import { RouterLink, RouterView } from "vue-router";

// NOTE: Модули в админке
import CabinetHeader from "../../components/includes/cabinet/CabinetHeader.vue";
import CabinetAside from "../../components/includes/cabinet/CabinetAside.vue";
import CabinetContent from "../../components/includes/cabinet/CabinetContent.vue";

import axios from "axios";

export default {
	components: {
		CabinetHeader,
		CabinetAside,
		CabinetContent,

		RouterLink,
		RouterView,

		axios,
	},
	data() {
		return {
			loader: {
				loading: true,
				other: false,
			},

			/* Модальные окна */
			modalExit: {
				thin: true,
				values: {
					title: "Выход",
					look: "default",
				},
				options: {
					head: true,
					body: true,
					footer: true,
				},
			},
		};
	},
	watch: {
		"$store.getters.getLogoutCount": {
			handler() {
				if (this.$store.getters.getLogoutCount) {
					this.$refs.modalExit.open();
				}
			},
			deep: true,
		},
	},
	methods: {
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                 Модальное окно                    |*/
		/* |___________________________________________________|*/
		/* Открытие модального окна */
		openModal(name, title, look) {
			this[name].values.title = title;
			this[name].values.look = look;

			this.$refs[name].open();
		},
	},
	beforeCreate() {
		// Проверка начилия токена в локальном хранилище
		if (!localStorage.getItem("ctoken")) {
			this.$router.push({ name: "c-login" });

			return;
		} else {
			// Проверка токена в базе
			axios({
				method: "post",
				url: `/api/cabinet/check`,
				headers: {
					Accept: "application/json",
					Authorization: `Bearer ${localStorage.getItem("ctoken")}`,
				},
			})
				.then((response) => {
					if (response.data.success) {
						this.$store.commit("updateClient", response.data.result);

						this.loader.loading = false;
					} else {
						localStorage.removeItem("ctoken");
						this.$router.push({ name: "c-login" });
					}
				})
				.catch((error) => {
					localStorage.removeItem("ctoken");
					this.$router.push({ name: "c-login" });
					return;
				});
		}
	},
	mounted() {
		this.$store.commit("clearDebugger");
		this.$store.commit("closeAside");
	},
};
</script>

<style scoped>
#page {
	box-sizing: border-box;
	padding: 0px 0px 0px 0px;

	display: grid;
	grid-template-columns: auto 1fr;
	grid-template-rows: auto 1fr auto;
	grid-template-areas:
		"aside header"
		"aside content"
		"aside content";
	gap: 0px;

	min-height: 100dvh;
}
</style>
