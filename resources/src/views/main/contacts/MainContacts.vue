<template>
	<info-bar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link to="/contacts">Контакты</router-link>
	</info-bar>

	<block :minHeight="400">
		<template v-if="loading.sections.contacts">
			<MainContactsList :contacts="contacts" v-if="contacts.length > 0"></MainContactsList>

			<Empty :minHeight="300" v-else />
		</template>

		<loader-child
			:isLoading="loading.loader.contacts"
			:minHeight="400"
			@loaderChildAfterLeave="loaderChildAfterLeave"
		/>
	</block>
</template>

<script>
import InfoBar from "../../../components/ui/main/InfoBar.vue";
import LoaderChild from "../../../components/includes/LoaderChild.vue";
import Block from "../../../components/ui/main/blocks/Block.vue";

import MainContactsList from "./MainContactsList.vue";
import Empty from "../../../components/includes/Empty.vue";

import axios from "axios";
import sorted from "../../../services/sorted.js";

export default {
	components: {
		InfoBar,
		LoaderChild,
		Block,
		MainContactsList,
		Empty,
		axios,
		sorted,
	},
	data() {
		return {
			loading: {
				loader: {
					contacts: true,
				},
				sections: {
					contacts: false,
				},
			},
			contacts: [
				{
					id: 2,
					order: 2,
					name: "Колл-центр",
					clinic: {
						id: null,
						coordinates: null,
					},
					phones: [
						{
							id: 2,
							name: "+7 999 999 99 99",
						},
					],
					mails: [
						{
							id: 2,
							name: "u9j6G@example.com",
						},
					],
				},
				{
					id: 1,
					order: 1,
					name: "Контакт 1",
					clinic: {
						id: 1,
						coordinates: {
							width: 37.588144,
							longitude: 55.733842,
						},
					},
					phones: [
						{
							id: 1,
							name: "+7 999 999 99 99",
						},
						{
							id: 2,
							name: "+7 999 999 99 99",
						},
						{
							id: 3,
							name: "+7 999 999 99 99",
						},
					],
					mails: [
						{
							id: 1,
							name: "u9j6G@example.com",
						},
					],
				},
				{
					id: 3,
					order: 3,
					name: "Контакт 3",
					clinic: {
						id: 1,
						coordinates: {
							width: 37.588144,
							longitude: 55.733842,
						},
					},
					phones: [],
					mails: [],
				},
			],
		};
	},
	methods: {
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                   Загрузчик                       |*/
		/* |___________________________________________________|*/
		/* После скрытия элементы */
		loaderChildAfterLeave() {
			this.loading.sections.contacts = true;
		},
	},
	mounted() {
		this.loading.loader.contacts = false;

		axios({
			method: "post",
			url: `${this.$store.state.axios.urlApi}` + `get-contacts-clinics-all`,
		})
			.then((response) => {
				if (response.data.status) {
					console.log(response.data);
				} else {
					// this.specialists = null;

					let debbugStory = {
						title: "Ошибка.",
						body: response.data.message,
						type: "Error",
					};
					this.$store.commit("debuggerState", debbugStory);
				}
			})
			.catch((error) => {
				let debbugStory = {
					title: "Ошибка.",
					body: "Произошла ошибка при получении данных о слайдере.",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
			})
			.finally(() => {
				if (this.specialists != null) {
					this.loading.loader.specialists = false;
				}
			});
	},
};
</script>

<style scoped></style>
