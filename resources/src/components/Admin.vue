<template>
	<debugger></debugger>
	<Loader :isLoading="loader.loading" />

	<AdminHeader v-if="loader.other" />
	<AdminAside v-if="loader.other" />
	<AdminContent v-if="loader.other" />
</template>

<script>
import { RouterLink, RouterView } from "vue-router";
import Debugger from "./includes/Debugger.vue";
import Loader from "./includes/loader.vue";
import axios from "axios";
import AdminHeader from "./includes/admin/AdminHeader.vue";
import AdminAside from "./includes/admin/AdminAside.vue";
import AdminContent from "./includes/admin/AdminContent.vue";
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
			this.$router.push("/login");
			return;
		} else {
			// Проверка токена в базе
			axios({
				method: "post",
				url: `${this.$store.state.axios.urlApi}` + `check-user`,
				headers: {
					Accept: "application/json",
					Authorization: `Bearer ${localStorage.getItem("token")}`,
				},
			})
				.then((response) => {
					if (response.data.status) {
						this.$store.commit("setUser", response.data.data);

						this.loader.loading = false;
						this.loader.other = true;
					} else {
						localStorage.removeItem("token");
						this.$router.push({ name: "login" });
					}
				})
				.catch((error) => {
					localStorage.removeItem("token");
					this.$router.push({ name: "login" });
					return;
				});
		}
	},
	mounted() {
		this.$store.commit("setDebuggerClose");
	},
};
</script>

<style scoped></style>
