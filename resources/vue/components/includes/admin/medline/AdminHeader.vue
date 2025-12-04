<template>
	<VueHeader ref="header">
		<template #left>
			<div class="header__close" @click="$store.commit('setIsHide')">
				<VueIcon
					v-if="$store.getters.getIsHide"
					:name="'Arrow Menu Open'"
					:fill="'black'"
					:hover="'var(--primary-color)'"
					:width="'30px'"
					:height="'30px'"
				/>
				<VueIcon
					v-else
					:name="'Arrow Menu Close'"
					:fill="'black'"
					:hover="'var(--primary-color)'"
					:width="'30px'"
					:height="'30px'"
				/>
			</div>
		</template>

		<template #right>
			<div class="header__user" @click="$refs.header.open()">
				<span class="header__user-info">
					<span class="header__user-name"> {{ $store.getters.getUserNickname }} </span>
					<span class="header__user-email"> {{ $store.getters.getUserEmail }} </span>
				</span>
				<div class="header__user-icon">
					<img :src="$store.getters.getUserImage" alt="" />
				</div>
			</div>
		</template>

		<template #dropdown>
			<div class="header__dropdown__item">
				<VueIcon :name="'Info'" :fill="'var(--primary-color)'" :width="'24px'" :height="'24px'" />
				О системе
			</div>
			<div class="header__dropdown__item">
				<VueIcon :name="'Settings'" :fill="'var(--primary-color)'" :width="'24px'" :height="'24px'" />
				Настройки
			</div>

			<div class="header__dropdown__item" @click="logout">
				<VueIcon :name="'Logout'" :fill="'var(--delete-background-color)'" :width="'24px'" :height="'24px'" />
				Выход
			</div>
		</template>
	</VueHeader>
</template>

<script>
import VueHeader from "../../../ui/VueHeader.vue";

export default {
	components: { VueHeader },
	methods: {
		logout() {
			this.$store.commit("logoutOpen");
			this.$refs.header.close();
		},
	},
};
</script>

<style scoped>
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
	align-items: flex-end;
	gap: 5px;
}

.header__user-icon {
	cursor: pointer;

	border-radius: 100px;

	width: 50px;
	height: 50px;
	background-color: var(--primary-color);
}

.header__user-icon img {
	width: 100%;
	height: 100%;

	border-radius: 100px;
	object-fit: cover;
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
