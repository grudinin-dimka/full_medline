<template>
	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                    СПИСОК ЦЕН                     |-->
	<!--|___________________________________________________|-->
	<block-once :minHeight="200">
		<template #title>РАСПИСАНИЕ</template>

		<template #options>
			<icon-load :width="28" :height="28" v-if="disabled.schedule.save" />
			<icon-save :width="28" :height="28" @click="updateSheduleFromServe" v-else />
		</template>
	</block-once>
</template>

<script>
import BlockOnce from "../../../components/ui/admin/blocks/BlockOnce.vue";
import BlockTitle from "../../../components/ui/admin/blocks/BlockTitle.vue";
import InfoBar from "../../../components/ui/main/InfoBar.vue";
import BlockButtons from "../../../components/ui/admin/blocks/BlockButtons.vue";

import ButtonDefault from "../../../components/ui/admin/buttons/ButtonDefault.vue";

import IconLoad from "../../../components/icons/IconLoad.vue";
import IconSave from "../../../components/icons/IconSave.vue";

import axios from "axios";

export default {
	components: {
		BlockOnce,
		BlockTitle,
		InfoBar,
		BlockButtons,

		ButtonDefault,

		IconLoad,
		IconSave,
	},
	data() {
		return {
			disabled: {
				schedule: {
					save: false,
				},
			},
		};
	},
	methods: {
		updateSheduleFromServe() {
			this.disabled.schedule.save = true;

			axios({
				method: "post",
				url: `${this.$store.getters.urlApi}` + `save-shedules-all`,
				headers: {
					Accept: "application/json",
					Authorization: `Bearer ${localStorage.getItem("token")}`,
				},
				data: {
					type: "manual",
				},
			})
				.then((response) => {
					console.log(response.data);

					let debbugStory = {
						title: "Успешно!",
						body: "Расписание обновлено.",
						type: "Completed",
					};
					this.$store.commit("debuggerState", debbugStory);
				})
				.catch((error) => {
					console.log(error);

					let debbugStory = {
						title: "Ошибка.",
						body: "Не удалось обновить расписание.",
						type: "Error",
					};
					this.$store.commit("debuggerState", debbugStory);
				})
				.finally(() => {
					this.disabled.schedule.save = false;
				});
		},
	},
};
</script>

<style scoped></style>
