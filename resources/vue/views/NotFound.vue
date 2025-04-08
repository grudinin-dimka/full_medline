<template>
	<div class="not-found">
		<div class="not-found-container">
			<div class="not-found-container-status">
				404
				<div class="not-found-container-title">Страница не найдена <code>;(</code></div>
			</div>
			<ButtonDefault @click="$router.push({ name: 'home' })">На главную</ButtonDefault>
		</div>
	</div>
</template>

<script>
import ButtonDefault from "../components/ui/admin/buttons/ButtonDefault.vue";

export default {
	components: {
		ButtonDefault,
	},
};
</script>

<style scoped>
.not-found {
	display: flex;
	justify-content: center;
	align-items: center;

	min-height: 100dvh;
}

.not-found-container {
	display: flex;
	justify-content: center;
	align-items: center;
	flex-direction: column;
	gap: 20px;

	text-align: center;
}

.not-found-container-status {
	font-size: 100px;
	color: var(--primary-color);
	margin: 0px;
}

.not-found-container-title {
	font-size: 20px;
	color: var(--primary-color);
}
</style>
