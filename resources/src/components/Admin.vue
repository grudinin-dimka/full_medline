<template>
	<!-- Дебагер -->
	<debugger></debugger>

	<!-- Загрузка страницы -->
	<Loader :isLoading="loader.loading" />

	<!-- Шапка страницы -->
	<AdminHeader v-if="loader.other" />
	<!-- Боковая панель страницы -->
	<AdminAside v-if="loader.other" />
	<!-- Основное содержимое страницы -->
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
				url: `${this.$store.state.axios.urlApi}` + `check-token`,
				headers: {
					Accept: "application/json",
					Authorization: `Bearer ${localStorage.getItem("token")}`,
				},
			})
				.then((response) => {
					this.loader.loading = false;
					this.loader.other = true;
				})
				.catch((error) => {
					this.$router.push("/login");
					localStorage.removeItem("token");
					return;
				});
		}
	},
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
};
</script>

<style scoped></style>
