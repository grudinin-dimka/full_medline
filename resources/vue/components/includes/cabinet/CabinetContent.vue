<template>
	<VueContent>
		<slot></slot>
	</VueContent>
</template>

<script>
import VueContent from "../../ui/VueContent.vue";

export default {
	components: { VueContent },
};
</script>

<style scoped></style>
