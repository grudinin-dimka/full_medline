<template>
	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                    СПИСОК ЦЕН                     |-->
	<!--|___________________________________________________|-->
	<block-once :minHeight="200">
		<block-title>
			<template #title>РАСПИСАНИЕ</template>
			<template #buttons>
				<icon-load :width="28" :height="28" v-if="disabled.schedule.save" />
				<icon-save :width="28" :height="28" @click="updateSheduleFromServe" v-else />
			</template>
		</block-title>
	</block-once>

	<button class="shedule_refresh" @click="updateSheduleFromServe">
		<svg
			xmlns="http://www.w3.org/2000/svg"
			height="45px"
			viewBox="0 -960 960 960"
			width="45px"
			fill="white"
		>
			<path
				d="M480-160q-134 0-227-93t-93-227q0-134 93-227t227-93q69 0 132 28.5T720-690v-110h80v280H520v-80h168q-32-56-87.5-88T480-720q-100 0-170 70t-70 170q0 100 70 170t170 70q77 0 139-44t87-116h84q-28 106-114 173t-196 67Z"
			/>
		</svg>
	</button>
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
				url: `${this.$store.state.axios.urlApi}` + `save-shedules-all`,
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

<style scoped>
.shedule_refresh {
	cursor: pointer;
	position: fixed;
	right: 30px;
	bottom: 30px;
	z-index: 10;
	width: 80px;
	height: 80px;
	border-radius: 100px;
	border: none;
	background-color: var(--button-default-color);
	color: white;
}

.shedule_refresh:hover {
	background-color: var(--button-default-color-hover);
}
</style>
