<template>
	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                   БЛОК ПРОФИЛЯ                    |-->
	<!--|___________________________________________________|-->
	<block-once :minHeight="400">
		<block-title>
			<template #title>ПОЛЬЗОВАТЕЛИ</template>
		</block-title>

		<LoaderChild
			:isLoading="loading.loader.users"
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

import axios from "axios";

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
		axios,
	},
	data() {
		return {
			loading: {
				loader: {
					users: true,
				},
				sections: {
					users: false,
				},
			},
			currentUser: {
				errors: {
					family: {
						body: "",
						status: false,
					},
					name: {
						body: "",
						status: false,
					},
					surname: {
						body: "",
						status: false,
					},
					dateOfBirth: {
						body: "",
						status: false,
					},
					nickname: {
						body: "",
						status: false,
					},
					email: {
						body: "",
						status: false,
					},
					status: {
						body: "",
						status: false,
					},
					rights: {
						body: "",
						status: false,
					},
					path: {
						body: "",
						status: false,
					},
				},
				data: {
					family: {
						body: "",
						edited: false,
					},
					name: {
						body: "",
						edited: false,
					},
					surname: {
						body: "",
						edited: false,
					},
					dateOfBirth: {
						body: "",
						edited: false,
					},
					nickname: {
						body: "",
						edited: false,
					},
					email: {
						body: "",
						edited: false,
					},
					status: {
						body: "",
						edited: false,
					},
					rights: {
						body: "",
						edited: false,
					},
					path: {
						body: "",
						edited: false,
					},
				},
			},
			users: [],
		};
	},
	methods: {
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                   Загрузчик                       |*/
		/* |___________________________________________________|*/
		/* После скрытия элементы */
		loaderChildAfterLeave() {
			this.loading.sections.users = true;
		},
	},
	mounted() {
		this.loading.loader.users = false;
	},
};
</script>

<style scoped></style>
