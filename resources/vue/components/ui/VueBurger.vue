<template>
	<div class="burger" :class="{ 'burger--active': isActive }">
		<div class="burger-line"></div>
		<div class="burger-line"></div>
		<div class="burger-line"></div>
	</div>
</template>
<script>
export default {
	name: "VueBurger",
	props: {
		isActive: {
			type: Boolean,
			default: false,
		},
	},
};
</script>

<style scoped>
.burger {
	display: none;
	cursor: pointer;
	position: fixed;
	top: 27.5px;
	right: 27.5px;
	z-index: 200;

	flex-direction: column;
	gap: 5px;
}

.burger-line {
	width: 25px;
	height: 3px;
	background-color: black;

	transition: var(--default-transition);
}

.burger:hover .burger-line {
	background-color: var(--primary-color);
}

.burger--active .burger-line:nth-child(1) {
	transform: rotate(45deg) translate(6px, 5.5px);
}

.burger--active .burger-line:nth-child(2) {
	opacity: 0;
	transform: translateX(-5px);
}

.burger--active .burger-line:nth-child(3) {
	transform: rotate(-45deg) translate(6px, -5px);
}

@media screen and (width <= 850px) {
	.burger {
		display: flex;
	}
}
</style>
