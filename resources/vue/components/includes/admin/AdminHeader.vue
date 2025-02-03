<template>
	<header>
		<img src="../../../assets/svg/logo.svg" alt="Медлайн" />
		<div class="user">
			<p>{{ $store.state.user.nickname }}</p>
		</div>
	</header>
	<!-- Кнопка "бургер" -->
	<div class="burger" @click="openBurger" :class="{ active: $store.state.burger.status }">
		<div></div>
		<div></div>
		<div></div>
	</div>
</template>

<script>
import Logo from "../../icons/logo.vue";

export default {
	components: {
		Logo,
	},
	methods: {
		openBurger() {
			if (this.$store.state.burger.status) {
				this.$store.state.burger.status = false;
			} else {
				this.$store.state.burger.status = true;
			}
		},
	},
};
</script>

<style scoped>
header {
	position: fixed;
	z-index: 200;

	top: 0px;
	left: 0px;
	right: 0px;
	display: flex;
	justify-content: space-between;
	align-items: center;

	padding: 0px 30px;

	height: 80px;
	background-color: white;
	box-shadow: 0px 0px 20px 0px rgba(0, 0, 0, 0.1);
}

header img {
	margin-left: 30px;
	width: 80px;
}

.user p {
	font-size: 20px;
}

.burger {
	cursor: pointer;
	display: none;
	position: fixed;
	z-index: 100;

	top: 30px;
	right: 30px;
	height: 30px;
}

.burger div:nth-child(1),
.burger div:nth-child(2),
.burger div:nth-child(3) {
	position: relative;

	width: 30px;
	height: 3px;
	background-color: #00abbd;

	transition: all 0.5s;
}

.burger div:nth-child(2) {
	top: 7px;
}

.burger div:nth-child(3) {
	top: 15px;
}

.burger.active div:nth-child(1) {
	top: 10px;
	position: absolute;
	transform: rotate(-45deg);
	background-color: white;
}

.burger.active div:nth-child(2) {
	top: 10px;
	transform: rotate(45deg);
	background-color: white;
}

.burger.active div:nth-child(3) {
	top: 10px;
	opacity: 0;
	transform: rotate(45deg);
	background-color: white;
}

@media screen and (max-width: 860px) {
	.user p {
		font-size: 20px;
		margin-right: 50px;
	}

	.burger {
		cursor: pointer;
		display: block;
		position: fixed;
		z-index: 500;

		top: 30px;
		right: 30px;
		height: 30px;
	}
}

@media screen and (max-width: 600px) {
	header > img {
		display: none;
	}
}
</style>
