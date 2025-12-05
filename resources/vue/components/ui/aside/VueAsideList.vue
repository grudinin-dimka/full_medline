<template>
	<div class="aside__list" :class="{ 'aside__list--hiden': $store.getters.getIsHide }" ref="asideList">
		<VueAsideLink @click.prevent="isShow = !isShow" :is-active="isShow">
			<div class="aside__list-arrow" v-if="!$store.getters.getIsHide" :class="{ show: isShow }">
				<VueIcon :name="'Arrow'" :fill="'black'" :width="'14px'" :height="'14px'" />
			</div>

			<slot name="title"></slot>
		</VueAsideLink>

		<div class="aside__list-content" :class="{ show: isShow }">
			<div class="aside__list-line" v-if="!$store.getters.getIsHide"></div>

			<slot name="content"></slot>
		</div>
	</div>
</template>

<script>
import VueAsideLink from "./VueAsideLink.vue";

export default {
	props: {
		alias: {
			type: Array,
			default: [],
		},
	},
	components: {
		VueAsideLink,
	},
	data() {
		return {
			isShow: false,
		};
	},
	methods: {
		handleClickOutside(event) {
			if (!this.$refs.asideList.contains(event.target) && !this.alias.includes(localStorage.getItem("page"))) {
				this.isShow = false;
			}
		},
	},
	mounted() {
		if (this.alias.includes(this.$route.name)) {
			this.isShow = true;
		}

		document.addEventListener("click", this.handleClickOutside);
	},
	unmounted() {
		document.removeEventListener("click", this.handleClickOutside);
	},
};
</script>

<style>
.aside__list-content {
	position: relative;

	display: none;
	flex-direction: column;
	gap: var(--aside-list-gap);

	margin: var(--aside-list-margin);

	transition: var(--aside-list-transition);
}

.aside__list-line {
	position: absolute;
	top: 0px;
	left: -15px;

	border-radius: 20px;

	width: 2px;
	height: 100%;
	background-color: var(--default-border-color);
}

.aside__list.aside__list--hiden .aside__list-content {
	margin-left: 0px;
}

.aside__list-content.show {
	display: flex;
}

.aside__list-arrow {
	position: absolute;
	top: var(--aside-arrow-top);
	right: var(--aside-arrow-right);
	display: flex;
	justify-content: center;
	align-items: center;

	border-radius: 50%;

	width: var(--aside-arrow-width);
	height: var(--aside-arrow-height);
	rotate: 180deg;

	transition: var(--aside-arrow-transition);
}

.aside__list-arrow.show {
	rotate: 0deg;
}

.aside__list.aside__list--hiden .aside__list-arrow {
	top: 22px;
	right: -12px;
}
</style>
