<template>
	<block-once :minHeight="100">
		<template #title>
			ПОЛЬЗОВАТЕЛИ
		</template>
		<template #body>
			ПОЛЬЗОВАТЕЛИ
		</template>
	</block-once>
</template>

<script>
import BlockOnce from "../../../components/ui/admin/blocks/BlockOnce.vue";

export default {
	components: {
		BlockOnce,
	},
};
</script>

<style scoped></style>
