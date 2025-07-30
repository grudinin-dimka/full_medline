<template>
	<RouterView />
</template>

<script>
import { RouterView } from "vue-router";

export default {
	components: {
		RouterView,
	},
	beforeCreate() {
		if (this.$store.getters.userRights !== "creator" && this.$store.getters.userRights !== "admin") {
			this.$router.push("/404");
		}
	},
};
</script>

<style scoped></style>
