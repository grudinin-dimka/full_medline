<template>
	<!-- Дебагер -->
	<VueDebugger />
	<!-- Вики -->
	<VueWiki />

	<!-- Загрузка -->
	<VueLoader :isLoading="loader.loading" />

	<template v-if="!loader.loading">
		<section id="page">
			<AdminHeader />

			<AdminAside />

			<AdminContent>
				<RouterView></RouterView>
			</AdminContent>
		</section>
	</template>
</template>

<script>
import { RouterLink, RouterView } from "vue-router";

// NOTE: Модули в админке
import AdminHeader from "../components/includes/admin/medline/AdminHeader.vue";
import AdminContent from "../components/includes/admin/AdminContent.vue";
import AdminAside from "../components/includes/admin/AdminAside.vue"

import axios from "axios";

export default {
	components: {
		AdminHeader,
		AdminAside,
		AdminContent,

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
		};
	},
	beforeCreate() {
		// Проверка начилия токена в локальном хранилище
		if (!localStorage.getItem("token")) {
			this.$router.push({ name: "not-found" });
			return;
		} else {
			// Проверка токена в базе
			axios({
				method: "post",
				url: `${this.$store.getters.urlApi}` + `check-user`,
				headers: {
					Accept: "application/json",
					Authorization: `Bearer ${localStorage.getItem("token")}`,
				},
			})
				.then((response) => {
					if (response.data.status) {
						this.$store.commit("updateUser", response.data.data);

						this.loader.loading = false;
						this.loader.other = true;
					} else {
						localStorage.removeItem("token");
						this.$router.push({ name: "not-found" });
					}
				})
				.catch((error) => {
					localStorage.removeItem("token");
					this.$router.push({ name: "not-found" });
					return;
				});
		}
	},
	mounted() {
		this.$store.commit("clearDebugger");
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
