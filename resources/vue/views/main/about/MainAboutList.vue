<template>
	<div class="about">
		<div class="about__item" v-for="about in abouts" :key="about.id">
			<div
				class="about__item-title"
				v-if="about.title !== ''"
				:class="{ skeleton: !about.title && about.title !== '' }"
			>
				<VueTiptap
					v-if="about.title"
					v-model="about.title"
					:editable="false"
					:limit="500"
					:minHeight="0"
				/>
			</div>

			<div class="about__item-images">
				<div
					class="item__images-item"
					v-if="about.imageOne !== ''"
					:class="{ skeleton: !about.imageOne }"
				>
					<img v-if="about.imageOne" :src="about.pathOne" alt="" loading="lazy"/>
				</div>

				<div
					class="item__images-item"
					v-if="about.imageTwo !== ''"
					:class="{ skeleton: !about.imageTwo }"
				>
					<img v-if="about.imageTwo" :src="about.pathTwo" alt="" loading="lazy"/>
				</div>

				<div
					class="item__images-item"
					v-if="about.imageThree !== ''"
					:class="{ skeleton: !about.imageThree }"
				>
					<img v-if="about.imageThree" :src="about.pathThree" alt="" loading="lazy"/>
				</div>
			</div>

			<div
				class="about__item-description"
				v-if="about.description !== ''"
				:class="{ skeleton: !about.description && about.description !== '' }"
			>
				<VueTiptap
					v-if="about.description"
					v-model="about.description"
					:editable="false"
					:limit="10_000"
					:minHeight="0"
				/>
			</div>
		</div>
	</div>
</template>

<script>
import VueTiptap from "../../../components/modules/VueTiptap.vue";

export default {
	props: {
		abouts: {
			type: Array,
			required: true,
			default: [],
		},
	},
	components: {
		VueTiptap,
	},
};
</script>

<style scoped>
.about {
	display: flex;
	flex-direction: column;
	gap: 20px;

	width: 1350px;
	font-size: 18px;
}

.about__item {
	display: flex;
	flex-direction: column;
	gap: 20px;
}

.about__item-title {
	font-size: 1.25rem;
	font-weight: bold;

	color: var(--primary-color);
	min-height: 40px;

	border-radius: var(--default-border-radius);
}

.about__item-images {
	display: flex;
	align-items: center;
	flex-wrap: wrap;
	gap: 10px;

	width: 100%;
}

.item__images-item {
	flex-grow: 1;
	flex-basis: 300px;
	padding: var(--default-padding);
	border-radius: var(--default-border-radius);

	width: 100%;
	height: 300px;
	background-color: var(--skeleton-background-color);
}

.item__images-item > img {
	width: 100%;
	height: 100%;
	border-radius: calc(var(--default-border-radius) / 1.5);

	object-fit: cover;

	animation: show 0.5s ease-in-out;
}

.about__item-description {
	min-height: 50px;

	border-radius: var(--default-border-radius);
}

@media screen and (width <= 1425px) {
	.about {
		width: 100%;
	}
}
</style>
