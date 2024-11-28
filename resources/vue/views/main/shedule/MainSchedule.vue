<template>
	<router-view></router-view>
</template>

<script>
import InfoBar from "../../../components/ui/main/InfoBar.vue";
import LoaderChild from "../../../components/includes/LoaderChild.vue";
import Block from "../../../components/ui/main/blocks/Block.vue";

import { RouterLink } from "vue-router";

export default {
	components: {
		InfoBar,
		LoaderChild,
		Block,
		RouterLink,
	},
	data() {
		return {
			isLoading: true,
		};
	},
};
</script>

<style scoped>
.shedule {
	width: 1250px;
}
</style>
