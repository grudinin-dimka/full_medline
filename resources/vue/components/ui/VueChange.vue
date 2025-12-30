<template>
	<div class="vue-change" ref="change">
		<div class="vue-change__header">
			<div
				class="vue-change__item"
				:class="{
					'vue-change__item--skeleton': isLoading,
				}"
				:style="getStyle(list.find((item) => item.value === modelValue))"
				@click="isShow = !isShow"
			>
				{{ list.find((item) => item.value === modelValue).label }}
			</div>

			<div class="vue-change__open" :class="{ 'vue-change__open--active': isShow }" @click="isShow = !isShow">
				<VueIcon :name="'Arrow Drop Down'" :width="'30px'" :height="'30px'" />
			</div>
		</div>

		<div class="vue-change__content" :class="{ 'vue-change__content--active': isShow }">
			<div
				class="vue-change__item"
				v-for="item in list"
				:class="{
					'vue-change__item--active': modelValue === item.value,
					'vue-change__item--skeleton': isLoading,
				}"
				:style="getStyle(item)"
				@click="changeModel(item)"
			>
				{{ item.label }}
			</div>
		</div>
	</div>
</template>
<script>
export default {
	props: {
		modelValue: {
			type: [String, Number, Boolean, null, Object],
			default: {},
			required: true,
		},
		list: {
			type: Array,
			default: [],
			required: true,
		},
		isLoading: {
			type: Boolean,
			default: true,
		},
	},
	data() {
		return {
			isShow: false,
		};
	},
	methods: {
		/* Получение класса у времени */
		getStyle(item) {
			if (item.value === this.modelValue) {
				return {
					color: "black",
					border: item.style.border,
					backgroundColor: item.style.backgroundColor,
				};
			} else {
				return {
					borderColor: item.style.backgroundColor,
					backgroundColor: item.style.backgroundColor,
				};
			}
		},

		changeModel(item) {
			this.isShow = false;

			this.$emit("update:modelValue", item.value);
		},
	},
};
</script>
<style scoped>
.vue-change {
	display: flex;
	justify-content: center;
	flex-direction: column;
	align-items: center;
	gap: 10px;

	width: 1350px;
}

.vue-change__header {
	display: none;

	width: 100%;
}

.vue-change__header .vue-change__item {
	width: 100%;
}

.vue-change__open {
	cursor: pointer;
	display: flex;
	justify-content: center;
	align-items: center;

	width: 45px;
	height: 45px;

	border: var(--default-border);
	border-radius: 100px;

	transition: var(--default-transition);
}

.vue-change__open:is(:hover, .vue-change__open--active) {
	border: var(--default-border-focus);
	background-color: var(--item-background-color);
}

.vue-change__content {
	display: flex;
	align-items: center;
	gap: 10px;
	flex-wrap: wrap;

	width: 100%;
}

.vue-change__item {
	cursor: pointer;
	display: flex;
	align-items: center;

	border: var(--default-border);
	border-radius: 100px;
	padding: 5px 10px;

	font-size: 1.125rem;
	color: var(--default-font-color);

	min-height: 45px;
	min-width: 100px;

	transition: var(--default-transition);
}

.vue-change__item:is(.vue-change__item--active, :hover) {
	border: var(--default-border-focus);
	background-color: var(--item-background-color);
}

.vue-change__item--skeleton {
	background-image: linear-gradient(
		120deg,
		var(--skeleton-background-color) 50%,
		var(--skeleton-line-color) 60%,
		var(--skeleton-line-color) 61%,
		var(--skeleton-background-color) 70%
	);
	background-size: 200%;
	background-position: 100% 0;

	animation: waves 2s linear infinite;
}

.vue-change__item--skeleton:hover {
	border: var(--default-border);
}

@media screen and (width <= 1450px) {
	.vue-change {
		width: 100%;
	}
}

@media screen and (width <= 700px) {
	.vue-change__item {
		width: 100%;
	}
}

@media screen and (width <= 550px) {
	.vue-change__header {
		display: grid;
		grid-template-columns: 1fr auto;
		gap: calc(var(--default-gap) / 2);
	}

	.vue-change__content {
		display: none;

		border: var(--default-border);
		border-radius: 30px;
		padding: var(--default-padding);
	}

	.vue-change__content--active {
		display: flex;
	}

	.vue-change__header .vue-change__item {
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
	}
}
</style>
