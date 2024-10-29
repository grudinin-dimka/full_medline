<template>
	<info-bar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link to="/contacts">Контакты</router-link>
	</info-bar>

	<block :minHeight="400">
		<template v-if="loading.sections.contacts">
			<MainContactsList :contacts="contacts"></MainContactsList>
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

export default {
	components: {
		InfoBar,
		LoaderChild,
		Block,
		MainContactsList,
		Empty,
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
					id: 1,
					order: 1,
					name: "Контакт 1",
					clinicId: 1,
					phones: [
						{
							id: 1,
							body: "+7 999 999 99 99",
						},
					],
					mails: [
						{
							id: 1,
							body: "u9j6G@example.com",
						},
					],
				},
				{
					id: 2,
					order: 2,
					name: "Контакт 2",
					clinicId: null,
					phones: [
						{
							id: 2,
							body: "+7 999 999 99 99",
						},
					],
					mails: [
						{
							id: 2,
							body: "u9j6G@example.com",
						},
					],
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
	},
};
</script>

<style scoped></style>
