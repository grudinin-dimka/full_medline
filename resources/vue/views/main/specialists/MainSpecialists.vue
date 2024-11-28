<template>
   <router-view></router-view>   
</template>

<script>
import { RouterView, RouterLink } from "vue-router";
import InfoBar from "../../../components/ui/main/InfoBar.vue";

export default {
	components: {
      InfoBar,
      RouterView,
		RouterLink,
	},
};
</script>

<style scoped>

</style>
