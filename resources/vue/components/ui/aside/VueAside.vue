<template>
	<aside class="aside" :class="{ 'aside--small': isSmall, 'aside--hide': isHide }">
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
		isSmall: {
			type: Boolean,
			default: false,
		},
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

	height: var(--aside-height);
	max-height: var(--aside-max-height);

	width: var(--aside-width);
	background-color: var(--aside-background-color);
	color: var(--aside-font-color);

	box-shadow: var(--aside-box-shadow);

	transition: var(--aside-transition);
}

.aside--small {
	width: var(--aside-width--hiden);
}

.aside--small .aside__item {
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

@media screen and (width <= 850px) {
	.aside {
		position: fixed;
		z-index: 100;
		left: 0px;
		top: 0px;

		visibility: visible;
		opacity: 1;

		width: 100%;

		box-shadow: 0px 0px 10px 20px rgba(0, 0, 0, 0.15);
	}

	.aside--hide {
		left: 100%;
		visibility: hidden;
		opacity: 0;
	}
}
</style>
