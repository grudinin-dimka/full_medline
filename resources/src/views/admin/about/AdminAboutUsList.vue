<template>
	<div class="about">
		<div
			class="item"
			@click="$emit('touchEditBlock')"
			v-for="block in infoBlocks"
			:key="block.id"
		>
			<div class="body">
				<div class="images">
					<template v-if="block.images">
						<div
							v-if="block.images.length > 0"
							class="item"
							:style="{
								backgroundImage: `url(${block.images[0].path})`,
							}"
						></div>
						<div
							v-else
							class="item default"
							:style="{
								backgroundImage: `url(/storage/default/stones-none-default.png)`,
							}"
						></div>
						<div
							v-if="block.images.length > 1"
							class="item"
							:style="{
								backgroundImage: `url(${block.images[1].path})`,
							}"
						></div>
						<div
							v-else
							class="item default"
							:style="{
								backgroundImage: `url(/storage/default/stones-none-default.png)`,
							}"
						></div>
						<div
							v-if="block.images.length > 2"
							class="item"
							:style="{
								backgroundImage: `url(${block.images[2].path})`,
							}"
						></div>
						<div
							v-else
							class="item default"
							:style="{
								backgroundImage: `url(/storage/default/stones-none-default.png)`,
							}"
						></div>
					</template>
				</div>
				<div class="title">{{ block.title }}</div>
				<div class="description">{{ block.description }}</div>
			</div>
		</div>
	</div>
</template>

<script>
export default {
	props: {
		infoBlocks: {
			type: Array,
			required: true,
			default: [],
		},
	},
	data() {
		return {};
	},
};
</script>

<style scoped>
.about {
	display: grid;
	grid-template-columns: repeat(3, 1fr);
	grid-auto-rows: 35	0px;
	gap: 20px;

	animation: show-bottom-to-top-15 0.5s ease-in-out;
}

.about > .item {
	cursor: pointer;
	display: flex;
	flex-direction: column;
	gap: 10px;

	border: 2px solid var(--input-border-color-inactive);
	border-radius: 10px;
	padding: 10px;

	font-size: 16px;

	transition: all 0.2s;
}

.about > .item > .body {
	display: flex;
	flex-direction: column;
	gap: 10px;
}

.about > .item:hover {
	border: 2px solid var(--input-border-color-active);
	background-color: #f2feff;
}

.images {
	display: flex;
	align-items: center;
	flex-wrap: wrap;
	gap: 10px;
}

.images > .item {
	flex: 1 0 100px;
	border-radius: 10px;
	min-width: 100px;
	min-height: 150px;
	background-position: center center;
	background-repeat: no-repeat;
	background-size: cover;
}

.images > .item.default {
	border: 3px dotted #e4e4e4;
	background-size: contain;
}

.description {
	height: 130px;
	overflow-y: scroll;
}

.item:hover > .body > .description::-webkit-scrollbar-thumb {
	background-color: rgb(220, 220, 220);
	border: 6px solid #f2feff;
	border-radius: 10px;
}

.description::-webkit-scrollbar {
	width: 18px;
	transition: all 0.2s;
}

.description::-webkit-scrollbar-track {
	background-color: rgba(255, 255, 255, 0);
	border-radius: 10px;
}

.description::-webkit-scrollbar-thumb {
	background-color: rgb(220, 220, 220);
	border: 6px solid #ffffff;
	border-radius: 10px;
}

.description::-webkit-scrollbar-thumb:hover {
	background-color: rgb(210, 210, 210);
	cursor: all-scroll;
}

.title {
	font-weight: bold;
	font-size: 20px;
	height: 30px;
	width: 200px;
	overflow-x: hidden;
	text-wrap:nowrap;
}
</style>
