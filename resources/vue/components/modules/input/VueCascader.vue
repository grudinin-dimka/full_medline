<template>
	<div class="vue-radio-group">
		<div class="vue-radio-group__title" v-if="$slots.title">
			<slot name="title"></slot>
		</div>

		<label class="vue-radio-group__label" v-for="item in list">
			<input
				class="vue-radio-group__input"
				type="radio"
				:name="name"
				:value="item.value"
				v-model="picked"
			/>
			<template v-if="$slots[item.slot]">
				<slot :label="item.label"></slot>
			</template>

			<template v-else>
				{{ item.label }}
			</template>
		</label>
	</div>
</template>

<script>
export default {
	name: "VueCascader",
	props: {
		modelValue: {
			type: String,
			default: "",
			required: true,
		},
		list: {
			type: Array,
			default: [],
			required: true,
		},
		name: {
			type: String,
			default: "",
			required: true,
		},
	},
	data() {
		return {
			picked: this.modelValue,
		};
	},
	watch: {
		picked(value) {
			this.$emit("update:modelValue", value);
		},
	},
};
</script>

<style scoped>
.vue-radio-group {
	display: flex;
	gap: 5px;

	width: fit-content;
	padding: 5px;


	border: var(--default-border);
	border-radius: 100px;
	background-color: rgba(255, 255, 255, 0.75);
}

.vue-radio-group__title {
	cursor: default;
	position: relative;

	padding: 10px;

	color: var(--primary-color);
	font-weight: 500;

	height: 40px;
	border-top: 0px;
	border-right: var(--default-border-width);
	border-bottom: 0px;
	border-left: 0px;
	border-style: var(--default-border-style);
	border-color: var(--default-border-color);
	border-radius: 15px;
	background-color: white;

	transition: all 0.2s ease;
}

.vue-radio-group__label {
	user-select: none;
	cursor: pointer;
	display: flex;
	justify-content: center;
	align-items: center;
	gap: 5px;
	
	padding: 10px;

	border-radius: 100px;
	height: 40px;
	min-width: 40px;

	transition: all 0.2s ease;
}

.vue-radio-group__input {
	display: none;
}

.vue-radio-group__label:has(.vue-radio-group__input:checked) {
	background-color: var(--primary-color);
	color: white;
}
</style>
