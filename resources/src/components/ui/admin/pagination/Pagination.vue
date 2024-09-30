<template>
	<div class="pagination">
		<div class="buttons-left">
			<div class="item first" @click="$emit('changePage', 1)">
				<svg
					xmlns="http://www.w3.org/2000/svg"
					height="24px"
					viewBox="0 -960 960 960"
					width="24px"
					fill="#e8eaed"
				>
					<path
						d="M440-240 200-480l240-240 56 56-183 184 183 184-56 56Zm264 0L464-480l240-240 56 56-183 184 183 184-56 56Z"
					/>
				</svg>
			</div>
			<div class="item previous" @click="$emit('changePage', pagination.pages.current - 1)">
				<svg
					xmlns="http://www.w3.org/2000/svg"
					height="22px"
					viewBox="0 -960 960 960"
					width="22px"
					fill="black"
				>
					<path d="M560-240 320-480l240-240 56 56-184 184 184 184-56 56Z" />
				</svg>
			</div>
		</div>
		<div
			v-for="page in pages"
			:key="page"
			class="item"
			:class="{ active: page === pagination.pages.current }"
			@click="$emit('changePage', page)"
		>
			{{ page }}
		</div>
		<div class="buttons-right">
			<div class="item next" @click="$emit('changePage', pagination.pages.current + 1)">
				<svg
					xmlns="http://www.w3.org/2000/svg"
					height="22px"
					viewBox="0 -960 960 960"
					width="22px"
					fill="black"
				>
					<path d="M504-480 320-664l56-56 240 240-240 240-56-56 184-184Z" />
				</svg>
			</div>
			<div class="item last" @click="$emit('changePage', pages)">
				<svg
					xmlns="http://www.w3.org/2000/svg"
					height="22px"
					viewBox="0 -960 960 960"
					width="22px"
					fill="#e8eaed"
				>
					<path
						d="M383-480 200-664l56-56 240 240-240 240-56-56 183-184Zm264 0L464-664l56-56 240 240-240 240-56-56 183-184Z"
					/>
				</svg>
			</div>
		</div>
	</div>
</template>

<script>
export default {
	props: {
		pages: {
			type: Number,
			required: true,
		},
		pagination: {
			type: Object,
			required: true,
		},
	},
};
</script>

<style scoped>
svg {
	cursor: pointer;
	fill: rgba(0, 171, 189, 0.5);
}

svg:hover {
	fill: rgba(0, 171, 189, 1);
}

.pagination {
	display: flex;
	gap: 12.5px;
	justify-content: center;
}

.pagination > .item {
	cursor: pointer;
	color: var(--input-border-color-inactive);
	font-size: 18px;
}

.pagination > .item:hover,
.pagination > .item.active {
	color: rgba(0, 171, 189, 1);
}

.pagination > .buttons-left {
	display: flex;
	gap: 0px;
}

.pagination > .buttons-right {
	display: flex;
	gap: 0px;
}
</style>
