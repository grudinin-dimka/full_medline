<template>
	<info-bar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link to="/about">О нас</router-link>
	</info-bar>

	<block>
		<template v-if="loading.sections.about">
			<MainAboutList :abouts="abouts" v-if="abouts.length > 0" />
			<Empty v-else />
		</template>

		<loader-child
			:isLoading="loading.loader.about"
			@loaderChildAfterLeave="loaderChildAfterLeave"
		/>
	</block>
</template>

<script>
import InfoBar from "../../../components/ui/main/InfoBar.vue";
import LoaderChild from "../../../components/modules/LoaderChild.vue";
import Block from "../../../components/ui/main/Block.vue";
import MainAboutList from "./MainAboutList.vue";

import Empty from "../../../components/modules/Empty.vue";

import axios from "axios";

export default {
	components: {
		InfoBar,
		LoaderChild,
		Block,
		MainAboutList,
		Empty,
		axios,
	},
	methods: {
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                   Загрузчик                       |*/
		/* |___________________________________________________|*/
		/* После скрытия элементы */
		loaderChildAfterLeave() {
			this.loading.sections.about = true;
		},
	},
	data() {
		return {
			loading: {
				loader: {
					about: true,
				},
				sections: {
					about: false,
				},
			},
			abouts: [],
		};
	},
	mounted() {
		this.loading.loader.about = false;

		axios({
			method: "get",
			url: `${this.$store.getters.urlApi}` + `get-abouts-all`,
		})
			.then((response) => {
				if (response.data.status) {
					this.abouts = response.data.data;

					this.abouts.sort((a, b) => {
						return a.order - b.order;
					});
				} else {
					this.$store.commit("addDebugger", {
						title: "Ошибка.",
						body: response.data.message,
						type: "error",
					});
				}
			})
			.catch((error) => {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			})
			.finally(() => {
				this.loading.loader.specialists = false;
			});
	},
};
</script>

<style scoped></style>
