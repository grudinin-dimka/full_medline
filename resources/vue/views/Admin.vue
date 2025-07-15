<template>
	<VueDebugger></VueDebugger>

	<VueLoader :isLoading="loader.loading" />

	<AdminHeader v-if="loader.other" />
	<AdminAside v-if="loader.other" />
	<AdminContent v-if="loader.other" />
</template>

<script>
import { RouterLink, RouterView } from "vue-router";
import VueDebugger from "../components/modules/VueDebugger.vue";
import VueLoader from "../components/modules/VueLoader.vue";

// NOTE: Модули в админке
import AdminHeader from "../components/includes/admin/medline/AdminHeader.vue";
import AdminAside from "../components/includes/admin/AdminAside.vue";
import AdminContent from "../components/includes/admin/AdminContent.vue";
import { compile } from "vue";

import axios from "axios";

export default {
	components: {
		VueLoader,
		VueDebugger,

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

<style scoped></style>
