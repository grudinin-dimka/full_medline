<template>
	<info-bar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link to="/contacts">Контакты</router-link>
	</info-bar>

	<block :minHeight="600">
		<MainContactsList :contacts="contacts" v-if="contacts.length > 0"></MainContactsList>
		<Empty :minHeight="300" v-else />
	</block>
</template>

<script>
import VueLoader from "../../../components/modules/VueLoader.vue";

import MainContactsList from "./MainContactsList.vue";

import InfoBar from "../../../components/ui/main/InfoBar.vue";
import Block from "../../../components/ui/main/Block.vue";
import Empty from "../../../components/modules/Empty.vue";

import api from "../../../mixin/api.js";
import sorted from "../../../services/sorted.js";

import fakeDelay from "../../../mixin/fake-delay.js";

export default {
	components: {
		VueLoader,

		InfoBar,
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
					name: null,
					phones: null,
					mails: null,
					clinicId: null,
				},
				{
					id: 2,
					name: null,
					phones: null,
					mails: null,
					clinicId: null,
				},
				{
					id: 3,
					name: null,
					phones: null,
					mails: null,
					clinicId: null,
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
		fakeDelay(this.$store.getters.timeout, () =>
			api({
				method: "get",
				url: `get-contacts-clinics-all`,
			})
		).then((response) => {
			try {
				if (!response) return;

				for (let i = 0; i < response.data.result.length; i++) {
					this.contacts[i] = response.data.result[i];
				}

				this.contacts.splice(response.data.result.length, this.contacts.length);
				
				sorted.sortNumberByKey("up", this.contacts, "order");

				this.loading.loader.contacts = false;
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			}
		});
	},
};
</script>

<style scoped></style>
