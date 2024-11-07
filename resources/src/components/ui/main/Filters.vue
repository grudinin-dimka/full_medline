<template>
	<div class="filters">
		<div
			class="item"
			v-for="(filter, index) in filters"
			:key="filter.id"
			:class="{ active: filter.status }"
			@click="this.$emit('changeActiveFilter', filter)"
		>
			<div>{{ filter.name }}</div>
		</div>
	</div>
</template>

<script>
export default {
	props: {
		filters: {
			type: Array,
			required: true,
			default: [],
		},
	},
	// computed: {
	// 	getFiltersActivity() {
	// 		let filtersActivity = [...this.filters];
	// 		filtersActivity.forEach((filter) => (filter.status = false));
	// 		return [...filtersActivity];
	// 	},
	// },
	methods: {
		// changeActiveFilter(index) {
		// 	this.getFiltersActivity.forEach((filter, filterIndex) => {
		// 		if (filterIndex === index) {
		// 			filter.status = !filter.status;
		// 		} else {
		// 			filter.status = false;
		// 		}
		// 	});
		// 	this.$emit("change", index);
		// },
	},
};
</script>

<style scoped>
.filters {
	display: inline-flex;
	flex-wrap: wrap;
	gap: 10px;

	width: 100%;
	max-width: 1250px;

	animation: show 0.5s ease-out;
}

.filters > .item {
	cursor: pointer;

	padding: 5px;
	border: 2px solid var(--input-border-color-inactive);
	border-radius: 10px;

	font-size: 18px;

	transition: all 0.15s;
}

.filters > .item:hover {
	border: 2px solid var(--input-border-color-active);
	background-color: #f2feff;
}

.filters > .item.active {
	border: 2px solid var(--input-border-color-active);
	background-color: #f2feff;
}

@keyframes show {
	0% {
		opacity: 0;
	}
	100% {
		opacity: 1;
	}
}

/* @media screen and (width <= 1330px) {
	.filters-body {
		width: 100%;
	}
} */
</style>
