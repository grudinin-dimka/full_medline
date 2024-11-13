<template>
	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                   БЛОК ПРОФИЛЯ                    |-->
	<!--|___________________________________________________|-->
	<block-once :minHeight="400">
		<block-title>
			<template #title>ПРОФИЛЬ</template>
		</block-title>

		<div class="profile-info" v-if="loading.sections.profile">
			<div class="item avatar">
				<img src="../../../assets/img/map-clear.png" alt="" />
			</div>
			<div class="item fio">
				<div class="title">Контактная информация</div>
				<div class="body">
					<div class="block">
						<div class="title">Фамилия</div>
						<div class="content">Иванов</div>
					</div>
					<div class="block">
						<div class="title">Имя</div>
						<div class="content">Иван</div>
					</div>
					<div class="block">
						<div class="title">Отчество</div>
						<div class="content">Иванович</div>
					</div>
				</div>
			</div>
			<div class="item advanced">информация</div>
			<div class="item">информация</div>
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
	display: grid;
	grid-template: 1fr 1fr / 1fr 1fr;
	gap: 20px;
	animation: show-bottom-to-top-15 0.5s ease-in-out;
}

.profile-info > .item {
	display: flex;
	flex-direction: column;
	gap: 20px;
}

.profile-info > .item:not(.avatar) {
	padding: 20px;
	/* background-color: rgb(230, 252, 255); */
	border-radius: 10px;
}

.item.fio > .title {
	font-size: 22px;
}

.item.fio > .body {
	display: flex;
	flex-direction: column;
	/* justify-content: space-evenly; */
	gap: 20px;
	height: 100%;
}

.item.fio > .body > .block {
   display: flex;
   flex-direction: column;
   gap: 5px;
}

.item.fio > .body > .block > .title {
   color: rgb(200, 200, 200);
}

.item.fio > .body > .block > .title {
   color: rgb(200, 200, 200);
}
</style>
