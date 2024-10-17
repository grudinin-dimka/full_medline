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
					<div
						v-for="image in block.images"
						class="item"
						:style="{
							backgroundImage: `url(${image.path})`,
						}"
					></div>
					
				</div>
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
	grid-auto-rows: 400px;
	gap: 20px;
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
	min-height: 200px;
	background-position: center center;
	background-repeat: no-repeat;
	background-size: cover;
}

.description {
	height: 160px;
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
</style>
