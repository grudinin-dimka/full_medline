<template>
	<aside class="aside" :class="{ 'aside--hiden': isHide }">
		<div class="aside__header">
			<slot name="header"></slot>
		</div>

		<div class="aside__body">
			<slot name="body"></slot>
		</div>

		<div class="aside__footer">
			<slot name="footer"></slot>
		</div>
	</aside>
</template>

<script>
export default {
	props: {
		isHide: {
			type: Boolean,
			default: false,
		},
	},
};
</script>

<style scoped>
.aside {
	position: relative;
	grid-area: aside;

	box-sizing: border-box;
	display: grid;
	grid-template-rows: auto 1fr auto;
	gap: var(--aside-gap);

	padding: var(--aside-padding);
	margin: var(--aside-margin);
	border-top: var(--aside-border-top);
	border-right: var(--aside-border-right);
	border-bottom: var(--aside-border-bottom);
	border-left: var(--aside-border-left);
	border-style: var(--aside-border-style);
	border-color: var(--aside-border-color);

	height: 100%;
	max-height: 100dvh;

	width: var(--aside-width);
	background-color: var(--aside-background-color);
	color: var(--aside-font-color);

	transition: all 0.2s;
}

.aside--hiden {
	width: 110px;
}

.aside--hiden .aside__item {
	justify-content: center;

	padding: 0px;

	width: 60px;
	height: 60px;
}

/* Header */
.aside__header {
	display: flex;
	flex-direction: column;
	gap: var(--aside-header-gap);
}

/* Body */
.aside__body {
	display: flex;
	flex-direction: column;
	gap: var(--aside-body-gap);

	overflow: auto;
	max-height: 100%;
}

.aside__footer {
	display: flex;
	flex-direction: column;
	gap: var(--aside-footer-gap);
}
</style>
