<template>
	<header :class="{ 'header--hidden': false }" ref="header">
		<div class="header__item header__item--left">
         <slot name="left"></slot>
      </div>

		<div class="header__item header__item--right">
         <slot name="right"></slot>
      </div>

		<div class="header__dropdown" v-if="isShow" ref="headerDropdown">
			<div class="header__dropdown__body">
            <slot name="dropdown"></slot>
         </div>

			<div class="header__dropdown__footer">
				<div class="header__dropdown__item" @click="logout">
					<VueIcon :name="'Logout'" :fill="'var(--delete-background-color)'" :width="'24px'" :height="'24px'" />
					Выход
				</div>
			</div>
		</div>

		<!-- <a @click.prevent="$router.push({ name: 'home' })" href="/" alt="Медлайн">
			<img src="../../../../assets/svg/medline/logo.svg" alt="Медлайн" />
		</a>
		<div class="user">
			<p>{{ $store.getters.userNickname }}</p>
		</div> -->
	</header>

	<!-- Кнопка "бургер" -->
	<!-- <div class="burger" @click="$store.commit('setBurgerAdmin')" :class="{ active: $store.getters.burgerAdminStatus }">
		<div></div>
		<div></div>
		<div></div>
	</div> -->
</template>

<script>
export default {
	data() {
		return {
			isShow: false,
		};
	},
	methods: {
		/* Закрытие месяца */
		handleClickOutside(e) {
			if (this.$refs.header && !this.$refs.header.contains(e.target)) {
				this.isShow = false;
			}
		},

		logout() {
			this.$store.commit("logoutOpen");
			this.close();
		},

		close() {
			this.isShow = false;
		},
	},
	mounted() {
		document.addEventListener("click", this.handleClickOutside);
	},
	beforeUnmount() {
		document.removeEventListener("click", this.handleClickOutside);
	},
};
</script>

<style scoped>
header {
	position: relative;
	grid-area: header;
	display: flex;
	justify-content: space-between;
	align-items: center;

	border-top: var(--header-border-top);
	border-right: var(--header-border-right);
	border-bottom: var(--header-border-bottom);
	border-left: var(--header-border-left);
	border-style: var(--header-border-style);
	border-color: var(--header-border-color);
	border-radius: var(--header-border-radius);
	padding: var(--header-padding);
	margin: var(--header-margin);

	background-color: var(--header-background-color);
	height: var(--header-height);

	box-shadow: var(--header-box-shadow);
}

.header__item {
	display: flex;
	align-items: center;
	gap: var(--default-gap);
}

.header__user {
	display: flex;
	justify-content: center;
	align-items: center;
	gap: calc(var(--default-gap) / 2);
}

.header__user-name {
	font-size: 1.125rem;
}

.header__user-email {
	font-size: 1.125rem;
	color: rgba(0, 0, 0, 0.5);
}

.header__user-info {
	display: flex;
	flex-direction: column;
	justify-content: center;
	align-items: center;
	gap: 5px;
}

.header__user-icon {
	cursor: pointer;

	border-radius: 100px;

	width: 50px;
	height: 50px;
	background-color: var(--primary-color);
}

/* Выпадающее меню */
.header__dropdown {
	position: absolute;
	top: calc(100% + 10px);
	right: 10px;
	z-index: 1;

	display: flex;
	flex-direction: column;

	width: min(100%, 250px);

	background-color: white;
	border-radius: 10px;
	border: var(--default-border);
	box-shadow: var(--default-box-shadow);

	animation: fadeIn 0.2s ease;
}

.header__dropdown__body {
	display: flex;
	flex-direction: column;
	gap: 5px;

	padding: 10px;
}

.header__dropdown__footer {
	display: flex;
	flex-direction: column;
	gap: 5px;

	border-top: var(--default-border-width);
	border-right: 0px;
	border-bottom: 0px;
	border-left: 0px;
	border-style: var(--default-border-style);
	border-color: var(--default-border-color);

	padding: 10px;
}

.header__dropdown__item {
	user-select: none;
	cursor: pointer;
	display: flex;
	justify-content: flex-start;
	align-items: center;
	gap: 10px;

	background-color: white;
	padding: 10px;
	border-radius: 10px;

	font: var(--default-font-weight) 1.125rem var(--default-font-family);
}

.header__dropdown__item:hover {
	background-color: rgba(0, 0, 0, 0.05);
}
</style>
