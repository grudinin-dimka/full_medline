<template>
	<InfoBar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link :to="`/vacancies`">Вакансии</router-link>
	</InfoBar>

	<Block :minHeight="100">
		<div class="vacancies" v-if="vacancies.length > 0">
			<div
				class="vacancies__item"
				v-for="vacancy in vacancies"
				:class="{ 'skeleton': loading.loader.vacancies }"
			>
				<div class="vacancies__item-title">
					<VueTiptap :editable="false" :limit="10_000" v-model="vacancy.title" />
				</div>
				<div class="vacancies__item-description">
					<VueTiptap :editable="false" :limit="10_000" v-model="vacancy.description" />
				</div>
			</div>
		</div>

		<Empty :minHeight="300" v-else />
	</Block>
</template>

<script>
import VueIcon from "../../../components/modules/icon/VueIcon.vue";
import VueTiptap from "../../../components/modules/VueTiptap.vue";

import InfoBar from "../../../components/ui/main/InfoBar.vue";
import Block from "../../../components/ui/main/Block.vue";
import Empty from "../../../components/modules/Empty.vue";
import LoadText from "../../../components/ui/main/LoadText.vue";

import api from "../../../mixin/api";
import sorted from "../../../services/sorted";
import fakeDelay from "../../../mixin/fake-delay";

export default {
	components: {
		VueIcon,
		VueTiptap,

		InfoBar,
		Block,
		Empty,
		LoadText,
	},
	data() {
		return {
			loading: {
				loader: {
					vacancies: true,
				},
			},
         
			/* Данные */
			vacancies: [],
		};
	},
	created() {
		// fakeDelay(this.$store.getters.timeout, () =>
		// 	api({
		// 		method: "get",
		// 		url: this.$store.getters.urlApi + `get-travels`,
		// 	})
		// ).then((response) => {
		// 	if (!response) return;
		// 	try {
		// 		for (let i = 0; i < response.data.result.length; i++) {
		// 			this.travels[i] = response.data.result[i];
		// 		}
		// 		this.travels.splice(response.data.result.length, this.travels.length);
		// 		this.loading.loader.travels = false;
		// 	} catch (error) {
		// 		this.$store.commit("addDebugger", {
		// 			title: "Ошибка.",
		// 			body: error,
		// 			type: "error",
		// 		});
		// 	}
		// });
	},
};
</script>

<style scoped>
.vacancies {
	display: flex;
	flex-direction: column;
	gap: 10px;

	margin: 0 auto;

	width: 1350px;
}

@media screen and (max-width: 1450px) {
	.vacancies {
		width: 100%;
	}
}
</style>
