<template>
	<router-view></router-view>
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
					this.$store.commit("addDebugger", {
						title: "Успешно!",
						body: "Расписание обновлено.",
						type: "completed",
					});
				})
				.catch((error) => {
					this.$store.commit("addDebugger", {
						title: "Ошибка.",
						body: error,
						type: "error",
					});
				})
				.finally(() => {
					this.disabled.schedule.save = false;
				});
		},
	},
};
</script>

<style scoped></style>
