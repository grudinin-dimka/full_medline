<template>
	<header :class="{ 'header--hidden': isHidden }" ref="header">
		<div class="header__item header__item--left">
			<slot name="left"></slot>
		</div>

		<div class="header__item header__item--right">
			<slot name="right"></slot>
		</div>

		<div class="header__dropdown" v-if="isShow" ref="headerDropdown">
			<slot name="dropdown"></slot>
		</div>
	</header>
</template>

<script>
export default {
	props: {
		isHidden: {
			type: Boolean,
			default: false,
		},
	},
	data() {
		return {
			isShow: false,
		};
	},
	methods: {
		open() {
			this.isShow = true;
		},

		close() {
			this.isShow = false;
		},

		handleClickOutside(e) {
			if (this.$refs.header && !this.$refs.header.contains(e.target)) {
				this.close();
			}
		},

		logout() {
			this.$store.commit("logoutOpen");
			this.close();
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

/* Выпадающее меню */
.header__dropdown {
	position: absolute;
	top: calc(100% + 10px);
	right: 10px;
	z-index: 50;

	display: flex;
	flex-direction: column;
	gap: 5px;

	padding: 10px;
	width: min(100%, 250px);

	background-color: white;
	border-radius: 10px;
	border: var(--default-border);
	box-shadow: var(--default-box-shadow);

	animation: fadeIn 0.2s ease;
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
</style>
