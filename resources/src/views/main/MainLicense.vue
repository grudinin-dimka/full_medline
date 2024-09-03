<template>
	<info-bar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link to="/licenses">Лицензии</router-link>
	</info-bar>

	<loader-child :isLoading="isLoading" />
</template>

<script>
import InfoBar from "../../components/ui/main/InfoBar.vue";
import LoaderChild from "../../components/includes/LoaderChild.vue";

export default {
   components: {
      InfoBar,
		LoaderChild,
   },
	data() {
		return {
			isLoading: true,
		}
	}
}
</script>


<style scoped>

</style>