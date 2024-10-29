<template>
	<div class="about">
		<div class="item" v-for="about in abouts" :key="about.id">
			<div class="title" v-if="about.title">
				{{ about.title }}
			</div>
			<div class="images">
				<div
					v-if="about.imageOne"
					class="item"
					:style="{
						backgroundImage: `url(${about.pathOne})`,
					}"
				></div>
				<div
					v-if="about.imageTwo"
					class="item"
					:style="{
						backgroundImage: `url(${about.pathTwo})`,
					}"
				></div>
				<div
					v-if="about.imageThree"
					class="item"
					:style="{
						backgroundImage: `url(${about.pathThree})`,
					}"
				></div>
			</div>
			<div class="description" v-if="about.description">
				{{ about.description }}
			</div>
		</div>
	</div>
</template>

<script>
export default {
	props: {
		abouts: {
			type: Array,
			required: true,
			default: [],
		},
	},
};
</script>

<style scoped>
.about {
	display: flex;
	flex-direction: column;
	gap: 10px;

	width: 1000px;
	font-size: 18px;
}

.about > .item {
	display: flex;
	flex-direction: column;
	gap: 10px;
}

.about > .item > .title {
	font-size: 22px;
	font-weight: bold;

	color: var(--primary-color);
}

.images {
	display: flex;
	align-items: center;
	flex-wrap: wrap;
	gap: 10px;
}

.images > .item {
	flex: 1 0 300px;
	border-radius: 10px;
	width: 300px;
	height: 300px;
	background-position: center center;
	background-repeat: no-repeat;
	background-size: cover;
}

@media screen and (width <= 1080px) {
	.about {
		width: auto;
	}
}
</style>
