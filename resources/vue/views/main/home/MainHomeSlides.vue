<template>
	<carousel
		:itemsToShow="calcItemsToShow"
		:clamp="true"
		:wrapAround="true"
		:transition="750"
		:autoplay="5000"
		:mouseDrag="true"
		:touchDrag="true"
		:pauseAutoplayOnHover="true"
	>
		<slide v-for="slide in slides" :key="slide.id">
			<a :href="slide.link" class="slider__link" :class="{ skeleton: !slide.path }">
				<img
					v-if="slide.path"
					:src="slide.path"
					alt="Слайд"
					class="slider__img"
					loading="lazy"
				/>
			</a>
		</slide>
	</carousel>
</template>

<script>
import { Carousel, Slide } from "vue3-carousel";
import "vue3-carousel/dist/carousel.css";

export default {
	components: { Carousel, Slide },
	props: {
		slides: { type: Array, required: true, default: () => [] },
	},
	data() {
		return {
			windowWidth: typeof window !== "undefined" ? window.innerWidth : 0,
		};
	},
	computed: {
		calcItemsToShow() {
			const width = this.windowWidth;
			if (!width) return 1;

			return width >= 2300
				? 7
				: width > 1920
				? 6
				: width >= 1600
				? 5
				: width >= 1300
				? 4
				: width >= 960
				? 3
				: width >= 660
				? 2
				: width >= 500
				? 1.5
				: 1;
		},
	},
	methods: {
		onResize() {
			this.windowWidth = window.innerWidth;
		},
	},
	mounted() {
		window.addEventListener("resize", this.onResize);
	},
	beforeUnmount() {
		window.removeEventListener("resize", this.onResize);
	},
};
</script>

<style>
.carousel {
	width: 100%;
	contain: layout paint style;
}

.slider__link {
	height: 375px;
	width: 300px;

	border-radius: var(--default-border-radius);
	background-color: var(--skeleton-background-color);
	display: block;
	overflow: hidden;

	will-change: transform;
}

.slider__img {
	height: 100%;
	width: 100%;
	object-fit: cover;
	transform: translateZ(0);
	will-change: transform;

	animation: show 0.5s ease-in-out;
}

.carousel__slide:hover .slider__link {
	background-image: linear-gradient(120deg, #ececec 50%, #fafafa 60%, #fafafa 61%, #ececec 70%);
	background-size: 200%;
	background-position: 100% 0;

	animation: waves 2s linear infinite;
}

@media (max-width: 450px) {
	.slider__link {
		width: auto;
		height: 100%;
		
		min-height: 375px;
	}
}
</style>
