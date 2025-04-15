<template>
	<debugger></debugger>
	<Loader :isLoading="loader.loading" />

	<AdminHeader v-if="loader.other" />
	<AdminAside v-if="loader.other" />
	<AdminContent v-if="loader.other" />
</template>

<script>
import { RouterLink, RouterView } from "vue-router";
import Debugger from "../components/modules/Debugger.vue";
import Loader from "../components/modules/loader.vue";
import axios from "axios";
import AdminHeader from "../components/includes/admin/AdminHeader.vue";
import AdminAside from "../components/includes/admin/AdminAside.vue";
import AdminContent from "../components/includes/admin/AdminContent.vue";
import { compile } from "vue";

export default {
	components: {
		Loader,
		Debugger,
		RouterLink,
		RouterView,
		axios,
		AdminHeader,
		AdminAside,
		AdminContent,
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
