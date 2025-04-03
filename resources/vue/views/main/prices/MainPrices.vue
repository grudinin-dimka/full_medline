<template>
	<router-view :key="$route.fullPath"></router-view>
</template>

<script>
import { RouterView } from "vue-router";

export default {
	components: {
		RouterView,
	},
};
</script>

<style></style>
