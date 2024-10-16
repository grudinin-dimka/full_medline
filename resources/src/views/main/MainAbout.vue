<template>
	<info-bar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link to="/about">О нас</router-link>
	</info-bar>

	<block>
		<loader-child :isLoading="isLoading" />
	</block>
</template>

<script>
import InfoBar from "../../components/ui/main/InfoBar.vue";
import LoaderChild from "../../components/includes/LoaderChild.vue";
import Block from "../../components/ui/main/blocks/Block.vue";

export default {
   components: {
      InfoBar,
		LoaderChild,
		Block,
   },
	data() {
		return {
			isLoading: true,
		}
	}
}
</script>


<style scoped></style>