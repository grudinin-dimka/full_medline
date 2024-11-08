<template>
	<div class="filters">
		<div class="container">
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
};
</script>

<style scoped>
.filters {
	display: flex;
	justify-content: center;
	width: 100%;
}

.container {
	display: inline-flex;
	flex-wrap: wrap;
	gap: 10px;

	width: 1350px;

	animation: show 0.5s ease-out;
}

.container > .item {
	cursor: pointer;

	padding: 5px;
	border-top: 2px;
	border-top-color: white;
	border-right: 2px;
	border-right-color: white;
	border-bottom: 2px;
	border-bottom-color: var(--input-border-color-inactive);
	border-left: 2px;
	border-left-color: white;
	border-style: solid;

	font-size: 18px;

	transition: all 0.15s;
}

.container > .item:nth-of-type(2) {
	/* border: 2px solid #c5c569;
	background-color: #ffffe0; */

	border-top: 2px;
	border-top-color: white;
	border-right: 2px;
	border-right-color: white;
	border-bottom: 2px;
	border-bottom-color: #ffad00;
	border-left: 2px;
	border-left-color: white;
	border-style: solid;
}

.container > .item:nth-of-type(3) {
	/* border: 2px solid #c39090;
	background-color: #ffe2e2; */

	border-top: 2px;
	border-top-color: white;
	border-right: 2px;
	border-right-color: white;
	border-bottom: 2px;
	border-bottom-color: #ff0d00;
	border-left: 2px;
	border-left-color: white;
	border-style: solid;
}

.container > .item:nth-of-type(4) {
	/* border: 2px solid #8ac397;
	background-color: #e2ffe7; */

	border-top: 2px;
	border-top-color: white;
	border-right: 2px;
	border-right-color: white;
	border-bottom: 2px;
	border-bottom-color: #49d369;
	border-left: 2px;
	border-left-color: white;
	border-style: solid;
}

.container > .item:nth-of-type(5) {
	/* border: 2px solid #b49acb;
	background-color: #f0e2ff; */

	border-top: 2px;
	border-top-color: white;
	border-right: 2px;
	border-right-color: white;
	border-bottom: 2px;
	border-bottom-color: #0036c8;
	border-left: 2px;
	border-left-color: white;
	border-style: solid;
}

.container > .item.active {
	border-radius: 10px;
	border-color: var(--input-border-color-active);
}

@keyframes show {
	0% {
		opacity: 0;
	}
	100% {
		opacity: 1;
	}
}

@media screen and (width <= 600px) {
	.container > .item {
		flex: 1 0 250px;
	}
}
</style>
