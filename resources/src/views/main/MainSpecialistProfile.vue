<template>
   <info-bar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link to="/specialists">Специалисты</router-link>
   </info-bar>

	<div>
      User {{ $route.params.id }}
   </div>
</template>

<script>
import InfoBar from "../../components/ui/main/InfoBar.vue";

export default {
	components: {
		InfoBar,
	},
};
</script>

<style scoped></style>
