<template>
	<info-bar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link to="/contacts">Контакты</router-link>
	</info-bar>

	<block>
		<template v-if="loading.sections.contacts">
			<MainContactsList :contacts="contacts" v-if="contacts.length > 0"></MainContactsList>

			<Empty :minHeight="300" v-else />
		</template>

		<loader-child
			:isLoading="loading.loader.contacts"
			@loaderChildAfterLeave="loaderChildAfterLeave"
		/>
	</block>
</template>

<script>
import InfoBar from "../../../components/ui/main/InfoBar.vue";
import LoaderChild from "../../../components/modules/LoaderChild.vue";
import Block from "../../../components/ui/main/Block.vue";

import MainContactsList from "./MainContactsList.vue";
import Empty from "../../../components/modules/Empty.vue";

import api from "../../../services/api";
import sorted from "../../../services/sorted.js";

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
			contacts: [],
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

		api({
			method: "get",
			url: this.$store.getters.urlApi + `get-contacts-clinics-all`,
		})
			.then((response) => {
				if (!response) return;

				this.contacts = response.data.result;
				sorted.sortByOrder("up", this.contacts);
			})
			.catch((error) => {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
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
