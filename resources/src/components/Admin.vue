<template>
	<!-- Дебагер -->
	<debugger></debugger>

	<!-- Шапка страницы -->
	<AdminHeader />
	<!-- Боковая панель страницы -->
	<AdminAside />
	<!-- Основное содержимое страницы -->
	<AdminContent />
</template>

<script>
import { RouterLink, RouterView } from "vue-router";
import Debugger from "./includes/Debugger.vue";
import axios from "axios";
import AdminHeader from "./includes/admin/AdminHeader.vue";
import AdminAside from "./includes/admin/AdminAside.vue";
import AdminContent from "./includes/admin/AdminContent.vue";
import { compile } from "vue";

export default {
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
					return "bad";
				})
				.catch((error) => {
					this.$router.push("/login");
					localStorage.removeItem("token");
					return;
				});
		}
	},
	components: {
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
