<template>
	<div class="home__carousel">
		<div class="home__carousel-hide home__carousel-hide--left"></div>

		<Carousel
			:key="carouselKey"
			:itemsToShow="calcItemsToShow"
			:transition="750"
			:autoplay="5000"
			:mouseDrag="true"
			:wrapAround="true"
			:touchDrag="true"
			:gap="20"
			:pauseAutoplayOnHover="true"
		>
			<Slide v-for="(item, index) in list" :key="`${item.id}-${index}`">
				<a :href="item.link" class="slider__link" :class="{ skeleton: !item.path }">
					<img v-if="item.path" :src="item.path" alt="Слайд" class="slider__img" loading="lazy" />
				</a>
			</Slide>
		</Carousel>

		<div class="home__carousel-hide home__carousel-hide--right"></div>
	</div>
</template>

<script>
import { Carousel, Slide } from "vue3-carousel";
import "vue3-carousel/dist/carousel.css";

export default {
	components: { Carousel, Slide },
	props: {
		list: {
			type: Array,
			required: true,
			default: () => [],
		},
	},
	data() {
		return {
			windowWidth: typeof window !== "undefined" ? window.innerWidth : 0,
		};
	},
	computed: {
		carouselKey() {
			return this.list.map(i => i.id).join("-");
		},

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

.home__carousel {
	position: relative;
	margin: var(--default-margin);
}

.home__carousel-hide {
	z-index: 5;
	position: absolute;
	top: 0px;

	height: 440px;
	width: 370px;

	background-color: rgba(0, 0, 0, 0.1);
}

.home__carousel-hide--left {
	left: 0px;

	background: #ffffff;
	background: linear-gradient(270deg, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 1) 99%);
}

.home__carousel-hide--right {
	right: 0px;

	background: #ffffff;
	background: linear-gradient(90deg, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 1) 99%);
}

.slider__link {
	height: 440px;
	width: 370px;
	padding: var(--default-padding);

	border: var(--default-border);
	border-radius: var(--default-border-radius);
	background-color: var(--skeleton-background-color);
}

.slider__img {
	height: 100%;
	width: 100%;
	aspect-ratio: 4.4/3.7;

	object-fit: cover;
	border: var(--default-border);
	border-radius: calc(var(--default-border-radius) / 1.5);
	background-color: white;
	transform: translateZ(0);

	will-change: transform;

	animation: show 0.5s ease-in-out;
}

.carousel__slide:hover .slider__link {
	background-image: linear-gradient(
		120deg,
		var(--skeleton-background-color) 50%,
		var(--skeleton-line-color) 60%,
		var(--skeleton-line-color) 61%,
		var(--skeleton-background-color) 70%
	);
	background-size: 200%;
	background-position: 100% 0;

	animation: waves 2s linear infinite;
}

@media screen and (width <= 1300px) {
	.home__carousel-hide {
		width: 150px;
	}
}

@media screen and (width <= 500px) {
	.home__carousel-hide {
		display: none;
	}
}
</style>
