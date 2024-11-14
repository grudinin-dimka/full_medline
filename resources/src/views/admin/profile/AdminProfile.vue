<template>
	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                   БЛОК ПРОФИЛЯ                    |-->
	<!--|___________________________________________________|-->
	<block-once :minHeight="400">
		<block-title>
			<template #title>ПРОФИЛЬ</template>
		</block-title>
		<!-- TODO Доделать этот раздел с профилем -->
		<div class="profile-info" v-if="loading.sections.profile">
			<div class="item avatar">
				<div
					class="img"
					:style="{
						backgroundImage: `url(/storage/users/avatar.png)`,
					}"
				></div>
			</div>
			<div class="item fio">
				<div class="title">Контактная информация</div>
				<div class="body">
					<div class="block">
						<div class="title">Фамилия</div>
						<div class="content">Отстутствует</div>
					</div>
					<div class="block">
						<div class="title">Имя</div>
						<div class="content">Отстутствует</div>
					</div>
					<div class="block">
						<div class="title">Отчество</div>
						<div class="content">Отстутствует</div>
					</div>
					<div class="block">
						<div class="title">Возраст</div>
						<div class="content">Отстутствует</div>
					</div>
				</div>
			</div>
			<div class="item">
				<div class="title">Пользовательская информация</div>
				<div class="body">
					<div class="block">
						<div class="title">Псевдоним</div>
						<div class="content">Отстутствует</div>
					</div>
					<div class="block">
						<div class="title">Почта</div>
						<div class="content">Отстутствует</div>
					</div>
				</div>
			</div>
			<div class="item advanced">
				<div class="title">Расширенная информация</div>
				<div class="body">
					<div class="block">
						<div class="title">Статус</div>
						<div class="content">Отстутствует</div>
					</div>
					<div class="block">
						<div class="title">Права</div>
						<div class="content">Отстутствует</div>
					</div>
				</div>
			</div>
		</div>

		<LoaderChild
			:isLoading="loading.loader.profile"
			:minHeight="300"
			@loaderChildAfterLeave="loaderChildAfterLeave"
		></LoaderChild>
	</block-once>
</template>

<script>
import LoaderChild from "../../../components/includes/LoaderChild.vue";

import BlockOnce from "../../../components/ui/admin/blocks/BlockOnce.vue";
import BlockTitle from "../../../components/ui/admin/blocks/BlockTitle.vue";

import IconLoad from "../../../components/icons/IconLoad.vue";
import IconSave from "../../../components/icons/IconSave.vue";

import ContainerInput from "../../../components/ui/admin/containers/ContainerInput.vue";
import ContainerInputTwo from "../../../components/ui/admin/containers/input/ContainerInputTwo.vue";
import ContainerInputThree from "../../../components/ui/admin/containers/input/ContainerInputThree.vue";

export default {
	components: {
		LoaderChild,
		BlockOnce,
		BlockTitle,
		IconLoad,
		IconSave,
		ContainerInput,
		ContainerInputTwo,
		ContainerInputThree,
	},
	data() {
		return {
			loading: {
				loader: {
					profile: true,
				},
				sections: {
					profile: false,
				},
			},
		};
	},
	methods: {
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                   Загрузчик                       |*/
		/* |___________________________________________________|*/
		/* После скрытия элементы */
		loaderChildAfterLeave() {
			this.loading.sections.profile = true;
		},
	},
	mounted() {
		this.loading.loader.profile = false;
	},
};
</script>

<style scoped>
.profile-info {
	display: flex;
	flex-wrap: wrap;

	gap: 20px;
	animation: show-bottom-to-top-15 0.5s ease-in-out;
}

.profile-info > .item {
	display: flex;
	flex-direction: column;
	gap: 20px;
}

.profile-info > .item.avatar {
	width: 400px;
	height: 400px;
	padding: 20px 50px;
}

.profile-info > .item.avatar > .img {
	width: 100%;
	height: 100%;
	border-radius: 10px;
	background-size: contain;
	background-position: center center;
	background-repeat: no-repeat;
}

.profile-info > .item:not(.avatar) {
	flex: 1 0 400px;
	padding: 20px;
	background-color: rgb(245, 245, 245);
	border-radius: 10px;
}

.item > .title {
	font-size: 22px;
}

.item > .body {
	display: flex;
	flex-direction: column;
	gap: 20px;
	height: 100%;
}

.item > .body > .block {
	display: flex;
	flex-direction: column;
	gap: 5px;
}

.item > .body > .block > .title {
	font-size: 16px;
	color: rgb(180, 180, 180);
}

.item > .body > .block > .content {
	font-size: 20px;
}
</style>
