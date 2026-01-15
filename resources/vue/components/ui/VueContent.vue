<template>
	<main>
		<slot></slot>
	</main>
</template>

<style scoped>
main {
	display: flex;
	flex-direction: column;
	gap: var(--content-gap);
	grid-area: content;

	padding: var(--content-padding);

	overflow-y: auto;
	max-height: var(--content-max-height);

	display: flex;
	flex-direction: column;
	justify-content: flex-start;
	align-items: stretch;

	background-color: var(--content-background-color);
}

@media screen and (max-width: 450px) {
	main {
		padding: calc(var(--content-padding) / 2);
		gap: calc(var(--content-gap) / 2);
	}
}
</style>
