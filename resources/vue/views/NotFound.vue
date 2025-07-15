<template>
	<MainHeader />
	<MainNav />

	<div class="not-found">
		<div class="not-found-container">
			<div class="not-found-container-status">
				404
				<div class="not-found-container-title">Страница не найдена <code>;(</code></div>
			</div>

			<VueButton @click="$router.push({ name: 'home' })">Починить</VueButton>
		</div>
	</div>
</template>

<script>
import VueButton from "../components/ui/VueButton.vue";

// NOTE : Not Found
import MainNav from "../components/includes/main/medline/MainNav.vue";
import MainHeader from "../components/includes/main/medline/MainHeader.vue";

export default {
	components: {
		VueButton,

		MainNav,
		MainHeader,
	},
};
</script>

<style scoped>
.not-found {
	position: fixed;
	top: 50dvh;
	left: 50dvw;
	transform: translate(-50%, -50%);

	display: flex;
	justify-content: center;
	align-items: center;
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
