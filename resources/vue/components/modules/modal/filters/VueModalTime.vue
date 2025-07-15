<template>
	<VueModal ref="modal" :settings="modal">
		<template #title>Диапазон</template>
		<template #body>
			<VueInput
				v-model="currentModal.data.start.value"
				:type="'datetime-local'"
				:error="currentModal.errors.start.status"
			>
				<template #label> От </template>
				<template #error> {{ currentModal.errors.start.message }} </template>
			</VueInput>

			<VueInput
				v-model="currentModal.data.end.value"
				:type="'datetime-local'"
				:error="currentModal.errors.end.status"
			>
				<template #label> До </template>
				<template #error> {{ currentModal.errors.end.message }} </template>
			</VueInput>
		</template>
		<template #footer>
			<VueButton @click="submit">Применить</VueButton>
		</template>
	</VueModal>
</template>

<script>
import VueModal from "../VueModal.vue";

import VueInput from "../../input/VueInput.vue";

import VueButton from "../../../ui/VueButton.vue";

export default {
	emits: ["changed"],
	components: {
		VueModal,
		VueInput,

		VueButton,
	},
	data() {
		return {
			/* Модальное окно */
			modal: {
				thin: true,
				values: {
					title: "",
					look: "default",
				},
				options: {
					head: true,
					body: true,
					footer: true,
				},
			},

			/* Текущий фильтр времени */
			currentModal: {
				errors: {
					start: {
						status: false,
						message: "",
					},
					end: {
						status: false,
						message: "",
					},
				},
				data: {
					start: {
						value: "",
						edited: false,
					},
					end: {
						value: "",
						edited: false,
					},
				},
			},

			field: {},
		};
	},
	methods: {
		open(field) {
			this.field = field;

			this.$refs.modal.open();
		},

		submit() {
			this.field.from = this.currentModal.data.start.value;
			this.field.to = this.currentModal.data.end.value;

			// Заполнение поля, чтобы сортировка работала
			this.field.filter = "on";

			this.$emit("changed", this.field);

			this.$refs.modal.close();
		},
	},
};
</script>
