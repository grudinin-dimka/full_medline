<template>
	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                   БЛОК ПРОФИЛЯ                    |-->
	<!--|___________________________________________________|-->
	<block-once :minHeight="400">
		<template #title>ПРОФИЛЬ</template>

		<template #body>
			<div class="profile-info" v-if="loading.sections.profile">
				<div class="item avatar">
					<div
						class="img"
						:style="{
							backgroundImage: `url(${profile.path})`,
						}"
					></div>
				</div>
				<div class="item fio">
					<div class="title">Контактная информация</div>
					<div class="body">
						<div class="block">
							<div class="title">Фамилия</div>
							<div class="content">{{ profile.family }}</div>
						</div>
						<div class="block">
							<div class="title">Имя</div>
							<div class="content">{{ profile.name }}</div>
						</div>
						<div class="block">
							<div class="title">Отчество</div>
							<div class="content">
								{{ profile.surname === null ? "-" : profile.surname }}
							</div>
						</div>
						<div class="block">
							<div class="title">Возраст</div>
							<div class="content">{{ getDateAges(profile.dateOfBirth) }}</div>
						</div>
					</div>
				</div>
				<div class="item">
					<div class="title">Пользовательская информация</div>
					<div class="body">
						<div class="block">
							<div class="title">Псевдоним</div>
							<div class="content">{{ profile.nickname }}</div>
						</div>
						<div class="block">
							<div class="title">Почта</div>
							<div class="content">{{ profile.email }}</div>
						</div>
					</div>
				</div>
				<div class="item advanced">
					<div class="title">Расширенная информация</div>
					<div class="body">
						<div class="block">
							<div class="title">Статус</div>
							<div class="content">{{ profile.status }}</div>
						</div>
						<div class="block">
							<div class="title">Права</div>
							<div class="content">{{ profile.rights }}</div>
						</div>
					</div>
				</div>
			</div>

			<LoaderChild
				:isLoading="loading.loader.profile"
				:minHeight="300"
				@loaderChildAfterLeave="loaderChildAfterLeave"
			></LoaderChild>
		</template>
	</block-once>
</template>

<script>
import LoaderChild from "../../../components/modules/LoaderChild.vue";

import BlockOnce from "../../../components/ui/admin/blocks/BlockOnce.vue";

import ContainerInput from "../../../components/ui/admin/containers/ContainerInput.vue";
import ContainerInputTwo from "../../../components/ui/admin/containers/input/ContainerInputTwo.vue";
import ContainerInputThree from "../../../components/ui/admin/containers/input/ContainerInputThree.vue";

import axios from "axios";

export default {
	components: {
		LoaderChild,
		BlockOnce,

		ContainerInput,
		ContainerInputTwo,
		ContainerInputThree,

		axios,
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
			profile: {
				family: "Отсутствует",
				name: "Отсутствует",
				surname: "Отсутствует",
				dateOfBirth: "Отсутствует",
				nickname: "Отсутствует",
				email: "Отсутствует",
				status: "Отсутствует",
				rights: "Отсутствует",
				path: "/storage/users/avatar.png",
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
		getDateAges(date) {
			let dateNow = new Date();
			let dateAges = dateNow.getFullYear() - new Date(date).getFullYear();
			return dateAges;
		},
	},
	mounted() {
		axios({
			method: "get",
			headers: {
				Accept: "application/json",
				Authorization: `Bearer ${localStorage.getItem("token")}`,
			},
			url: `${this.$store.getters.urlApi}` + `get-profile-info`,
		})
			.then((response) => {
				for (let key in this.profile) {
					this.profile[key] = response.data.data[key];
				}
			})
			.catch((error) => {
				let debbugStory = {
					title: "Ошибка.",
					body: "Произошла ошибка при загрузке профиля.",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
			})
			.finally(() => {
				this.loading.loader.profile = false;
			});
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
	display: flex;
	justify-content: center;
	align-items: center;

	width: 400px;
	height: 400px;
	padding: 20px 50px;
}

.profile-info > .item.avatar > .img {
	width: 400px;
	height: 400px;
	border-radius: 300px;
	background-size: cover;
	background-position: center center;
	background-repeat: no-repeat;
}

.profile-info > .item:not(.avatar) {
	flex: 1 0 400px;
	padding: 20px;
	background-color: rgb(245, 245, 245);
	border-radius: calc(var(--default-border-radius) / 1.5);
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

@media screen and (width <= 1425px) {
	.profile-info > .item.avatar {
		width: 100%;
	}
}

@media screen and (width <= 600px) {
	.profile-info > .item.avatar {
		width: 100%;
		height: auto;
		padding: 20px 0px;
	}

	.profile-info > .item.avatar > .img {
		width: 300px;
		height: 300px;
	}

	.profile-info > .item:not(.avatar) {
		flex: 1 0 200px;
	}
}
</style>
