<template>
	<div class="filter-list">
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
.filter-list {
	display: flex;
	justify-content: center;
	width: 100%;
}

.filters .container {
	display: inline-flex;
	flex-wrap: wrap;
	gap: 10px;

	width: 1350px;

	animation: show 0.5s ease-out;
}

.filters .container > .item {
	cursor: pointer;

	padding: 5px;
	border-top: 2px;
	border-top-color: white;
	border-right: 2px;
	border-right-color: white;
	border-bottom: 2px;
	border-bottom-color: var(--input-border-color-active);
	border-left: 2px;
	border-left-color: white;
	border-style: solid;

	font-size: 18px;

	transition: all 0.15s;
}

.filters .container > .item:nth-of-type(2) {
	border-top: 2px;
	border-top-color: rgba(255, 255, 255, 0);
	border-right: 2px;
	border-right-color: rgba(255, 255, 255, 0);
	border-bottom: 2px;
	border-bottom-color: #ffad00;
	border-left: 2px;
	border-left-color: rgba(255, 255, 255, 0);
	border-style: solid;
}

.filters .container > .item:nth-of-type(3) {
	border-top: 2px;
	border-top-color: rgba(255, 255, 255, 0);
	border-right: 2px;
	border-right-color: rgba(255, 255, 255, 0);
	border-bottom: 2px;
	border-bottom-color: #ff0d00;
	border-left: 2px;
	border-left-color: rgba(255, 255, 255, 0);
	border-style: solid;
}

.filters .container > .item:nth-of-type(4) {
	border-top: 2px;
	border-top-color: rgba(255, 255, 255, 0);
	border-right: 2px;
	border-right-color: rgba(255, 255, 255, 0);
	border-bottom: 2px;
	border-bottom-color: #49d369;
	border-left: 2px;
	border-left-color: rgba(255, 255, 255, 0);
	border-style: solid;
}

.filters .container > .item:nth-of-type(5) {
	border-top: 2px;
	border-top-color: rgba(255, 255, 255, 0);
	border-right: 2px;
	border-right-color: rgba(255, 255, 255, 0);
	border-bottom: 2px;
	border-bottom-color: #0036c8;
	border-left: 2px;
	border-left-color: rgba(255, 255, 255, 0);
	border-style: solid;
}

.filters .container > .item:nth-of-type(1).active {
	border-top: 2px;
	border-top-color: rgba(255, 255, 255, 0);
	border-right: 2px;
	border-right-color: rgba(255, 255, 255, 0);
	border-bottom: 2px;
	border-bottom-color: var(--primary-color);
	border-left: 2px;
	border-left-color: rgba(255, 255, 255, 0);
	border-style: solid;

	color: var(--primary-color);
}

.filters .container > .item:nth-of-type(2).active {
	color: #ffad00;
}

.filters .container > .item:nth-of-type(3).active {
	color: #ff0d00;
}

.filters .container > .item:nth-of-type(4).active {
	color: #49d369;
}

.filters .container > .item:nth-of-type(5).active {
	color: #0036c8;
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
	.filters .container > .item {
		flex: 1 0 250px;
	}
}
</style>
