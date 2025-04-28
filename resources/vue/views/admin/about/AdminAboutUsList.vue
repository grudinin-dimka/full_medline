<template>
	<div class="about">
		<div
			class="item"
			:class="{
				create: block.create,
				delete: block.delete,
			}"
			@click="$emit('touchEditBlock', block)"
			v-for="block in infoBlocks"
			:key="block.order"
		>
			<div class="head">
				<div>
					id: {{ block.create ? "?" : block.id }}
				</div>
				<div>
					order: {{ block.order }}
				</div>
			</div>
			<div class="body">
				<div class="images">
					<div
						v-if="block.imageOne"
						class="item"
						:style="{
							backgroundImage: `url(${block.pathOne})`,
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
						v-if="block.imageTwo"
						class="item"
						:style="{
							backgroundImage: `url(${block.pathTwo})`,
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
						v-if="block.imageThree"
						class="item"
						:style="{
							backgroundImage: `url(${block.pathThree})`,
						}"
					></div>
					<div
						v-else
						class="item default"
						:style="{
							backgroundImage: `url(/storage/default/stones-none-default.png)`,
						}"
					></div>
				</div>
				<div class="title" :class="{ none: !block.title }">
					{{ block.title ? block.title : "Пусто" }}
				</div>
				<div class="description" :class="{ none: !block.description }">
					{{ block.description ? block.description : "Пусто" }}
				</div>
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
	grid-auto-rows: 35 0px;
	gap: 20px;

	animation: show-bottom-to-top-15 0.5s ease-in-out;
}

.about > .item {
	cursor: pointer;
	display: flex;
	flex-direction: column;
	gap: 10px;

	border: var(--input-border);
	border-radius: calc(var(--default-border-radius) / 1.5);
	padding: calc(var(--default-padding));

	font-size: 16px;

	transition: all 0.2s;
}

.about > .item.delete {
	border: var(--input-delete-border);
}

.about > .item.delete:hover {
	border: var(--input-delete-border);
	background-color: var(--input-delete-background-color-hover);
}

.about > .item.create {
	border: var(--input-create-border-focus);
}

.about > .item.create:hover {
	border: var(--input-create-border-focus);
	background-color: var(--input-create-background-color-hover);
}

.about > .item > .head {
	display: flex;
	gap: 10px;
}

.about > .item > .head > div {
	padding: 5px 10px;
	border-radius: calc(var(--input-border-radius) / 2);
	border: var(--input-border);

	transition: all 0.2s;
}

.about > .item:hover > .head > div {
	border: var(--input-border-focus);
}

.about > .item.create > .head > div {
	border: var(--input-create-border);
}

.about > .item.delete > .head > div {
	border: var(--input-delete-border);
}

.about > .item.delete:hover > .head > div {
	border: var(--input-delete-border);
}

.about > .item > .body {
	display: flex;
	flex-direction: column;
	gap: 10px;
}

.about > .item:hover {
	border: var(--input-border-focus);
	background-color: var(--item-background-color-active);
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
	box-sizing: border-box;
	border: 3px dotted #e4e4e4;

	background-size: contain;
}

.description {
	height: 115px;
	overflow-y: scroll;
	word-break: break-all;
}

.item:hover > .body > .description::-webkit-scrollbar-thumb {
	background-color: rgb(220, 220, 220);
	border: 6px solid #f2feff;
	border-radius: 10px;
}

.item.delete:hover > .body > .description::-webkit-scrollbar-thumb {
	background-color: rgb(220, 220, 220);
	border: 6px solid rgb(255, 240, 240);
	border-radius: 10px;
}

.item.create:hover > .body > .description::-webkit-scrollbar-thumb {
	background-color: rgb(220, 220, 220);
	border: 6px solid var(--create-background-color);
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
	-webkit-line-clamp: 1; /* Число отображаемых строк */
	display: -webkit-box; /* Включаем флексбоксы */
	-webkit-box-orient: vertical; /* Вертикальная ориентация */
	overflow: hidden; /* Обрезаем всё за пределами блока */
}

.title.none,
.description.none {
	color: rgb(200, 200, 200);
}

@media screen and (width <= 1700px) {
	.about {
		grid-template-columns: repeat(2, 1fr);
	}	
}

@media screen and (width <= 1200px) {
	.about {
		grid-template-columns: repeat(1, 1fr);
	}	
}
</style>
